<?php

use App\Http\Controllers\AdminCategoryPostController;
use App\Http\Controllers\AdminCategoryProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\ClientHomeController;
use App\Http\Controllers\CartController;



use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminSlideController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\CheckUserLogin;

// Route::get('/', function () {
//     return view('client.index');
// });
Route::get('/', [ClientHomeController::class, 'index']);
Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
Route::prefix('client')->group(function () {
    Route::get('/', [ClientHomeController::class, 'index'])->name("/");
    //Phải đi đăng nhập thông tin ở đây nhé
    Route::middleware([CheckUserLogin::class])->group(function () {
        //BUY NOW
        Route::get('/buy/now/{product}', [CartController::class, 'buy'])->name("buy.now");

        //ADD TO CART
        Route::get('/cart/show/', [CartController::class, 'show'])->name("cart.show");
        Route::get('/cart/add/{product}', [CartController::class, 'add'])->name("cart.add");
        Route::get('/cart/delete/{rowId}', [CartController::class, 'delete'])->name("cart.delete");
        Route::get('/cart/destroy/', [CartController::class, 'destroy'])->name("cart.destroy");
        Route::post('/cart/update/', [CartController::class, 'update'])->name("cart.update");
        // Route::post('/vnpay_payment/', [PaymentController::class, 'vnpay_payment'])->name("vnpay.payment");
        //CHECKOUT
        Route::get('/cart/checkout/', [CheckOutController::class, 'checkout'])->name("cart.checkout");
        Route::post('/cart/payment/', [CheckOutController::class, 'payment'])->name("cart.payment");
        Route::post('/payment/online/', [CheckOutController::class, 'createPayment'])->name("payment.online");
        //Lấy dữ liệu về khi bấm gửi tiền đi
        Route::get('/vnpay/return/', [CheckOutController::class, 'vnpayReturn'])->name("vnpay.return");
        //Chi tiết lịch sử của một user có bao nhiêu đơn hàng
        Route::get('/user/order/', [CheckOutController::class, 'order'])->name("user.order");
        Route::get('/user/detail/order/{order}', [CheckOutController::class, 'detail'])->name("user.detail.order");
        Route::get('/user/order/delete/{order}', [CheckOutController::class, 'delete'])->name("user.order.delete");

    });
    //SEARCH PRODUCT
    Route::get('/search', [ClientHomeController::class, 'search'])->name("search");
    //WITH PRODUCT
    Route::get('/detail/product/{product}', [ClientHomeController::class, 'detailProduct'])->name("detail.product");
    Route::get('/all/product/', [ClientHomeController::class, 'listProduct'])->name("all.product");
    Route::get('/detail/category/{category}', [ClientHomeController::class, 'detailCategory'])->name("detail.category");
    //WITH POST 
    Route::get('/all/post/', [ClientHomeController::class, 'listPost'])->name("all.post");
    Route::get('/detail/post/{post}', [ClientHomeController::class, 'detailPost'])->name("detail.post");
    Route::get('/detail/catPost/{cat}', [ClientHomeController::class, 'catPost'])->name("detail.catPost");


    //FILTER
    Route::get('filter/action', [ClientHomeController::class, 'action'])->name("filter.action");

    
});
Route::middleware([AdminAuthenticate::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('admin')->group(function () {
        // ROUTE WITH USERMODULE + CAN
        Route::get('/user/list', [AdminUserController::class, 'index'])->name('user.list')->can("user.show");
        Route::get('/user/add', [AdminUserController::class, 'add'])->name('user.add')->can("user.add");
        Route::post('/user/store', [AdminUserController::class, 'store'])->name('user.store')->can("user.add");
        Route::get('/user/delete/{id}', [AdminUserController::class, 'delete'])->name('user.delete')->can("user.delete");
        Route::get('/user/action', [AdminUserController::class, 'action'])->name('user.action')->can("user.edit");
        Route::get('/user/edit/{id}', [AdminUserController::class, 'edit'])->name('user.edit')->can("user.edit");
        Route::post('/user/update/{id}', [AdminUserController::class, 'update'])->name('user.update')->can("user.edit");
        Route::get('/user/restore/{id}', [AdminUserController::class, 'restore'])->name('user.restore')->can("user.edit");
        Route::get('/user/forceDelete/{id}', [AdminUserController::class, 'forceDelete'])->name('user.forceDelete')->can("user.edit");
        //ROUTE WITH PERMISSION
        Route::get('/permission/add', [AdminPermissionController::class, 'add'])->name('permission.add')->can("permission.add");
        Route::post('/permission/store', [AdminPermissionController::class, 'store'])->name('permission.store')->can("permission.add");
        Route::get('/permission/edit/{id}', [AdminPermissionController::class, 'edit'])->name('permission.edit')->can("permission.edit");
        Route::post('/permission/update/{id}', [AdminPermissionController::class, 'update'])->name('permission.update')->can("permission.edit");
        Route::get('/permission/delete/{id}', [AdminPermissionController::class, 'delete'])->name('permission.delete')->can("permission.delete");
        //ROUTE WITH ROLE
        Route::get('/role/list', [AdminRoleController::class, 'index'])->name('role.list')->can("role.show");
        Route::get('/role/add', [AdminRoleController::class, 'add'])->name('role.add')->can("role.add");
        Route::post('/role/store', [AdminRoleController::class, 'store'])->name('role.store')->can("role.add");
        Route::get('/role/edit/{role}', [AdminRoleController::class, 'edit'])->name('role.edit')->can("role.edit");
        Route::post('/role/update/{role}', [AdminRoleController::class, 'update'])->name('role.update')->can("role.edit");
        Route::get('/role/delete/{role}', [AdminRoleController::class, 'delete'])->name('role.delete')->can("role.delete");
        Route::post('/role/action', [AdminRoleController::class, 'action'])->name('role.action')->can("role.edit");
        Route::get('/role/restore/{id}', [AdminRoleController::class, 'restore'])->name('role.restore')->can("role.edit");
        Route::get('/role/forceDelete/{id}', [AdminRoleController::class, 'forceDelete'])->name('role.forceDelete')->can("role.edit");

        //ROUTE WITH PRODUCT
        Route::get('/product/list', [AdminProductController::class, 'index'])->name('product.list')->can("product.show");
        Route::get('/product/add', [AdminProductController::class, 'add'])->name('product.add')->can("product.add");
        Route::post('/product/store', [AdminProductController::class, 'store'])->name('product.store')->can("product.add");
        Route::get('/product/edit/{product}', [AdminProductController::class, 'edit'])->name('product.edit')->can("product.edit");
        Route::post('/product/update/{product}', [AdminProductController::class, 'update'])->name('product.update')->can("product.edit");
        Route::get('/product/delete/{product}', [AdminProductController::class, 'delete'])->name('product.delete')->can("product.delete");
        Route::post('/product/action', [AdminProductController::class, 'action'])->name('product.act')->can("product.edit");
        //Tạo thêm action với trạng thái ổn không định -> delete at
        Route::get('/product/restore/{id}', [AdminProductController::class, 'restore'])->name('product.restore')->can("product.edit");
        Route::get('/product/forcedelete/{id}', [AdminProductController::class, 'forceDelete'])->name('product.forcedelete')->can("product.edit");
        //ROUTE WITH CATEGORY PRODUCT
        Route::get('/product/cat/add', [AdminCategoryProductController::class, 'add'])->name('product.cat.add');
        Route::get('/product/cat/detail/{category}', [AdminCategoryProductController::class, 'detail'])->name('product.cat.detail');
        Route::post('/product/cat/store', [AdminCategoryProductController::class, 'store'])->name('product.cat.store');
        Route::get('/product/cat/edit/{category}', [AdminCategoryProductController::class, 'edit'])->name('product.cat.edit');
        Route::post('/product/cat/update/{category}', [AdminCategoryProductController::class, 'update'])->name('product.cat.update');
        Route::get('/product/cat/delete/{category}', [AdminCategoryProductController::class, 'delete'])->name('product.cat.delete');
        //ROUTE WITH CATEGORY PAGE
        Route::get('/page/list', [AdminPageController::class, 'index'])->name('page.list')->can("page.show");
        Route::get('/page/add', [AdminPageController::class, 'add'])->name('page.add')->can("page.add");
        Route::post('/page/store', [AdminPageController::class, 'store'])->name('page.store')->can("page.add");
        Route::get('/page/edit/{page}', [AdminPageController::class, 'edit'])->name('page.edit')->can("page.edit");
        Route::post('/page/update/{page}', [AdminPageController::class, 'update'])->name('page.update')->can("page.edit");
        Route::get('/page/delete/{page}', [AdminPageController::class, 'delete'])->name('page.delete')->can("page.delete");
        Route::get('/page/restore/{id}', [AdminPageController::class, 'restore'])->name('page.restore')->can("page.edit");
        Route::get('/page/forcedelete/{page}', [AdminPageController::class, 'forceDelete'])->name('page.forcedelete')->can("page.edit");
        Route::post('/page/action', [AdminPageController::class, 'action'])->name('page.action')->can("page.edit");
        //ROUTE WITH CATEGORY POST 
        Route::get('/post/cat/add', [AdminCategoryPostController::class, 'add'])->name('post.cat.add')->can("post.show");
        Route::get('/post/cat/detail/{cat}', [AdminCategoryPostController::class, 'detail'])->name('post.cat.detail')->can("post.show");
        Route::post('/post/cat/store', [AdminCategoryPostController::class, 'store'])->name('post.cat.store')->can("post.add");
        Route::get('/post/cat/edit/{cat}', [AdminCategoryPostController::class, 'edit'])->name('post.cat.edit')->can("post.edit");
        Route::post('/post/cat/update/{cat}', [AdminCategoryPostController::class, 'update'])->name('post.cat.update')->can("post.edit");
        Route::get('/post/cat/delete/{cat}', [AdminCategoryPostController::class, 'delete'])->name('post.cat.delete')->can("post.delete");
        //ROUTE WITH POST
        Route::get('/post/list', [AdminPostController::class, 'index'])->name('post.list')->can("post.show");
        Route::get('/post/add', [AdminPostController::class, 'add'])->name('post.add')->can("post.add");
        Route::post('/post/store', [AdminPostController::class, 'store'])->name('post.store')->can("post.add");
        Route::get('/post/edit/{post}', [AdminPostController::class, 'edit'])->name('post.edit')->can("post.edit");
        Route::post('/post/update/{post}', [AdminPostController::class, 'update'])->name('post.update')->can("post.edit");
        Route::get('/post/delete/{post}', [AdminPostController::class, 'delete'])->name('post.delete')->can("post.delete");
        Route::post('/post/action', [AdminPostController::class, 'action'])->name('post.action')->can("post.edit");
        //Tạo thêm action với trạng thái ổn không định -> delete at
        Route::get('/post/restore/{id}', [AdminPostController::class, 'restore'])->name('post.restore')->can("post.edit");
        Route::get('/post/forcedelete/{id}', [AdminPostController::class, 'forceDelete'])->name('post.forcedelete')->can("post.edit");
        //ROUTE WITH ORDER
        Route::get('/order/list', [AdminOrderController::class, 'index'])->name('order.list');
        Route::get('/order/edit/{order}', [AdminOrderController::class, 'edit'])->name('order.edit');
        Route::post('/order/update/{order}', [AdminOrderController::class, 'update'])->name('order.update');
        Route::get('/order/delete/{order}', [AdminOrderController::class, 'delete'])->name('order.delete');
        Route::get('/order/detail/{order}', [AdminOrderController::class, 'detail'])->name('order.detail');
        //Khôi phục và xoá cứng
        Route::get('/order/restore/{id}', [AdminOrderController::class, 'restore'])->name('order.restore');
        Route::get('/order/deleteForce/{id}', [AdminOrderController::class, 'deleteForce'])->name('order.deleteForce');
        Route::post('/order/action', [AdminOrderController::class, 'action'])->name('order.action');
        //Quản lý slider
        Route::get('/slide/list', [AdminSlideController::class, 'index'])->name("slide.list");
        Route::get('/slide/add', [AdminSlideController::class, 'add'])->name("slide.add");
        Route::post('/slide/store', [AdminSlideController::class, 'store'])->name("slide.store");

        Route::get('/slide/edit/{slide}', [AdminSlideController::class, 'edit'])->name("slide.edit");
        Route::post('/slide/update/{slide}', [AdminSlideController::class, 'update'])->name("slide.update");
        Route::get('/slide/delete/{slide}', [AdminSlideController::class, 'delete'])->name("slide.delete");
        Route::post('/slide/action', [AdminSlideController::class, 'action'])->name("slide.action");

        Route::get('/slide/restore/{id}', [AdminSlideController::class, 'restore'])->name("slide.restore");
        Route::get('/slide/forcedelete/{id}', [AdminSlideController::class, 'forcedelete'])->name("slide.forcedelete");



    });
});
require __DIR__ . '/auth.php';
