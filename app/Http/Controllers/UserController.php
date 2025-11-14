<?php

namespace App\Http\Controllers;

use App\DTOs\GetUserDTO;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\OrderService;
use App\Services\OTPService;
use App\Services\QuestionService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    protected $categoryService;
    protected $userService;
    protected $otpService;
    protected $cartService;
    protected $questionService;
    protected $orderService;
    protected $toResetPassword = 'Reset Password';
    protected $toRegister = 'Register';
    protected $not_only_otp = 'false';
    protected $only_otp = 'true';
    protected $trans_reset_password = "resetPassword";
    protected $trans_active_register = "activeRegister";
    protected $admin_url = 'admin.skinshop.com';

    public function __construct(CategoryService $categoryService, UserService $userService, OTPService $otpService, CartService $cartService, QuestionService $questionService)
    {
        $this->userService = $userService;
        $this->categoryService = $categoryService;
        $this->otpService = $otpService;
        $this->cartService = $cartService;
        $this->questionService = $questionService;
    }

    public function handleLogin(Request $request)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $parsedUrl = parse_url(url()->previous());
        $host = $parsedUrl['host'];

        $user = $this->userService->loginUser($request);
        if (parent::checkIsString($user)) {
            return redirect()->route('login')->with('failed', $user);
        }

        if (!in_array(RoleService::USER_ROLE, $user->roles)) {
            return redirect()->route('login')->with('failed', "Wrong account or password");
        }
        $isNeedSecurity = !$this->userService->checkUserSecurity($request, $user->user_id, $this->questionService);

        return redirect()->route('home')->with([
            'need_security' => $isNeedSecurity == true ? 'Security building steps need to be taken' : null,
        ]);
    }

    public function handleAdminLogin(Request $request)
    {
        $parsedUrl = parse_url(url()->previous());
        $host = $parsedUrl['host'];

        $user = $this->userService->loginUser($request);
        if (parent::checkIsString($user)) {
            return redirect()->route('admin.login')->with('failed', $user);
        }

        $isNeedSecurity = !$this->userService->checkUserSecurity($request, $user->user_id, $this->questionService);

        if (in_array(RoleService::ADMIN_ROLE, $user->roles) || in_array(RoleService::SUB_ADMIN_ROLE, $user->roles)) {
            return redirect()->route('admin.dashboard')->with([
                'success' => $user,
                'need_security' => $isNeedSecurity == true ? 'Security building steps need to be taken' : null,
            ]);
        } else {
            $this->userService->logoutUser($request);
            return redirect()->route('admin.login')->with('failed', "Wrong account or password");
        }
    }

    public function handleRegister(Request $request)
    {
        if (parent::checkMaintenance(null) == "off") {
            return redirect()->route('maintenance');
        }
        $user = $this->userService->registerUser($request, $this->otpService);
        if (parent::checkIsString($user)) {
            return redirect()->route('register')->with('register_failed', $user);
        } else {
            $locale = App::getLocale();
            return redirect()->to(url("/{$locale}/otp?t={$this->toRegister}&e={$request->email}&o={$this->only_otp}&tr={$this->trans_active_register}"));
        }
    }

    public function handleForgetPassword(Request $request)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $locale = App::getLocale();
        if ($request->user_email) {
            $e = $request->user_email;
            $sendEmail = $this->userService->forgetPassword($request, $this->otpService);
            if (parent::checkIsString($sendEmail)) {
                return redirect()->route('forgetPassword')->with('failed', $sendEmail);
            } else {
                return redirect()->to(url("/{$locale}/otp?t={$this->toResetPassword}&e={$e}&o={$this->not_only_otp}&tr={$this->trans_reset_password}"));
            }
        } else {
            return redirect()->route('forgetPassword');
        }
    }

    public function handleChangePassword(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $user = $this->userService->encryptUser($user);
        if ($user != null) {
            if (
                $request->current_password && $request->new_password
            ) {
                $changePassword = $this->userService->changePassword($request, $user);
                if (!parent::checkIsString($changePassword)) {
                    return redirect()->back()->with('change_password_success', "Password changed successfully");
                } else {
                    return redirect()->back()->with('error', $changePassword);
                }
            } else {
                return redirect()->back()->with('error', "An error occurred.");
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }
    }

    public function handleChangePasswordLevel2(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $user = $this->userService->encryptUser($user);
        if ($user != null) {
            if (
                $request->new_password_level_2 && $request->securityQuestion1
                && $request->securityQuestion2 && $request->securityQuestion3 && $request->securityAnswer1 && $request->securityAnswer2 && $request->securityAnswer3
            ) {
                $question_list = parent::setPasswordRequest($request);
                $request->merge(['question_list' => $question_list]);
                $changePasswordLevel2 = $this->userService->changePasswordLevel2($request, $user, $this->questionService);
                if (!parent::checkIsString($changePasswordLevel2)) {
                    return redirect()->back()->with('change_password_success', "Password level 2 changed successfully");
                } else {
                    return redirect()->back()->with('error', $changePasswordLevel2);
                }
            } else {
                return redirect()->back()->with('error', "An error occurred.");
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }
    }

    public function handleChangeSecurityQuestion(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $user = $this->userService->encryptUser($user);
        if ($user != null) {
            if (
                $request->user_password_level_2 && $request->securityQuestion1
                && $request->securityQuestion2 && $request->securityQuestion3 && $request->securityAnswer1 && $request->securityAnswer2 && $request->securityAnswer3
            ) {
                $question_list = parent::setPasswordRequest($request);
                $request->merge(['question_list' => $question_list]);
                $changeSecurityQuestion = $this->questionService->assignQuestionListForUser($request, $user, $this->userService);
                if (!parent::checkIsString($changeSecurityQuestion)) {
                    return redirect()->back()->with('change_success', "Change security question successfully");
                } else {
                    return redirect()->back()->with('error', $changeSecurityQuestion);
                }
            } else {
                return redirect()->back()->with('error', "An error occurred.");
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }
    }

    public function handleOtpResetPassword(Request $request)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $respones = $this->userService->resetPassword($request, $this->otpService);
        if (parent::checkIsString($respones)) {
            $locale = App::getLocale();
            return redirect()->to(url("/{$locale}/otp?t={$this->toResetPassword}&e={$request->user_email}&o={$this->not_only_otp}&tr={$this->trans_reset_password}"))->with('failed', $respones);
        } else {
            return redirect()->route('login')->with('reset_success', "Password changed successfully");
        }
    }

    public function handleOtpActiveUser(Request $request)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $respones = $this->userService->activeUserWithEmail($request, $this->otpService);
        if (parent::checkIsString($respones)) {
            $locale = App::getLocale();
            return redirect()->to(url("/{$locale}/otp?t={$this->toRegister}&e={$request->user_email}&o={$this->only_otp}&tr={$this->trans_active_register}"))->with('failed', $respones);
        } else {
            $isNeedSecurity = !$this->userService->checkUserSecurity($request, $respones->user_id, $this->questionService);
            return redirect()->route('home')->with([
                'need_security' => $isNeedSecurity == true ? 'Security building steps need to be taken' : null,
            ]);
        }
    }

    public function handleSetUpSecurity(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $user = $this->userService->encryptUser($user);
        if ($user != null) {
            if (
                $request->password_level_2 && $request->securityQuestion1
                && $request->securityQuestion2 && $request->securityQuestion3 && $request->securityAnswer1 && $request->securityAnswer2 && $request->securityAnswer3
            ) {
                $setPassLv2 = $this->userService->setPasswordLevel2($request, $user);
                if (parent::checkIsString($setPassLv2)) {
                    return redirect()->back()->with('security_error', $setPassLv2);
                }

                $question_list = parent::setPasswordRequest($request);
                $request->merge(['question_list' => $question_list]);

                $setQuestions = $this->questionService->assignQuestionListForUser($request, $user, $this->userService);
                if (parent::checkIsString($setQuestions)) {
                    return redirect()->back()->with('security_error', $setQuestions);
                }

                return redirect()->back()->with('security_success', "Security setup successful");
            } else {
                return redirect()->back()->with('error', "An error occurred.");
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }
    }

    public function login(Request $request)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $user_name = parent::checkTokenWhenReload($request, $this->userService);
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        if ($user_name != null) {
            return redirect()->route('home');
        }
        return view('user.login.index', ["categories" => $categories, "position_logo" => parent::getPositionLogo($categories)]);
    }

    public function logout(Request $request)
    {
        $this->userService->logoutUser($request);
        return redirect()->route('login');
    }

    public function adminLogout(Request $request)
    {
        $this->userService->logoutUser($request);
        return redirect()->route('admin.login');
    }

    public function forgetPassword(Request $request)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $user_name = parent::checkTokenWhenReload($request, $this->userService);
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        if ($user_name != null) {
            return redirect()->route('home');
        }
        return view('user.forget-password.index', ["categories" => $categories, "position_logo" => parent::getPositionLogo($categories)]);
    }

    public function changePassword(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $questions = $this->questionService->getQuestionList($request);
        $user_name = "";
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }

        return view('user.change-password.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user" => $user,
            "user_name" => $user_name,
            "cart" => $cart,
            "securityQuestions" => $questions,
            "countQuestion" => $this->questionService::MAX_QUESTIONS_QUANTITY
        ]);
    }

    public function changePasswordLevel2(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        if (!$this->userService->checkUserSecurity($request, $user->user_id, $this->questionService)) {
            return redirect()->route('home')->with([
                'need_security' => 'Security building steps need to be taken'
            ]);
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $questionsOfUser = $this->questionService->getQuestionListOfUser($request, $user);
        $user_name = "";
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }

        return view('user.change-password-level-2.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user" => $user,
            "user_name" => $user_name,
            "cart" => $cart,
            "securityQuestions" => $questionsOfUser
        ]);
    }

    public function changeSecurityQuestions(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        if (!$this->userService->checkUserSecurity($request, $user->user_id, $this->questionService)) {
            return redirect()->route('home')->with([
                'need_security' => 'Security building steps need to be taken'
            ]);
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $questions = $this->questionService->getQuestionList($request);
        $user_name = "";
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }

        return view('user.change-security-questions.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user" => $user,
            "user_name" => $user_name,
            "cart" => $cart,
            "securityQuestions" => $questions->data,
            "countQuestion" => $this->questionService::MAX_QUESTIONS_QUANTITY
        ]);
    }

    public function register(Request $request)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $user_name = parent::checkTokenWhenReload($request, $this->userService);
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        if ($user_name != null) {
            return redirect()->route('home');
        }
        return view('user.register.index', ["categories" => $categories, "position_logo" => parent::getPositionLogo($categories)]);
    }

    public function checkout(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        if ($user) {
            if (parent::checkUserInPage($user->roles) == false) {
                return redirect()->back()->with('error', "You are not allowed to checkout");
            }
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }

        return view('user.checkout.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart
        ]);
    }

    public function myAccount(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }

        return view('user.account.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user" => $user,
            "user_name" => $user_name,
            "cart" => $cart
        ]);
    }

    public function otp(Request $request)
    {
        if (!$request->query('t') || !$request->query('e') || !$request->query('o') || !$request->query('tr')) {
            abort(404);
        } else {
            $type = $request->query('t');
            $email = $request->query('e');
            $only_otp = $request->query('o');
            $transaction = $request->query('tr');

            $user = parent::checkTokenWhenReload($request, $this->userService);
            if ($user != null) {
                return redirect()->route('home');
            }

            if (trim($request->query('tr')) === $this->trans_active_register) {
                $existUser = $this->userService->getExistUserByEmail($email);
                if (!$existUser || $existUser->user_status == UserService::ACTIVE_STATUS || $existUser->user_status == UserService::DELETED_STATUS) {
                    abort(404);
                }
            }

            $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);

            return view('user.otp.index', [
                "type" => $type,
                "email" => $email,
                "only_otp" => $only_otp,
                "countOtp" => OTPService::OTP_LENGTH,
                "transaction" => $transaction,
                "categories" => $categories,
                "position_logo" => parent::getPositionLogo($categories)
            ]);
        }
    }

    public function resendOtpRegister(Request $request, $email)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $user_name = parent::checkTokenWhenReload($request, $this->userService);
        if ($user_name != null) {
            return redirect()->route('home');
        }
        $didInitialOTP = $this->otpService->resendOTP($request, $email, OTPService::TYPE_OTP_FOR_ACTIVE_ACCOUNT, $this->userService);
        if (is_string($didInitialOTP)) {
            return redirect()->back()->with('error' , "Send OTP failed: " . $didInitialOTP);
        }
        return redirect()->back()->with('success' , 'We have sent a verification code to your email :' . $email);
    }

    public function resendOtpForgetPass(Request $request, $email)
    {
        if (parent::checkMaintenance() == "off") {
            return redirect()->route('maintenance');
        }
        $user_name = parent::checkTokenWhenReload($request, $this->userService);
        if ($user_name != null) {
            return redirect()->route('home');
        }
        $didInitialOTP = $this->otpService->resendOTP($request, $email, OTPService::TYPE_OTP_FOR_FORGETTING_PASSWORD, $this->userService);
        if (is_string($didInitialOTP)) {
            return redirect()->back()->with('error' , "Send OTP failed: " . $didInitialOTP);
        }
        return redirect()->back()->with('success' , 'We have sent a verification code to your email :' . $email);
    }
}
