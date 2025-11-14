<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('logs');
        Schema::create('logs', function (Blueprint $table) {
            $table->uuid('log_id')->primary();
            $table->integer('log_type');
            $table->text('log_action');
            $table->integer('log_line');
            $table->string('log_url', 255);
            $table->string('log_request', 255)->nullable();
            $table->string('log_response', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
