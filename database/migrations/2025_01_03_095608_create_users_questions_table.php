<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_questions');
        // Create the table
        Schema::create('users_questions', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('question_id');
            $table->string('user_answer', 255);
            $table->primary(['user_id', 'question_id']);
            $table->index('question_id', 'fk_users_has_questions_questions1_idx');
            $table->index('user_id', 'fk_users_has_questions_users1_idx');

            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_questions');
    }
}
