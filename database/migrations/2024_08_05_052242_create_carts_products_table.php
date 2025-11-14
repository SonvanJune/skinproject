<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('carts_products');
        Schema::create('carts_products', function (Blueprint $table) {
            $table->uuid('cart_id');
            $table->uuid('product_id');
            $table->primary(['cart_id', 'product_id']);
            $table->index('product_id', 'fk_carts_has_products_products1_idx');
            $table->index('cart_id', 'fk_carts_has_products_carts1_idx');

            // Optional: You can add foreign key constraints if you have carts and products tables
            $table->foreign('cart_id')->references('cart_id')->on('carts')->onDelete('cascade');
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
        Schema::dropIfExists('carts_products');
    }
}
