<?php

namespace App\DTOs;

use App\Models\Coupon;

/**
 * Data Transfer Object for creating a coupon.
 */
class CreateCouponDTO
{
    /** @var string The name of the coupon. */
    public ?string $coupon_name;

    /** @var string The unique code for the coupon. */
    public ?string $coupon_code;

    /** @var string The release date of the coupon. */
    public string $coupon_release;

    /** @var string The expiration date of the coupon. */
    public ?string $coupon_expired;

    /** @var float The discount percentage of the coupon (per hundred). */
    public ?float $coupon_per_hundred;

    /** @var string The price or value of the coupon. */
    public ?string $coupon_price;


    /**
     * CreateCouponDTO constructor.
     *
     * @param array $data An associative array containing coupon data.
     */
    public function __construct(array $data)
    {
        $this->coupon_name = $data['coupon_name'];
        $this->coupon_code = $data['coupon_code'];
        $this->coupon_release = $data['coupon_release'];
        $this->coupon_expired = $data['coupon_expired'];
        $this->coupon_per_hundred = $data['coupon_per_hundred'];
        $this->coupon_price = $data['coupon_price'];
    }
    /**
     * Create a CreateCouponDTO instance from a Coupon model.
     *
     * @param Coupon $coupon The Coupon model instance.
     * @return self A new CreateCouponDTO instance.
     */
    public static function fromModel(Coupon $coupon): self
    {
        return new self([
            'coupon_name' => $coupon->coupon_name,
            'coupon_code' => $coupon->coupon_code,
            'coupon_release' => $coupon->coupon_release,
            'coupon_expired' => $coupon->coupon_expired,
            'coupon_per_hundred' => $coupon->coupon_per_hundred,
            'coupon_price' => $coupon->coupon_price
        ]);
    }
}
