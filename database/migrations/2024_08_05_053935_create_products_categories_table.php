<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products_categories');
        Schema::create('products_categories', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->uuid('category_id');

            $table->primary(['product_id', 'category_id']);
            $table->index('category_id', 'fk_products_has_categories_categories1_idx');
            $table->index('product_id', 'fk_products_has_categories_products_idx');

            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_categories');
    }
}
