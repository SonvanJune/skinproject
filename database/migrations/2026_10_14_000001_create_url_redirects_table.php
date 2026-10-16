<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('url_redirects')) {
            Schema::create('url_redirects', function (Blueprint $table) {
                $table->id();
                $table->string('source_path', 255);
                $table->string('target_path', 500);
                $table->unsignedSmallInteger('status_code')->default(301);
                $table->unsignedBigInteger('hits')->default(0);
                $table->timestamp('last_accessed_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('source_path', 'idx_source_path');
                $table->index('is_active', 'idx_is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_redirects');
    }
};
