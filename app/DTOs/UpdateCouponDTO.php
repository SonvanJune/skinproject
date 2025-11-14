<?php

namespace App\DTOs;

use App\Models\Coupon;

/**
 * Data Transfer Object for updating a coupon.
 */
class UpdateCouponDTO
{
    /** @var string The name of the coupon. */
    public string $name;

    /** @var string The unique code for the coupon. */
    public string $code;

    /** @var string The release date of the coupon. */
    public string $release;

    /** @var string The expiration date of the coupon. */
    public string $expired;

    /** @var float The discount percentage of the coupon (per hundred). */
    public float $coupon_per_hundred;

    /** @var string The price or value of the coupon. */
    public string $price;

    /**
     * UpdateCouponDTO constructor.
     *
     * @param array $data An associative array containing coupon data.
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->code = $data['code'];
        $this->release = $data['release'];
        $this->expired = $data['expired'];
        $this->coupon_per_hundred = $data['coupon_per_hundred'];
        $this->price = $data['price'];
    }

    /**
     * Create an UpdateCouponDTO instance from a Coupon model.
     *
     * @param Coupon $coupon The Coupon model instance.
     * @return self A new UpdateCouponDTO instance.
     */
    public static function fromModel(Coupon $coupon): self
    {
        return new self([
            'name' => $coupon->coupon_name ? $coupon->coupon_name : '',
            'code' => $coupon->coupon_code ? $coupon->coupon_code : '',
            'release' => $coupon->coupon_release ? $coupon->coupon_release->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            'expired' => $coupon->coupon_expired ? $coupon->coupon_release->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            'coupon_per_hundred' => $coupon->coupon_per_hundred ? $coupon->coupon_per_hundred : 0,
            'price' => $coupon->coupon_price ? $coupon->coupon_price : ''
        ]);
    }
}
