<?php

namespace App\Providers;

use App\Models\User;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\CouponService;
use App\Services\FileManagerService;
use App\Services\FileService;
use App\Services\FolderManagerService;
use App\Services\MailService;
use App\Services\OrderService;
use App\Services\OTPService;
use App\Services\PaypalService;
use App\Services\PermissionService;
use App\Services\PostService;
use App\Services\ProductImageService;
use App\Services\ProductService;
use App\Services\QuestionService;
use App\Services\RoleService;
use App\Services\SearchService;
use App\Services\Service;
use App\Services\SlideshowImageService;
use App\Services\TrackingCodeService;
use App\Services\UserService;
use App\Services\WishlistService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService();
        });
        $this->app->singleton(CouponService::class, function ($app) {
            return new CouponService();
        });
        $this->app->singleton(FileService::class, function ($app) {
            return new FileService();
        });
        $this->app->singleton(MailService::class, function ($app) {
            return new MailService();
        });
        $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService();
        });
        $this->app->singleton(OTPService::class, function ($app) {
            return new OTPService();
        });
        $this->app->singleton(PermissionService::class, function ($app) {
            return new PermissionService();
        });
        $this->app->singleton(PostService::class, function ($app) {
            return new PostService();
        });
        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService(
                $app->make(ProductImageService::class),
                 $app->make(PostService::class),
                 $app->make(CouponService::class));
        });
        $this->app->singleton(RoleService::class, function ($app) {
            return new RoleService();
        });
        $this->app->singleton(SlideshowImageService::class, function ($app) {
            return new SlideshowImageService();
        });
        $this->app->singleton(TrackingCodeService::class, function ($app) {
            return new TrackingCodeService();
        });
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService($app->make(UserService::class));
        });
        $this->app->singleton(FileManagerService::class, function ($app) {
            return new FileManagerService($app->make(FileService::class), $app->make(ProductService::class));
        });
        $this->app->singleton(FolderManagerService::class, function ($app) {
            return new FolderManagerService($app->make(FileManagerService::class));
        });
        $this->app->singleton(PaypalService::class, function ($app) {
            return new PaypalService();
        });
        $this->app->singleton(ProductImageService::class, function ($app) {
            return new ProductImageService();
        });
        $this->app->singleton(QuestionService::class, function ($app) {
            return new QuestionService();
        });
        $this->app->singleton(SearchService::class, function ($app) {
            return new SearchService();
        });
        $this->app->singleton(Service::class, function ($app) {
            return new Service();
        });
        $this->app->singleton(WishlistService::class, function ($app) {
            return new WishlistService($app->make(UserService::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Blade::component('component.editor.file-manager', 'file-manager');
    }
}
