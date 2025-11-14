<?php

namespace App\DTOs;

use App\Models\User;

class RestoreUserDTO
{
    // DTO properties mapping to User model attributes
    public string $user_id;
    public string $user_first_name;
    public string $user_last_name;
    public string $user_email;
    public int $user_status = 0;
    public string $user_phone;
    public string $user_birthday;
    public string $user_avatar;
    public string $created_at;
    public string $updated_at;
    public bool $did_restore;
    public array $role_list;

    /**
     * Constructor to initialize the DTO with user data.
     *
     * @param string $user_id The unique identifier of the user.
     * @param string $user_first_name The first name of the user.
     * @param string $user_last_name The last name of the user.
     * @param string $user_email The email address of the user.
     * @param int $user_status The status of the user's account (e.g., active/inactive).
     * @param string $user_phone The phone number of the user.
     * @param string $user_birthday The birthdate of the user (formatted as 'YYYY-MM-DD').
     * @param string $user_avatar The avatar file name or path for the user.
     * @param string $created_at The timestamp when the user was created.
     * @param string $updated_at The timestamp when the user was last updated.
     * @param string $did_restore A flag for restore success.
     * @param array $role_list A list of roles assigned to the user (e.g., admin, user).
     */
    public function __construct(
        string $user_id,
        string $user_first_name,
        string $user_last_name,
        string $user_email,
        int $user_status,
        string $user_phone,
        string $user_birthday,
        string $user_avatar,
        string $created_at,
        string $updated_at,
        bool $did_restore,
        array $role_list
    ) {
        $this->user_id = $user_id;
        $this->user_first_name = $user_first_name;
        $this->user_last_name = $user_last_name;
        $this->user_email = $user_email;
        $this->user_status = $user_status;
        $this->user_phone = $user_phone;
        $this->user_birthday = $user_birthday;
        $this->user_avatar = $user_avatar;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->did_restore = $did_restore;
        $this->role_list = $role_list;
    }

    /**
     * Creates a new restoreUserDTO instance using the provided User model and did_restore;.
     *
     * This method is used to map the User model data into the DTO format, 
     * ensuring consistent data transfer across application layers.
     *
     * @param User $user The User model containing the user data.
     * @param string $did_restore; An authentication did_restore; for the user.
     * @return self A new instance of the restoreUserDTO populated with user data.
     */
    public static function fromModel(User $user, bool $did_restore): self
    {
        return new self(
            $user->user_id,
            $user->user_first_name,
            $user->user_last_name,
            $user->user_email,
            $user->user_status,
            $user->user_phone,
            $user->user_birthday,
            $user->user_avatar,
            $user->created_at,
            $user->updated_at,
            $did_restore,
            $user->roles()->pluck("role_name")->toArray()
        );
    }
}
