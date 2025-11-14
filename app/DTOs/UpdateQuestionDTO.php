<?php

namespace App\DTOs;

use App\Models\Question;

class UpdateQuestionDTO
{
    // Properties representing the details of a question
    public string $question_id;
    public string $question_text;
    public string $created_at;
    public string $updated_at;

    /**
     * Constructor to initialize the UpdateQuestionDTO object with question details.
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
        string $updated_at
    ) {
        $this->question_id = $question_id;
        $this->question_text = $question_text;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * Static method to create an UpdateQuestionDTO object from a Question model instance.
     *
     * @param Question $question - The Question model instance.
     * @return self - Returns a new UpdateQuestionDTO instance populated with model data.
     */
    public static function fromModel(Question $question): self
    {
        return new self(
            $question->question_id,
            $question->question_text,
            $question->created_at,
            $question->updated_at
        );
    }
}