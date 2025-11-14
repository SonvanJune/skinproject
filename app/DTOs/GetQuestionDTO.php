<?php

namespace App\DTOs;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

class GetQuestionDTO
{
    // Properties representing the details of a question
    public string $question_id;
    public string $question_text;
    public string $created_at;
    public string $updated_at;
    public int $quantity_of_users = 0;

    /**
     * Constructor to initialize the GetQuestionDTO with question details.
     *
     * @param string $question_id - Unique identifier for the question.
     * @param string $question_text - The text content of the question.
     * @param string $created_at - Timestamp of when the question was created.
     * @param string $updated_at - Timestamp of when the question was last updated.
     */
    public function __construct(
        string $question_id,
        string $question_text,
        string $created_at,
        string $updated_at,
        int $quantity_of_users
    ) {
        $this->question_id = $question_id;
        $this->question_text = $question_text;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->quantity_of_users = $quantity_of_users;
    }

    /**
     * Static method to create a GetQuestionDTO instance from a Question model.
     *
     * @param Question $question - The Question model instance to convert into a DTO.
     * @return self - A new GetQuestionDTO instance populated with data from the Question model.
     */
    public static function fromModel(Question $question): self
    {
        return new self(
            $question->question_id,
            $question->question_text,
            $question->created_at,
            $question->updated_at,
            $question->users()->count()
        );
    }

    /**
     * Static method to convert a collection of Question models into an array of GetQuestionDTO instances.
     *
     * @param Collection|array $questions - A collection or array of Question model instances to be converted.
     * @return array - An array of GetQuestionDTO instances created from the Question models.
     */
    public static function fromModels(Collection|array $questions): array
    {
        $result = [];

        foreach ($questions as $question) {
            $result[] = self::fromModel($question);
        }

        return $result;
    }
}