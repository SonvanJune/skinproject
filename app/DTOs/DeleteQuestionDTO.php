<?php

namespace App\DTOs;

use App\Models\Question;

class DeleteQuestionDTO
{
    public string $question_id;
    public string $question_text;
    public string $created_at;  
    public string $updated_at;  

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
     * Create a DeleteQuestionDTO object from a Question model instance.
     * @param Question $question - The Question model instance.
     * @return self - Returns a DeleteQuestionDTO instance populated with question details.
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