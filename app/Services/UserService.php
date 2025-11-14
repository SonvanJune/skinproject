<?php

namespace App\Services;

use App\DTOs\ActiveUserDTO;
use App\DTOs\ChangePasswordDTO;
use App\DTOs\ChangePasswordLevel2DTO;
use App\DTOs\DeleteUserDTO;
use App\DTOs\ForgetPasswordDTO;
use App\DTOs\GetUserDTO;
use App\DTOs\LoginUserDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\RegisterUserDTO;
use App\DTOs\ResetPasswordDTO;
use App\DTOs\RestoreUserDTO;
use App\DTOs\SetPasswordLevel2DTO;
use App\DTOs\UpdateUserDTO;
use App\Models\OneTimePassword;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class UserService
{
    // Constants for user validation and configuration
    public const MIN_AGE = 0; // Minimum age for registration
    public const MAX_AGE = 100; // Maximum age for registration
    public const MIN_PASS_LENGTH = 6; // Minimum password length

    public const NAME_REGEX = "/^([A-Za-zÀ-ỹà-ỹ][A-Za-zÀ-ỹà-ỹ']*)(\s+([A-Za-zÀ-ỹà-ỹ][A-Za-zÀ-ỹà-ỹ']*))*$/u"; // Regex for valid names
    public const PHONE_NUMBER_REGEX = "/^(\+\d{1,2}\s?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/"; // Regex for valid phone numbers
    public const STATUS_REGEX = "/^\d$/"; // Regex for user status
    public const EMAIL_REGEX = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"; // Regex for user status

    public const DATE_FORMAT = "Y-m-d"; // Format for dates in input
    public const DB_DATE_FORMAT = 'Y-m-d H:i:s'; // Format for dates in database

    public const ACCESS_TOKEN_KEY_NAME = "ACCESS_TOKEN_KEY_NAME"; // Key name for storing access tokens
    public const REFRESH_TOKEN_KEY_NAME = "REFRESH_TOKEN_KEY_NAME"; // Key name for storing refresh tokens
    public const DEFAULT_ENCRYPT_KEY = "DEFAULT_ENCRYPT_KEY";


    public const INACTIVE_STATUS = 0;
    public const ACTIVE_STATUS = 1;
    public const DELETED_STATUS = -1;
    public const WARNING_RESTORE_STATUS = "WARNING_RESTORE_STATUS";

    public const PER_PAGE = 5; // Number of items per page in pagination
    public const DEFAULT_PAGE = 1;


    public const DEFAULT_AVATAR = "images/avatars/default_avatar.jpg";

    public const DEFAULT_TOKEN_TIME_TO_LIVE = 10;
    public const DEFAULT_REFRESH_TOKEN_TIME_TO_LIVE = 2000;


    /**
     * Registers a new user after validating input and checking conditions.
     *
     * @param Request $request The incoming HTTP request containing user data.
     * @param OTPService $otpService Service for sending OTP for account activation.
     * @return string|RegisterUserDTO Either an error message or a RegisterUserDTO instance.
     */
    public function registerUser(Request $request, OTPService $otpService): string|RegisterUserDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|max:255|regex:' . $this::NAME_REGEX,
                'last_name' => 'required|max:255|regex:' . $this::NAME_REGEX,
                'email' => 'required|email|regex:' . $this::EMAIL_REGEX,
                'password' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
                'birthday' => 'required|date|date_format:' . $this::DATE_FORMAT,
                'phone_number' => 'required',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if ($this->getExistUserByEmail(mb_strtolower($request->email, 'UTF-8'))) {
            return 'Account already exists';
        }

        $birthday = date($this::DATE_FORMAT, strtotime($request->input('birthday')));
        if (
            $birthday < date($this::DATE_FORMAT, strtotime("-" . $this::MAX_AGE . " years"))
            || $birthday > date($this::DATE_FORMAT, strtotime("-" . $this::MIN_AGE . " years"))
        ) {
            return 'Register birthday is invalid!';
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user->user_id = Str::uuid()->toString();
            $user->user_first_name = $request->first_name;
            $user->user_last_name = $request->last_name;
            $user->user_email = mb_strtolower($request->email, 'UTF-8');
            $user->user_status = $this::INACTIVE_STATUS;
            $user->user_password = Hash::make($request->password);
            $user->user_phone = $request->phone_number;
            $user->user_avatar = self::DEFAULT_AVATAR;
            $user->user_birthday = date($this::DB_DATE_FORMAT, strtotime($request->birthday));
            $user->save();

            $role = Role::where('role_name', RoleService::USER_ROLE)->first();

            if (!$role) {
                DB::rollBack();
                return "Cannot register user. Attach role to user failed";
            }

            $user->roles()->attach($role->role_id);

            $didInitialOTP = $otpService->sendOTP($request, $user, OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT);

            if (is_string($didInitialOTP)) {
                DB::rollBack();
                return "Cannot register user. Send OTP failed: " . $didInitialOTP;
            }

            $user = $this->encryptUser($user);
            $flag = $user->save();

            if (!$flag) {
                DB::rollBack();
                return "Cannot register user. Please try again";
            }

            DB::commit();

            return RegisterUserDTO::fromModel($user, $didInitialOTP);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to register: ' . $e->getMessage();
        }
    }

    /**
     * Registers a new user after validating input and checking conditions.
     *
     * @param Request $request The incoming HTTP request containing user data.
     * @return string|RegisterUserDTO Either an error message/warning or a RegisterUserDTO instance.
     */
    public function registerSubAdmin(Request $request): string|RegisterUserDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|max:255|regex:' . $this::NAME_REGEX,
                'last_name' => 'required|max:255|regex:' . $this::NAME_REGEX,
                'email' => 'required|email',
                'password' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
                'birthday' => 'required|date_format:' . $this::DATE_FORMAT,
                'phone_number' => 'required',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $existedUser = $this->getExistUserByEmail(mb_strtolower($request->email, 'UTF-8'));
        if ($existedUser) {
            if ($existedUser->user_status === self::DELETED_STATUS) {
                return self::WARNING_RESTORE_STATUS;
            }

            return 'Account already exists';
        }

        $birthday = date($this::DATE_FORMAT, strtotime($request->input('birthday')));
        if (
            $birthday < date($this::DATE_FORMAT, strtotime("-" . $this::MAX_AGE . " years"))
            || $birthday > date($this::DATE_FORMAT, strtotime("-" . $this::MIN_AGE . " years"))
        ) {
            return 'Register birthday is invalid!';
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user->user_id = Str::uuid()->toString();
            $user->user_first_name = $request->first_name;
            $user->user_last_name = $request->last_name;
            $user->user_email = mb_strtolower($request->email, 'UTF-8');
            $user->user_status = $this::ACTIVE_STATUS;
            $user->user_password = Hash::make($request->password);
            $user->user_phone = $request->phone_number;
            $user->user_avatar = self::DEFAULT_AVATAR;
            $user->user_birthday = date($this::DB_DATE_FORMAT, strtotime($request->birthday));
            $flag = $user->save();

            $role = Role::where('role_name', RoleService::SUB_ADMIN_ROLE)->first();

            if (!$role) {
                $user->delete();
                return "Cannot register user. Attach role to user failed";
            }

            $user->roles()->attach($role->role_id);

            $user = $this->encryptUser($user);
            $flag = $user->save();

            DB::commit();

            if (!$flag) {
                return "Cannot register user";
            }

            return RegisterUserDTO::fromModel($user, "");
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to register: ' . $e->getMessage();
        }
    }

    /**
     * Restore a deleted subadmin after validating input and checking conditions.
     *
     * @param Request $request The incoming HTTP request containing user data.
     * @return string|RestoreUserDTO Either an error message/warning or a RestoreUserDTO instance.
     */
    public function restoreSubAdmin(Request $request): string|RestoreUserDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $existedUser = $this->getExistUserByEmail(mb_strtolower($request->email, 'UTF-8'));
        if (!$existedUser || $existedUser->user_status !== self::DELETED_STATUS || !$existedUser->roles->contains('role_name', RoleService::SUB_ADMIN_ROLE)) {
            return 'This account is not a true account which is allowed to be restored';
        }

        DB::beginTransaction();
        try {
            $existedUser->user_status = $this::ACTIVE_STATUS;
            $flag = $existedUser->save();

            $user = $this->encryptUser($existedUser);
            $flag = $user->save();

            DB::commit();

            if (!$flag) {
                return "Cannot restore user";
            }

            return RestoreUserDTO::fromModel($user, $flag);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to restore: ' . $e->getMessage();
        }
    }

    /**
     * Activates a user account after verifying OTP.
     *
     * @param Request $request The incoming HTTP request containing the user ID and OTP.
     * @param OTPService $otpService Service for validating OTP.
     * @return string|ActiveUserDTO Either an error message or an ActiveUserDTO instance.
     */
    public function activeUserWithId(Request $request, OTPService $otpService): string|ActiveUserDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|max:255|regex:' . $this::NAME_REGEX,
                'one_time_password_code' => 'required|regex:' . OTPService::getOTPRegex(),
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = User::where("user_id", $request->input('user_id'))->first();

        if (!$user) {
            return "User not found";
        }

        $validateOTPDTO = $otpService->validateOTP($request, $user, OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT);

        if (is_string($validateOTPDTO)) {
            return "Cannot active user: " . $validateOTPDTO;
        }

        $request->merge(['user_status' => UserService::ACTIVE_STATUS]);
        $updatedUserDTO = $this->updateUserInformation($request);

        $otp = OneTimePassword::where([
            "one_time_password_code" => $request->input("one_time_password_code"),
            "user_id" => $user->user_id,
            "one_time_password_type" => OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT
        ])->first();

        if (!$otp) {
            return "Can not find one-time password";
        }

        $otp->delete();


        if (is_string($updatedUserDTO)) {
            return "Cannot activate user: " . $updatedUserDTO;
        }

        $ttl = config('jwt.ttl', self::DEFAULT_TOKEN_TIME_TO_LIVE);
        $rf_ttl = config('jwt.refresh_ttl', self::DEFAULT_REFRESH_TOKEN_TIME_TO_LIVE);

        JWTAuth::factory()->setTTL($ttl);
        $token = JWTAuth::fromUser($user);

        JWTAuth::factory()->setTTL($rf_ttl);
        $refreshToken = JWTAuth::fromUser($user);

        $request->session()->put($this::ACCESS_TOKEN_KEY_NAME, $token);
        $request->session()->put($this::REFRESH_TOKEN_KEY_NAME, $refreshToken);

        $user = $this->encryptUser($user);

        return ActiveUserDTO::fromModel($user, $token, $refreshToken);
    }

    /**
     * Activates a user account after verifying OTP.
     *
     * @param Request $request The incoming HTTP request containing the user ID and OTP.
     * @param OTPService $otpService Service for validating OTP.
     * @return string|ActiveUserDTO Either an error message or an ActiveUserDTO instance.
     */
    public function activeUserWithEmail(Request $request, OTPService $otpService): string|ActiveUserDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_email' => 'required|max:255|regex:' . $this::EMAIL_REGEX,
                'one_time_password_code' => 'required|regex:' . OTPService::getOTPRegex(),
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = $this->getExistUserByEmail($request->input("user_email"));
        $user = $this->encryptUser($user);

        if (!$user) {
            return "User not found";
        }

        $validateOTPDTO = $otpService->validateOTP($request, $user, OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT);

        if (is_string($validateOTPDTO)) {
            return "Cannot active user: " . $validateOTPDTO;
        }

        $request->merge(['user_status' => UserService::ACTIVE_STATUS]);
        $request->merge(['user_id' => $user->user_id]);
        $updatedUserDTO = $this->updateUserInformation($request);

        $otp = OneTimePassword::where([
            "one_time_password_code" => $request->input("one_time_password_code"),
            "user_id" => $user->user_id,
            "one_time_password_type" => OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT
        ])->first();

        if (!$otp) {
            return "Can not find one-time password";
        }

        $otp->delete();


        if (is_string($updatedUserDTO)) {
            return "Cannot activate user: " . $updatedUserDTO;
        }

        $ttl = config('jwt.ttl', self::DEFAULT_TOKEN_TIME_TO_LIVE);
        $rf_ttl = config('jwt.refresh_ttl', self::DEFAULT_REFRESH_TOKEN_TIME_TO_LIVE);

        JWTAuth::factory()->setTTL($ttl);
        $token = JWTAuth::fromUser($user);

        JWTAuth::factory()->setTTL($rf_ttl);
        $refreshToken = JWTAuth::fromUser($user);

        $request->session()->put($this::ACCESS_TOKEN_KEY_NAME, $token);
        $request->session()->put($this::REFRESH_TOKEN_KEY_NAME, $refreshToken);

        $user = $this->encryptUser($user);

        return ActiveUserDTO::fromModel($user, $token, $refreshToken);
    }

    /**
     * Logs in a user by verifying the email and password.
     *
     * @param Request $request The incoming HTTP request containing user credentials.
     * @return string|LoginUserDTO Either an error message or a LoginUserDTO instance.
     */
    public function loginUser(Request $request): string|LoginUserDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = $this->getExistUserByEmail($request->email);

        if ($user) {
            $checkPasswordFlag = $this->checkPassword($user, $request->input("password"));
        }

        if (!$user || !$checkPasswordFlag) {
            return "Invalid email or password";
        }

        $ttl = config('jwt.ttl', self::DEFAULT_TOKEN_TIME_TO_LIVE);
        $rf_ttl = config('jwt.refresh_ttl', self::DEFAULT_REFRESH_TOKEN_TIME_TO_LIVE);

        JWTAuth::factory()->setTTL($ttl);
        $token = JWTAuth::fromUser($user);

        JWTAuth::factory()->setTTL($rf_ttl);
        $refreshToken = JWTAuth::fromUser($user);

        $request->session()->put($this::ACCESS_TOKEN_KEY_NAME, $token);
        $request->session()->put($this::REFRESH_TOKEN_KEY_NAME, $refreshToken);

        $user = $this->encryptUser($user);

        return LoginUserDTO::fromModel($user, $token, $refreshToken);
    }

    /**
     * Logs out a user by invalidating their JWT token.
     *
     * @param Request $request The incoming HTTP request containing the JWT token.
     * @param User $user The user to log out.
     * @return true|string Either a LogoutUserDTO instance or an error message.
     */
    public function logoutUser(Request $request)
    {
        $token = $request->session()->get(UserService::ACCESS_TOKEN_KEY_NAME);
        $refreshToken = $request->session()->get(UserService::REFRESH_TOKEN_KEY_NAME);
        try {
            $token = new Token($request->bearerToken() ?? $token);
            JWTAuth::manager()->invalidate($token, true);

            $refreshToken = new Token($refreshToken);
            JWTAuth::manager()->invalidate($refreshToken, true);

            $request->session()->forget($this::ACCESS_TOKEN_KEY_NAME);
            $request->session()->forget($this::REFRESH_TOKEN_KEY_NAME);

            return true;
        } catch (Exception $e) {
            return 'Logout failed: ' . $e->getMessage();
        }
    }

    /**
     * Delete a user.
     *
     * @param Request $request
     * @param string $user_id
     * @return DeleteUserDTO|string
     */
    public function deleteUser(Request $request): DeleteUserDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|uuid'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        DB::beginTransaction();
        try {
            $user = User::where("user_id", $request->input("user_id"))->firstOrFail();

            $user->user_status = $this::DELETED_STATUS;

            $flag = $user->save();

            DB::commit();

            if (!$flag) {
                return "Cannot delete user";
            }

            return DeleteUserDTO::fromModel($user);
        } catch (Exception $e) {
            DB::rollBack();
            return "Delete user failed: " . $e->getMessage();
        }
    }

    /**
     * Update a user's information.
     *
     * @param Request $request
     * @return UpdateUserDTO|string
     */
    public function updateUserInformation(Request $request): UpdateUserDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|uuid',
                'first_name' => 'max:255|regex:' . $this::NAME_REGEX,
                'last_name' => 'max:255|regex:' . $this::NAME_REGEX,
                'birthday' => 'date|date_format:' . $this::DATE_FORMAT,
                'phone_number' => 'regex:' . $this::PHONE_NUMBER_REGEX,
                'user_status' => 'regex:' . $this::STATUS_REGEX,
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);

        DB::beginTransaction();
        try {
            $user = User::where("user_id", $request->input("user_id"))->firstOrFail();

            if ($request->has("first_name")) {
                $user->user_first_name = $this->encrypt_with_key($request->input("first_name"), $key);
            }

            if ($request->has("last_name")) {
                $user->user_last_name = $this->encrypt_with_key($request->input("last_name"), $key);
            }

            if ($request->has("birthday")) {
                $user->user_birthday = date($this::DB_DATE_FORMAT, strtotime($request->input("birthday")));
            }

            if ($request->has("phone_number")) {
                $user->user_phone = $this->encrypt_with_key($request->input("phone_number"), $key);
            }

            if (
                $request->has("user_status") &&
                (
                    $request->input("user_status") === UserService::ACTIVE_STATUS ||
                    $request->input("user_status") === UserService::INACTIVE_STATUS ||
                    $request->input("user_status") === UserService::DELETED_STATUS
                )
            ) {
                $user->user_status = $request->input("user_status");
            }

            $flag = $user->save();

            DB::commit();

            if (!$flag) {
                return "Cannot update user's information";
            }

            return UpdateUserDTO::fromModel($user);
        } catch (Exception $e) {
            DB::rollBack();
            return "Update user's information failed: " . $e->getMessage();
        }
    }

    /**
     * Read user information based on the provided user ID.
     *
     * @param Request $request The incoming HTTP request.
     * @return GetUserDTO|string Either a GetUserDTO instance or an error message.
     */
    public function readUserInformation(Request $request): GetUserDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|uuid'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = User::where("user_id", $request->input("user_id"))->first();

        if (!$user) {
            return "User not found";
        }

        return GetUserDTO::fromModel($user);
    }

    /**
     * Read users based on the provided permission ID, with pagination.
     *
     * @param Request $request The incoming HTTP request.
     * @return PaginatedDTO|string Either a PaginatedDTO instance or an error message.
     */
    public function readUsersByRole(Request $request): PaginatedDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'role_id' => 'required|uuid',
                'page' => 'nullable|numeric|integer',
                'per_page' => 'nullable|numeric|integer',
                'key' => 'nullable|string'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $role = Role::where("role_id", $request->input("role_id"))->first();

        if (!$role) {
            return "Role not found";
        }

        $perPage = $request->input('per_page', $this::PER_PAGE);
        $page = $request->input('page', $this::DEFAULT_PAGE);
        $skip = ($page - 1) * $perPage;

        $total = $role->users()->where('user_status', '<>', self::DELETED_STATUS)->count();
        $users = $role->users()->where('user_status', '<>', self::DELETED_STATUS)
            ->skip($skip)
            ->take($perPage)
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return PaginatedDTO::fromData(GetUserDTO::fromModels($users), $page, $perPage, $total, $request->query('key') ?? "");
    }

    /**
     * Update the roles of a user.
     *
     * @param Request $request The incoming HTTP request containing user_id and list_role.
     * @return UpdateUserDTO|string Either the updated user as a DTO on success or an error message on failure.
     */
    public function updateUserRoles(Request $request): UpdateUserDTO|string
    {
        Validator::extend('uuid_array', function ($attribute, $value, $parameters, $validator) {
            foreach ($value as $uuid) {
                if (!is_string($uuid) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
                    return false;
                }
            }
            return true;
        });

        $validator = Validator::make(
            $request->all(),
            [
                'sub_user_id' => 'required|uuid',
                'list_role' => 'required|uuid_array',
            ],
            [
                'list_role.uuid_array' => 'Require array of uuids'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = User::where("user_id", $request->input("sub_user_id"))->first();

        if (!$user) {
            return "User not found";
        }

        DB::beginTransaction();
        try {
            $flag = $user->roles()->sync($request->input("list_role"));

            DB::commit();

            if ($flag <= 0) {
                return "Cannot update user's roles";
            }

            return UpdateUserDTO::fromModel($user);
        } catch (Exception $e) {
            DB::rollBack();
            return "Update user's roles failed: " . $e->getMessage();
        }
    }

    /**
     * Retrieve a user by email. (already descrypt data)
     *
     * @param string $email
     * @param bool $email_encrypted = false if email is encrypted, decrypt it before get user data
     * @return User|null The user object or null if not found.
     */
    public function getExistUserByEmail(string $email, bool $email_encrypted = false): User|null
    {
        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);

        if ($email_encrypted) {
            $email = $this->decrypt_with_key($email, $key);
        }
        $user = User::where('user_email', $this->encrypt_with_key($email, $key))->first();
        if ($user) {
            $user = $this->decryptUser($user);
        }

        return $user;
    }

    /**
     * Retrieve a user based on the provided JWT token.
     *
     * @param Request $request The incoming HTTP request.
     * @return User|string Either the authenticated User instance or an error message.
     */
    public function getUserByToken(Request $request, string $token): User|string
    {
        try {
            $token = new Token($request->bearerToken() ?? $token);
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user || !$user->user_id) {
                return "User not found";
            }

            $user = User::where("user_id", $user->user_id)->first();
            if (!$user) {
                return "User not found";
            }

            $user = $this->decryptUser($user);
            return $user;
        } catch (Exception $e) {
            return "Not authenticated user: " . $e->getMessage();
        }
    }

    /**
     * Retrieve a user based on the provided JWT token.
     *
     * @param Request $request The incoming HTTP request.
     * @return User|string Either the authenticated User instance or an error message.
     */
    public function getUserInformationByToken(Request $request, string $token): GetUserDTO|string
    {
        try {
            $token = new Token($request->bearerToken() ?? $token);
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user || !$user->user_id) {
                return "User not found";
            }

            $user = User::where("user_id", $user->user_id)->first();
            if (!$user) {
                return "User not found";
            }

            $user = $this->decryptUser($user);
            return getUserDTo::fromModel($user);
        } catch (Exception $e) {
            return "Not authenticated user: " . $e->getMessage();
        }
    }

    /**
     * Check if a password matches a decrypt_with_key password.
     *
     * @param User $user
     * @param string $password
     * @return bool Whether the password matches the decrypt_with_key password.
     */
    private function checkPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->user_password);
    }

    /**
     * Check if a password matches a decrypt_with_key password.
     *
     * @param User $user
     * @param string $password
     * @return bool Whether the password matches the decrypt_with_key password.
     */
    public function checkPasswordLevel2(User $user, string $password): bool
    {
        return strcmp($this->decrypt_with_key($user->user_password, UserService::DEFAULT_ENCRYPT_KEY), $password) === 0 ? true : false;
    }

    /**
     * Check if a given key exists in the session and optionally verify its value.
     *
     * @param Request $request The current HTTP request instance.
     * @param string $key The key to check in the session.
     * @return bool Returns true if the key exists in the session and, if $value is provided, matches the session's value.
     * false if the key does not exist or is not a valid
     * string if the token is valid but expired, reuturn as a new token
     */
    public function refreshToken(Request $request): bool| string
    {
        $storedRefreshToken = $request->session()->get(UserService::REFRESH_TOKEN_KEY_NAME);

        if (!$storedRefreshToken) {
            return false;
        }

        $ttl = config('jwt.ttl', self::DEFAULT_TOKEN_TIME_TO_LIVE);

        try {
            $user = JWTAuth::setToken($storedRefreshToken)->authenticate();
            JWTAuth::factory()->setTTL($ttl);
            $newToken = JWTAuth::fromUser($user);
            $request->session()->put(UserService::ACCESS_TOKEN_KEY_NAME, $newToken);
            return $newToken;
        } catch (TokenExpiredException $e) {
            return false;
        }
    }

    /**
     * Reset the user's password based on the provided request data.
     *
     * @param Request $request  The incoming HTTP request, containing the new password and JWT token.
     * @return string|UpdateUserDTO Returns a string in case of failure, or the updated user as a DTO on success.
     */
    public function resetPassword(Request $request, OTPService $otpService): string|ResetPasswordDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_email' => 'required|email',
                'password' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
                'one_time_password_code' => 'required|regex:' . OTPService::getOTPRegex(),
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = $this->getExistUserByEmail($request->input('user_email'));
        $user = $this->encryptUser($user);

        if (!$user) {
            return "User not found with provided email";
        }

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $validateOTPDTO = $otpService->validateOTP($request, $user, OTPService::TYPE_OTP_FOR_FORGETTING_PASSWORD);

        if (is_string($validateOTPDTO)) {
            return "Cannot reset password: " . $validateOTPDTO;
        }

        $otp = OneTimePassword::where([
            "one_time_password_code" => $request->input("one_time_password_code"),
            "user_id" => $user->user_id,
            "one_time_password_type" => OTPService::TYPE_OTP_FOR_FORGETTING_PASSWORD
        ])->first();

        DB::beginTransaction();
        try {
            $user->user_password = Hash::make($request->input('password'));
            $otp->delete();
            $flag = $user->save();

            DB::commit();

            if (!$flag) {
                return "Cannot reset password";
            }

            $user = $this->encryptUser($user);

            return ResetPasswordDTO::fromModel($user, $flag);
        } catch (Exception $e) {
            DB::rollBack();
            return "Reset password failed: " . $e->getMessage();
        }
    }

    /**
     * Send OTP for password reset request.
     *
     * @param Request $request The incoming request containing user data.
     * @param OTPService $otpService The service to handle OTP operations.
     * @return string|ForgetPasswordDTO Returns a success DTO or an error message.
     */
    public function forgetPassword(Request $request, OTPService $otpService): string|ForgetPasswordDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_email' => 'required|email',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = $this->getExistUserByEmail($request->input('user_email'));

        if (!$user) {
            return "User not found with provided email";
        }

        if ($user->user_status == UserService::INACTIVE_STATUS || $user->user_status == UserService::DELETED_STATUS) {
            return "User was not actived";
        }

        $didSendOTP = $otpService->sendOTP($request, $user, OTPService::TYPE_OTP_FOR_FORGETTING_PASSWORD);

        if (is_string($didSendOTP)) {
            return "Cannot send OTP to forget password: " . $didSendOTP;
        }

        $user = $this->encryptUser($user);
        return ForgetPasswordDTO::fromModel($user, $didSendOTP);
    }

    /**
     * Change the user's password after validating the current password.
     *
     * @param Request $request The incoming request containing 'current_password' and 'new_password'.
     * @param User $user The user whose password is being updated.
     * @return string|ChangePasswordDTO Returns a validation error string, failure message, or the updated user as a DTO.
     */
    public function changePassword(Request $request, User $user): string|ChangePasswordDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
                'new_password' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!$this->checkPassword($user, $request->input("current_password"))) {
            return "Invalid current password";
        }

        if ($request->input("new_password") === $request->input("current_password")) {
            return "The new password is the same as the old one";
        }

        DB::beginTransaction();
        try {
            $user->user_password = Hash::make($request->input('new_password'));
            $flag = $user->save();
            DB::commit();

            if (!$flag) {
                return "Cannot change password";
            }

            return ChangePasswordDTO::fromModel($user, $flag);
        } catch (Exception $e) {
            DB::rollBack();
            return "Change password failed: " . $e->getMessage();
        }
    }

    /**
     * set the user's password level 2.
     *
     * @param Request $request The incoming request containing 'password_level_2'.
     * @param User $user The user whose password level 2 is being created.
     * @return string|SetPasswordLevel2DTO Returns a validation error string, failure message, or the updated user as a DTO.
     */
    public function setPasswordLevel2(Request $request, User $user): string|SetPasswordLevel2DTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password_level_2' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!empty($user->user_password_level_2)) {
            return "The password level 2 is already set for user";
        }

        DB::beginTransaction();
        try {
            $user->user_password_level_2 = $this->encrypt_with_key($request->input('password_level_2'), UserService::DEFAULT_ENCRYPT_KEY);

            $flag = $user->save();
            DB::commit();

            if (!$flag) {
                return "Cannot set password level 2";
            }

            return SetPasswordLevel2DTO::fromModel($user, $flag);
        } catch (Exception $e) {
            DB::rollBack();
            return "Set password level 2 failed: " . $e->getMessage();
        }
    }

    /**
     * Change the user's password level 2 after validating the current password level 2.
     *
     * @param Request $request The incoming request containing 'current_password_level_2' and 'new_password_level_2'.
     * @param User $user The user whose password level 2 is being updated.
     * @param QuestionService $questionService The question service which check that if the user is allowed to change password level.
     * @return string|ChangePasswordLevel2DTO Returns a validation error string, failure message, or the updated user as a DTO.
     */
    public function changePasswordLevel2(Request $request, User $user, QuestionService $questionService): string|ChangePasswordLevel2DTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'new_password_level_2' => 'required|max:255|min:' . $this::MIN_PASS_LENGTH,
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (empty($user->user_password_level_2)) {
            return "The password level 2 is not set for user yet";
        }

        $checkDTO = $questionService->checkQuestionListForUser($request, $user);

        if (is_string($checkDTO)) {
            return "Cannot change password level 2: " . $checkDTO;
        }

        if (!$checkDTO->flag) {
            return "The answer to the security question is incorrect";
        }

        DB::beginTransaction();
        try {
            $user->user_password_level_2 = $this->encrypt_with_key($request->input('new_password_level_2'), UserService::DEFAULT_ENCRYPT_KEY);
            $flag = $user->save();
            DB::commit();

            if (!$flag) {
                return "Cannot change password level 2";
            }

            return ChangePasswordLevel2DTO::fromModel($user, $flag);
        } catch (Exception $e) {
            DB::rollBack();
            return "Change password level 2 failed: " . $e->getMessage();
        }
    }

    /**
     * Checks if the user has met all security requirements.
     *
     * This function verifies that the user has answered the required number of security questions 
     * and has set a second-level password for enhanced security.
     *
     * @param Request $request The HTTP request object containing user input or session data.
     * @param User $user The user object representing the current user being checked.
     * @param QuestionService $questionService Service responsible for handling security questions.
     *
     * @return bool Returns true if all security conditions are met and did set password level 2; otherwise, returns false.
     */
    public function checkUserSecurity(Request $request, string $user_id, QuestionService $questionService): bool
    {
        $user = User::where("user_id", $user_id)->first();
        $questionsDTO = $questionService->getQuestionListOfUser($request, $user);

        if (count($questionsDTO) != QuestionService::MAX_QUESTIONS_QUANTITY) {
            return false;
        }

        if (empty($user->user_password_level_2)) {
            return false;
        }

        return true;
    }

    /**
     * Encrypts a plaintext string using AES-256-CBC with a given key.
     *
     * @param string $plaintext The data to be encrypted.
     * @param string $key The encryption key.
     * @return string The base64 encoded encrypted data.
     */
    public function encrypt_with_key($plaintext, $key): string
    {
        $iv = substr(hash('sha256', $key), 0, openssl_cipher_iv_length('aes-256-cbc'));

        $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($ciphertext);
    }

    /**
     * Decrypts a base64 encoded encrypted string using AES-256-CBC with a given key.
     *
     * @param string $encryptedData The base64 encoded encrypted data.
     * @param string $key The decryption key.
     * @return string The decrypted plaintext.
     */
    public function decrypt_with_key($encryptedData, $key): string
    {
        $ciphertext = base64_decode($encryptedData);

        $iv = substr(hash('sha256', $key), 0, openssl_cipher_iv_length('aes-256-cbc'));

        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return $plaintext;
    }

    /**
     * Encrypts sensitive attributes of a User object.
     *
     * @param User $user The User object with attributes to encrypt.
     * @return User The User object with encrypted attributes.
     */
    public function encryptUser(User $user): User
    {
        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);

        $user->user_first_name = $this->encrypt_with_key($user->user_first_name, $key);
        $user->user_last_name = $this->encrypt_with_key($user->user_last_name, $key);
        $user->user_email = $this->encrypt_with_key($user->user_email, $key);
        $user->user_phone = $this->encrypt_with_key($user->user_phone, $key);
        $user->user_birthday = $this->encrypt_with_key($user->user_birthday, $key);

        return $user;
    }

    /**
     * Decrypts sensitive attributes of a User object.
     *
     * @param User $user The User object with encrypted attributes.
     * @return User The User object with decrypted attributes.
     */
    public function decryptUser(User $user): User
    {
        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);

        $user->user_first_name = $this->decrypt_with_key($user->user_first_name, $key);
        $user->user_last_name = $this->decrypt_with_key($user->user_last_name, $key);
        $user->user_email = $this->decrypt_with_key($user->user_email, $key);
        $user->user_phone = $this->decrypt_with_key($user->user_phone, $key);
        $user->user_birthday = $this->decrypt_with_key($user->user_birthday, $key);

        return $user;
    }
}
