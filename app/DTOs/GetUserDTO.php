<?php

namespace App\DTOs;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetUserDTO
{
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
    public array $roles;

    /**
     * Constructor to initialize the DTO with the given values.
     *
     * @param string $user_id The user's ID.
     * @param string $user_first_name The user's first name.
     * @param string $user_last_name The user's last name.
     * @param string $user_email The user's email address.
     * @param int $user_status The user's status (e.g., active, inactive).
     * @param string $user_phone The user's phone number.
     * @param string $user_birthday The user's date of birth.
     * @param string $user_birthday The user's avatar.
     * @param string $created_at Timestamp when the user was created.
     * @param string $updated_at Timestamp when the user was last updated.
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
        array $roles
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
        $this->roles = $roles;
    }

    /**
     * Creates a GetUserDTO instance from a User model.
     *
     * @param User $user The User model to convert into a DTO.
     * @param string $token Optional token for authentication or additional use.
     * @return self The GetUserDTO instance.
     */
    public static function fromModel(User $user): self
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
            $user->roles()->pluck("role_name")->toArray()
        );
    }

    /**
     * Creates an array of GetUserDTO instances from an array of User models.
     *
     * @param array $users Array of User models to convert into DTOs.
     * @return array Array of GetUserDTO instances.
     */
    public static function fromModels(Collection $users): array
    {
        $result = [];
        foreach ($users as $user) {
            $result[] = self::fromModel($user);
        }
        return $result;
    }
}
