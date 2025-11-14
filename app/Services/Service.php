<?php

namespace App\Services;

use App\DTOs\TotalDTo;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Question;
use App\Models\Role;
use App\Models\SlideshowImage;
use App\Models\User;
use Illuminate\Http\Request;

class Service
{
    /**
     * Returns the total number of products and categories available in the system.
     *
     * @param Request $request The incoming HTTP request (if any parameters or authentication are needed).
     * @return TotalDTO containing the total number of products and categories.
     */
    public function getTotal()
    {
        $totalProduct = 0;
        $totalProductRelease = 0;
        $totalUser = 0;
        $totalCategory = 0;
        $totalCategoryRelease = 0;
        $totalBrand = 0;
        $totalBrandRelease = 0;
        $totalSubadmin = 0;
        $totalSecurityQuestion = 0;
        $totalRole = 0;
        $totalPost = 0;
        $totalPostRelease = 0;
        $totalSlideShow = 0;
        $totalCoupon = 0;
        $totalCouponExpired = 0;
        $totalCouponRelease = 0;
        $totalOrder = 0;

        $totalProduct = Post::where('post_status', '!=' , Post::TYPE_POST_DELETE)->where('post_type', '=', Post::TYPE_PRODUCT)->count();
        $totalProductRelease = Post::where('post_status', '!=' , Post::TYPE_POST_DELETE)->where('post_type', '=', Post::TYPE_PRODUCT)->where('post_release', '<=', now())->where('post_status', Post::STATUS_RELEASE)->count();
        $totalPost = Post::where('post_status', '!=' , Post::TYPE_POST_DELETE)->where('post_type', '=', Post::TYPE_POST)->count();
        $totalPostRelease = Post::where('post_status', '!=' , Post::TYPE_POST_DELETE)->where('post_type', '=', Post::TYPE_POST)->where('post_release', '<=', now())->where('post_status', Post::STATUS_RELEASE)->count();
        $totalUser = User::all()->count();
        $totalSubadmin = User::whereHas('roles', function ($query) {
            $query->where('role_name', RoleService::SUB_ADMIN_ROLE);
        })->count();
        $totalRole = Role::all()->count();
        $totalCategory = Category::where('category_status', '!=' , Category::CATEGORY_STATUS_DELETE)->where('category_type', '=', Category::CATEGORY_TYPE_DEFAULT)->count();
        $totalCategoryRelease = Category::where('category_status', Category::CATEGORY_STATUS_ACTIVE)
            ->where('category_release', '<=', now())->where('category_type', '=', Category::CATEGORY_TYPE_DEFAULT)->count();
        $totalBrand = Category::where('category_type', '=', Category::CATEGORY_TYPE_BRAND)->count();
        $totalBrandRelease = Category::where('category_status', Category::CATEGORY_STATUS_ACTIVE)
            ->where('category_release', '<=', now())->where('category_type', '=', Category::CATEGORY_TYPE_BRAND)->count();
        $totalSecurityQuestion = Question::all()->count();
        $totalSlideShow = SlideshowImage::all()->count();
        $totalCoupon = Coupon::all()->count();
        $totalCouponRelease = Coupon::where('coupon_release', '<=', now())->count();
        $totalCouponExpired = Coupon::where('coupon_release', '<=', now())->where('coupon_expired', '<=', now())->count();
        $totalOrder = Order::where('order_status', '=', OrderService::STATUS_BOUGHT)->count();
        return TotalDTo::fromModel($totalProduct, $totalProductRelease, $totalUser, $totalCategory, $totalCategoryRelease, $totalSubadmin, $totalSecurityQuestion, $totalRole, $totalPost, $totalPostRelease, $totalSlideShow, $totalCoupon, $totalCouponExpired,$totalCouponRelease, $totalBrand, $totalBrandRelease, $totalOrder);
    }

    /**
     * Get the status of maintaince
     * @return 'on' or 'off'
     */
    public function getStatusMaintenance(): string
    {
        if (file_exists(resource_path('setting/maintenance.php'))) {
            $config = include resource_path('setting/maintenance.php');;
            return $config['status'];
        }

        return 'off';
    }
}
