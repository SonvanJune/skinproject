<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('posts');
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('post_id')->primary();
            $table->string('post_name', 255)->nullable();
            $table->string('post_slug', 255);
            $table->timestamp('post_release')->useCurrent();
            $table->boolean('post_status')->default(false);
            $table->integer('post_type');
            $table->uuid('user_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->text('post_content');
            $table->string('post_image_path', 255)->nullable();
            $table->string('post_image_alt', 255)->nullable();

            $table->index('user_id', 'user_id_idx');

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
        Schema::dropIfExists('posts');
    }
}
