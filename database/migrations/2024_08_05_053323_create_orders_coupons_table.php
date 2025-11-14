<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('orders_coupons');
        Schema::create('orders_coupons', function (Blueprint $table) {
            $table->uuid('order_id');
            $table->uuid('coupon_id');
            $table->primary(['order_id', 'coupon_id']);
            $table->index('order_id', 'fk_orders_has_coupons_coupons1_idx');
            $table->index('coupon_id', 'fk_coupons_has_orders_orders1_idx');

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('coupon_id')->references('coupon_id')->on('coupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_coupons');
    }
}
