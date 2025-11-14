<?php

use App\Services\OTPService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOneTimePasswordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('one_time_passwords');
        Schema::create('one_time_passwords', function (Blueprint $table) {
            $table->uuid('one_time_password_id');
            $table->string('one_time_password_code', OTPService::OTP_LENGTH);
            $table->timestamp('created_at')->useCurrent();
            $table->uuid('user_id');
            $table->integer('one_time_password_type');
            $table->primary(['one_time_password_id', 'user_id']);
            $table->index('user_id', 'fk_one_time_passwords_users1_idx');

            $table->foreign('user_id', 'fk_one_time_passwords_users1')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('one_time_passwords');
    }
}
