<?php

namespace App\DTOs;

use App\Models\OneTimePassword;
use App\Models\User;

class GetOTPDTO
{
    public string $one_time_password_id;
    public string $one_time_password_code;
    public int $one_time_password_type;
    public string $created_at;
    public string $user_id;

    public function __construct(
        string $one_time_password_id,
        string $one_time_password_code,
        int $one_time_password_type,
        string $user_id,
        string $created_at
    ) {
        $this->one_time_password_id = $one_time_password_id;
        $this->one_time_password_code = $one_time_password_code;
        $this->one_time_password_type = $one_time_password_type;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }

    public static function fromModel(OneTimePassword $otp): self
    {
        return new self(
            $otp->one_time_password_id,
            $otp->one_time_password_code,
            $otp->one_time_password_type,
            $otp->user_id,
            $otp->created_at,
        );
    }

    public static function fromModels(array $otps): array
    {
        $result = [];
        foreach ($otps as $otp) {
            $result[] = self::fromModel($otp);
        }
        return $result;
    }
}
