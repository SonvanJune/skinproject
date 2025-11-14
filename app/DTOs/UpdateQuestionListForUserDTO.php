<?php

namespace App\DTOs;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UpdateQuestionListForUserDTO
{
    public string $user_id;
    public string $user_first_name; 
    public string $user_last_name;
    public string $user_email;  
    public int $user_status = 0; 
    public string $user_phone;    
    public string $user_birthday;  
    public string $created_at;      
    public string $updated_at;    
    public array | Collection $questions;

    /**
     * Constructor to initialize the DTO with given user details and assigned questions.
     *
     * @param string $user_id The unique identifier for the user.
     * @param string $user_first_name The user's first name.
     * @param string $user_last_name The user's last name.
     * @param string $user_email The user's email address.
     * @param int $user_status The user's status (e.g., active or inactive).
     * @param string $user_phone The user's phone number.
     * @param string $user_birthday The user's date of birth.
     * @param string $created_at Timestamp of when the user was created.
     * @param string $updated_at Timestamp of when the user was last updated.
     * @param array | Collection $questions List of questions assigned to the user.
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
        array | Collection $questions
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
        $this->questions = $questions;
    }

    /**
     * Static method to create an AssignQuestionListForUserDTO instance from a User model.
     *
     * @param User $user The User model instance to convert into a DTO.
     * @return self Returns the created AssignQuestionListForUserDTO instance.
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
            $user->created_at,                
            $user->updated_at,                
            $user->questions()->get()->pluck("question_text")->toArray()
        );
    }
}