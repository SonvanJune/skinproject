<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('coupons');
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('coupon_id')->primary();
            $table->string('coupon_name', 255)->nullable();
            $table->string('coupon_code', 255)->nullable();
            $table->timestamp('coupon_release')->useCurrent();
            $table->timestamp('coupon_expired')->nullable();
            $table->double('coupon_per_hundred')->nullable();
            $table->string('coupon_price', 13)->nullable();
            $table->uuid('product_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index('product_id', 'fk_coupons_products1_idx');
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
        Schema::dropIfExists('coupons');
    }
}
