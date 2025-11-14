<?php

namespace App\Services;

use App\DTOs\CreateOTPDTO;
use App\DTOs\DeleteOTPDTO;
use App\DTOs\GetOTPDTO;
use App\DTOs\ValidateOTPDTO;
use App\Mail\OTPForForgetingPasswordMail;
use App\Mail\OTPForRegistrationMail;
use App\Mail\OTPForTradingMail;
use App\Models\OneTimePassword;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OTPService
{
    public const DEFAULT_VALID_OTP_MINUTE_LIMIT = 5;

    public const TYPE_OTP_FOR_FORGETTING_PASSWORD = 1;
    public const TYPE_OTP_FOR_TRADING = 2;
    public const TYPE_OTP_FOR_ACTIVE_ACCOUNT = 3;

    public const OTP_LENGTH = 6;

    public static function getOTPRegex()
    {
        return "/^\w{" . OTPService::OTP_LENGTH . "}$/";
    }

    /**
     * Create a new OTP for a user.
     *
     * @param Request $request The HTTP request object
     * @return CreateOTPDTO|string Returns a DTO containing the OTP data or an error message
     */
    public function createOTP(Request $request, User $user, int $otp_type)
    {
        if (!$this->checkTypeOfOTP($otp_type)) {
            return "Invalid OTP type to create";
        }

        //check exist otp
        $otp = OneTimePassword::where(["user_id" => $user->user_id, "one_time_password_type" => $otp_type])->first();

        try {
            DB::beginTransaction();

            if (!$otp) {
                $otp = new OneTimePassword();
                $otp->one_time_password_id = Str::uuid()->toString();
                $otp->user_id = $user->user_id;
                $otp->created_at = now();
                $otp->one_time_password_type = $otp_type;
            }
            $otp->created_at = now();
            $otp->one_time_password_code = $this->generateOTP();
            $otp->save();

            DB::commit();

            return CreateOTPDTO::fromModel($otp);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to create OTP: ' . $e->getMessage();
        }
    }

    /**
     * Sends an OTP (One-Time Password) to the user based on the provided OTP type.
     *
     * @param Request $request The current HTTP request instance.
     * @param User $user The user to whom the OTP will be sent.
     * @param int $otp_type The type of OTP to send (e.g., account activation, password reset, trading).
     * @return string|bool A message string on failure or true on success.
     */
    public function sendOTP(Request $request, User $user, int $otp_type): string|bool
    {
        if (!$this->checkTypeOfOTP($otp_type)) {
            return "Invalid OTP type to send";
        }

        $otpDTO = $this->createOTP($request, $user, $otp_type);

        if (is_string($otpDTO)) {
            return "Cannot send OTP: " . $otpDTO;
        }

        $mail = new Mailable();

        switch ($otp_type) {
            case OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT:
                $mail = new OTPForRegistrationMail($user, $otpDTO);
                break;

            case OTPService::TYPE_OTP_FOR_FORGETTING_PASSWORD:
                $mail = new OTPForForgetingPasswordMail($user, $otpDTO);
                break;

            case OTPService::TYPE_OTP_FOR_TRADING:
                $mail = new OTPForTradingMail($user, $otpDTO);
                break;

            default:
                return false;
        }

        try {
            Mail::to($user->user_email)->send($mail);
            Log::alert("success");
            return true;
        } catch (\Exception $e) {
            return "Error sending OTP email: " . $e->getMessage();
        }
    }

    public function resendOTP(Request $request, string $email, int $otp_type, UserService $userService): string|bool
    {
        if (!$this->checkTypeOfOTP($otp_type)) {
            return "Invalid OTP type to send";
        }
        $user = $userService->getExistUserByEmail($email);
        if(!$user){
            return "User not found with email: " . $email;
        }
        $otpDTO = $this->createOTP($request, $user, $otp_type);
        if (is_string($otpDTO)) {
            return "Cannot send OTP: " . $otpDTO;
        }

        $mail = new Mailable();

        switch ($otp_type) {
            case OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT:
                $mail = new OTPForRegistrationMail($user, $otpDTO);
                break;

            case OTPService::TYPE_OTP_FOR_FORGETTING_PASSWORD:
                $mail = new OTPForForgetingPasswordMail($user, $otpDTO);
                break;

            case OTPService::TYPE_OTP_FOR_TRADING:
                $mail = new OTPForTradingMail($user, $otpDTO);
                break;

            default:
                return false;
        }

        try {
            Mail::to($email)->send($mail);
            return true;
        } catch (\Exception $e) {
            return "Error sending OTP email: " . $e->getMessage();
        }
    }

    /**
     * Retrieve an OTP by the user ID.
     *
     * @param Request $request The HTTP request object
     * @return GetOTPDTO|string Returns a DTO with the OTP or an error message
     */
    public function readOTPByUser(Request $request, User $user, int $otp_type): GetOTPDTO|string
    {
        if (!$this->checkTypeOfOTP($otp_type)) {
            return "Invalid OTP type to read";
        }

        $otp = OneTimePassword::where(['user_id' => $user->user_id, "one_time_password_type" => $otp_type])->first();

        if (!$otp) {
            return "OTP not found";
        }

        return GetOTPDTO::fromModel($otp);
    }

    /**
     * Delete an OTP for a user.
     *
     * @param Request $request The HTTP request object containing the OTP ID
     * @return DeleteOTPDTO|string Returns a DTO with the deletion flag or an error message
     */
    public function deleteOTP(Request $request, User $user): DeleteOTPDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'one_time_password_id' => 'required|uuid',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!OneTimePassword::where(["one_time_password_id" => $request->input("one_time_password_id"), "user_id" => $user->user_id])->exists()) {
            return "OTP not exists";
        }

        $otp = OneTimePassword::where(["one_time_password_id" => $request->input("one_time_password_id"), "user_id" => $user->user_id])->first();


        try {
            DB::beginTransaction();
            $flag = DB::update("delete from one_time_passwords where user_id = ? and one_time_password_id = ?", [$user->user_id, $request->input("one_time_password_id")]);
            $otp->save();
            DB::commit();

            if ($flag <= 0) {
                return "Cannot delete OTP";
            }

            return DeleteOTPDTO::fromModel($otp);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to remove otp: ' . $e->getMessage();
        }
    }

    /**
     * Validates an OTP (One-Time Password) for a given user and OTP type.
     *
     * @param Request $request The current HTTP request instance.
     * @param User $user The user associated with the OTP.
     * @param int $otp_type The type of OTP to validate (e.g., account activation, password reset, trading).
     * @return ValidateOTPDTO|string Returns a DTO object on successful validation or an error message string on failure.
     */
    public function validateOTP(Request $request, User $user, int $otp_type): ValidateOTPDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'one_time_password_code' => 'required|regex:' . OTPService::getOTPRegex(),
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $otpExists = OneTimePassword::where([
            "one_time_password_code" => $request->input("one_time_password_code"),
            "user_id" => $user->user_id
        ])->exists();

        if (!$otpExists) {
            return "OTP not exists";
        }

        $otp = OneTimePassword::where([
            "one_time_password_code" => $request->input("one_time_password_code"),
            "user_id" => $user->user_id
        ])->first();

        if ($otp->one_time_password_type != $otp_type) {
            return "OTP type does not match";
        }

        $limit = config("app.env.VALID_OTP_MINUTE_LIMIT", $this::DEFAULT_VALID_OTP_MINUTE_LIMIT);

        $limitDate = date("Y-m-d H:i:s", strtotime("$otp->created_at + $limit minutes"));

        $flag = now() <= $limitDate;

        if (!$flag) {
            return "Cannot validate the OTP. OTP is expired";
        }

        return ValidateOTPDTO::fromModel($otp, $flag);
    }

    /**
     * Generate a random OTP code.
     *
     * @return string Returns a 6-character OTP code
     */
    private function generateOTP(): string
    {
        $min = (int) str_repeat("1", $this::OTP_LENGTH);
        $otp = rand($min, $min * 9) . "";
        return $otp;
    }

    /**
     * Checks if the provided OTP type is valid.
     *
     * @param int $otp_type The OTP type to check.
     * @return bool Returns true if the OTP type is valid, false otherwise.
     */
    private function checkTypeOfOTP(int $otp_type): bool
    {
        $types = [
            OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT,  
            OTPService::TYPE_OTP_FOR_FORGETTING_PASSWORD,
            OTPService::TYPE_OTP_FOR_TRADING  
        ];

        return in_array($otp_type, $types);
    }
}
