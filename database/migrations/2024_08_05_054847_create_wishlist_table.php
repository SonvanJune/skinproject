<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateWishlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('wishlist');

        // Create the table
        Schema::create('wishlist', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('product_id');
            $table->primary(['user_id', 'product_id']);
            $table->index('product_id', 'fk_users_has_products_products1_idx');
            $table->index('user_id', 'fk_users_has_products_users1_idx');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('wishlist');
    }
}
