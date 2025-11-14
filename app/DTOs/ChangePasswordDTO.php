<?php

namespace App\DTOs;

use App\Models\User;

class ChangePasswordDTO
{
    // User properties
    public string $user_id;
    public string $user_first_name;
    public string $user_last_name;
    public string $user_email;
    public int $user_status = 0;
    public string $user_phone;
    public string $user_birthday;
    public string $created_at;
    public string $updated_at;
    public array $roles;
    public bool $did_change_password;

    /**
     * Constructor to initialize the DTO with user details.
     *
     * @param string $user_id The user's unique identifier.
     * @param string $user_first_name The user's first name.
     * @param string $user_last_name The user's last name.
     * @param string $user_email The user's email address.
     * @param int $user_status The user's status (e.g., active, inactive).
     * @param string $user_phone The user's phone number.
     * @param string $user_birthday The user's date of birth.
     * @param string $created_at Timestamp when the user was created.
     * @param string $updated_at Timestamp when the user was last updated.
     * @param array $roles The user's roles, passed as an array of role names.
     */
    public function __construct(
        string $user_id,
        string $user_first_name,
        string $user_last_name,
        string $user_email,
        int $user_status,
        string $user_phone,
        string $user_birthday,
        string $created_at,
        string $updated_at,
        array $roles,
        bool $did_change_password
    ) {
        $this->user_id = $user_id;
        $this->user_first_name = $user_first_name;
        $this->user_last_name = $user_last_name;
        $this->user_email = $user_email;
        $this->user_status = $user_status;
        $this->user_phone = $user_phone;
        $this->user_birthday = $user_birthday;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->roles = $roles;
        $this->did_change_password = $did_change_password;
    }

    /**
     * Create an UpdateUserDTO instance from a User model.
     *
     * @param User $user The User model instance.
     * @return self An instance of UpdateUserDTO.
     */
    public static function fromModel(User $user, bool $did_change_password): self
    {
        return new self(
            $user->user_id,
            $user->user_first_name,
            $user->user_last_name,
            $user->user_email,
            $user->user_status,
            $user->user_phone,
            $user->user_birthday,
            $user->created_at,
            $user->updated_at,
            $user->roles()->get()->pluck('role_name')->toArray(),
            $did_change_password
        );
    }
}
