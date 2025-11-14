<?php
namespace App\DTOs;

use App\Models\User;

class LoginUserDTO
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
    public string $accessToken;
    public string $refreshToken;
    public array $roles;

    /**
     * Initializes the LoginUserDTO with user data.
     *
     * @param string $user_id User's unique identifier.
     * @param string $user_first_name User's first name.
     * @param string $user_last_name User's last name.
     * @param string $user_email User's email address.
     * @param int $user_status Status of the user's account (e.g., 0 = inactive, 1 = active).
     * @param string $user_phone User's phone number.
     * @param string $user_birthday User's birthdate in 'YYYY-MM-DD' format.
     * @param string $user_avatar User's avatar file name or path.
     * @param string $created_at Timestamp when the user record was created.
     * @param string $updated_at Timestamp when the user record was last updated.
     * @param string $accessToken Authentication token for the user.
     * @param string $accessToken Authentication refresh token for the access token.
     * @param array $roles Roles assigned to the user.
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
        string $accessToken,
        string $refreshToken,
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
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->roles = $roles;
    }

    /**
     * Creates a new `LoginUserDTO` instance from a User model and token.
     *
     * This method converts the User model's data into a `LoginUserDTO` object, 
     * making it easier to transfer user-related data between layers of the application.
     *
     * @param User $user The User model instance containing user information from the database.
     * @param string $accessToken A token for user authentication (e.g., JWT).
     * @return self A new `LoginUserDTO` instance populated with the user's data and authentication token.
     */
    public static function fromModel(User $user, string $accessToken, string $refreshToken): self
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
            $accessToken,
            $refreshToken,
            $user->roles()->pluck("role_name")->toArray()
        );
    }
}