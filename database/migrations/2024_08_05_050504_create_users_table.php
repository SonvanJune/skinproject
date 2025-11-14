<?php

use App\Services\UserService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        // Create the table
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('user_first_name', 255);
            $table->string('user_last_name', 255);
            $table->string('user_email', 255);
            $table->string('user_password', 255);
            $table->string('user_password_level_2', 255)->nullable();
            $table->integer('user_status')->default(0);
            $table->string('user_phone', 255);
            $table->string('user_birthday', 255);
            $table->string('user_avatar', 255)->default(UserService::DEFAULT_AVATAR);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
