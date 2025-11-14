<?php

namespace App\Mail;

use App\DTOs\CreateOTPDTO;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPForForgetingPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $user;
    public string $otp;
    public int $valid_duration;

    public string $email = '';
    public string $app_name;
    public string $support_mail;

    /**
     * Create a new message instance.
     * 
     * @param User $user the client
     * @param CreateOTPDTO $otp the dto contains the information of the otp that need to be sent to the client
     * @return void
     */
    public function __construct(User $user, CreateOTPDTO $otp)
    {
        $this->user = $user->user_first_name . " " . $user->user_last_name;
        $this->otp = $otp->one_time_password_code;
        $this->valid_duration = config('valid.otp.minute.limit', OTPService::DEFAULT_VALID_OTP_MINUTE_LIMIT);
        $this->email = $user->user_email;

        $this->app_name = config('app.name', __('message.webName'));
        $this->support_mail = config('mail.email_customer', '');
    }

    /**
     * Build the message contains the otp to change user's password
     *
     * @return $this the instance to send the message
     */
    public function build()
    {
        return $this
            ->to($this->email)
            ->subject(__('message.webName') . " Password Change OTP")
            ->view('mail.forgeting_password');
    }
}
