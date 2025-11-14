<?php

namespace App\DTOs;

class TotalDTo
{
    public int $totalProduct;
    public int $totalProductRelease;
    public int $totalUser;
    public int $totalCategory;
    public int $totalCategoryRelease;
    public int $totalBrand;
    public int $totalBrandRelease;
    public int $totalSubadmin;
    public int $totalSecurityQuestion;
    public int $totalRole;
    public int $totalPost;
    public int $totalPostRelease;
    public int $totalSlideShow;
    public int $totalCoupon;
    public int $totalCouponExpired;
    public int $totalCouponRelease;
    public int $totalOrder;

    public function __construct(int $totalProduct,int $totalProductRelease, int $totalUser, int $totalCategory,int $totalCategoryRelease, int $totalSubadmin, int $totalSecurityQuestion, int $totalRole, int $totalPost, int $totalPostRelease, int $totalSlideShow, int $totalCoupon, int $totalCouponExpired, int $totalCouponRelease, int $totalBrand, int $totalBrandRelease, int $totalOrder)
    {
        $this->totalProduct = $totalProduct;
        $this->totalProductRelease = $totalProductRelease;
        $this->totalUser = $totalUser;
        $this->totalCategory = $totalCategory;
        $this->totalCategoryRelease = $totalCategoryRelease;
        $this->totalSubadmin = $totalSubadmin;
        $this->totalSecurityQuestion = $totalSecurityQuestion;
        $this->totalRole = $totalRole;
        $this->totalPost = $totalPost;
        $this->totalPostRelease = $totalPostRelease;
        $this->totalSlideShow = $totalSlideShow;
        $this->totalCoupon = $totalCoupon;
        $this->totalCouponExpired = $totalCouponExpired;
        $this->totalCouponRelease = $totalCouponRelease;
        $this->totalBrand = $totalBrand;
        $this->totalBrandRelease = $totalBrandRelease;
        $this->totalOrder = $totalOrder;
    }

    public static function fromModel(int $totalProduct,int $totalProductRelease, int $totalUser, int $totalCategory,int $totalCategoryRelease, int $totalSubadmin, int $totalSecurityQuestion, int $totalRole, int $totalPost, int $totalPostRelease, int $totalSlideShow, int $totalCoupon, int $totalCouponExpired, int $totalCouponRelease, int $totalBrand, int $totalBrandRelease, int $totalOrder): self
    {
        return new self($totalProduct,$totalProductRelease,$totalUser, $totalCategory, $totalCategoryRelease, $totalSubadmin, $totalSecurityQuestion, $totalRole, $totalPost, $totalPostRelease, $totalSlideShow, $totalCoupon, $totalCouponExpired, $totalCouponRelease, $totalBrand, $totalBrandRelease, $totalOrder);
    }
}