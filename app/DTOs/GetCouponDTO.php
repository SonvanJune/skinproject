<?php

namespace App\DTOs;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderCoupon;
use Illuminate\Support\Collection;

/**
 * Data Transfer Object for retrieving coupon information.
 */
class GetCouponDTO
{
    /** @var string The id of the coupon. */
    public string $coupon_id;

    /** @var string The name of the coupon. */
    public string $coupon_name;

    /** @var string The unique code for the coupon. */
    public ?string $coupon_code;

    /** @var string The release date of the coupon. */
    public string $coupon_release;

    /** @var string The expiration date of the coupon. */
    public ?string $coupon_expired;

    /** @var int The discount percentage of the coupon (per hundred). */
    public ?int $coupon_per_hundred;

    /** @var string The price or value of the coupon. */
    public ?string $coupon_price;

    /** @var GetProductDTO The price or value of the coupon. */
    public ?GetProductDTO $product;

    /** @var bool the status is use in order or not*/
    public bool $is_used;

    /**
     * GetCouponDTO constructor.
     *
     * @param string $coupon_name The name of the coupon.
     * @param string $coupon_code The unique code for the coupon.
     * @param  $coupon_release The release date of the coupon.
     * @param  $coupon_expired The expiration date of the coupon.
     * @param int $coupon_per_hundred The discount percentage of the coupon (per hundred).
     * @param string $coupon_price The price or value of the coupon.
     */
    public function __construct(
        string $coupon_id,
        string $coupon_name,
        ?string $coupon_code,
        string $coupon_release,
        ?string $coupon_expired,
        ?int $coupon_per_hundred,
        ?string $coupon_price,
        ?GetProductDTO $product,
        bool $is_used
    ) {
        $this->coupon_id = $coupon_id;
        $this->coupon_name = $coupon_name;
        $this->coupon_code = $coupon_code;
        $this->coupon_release = $coupon_release;
        $this->coupon_expired = $coupon_expired;
        $this->coupon_per_hundred = $coupon_per_hundred;
        $this->coupon_price = $coupon_price;
        $this->product = $product;
        $this->is_used = $is_used;
    }

    /**
     * Create a GetCouponDTO instance from a Coupon model.
     *
     * @param Coupon $coupon The Coupon model instance.
     * @return self A new GetCouponDTO instance.
     */
    public static function fromModel(Coupon $coupon): self
    {
        return new self(
            $coupon->coupon_id,
            $coupon->coupon_name,
            $coupon->coupon_code,
            $coupon->coupon_release,
            $coupon->coupon_expired,
            $coupon->coupon_per_hundred,
            $coupon->coupon_price,
            $coupon->product ? GetProductDTO::fromModel($coupon->product, null) : null,
            self::checkIsUseInOrder($coupon)
        );
    }

    /**
     * Create an array of GetCouponDTO instances from an array of Coupon models.
     *
     * @param array $coupons An array of Coupon model instances.
     * @return array An array of GetCouponDTO instances.
     */
    public static function fromModels(Collection $coupons): array
    {
        $result = [];
        foreach ($coupons as $coupon) {
            $result[] = self::fromModel($coupon);
        }
        return $result;
    }

    public static function checkIsUseInOrder(Coupon $coupon)
    {
        if ($coupon->coupon_code) {
            $couponUseInOrder = Order::where('coupon_id', $coupon->coupon_id)->first();
            if ($couponUseInOrder) {
                return true;
            }
        } else {
            $couponUseInOrderCoupon = OrderCoupon::where('coupon_id', $coupon->coupon_id)->first();
            if ($couponUseInOrderCoupon) {
                return true;
            }
        }
        return false;
    }
}
