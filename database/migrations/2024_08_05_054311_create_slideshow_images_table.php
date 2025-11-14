<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSlideshowImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('slideshow_images');
        Schema::create('slideshow_images', function (Blueprint $table) {
            $table->uuid('slideshow_image_id')->primary();
            $table->string('slideshow_image_url', 255);
            $table->integer('slideshow_image_index');
            $table->string('slideshow_image_alt', 255);
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
        Schema::dropIfExists('slideshow_images');
    }
}
