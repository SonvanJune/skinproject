<?php

namespace App\DTOs;

use App\Models\Question;

class CreateQuestionDTO
{
    // Properties for the question details
    public string $question_id;
    public string $question_text;
    public string $created_at;
    public string $updated_at;

    /**
     * Constructor to initialize the DTO with question details.
     *
     * @param string $question_id - The unique identifier for the question.
     * @param string $question_text - The text content of the question.
     * @param string $created_at - Timestamp of question creation.
     * @param string $updated_at - Timestamp of last update.
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
     * Static factory method to create a DTO from a Question model.
     *
     * @param Question $question - The Question model instance.
     * @return self - A new DTO instance with mapped data.
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