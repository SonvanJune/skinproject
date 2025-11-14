<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->string('product_name', 255);
            $table->string('product_price', 11);
            $table->string('product_file_path', 255);
            $table->uuid('post_id');
            $table->integer('product_views')->default(0);
            $table->integer('product_fake_views')->default(0);
            $table->boolean('product_status_views')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->primary(['product_id', 'post_id']);
            $table->index('post_id', 'fk_products_posts1_idx');
            $table->foreign('post_id')->references('post_id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
