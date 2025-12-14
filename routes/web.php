<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\AdminMailController;
use App\Http\Controllers\Admin\AdminMaintenanceController;
use App\Http\Controllers\Admin\AdminPaypalSettingController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSlideShowController;
use App\Http\Controllers\Admin\AdminQuestionController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminTrackingCodeController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\FolderManagerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localizationRedirect']], function () {
    Route::get('/', [PageController::class, 'home'])->name("home");
    Route::get('/file-management', [FileManagerController::class, 'fileManager'])->name("file");
    Route::get('/editor', [FileManagerController::class, 'editor'])->name("file");

    Route::get('/product-category/{slug}', [CategoryController::class, 'productCategory'])->name("product.category");
    Route::get('/product/{slug}', [ProductController::class, 'product'])->name("product");
    Route::get('/brand/{slug}', [BrandController::class, 'brand'])->name("brand");
    Route::get('/blog/{slug}', [PostController::class, 'postDetail'])->name("post.detail");
    Route::get('/blog', [PostController::class, 'blog'])->name("blog");
    Route::get('/login', [UserController::class, 'login'])->name("login");
    Route::get('/logout', [UserController::class, 'logout'])->name("logout");
    Route::get('/register', [UserController::class, 'register'])->name("register");
    Route::get('/forget-password', [UserController::class, 'forgetPassword'])->name("forgetPassword");
    Route::get('/account/change-security-questions', [UserController::class, 'changeSecurityQuestions'])->name("changeSecurityQuestions");
    Route::get('/account/change-password', [UserController::class, 'changePassword'])->name("changePassword");
    Route::get('/account/change-password-level-2', [UserController::class, 'changePasswordLevel2'])->name("changePasswordLevel2");
    Route::get('/account/orders', [OrderController::class, 'order'])->name("order");
    Route::get('/account', [UserController::class, 'myAccount'])->name("account");
    Route::get('/checkout', [UserController::class, 'checkout'])->name("checkout");
    Route::get('/otp', [UserController::class, 'otp']);
    Route::get('/cart', [CartController::class, 'cart'])->name("cart");
    Route::post('/login', [UserController::class, 'handleLogin'])->name('login.submit');
    Route::post('/register', [UserController::class, 'handleRegister'])->name('register.submit');
    Route::post('/forget-password', [UserController::class, 'handleForgetPassword'])->name('forgetPassword.submit');
    Route::post('/account/change-password', [UserController::class, 'handleChangePassword'])->name('changePassword.submit');
    Route::post('/account/change-password-level-2', [UserController::class, 'handleChangePasswordLevel2'])->name('changePasswordlv2.submit');
    Route::post('/account/change-security-questions', [UserController::class, 'handleChangeSecurityQuestion'])->name('changeSecurityQuestion.submit');
    Route::post('/otp', [UserController::class, 'handleOtpResetPassword'])->name('resetPassword');
    Route::post('/active', [UserController::class, 'handleOtpActiveUser'])->name('activeRegister');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/delete', [CartController::class, 'deleteItemOfCart'])->name('cart.delete');
    Route::post('/security/setup', [UserController::class, 'handleSetUpSecurity'])->name('security.setup');

    Route::get('paypal/payment/success', [PayPalController::class, 'paymentSuccess'])->name('paypal.payment.success');
    Route::get('paypal/payment/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.payment.cancel');
    Route::get('paypal/payment/error', [PayPalController::class, 'paymentError'])->name('paypal.payment.error');
    Route::post('paypal/payment', [PayPalController::class, 'payment'])->name('paypal.payment');

    Route::post('coupon/apply', [CouponController::class, 'applyCoupon'])->name('coupon.apply');

    Route::get('contact-us', [PageController::class, 'contact'])->name('contact');
    Route::post('contact-us', [PageController::class, 'sendContactEmail'])->name('contact.send');
    Route::get('wishlist', [WishlistController::class, 'wishlist'])->name('wishlist');
    Route::post('wistlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('download/product', [ProductController::class, 'downloadProduct'])->name('downloadProduct');
    Route::get('search', [SearchController::class, 'search'])->name('search');
    Route::get('search-page', [SearchController::class, 'index'])->name('search.page');

    Route::get('new-products', [PageController::class, 'newProducts'])->name('newProducts');
    Route::get('popular-products', [PageController::class, 'popularProducts'])->name('popularProducts');
    Route::get('sales', [PageController::class, 'saleProducts'])->name('saleProducts');
    Route::get('helps', [PageController::class, 'helps'])->name('helps');
    Route::get('policies', [PageController::class, 'policies'])->name('policies');
    Route::get('maintenance', [PageController::class, 'maintenance'])->name('maintenance');

    // -------------------------------
    // Admin
    // -------------------------------
    Route::get('/admin/login', [PageController::class, 'adminLogin'])->name('admin.login');
    Route::get('/admin', [PageController::class, 'admin'])->name('admin.dashboard')->middleware('admin');
    Route::post('/admin/login', [UserController::class, 'handleAdminLogin'])->name('admin.login.submit');
    Route::get('/admin/logout', [UserController::class, 'adminLogout'])->name("admin.logout");
    Route::get('/admin/languages', [AdminLanguageController::class, 'index'])->middleware('admin')->name('admin.languages');
    Route::post('/admin/languages/update', [AdminLanguageController::class, 'update'])->middleware('admin')->name('admin.languages.update');
    Route::get('/admin/paypal-settings', [AdminPaypalSettingController::class, 'showForm'])->middleware('admin')->name('admin.paypal.form');
    Route::post('/admin/paypal-settings', [AdminPaypalSettingController::class, 'save'])->middleware('admin')->name('admin.paypal.save');
    Route::get('/admin/maintenance', [AdminMaintenanceController::class, 'index'])->middleware('admin')->name('admin.maintenance.index');
    Route::post('/admin/maintenance/update', [AdminMaintenanceController::class, 'update'])->middleware('admin')->name('admin.maintenance.update');
    
    //Default images
    Route::get('/admin/defaultImages', [ImageController::class, 'index'])->name('admin.defaultImages.index');
    Route::post('/admin/defaultImages/{image}', [ImageController::class, 'update'])->name('admin.defaultImages.update');

    // Products
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->middleware('admin')->name("admin.products.create");
    Route::post('/admin/products/store', [AdminProductController::class, 'store'])->middleware('admin')->name("admin.products.store");
    Route::get('/admin/products/{post_slug}/edit', [AdminProductController::class, 'edit'])->middleware('admin')->name("admin.products.edit");
    Route::put('/admin/products/update', [AdminProductController::class, 'update'])->middleware('admin')->name("admin.products.update");
    Route::delete('/admin/products/{post_slug}', [AdminProductController::class, 'destroy'])->middleware('admin')->name('admin.products.delete');
    Route::get('/admin/products', [AdminProductController::class, 'index'])->middleware('admin')->name("admin.products");
    Route::get('/admin/products/search', [SearchController::class, 'searchProduct'])->middleware('admin')->name("admin.products.search");

    // Categories
    Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->middleware('admin')->name("admin.categories");
    Route::get('/admin/categories/create/{category_slug?}', [AdminCategoryController::class, 'create'])->middleware('admin')->name("admin.categories.create");
    Route::post('/admin/categories/store', [AdminCategoryController::class, 'store'])->middleware('admin')->name("admin.categories.store");
    Route::get('/admin/categories/{category_slug}/edit', [AdminCategoryController::class, 'edit'])->middleware('admin')->name("admin.categories.edit");
    Route::put('/admin/categories/update', [AdminCategoryController::class, 'update'])->middleware('admin')->name("admin.categories.update");
    Route::delete('/admin/categories/{category_slug}', [AdminCategoryController::class, 'destroy'])->middleware('admin')->name("admin.categories.delete");
    Route::get('/admin/categories/search', [SearchController::class, 'searchCategory'])->middleware('admin')->name("admin.categories.search");

    //Posts
    Route::get('/admin/posts', [AdminPostController::class, 'index'])->middleware('admin')->name("admin.posts");
    Route::get('/admin/posts/create', [AdminPostController::class, 'create'])->middleware('admin')->name("admin.posts.create");
    Route::get('/admin/posts/{slug}/edit', [AdminPostController::class, 'edit'])->middleware('admin')->name("admin.posts.edit");
    Route::delete('/admin/posts/{slug}', [AdminPostController::class, "destroy"])->middleware('admin')->name("admin.posts.destroy");
    Route::post('/admin/posts', [AdminPostController::class, "store"])->middleware('admin')->name("admin.posts.store");
    Route::put('/admin/posts/update', [AdminPostController::class, "update"])->middleware('admin')->name("admin.posts.update");
    Route::get('/admin/posts/search', [SearchController::class, "searchPost"])->middleware('admin')->name("admin.posts.search");

    //Slideshows
    Route::get('/admin/slideshows', [AdminSlideShowController::class, 'index'])->middleware('admin')->name("admin.slideshows");
    Route::get('/admin/slideshows/create', [AdminSlideShowController::class, 'create'])->middleware('admin')->name("admin.slideshows.create");
    Route::post('/admin/slideshows/store', [AdminSlideShowController::class, 'store'])->middleware('admin')->name("admin.slideshows.store");
    Route::get('/admin/slideshows/{slideshow_image_id}/edit', [AdminSlideShowController::class, 'edit'])->middleware('admin')->name("admin.slideshows.edit");
    Route::put('/admin/slideshows/update', [AdminSlideShowController::class, 'update'])->middleware('admin')->name("admin.slideshows.update");
    Route::delete('/admin/slideshows/{slideshow_image_id}', [AdminSlideShowController::class, 'destroy'])->middleware('admin')->name("admin.slideshows.delete");

    // Sub admin
    Route::get('/admin/subadmins', [SubAdminController::class, "index"])->middleware('admin')->name("admin.subadmins");
    Route::get('/admin/subadmins/create', [SubAdminController::class, "create"])->middleware('admin')->name("admin.subadmins.create");
    Route::get('/admin/subadmins/edit/{id}', [SubAdminController::class, "edit"])->middleware('admin')->name("admin.subadmins.edit");
    Route::put('/admin/subadmins', [SubAdminController::class, "update"])->middleware('admin')->name("admin.subadmins.update");
    Route::post('/admin/subadmins/store', [SubAdminController::class, "store"])->middleware('admin')->name("admin.subadmins.store");
    Route::delete('/admin/subadmins/{id}', [SubAdminController::class, "destroy"])->middleware('admin')->name("admin.subadmins.destroy");
    Route::put('/admin/subadmins/active/{id}/{status}', [SubAdminController::class, "active"])->middleware('admin')->name("admin.subadmins.active");
    Route::put('/admin/subadmins/restore/{email}', [SubAdminController::class, "restore"])->middleware('admin')->name("admin.subadmins.restore");

    // Role
    Route::get('/admin/roles', [AdminRoleController::class, "index"])->middleware('admin')->name("admin.roles");
    Route::get('/admin/roles/create', [AdminRoleController::class, "create"])->middleware('admin')->name("admin.roles.create");
    Route::get('/admin/roles/edit/{id}/{duplicated?}', [AdminRoleController::class, "edit"])->middleware('admin')->name("admin.roles.edit");
    Route::delete('/admin/roles/{id}', [AdminRoleController::class, "destroy"])->middleware('admin')->name("admin.roles.destroy");
    Route::post('/admin/roles', [AdminRoleController::class, "store"])->middleware('admin')->name("admin.roles.store");
    Route::put('/admin/roles/{duplicated?}', [AdminRoleController::class, "update"])->middleware('admin')->name("admin.roles.update");

    //Question
    Route::get('/admin/questions', [AdminQuestionController::class, "index"])->middleware('admin')->name("admin.questions");
    Route::get('/admin/questions/create', [AdminQuestionController::class, "create"])->middleware('admin')->name("admin.questions.create");
    Route::get('/admin/questions/edit/{id}/{duplicated?}', [AdminQuestionController::class, "edit"])->middleware('admin')->name("admin.questions.edit");
    Route::delete('/admin/questions/{id}', [AdminQuestionController::class, "destroy"])->middleware('admin')->name("admin.questions.destroy");
    Route::post('/admin/questions', [AdminQuestionController::class, "store"])->middleware('admin')->name("admin.questions.store");
    Route::put('/admin/questions/{duplicated?}', [AdminQuestionController::class, "update"])->middleware('admin')->name("admin.questions.update");

    //Coupons
    Route::get('/admin/coupons', [AdminCouponController::class, "index"])->middleware('admin')->name("admin.coupons");
    Route::get('/admin/coupons/create', [AdminCouponController::class, "create"])->middleware('admin')->name("admin.coupons.create");
    Route::get('/admin/coupons/edit/{id}', [AdminCouponController::class, "edit"])->middleware('admin')->name("admin.coupons.edit");
    Route::post('/admin/coupons/store', [AdminCouponController::class, "store"])->middleware('admin')->name("admin.coupons.store");
    Route::post('/admin/coupons/update', [AdminCouponController::class, "update"])->middleware('admin')->name("admin.coupons.update");
    Route::delete('/admin/coupons/delete', [AdminCouponController::class, "delete"])->middleware('admin')->name("admin.coupons.delete");
    Route::get('/admin/coupons/search', [SearchController::class, "searchCoupon"])->middleware('admin')->name("admin.coupons.search");

    //Tracking Codes
    Route::get('/admin/tracking-codes', [AdminTrackingCodeController::class, "index"])->middleware('admin')->name("admin.tracking-codes");
    Route::get('/admin/tracking-codes/create', [AdminTrackingCodeController::class, "create"])->middleware('admin')->name("admin.tracking-codes.create");
    Route::delete('/admin/tracking-codes/{id}', [AdminTrackingCodeController::class, "destroy"])->middleware('admin')->name("admin.tracking-codes.destroy");
    Route::post('/admin/tracking-codes', [AdminTrackingCodeController::class, "store"])->middleware('admin')->name("admin.tracking-codes.store");
    Route::put('/admin/tracking-codes', [AdminTrackingCodeController::class, "update"])->middleware('admin')->name("admin.tracking-codes.update");

    // Mail
    Route::get('/admin/mails', [AdminMailController::class, "index"])->middleware('admin')->name("admin.mails");
    Route::post('/admin/mails/render', [AdminMailController::class, "render"])->middleware('admin')->name("admin.mails.render");
    Route::get('/admin/mails/edit/{mail_id}', [AdminMailController::class, "edit"])->middleware('admin')->name("admin.mails.edit");
    Route::put('/admin/mails/update/{mail_id}', [AdminMailController::class, "update"])->middleware('admin')->name("admin.mails.update");


    // File manager route
    // File routes
    Route::post('/files/upload', [FileManagerController::class, 'store'])->middleware('admin')->name('upload.file');
    Route::post('/files/rename', [FileManagerController::class, 'renameFile'])->middleware('admin')->name('rename.file');
    Route::post('/files/remove', [FileManagerController::class, 'removeFile'])->middleware('admin')->name('remove.file');
    Route::post('/files/move', [FileManagerController::class, 'moveFile'])->middleware('admin')->name('move.file');
    // Folder routes
    Route::post('/folders/create', [FolderManagerController::class, 'store'])->middleware('admin')->name('folders.create');
    Route::post('/folders/delete', [FolderManagerController::class, 'deleteFolder'])->middleware('admin')->name('folder.delete');
    Route::post('/folders/rename', [FolderManagerController::class, 'renameFolder'])->middleware('admin')->name('folder.rename');

    Route::post('/translate', [TranslateController::class, 'translate'])->name('translate');
    Route::get('/get-order-history', [OrderController::class, 'getOrderByAdmin'])->middleware('admin')->name('getOrderByAdmin');
});

Route::get('/lang/{locale}', [PageController::class, 'setLocale'])->name('setLocale');

Route::get('/get-file/{filename?}', [FileManagerController::class, 'getFile'])->where('filename', '.*')->name('get.file');
Route::get('/resend-otp/activeUser/{email}', [UserController::class, 'resendOtpRegister'])->name('resend.otp.active');
Route::get('/resend-otp/forgetPassword/{email}', [UserController::class, 'resendOtpForgetPass'])->name('resend.otp.forget.pass');
Route::get('/sitemap.xml', function () {
    $path = base_path('sitemap.xml');

    if (!File::exists($path)) {
        abort(404, 'Sitemap not found');
    }

    $content = File::get($path);

    return Response::make($content, 200)
        ->header('Content-Type', 'application/xml');
});
