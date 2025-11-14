<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create 10 sample questions
        for ($i = 1; $i <= 10; $i++) {
            Question::create([
                'question_id' => Str::uuid(),
                'question_text' => 'Here is the question ' . $i . '?',
            ]);
        }
    }
}
