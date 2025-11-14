<?php

namespace App\DTOs;

use App\Models\User;

class ForgetPasswordDTO
{
    public string $user_email;
    public bool $did_send_otp;

    /**
     * Constructor to initialize the DTO with user details.
     *
     * @param string $user_id The user's unique identifier.
     * @param bool $did_send_otp Indicates if an OTP was sent.
     */
    public function __construct(
        string $user_email,
        bool $did_send_otp
    ) {
        $this->user_email = $user_email;
        $this->did_send_otp = $did_send_otp;
    }

    /**
     * Creates an instance of ForgetPasswordDTO from a User model.
     *
     * @param User $user The User model instance.
     * @param bool $did_send_otp Indicates if an OTP was sent.
     * @return self An instance of ForgetPasswordDTO.
     */
    public static function fromModel(User $user, bool $did_send_otp): self
    {
        return new self(
            $user->user_email,    
            $did_send_otp
        );
    }
}