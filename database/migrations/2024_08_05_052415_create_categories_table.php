<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('categories');
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('category_id')->default(DB::raw('(uuid())'))->primary();
            $table->string('category_name', 255);
            $table->string('category_slug', 255);
            $table->string('category_image_path', 255)->nullable();
            $table->string('category_image_alt', 255)->nullable();
            $table->tinyInteger('category_status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->uuid('parent_id')->nullable();
            $table->tinyInteger('category_type')->default(1);
            $table->text('category_description')->nullable();
            $table->integer('category_topbar_index')->nullable();
            $table->integer('category_home_index')->nullable();
            $table->timestamp('category_release')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->index('category_id');
            $table->index('parent_id', 'fk_categories_categories1_idx');
            $table->foreign('parent_id')->references('category_id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
