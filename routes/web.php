
<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

// Home Page

//Route::get('/admin', 'AdminController@login');
Route::match(['get', 'post'], '/admin', 'AdminController@login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'IndexController@index');

// Category/Listing Page
Route::get('/products/{url}', 'ProductController@products');

// Product Detail Page
Route::get('/product/{id}', 'ProductController@product');

// Get Product Attribute price
Route::get('/get-product-price', 'ProductController@getProductPrice');

// Cart Page
Route::match(['get', 'post'], '/cart', 'ProductController@cart');

// Add To Cart Route
Route::match(['get', 'post'], '/add-cart', 'ProductController@addtocart');

// Delete Product from Cart Page
Route::get('/cart/delete-product/{id}', 'ProductController@deleteCartProduct');

// Apply Coupon
Route::post('/cart/apply-coupon', 'ProductController@applyCoupon');

// Update Product Quantity in Cart
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductController@updateCartQuantity');

// User Register/Login Page
Route::get('/login-register', 'UserController@userLoginRegister');

// User Register Form Submit
Route::post('/user-register', 'UserController@register');

// User Forgot Password
Route::match(['get','post'], '/forgot-password', 'UserController@forgotPassword');

// Confirm Account
Route::get('confirm/{code}', 'UserController@confirmAccount');

// User Logout
Route::get('/user-logout', 'UserController@logout');

// User Login
Route::post('/user-login', 'UserController@login');

Route::post('/search-products', 'ProductController@searchProduct');

// Check is User already exists
Route::match(['get', 'post'], '/check-email', 'UserController@checkEmail');

// User Account Page
Route::group(['middleware' => ['FrontLogin']], function(){
    // User Account Page
    Route::match(['get', 'post'], '/account', 'UserController@account');
    // Update User Password
    Route::match(['get', 'post'], '/update-user-pwd', 'UserController@updatePassword');
    // Check User Password
    Route::post('/check-user-pwd', 'UserController@chkUserPassword');
    // Order Review Page
    Route::match(['get', 'post'], '/checkout', 'ProductController@checkout');
    // Order Review Page
    Route::match(['get', 'post'], '/order-review', 'ProductController@orderReview');
    // Place Order 
    Route::match(['get', 'post'], '/place-order', 'ProductController@placeOrder');
    // Thank you Page
    Route::get('/thanks', 'ProductController@thanks');
    // Paypal Page
    Route::get('/paypal', 'ProductController@paypal');
    // User Ordered Page
    Route::get('/orders', 'ProductController@userOrders');
    // User Ordered Products Page
    Route::get('/orders/{id}', 'ProductController@userOrderDetails');
    // Paypal Thanks Page
    Route::get('/paypal/thanks', 'ProductController@thanksPaypal');
    
});


Route::group(['middleware' => ['AdminLogin']], function() {
    Route::get('/admin/dashboard', 'AdminController@dashboard');
    Route::get('/admin/settings', 'AdminController@settings');
    Route::get('/admin/check-pwd', 'AdminController@chkPassword');
    Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');

    // Category Routes (Admin)
    Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@addCategory');
    Route::match(['get', 'post'], '/admin/edit-category/{id}', 'CategoryController@editCategory');
    Route::get('/admin/view-categories', 'CategoryController@viewCategories');
    Route::match(['get', 'post'], '/admin/delete-category/{id}', 'CategoryController@deleteCategory');
    

    // Product Routes
    Route::match(['get', 'post'], '/admin/add-product', 'ProductController@addProduct');
    Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductController@editProduct');
    Route::get('/admin/view-products', 'ProductController@viewProducts');
    Route::get('/admin/delete-product/{id}', 'ProductController@deleteProduct');
    Route::get('/admin/delete-product-image/{id}', 'ProductController@deleteProductImage');
    Route::get('/admin/delete-alt-image/{id}', 'ProductController@deleteAltImage');

    // Products Attributes Routes
    Route::match(['get', 'post'], 'admin/add-attributes/{id}', 'ProductController@addAttributes');
    Route::match(['get', 'post'], 'admin/edit-attributes/{id}', 'ProductController@editAttributes');
    Route::match(['get', 'post'], 'admin/add-images/{id}', 'ProductController@addImages');
    Route::get('/admin/delete-attribute/{id}', 'ProductController@deleteAttribute');

    // Coupon Routes
    Route::match(['get', 'post'], '/admin/add-coupon', 'CouponController@addCoupon');
    Route::match(['get', 'post'], '/admin/edit-coupon/{id}', 'CouponController@editCoupon');
    Route::get('/admin/delete-coupon/{id}', 'CouponController@deleteCoupon');
    Route::get('/admin/view-coupons', 'CouponController@viewCoupons');

    // Banner Routes
    Route::match(['get', 'post'], '/admin/add-banner', 'BannerController@addBanner');
    Route::match(['get', 'post'], '/admin/edit-banner/{id}', 'BannerController@editBanner');
    Route::get('/admin/delete-banner/{id}', 'BannerController@deleteBanner');
    Route::get('/admin/view-banners', 'BannerController@viewBanners');

    // Orders Routes
    Route::get('/admin/view-orders', 'ProductController@viewOrders');

    // Order Details Routes
    Route::get('/admin/view-order/{id}', 'ProductController@viewOrderDetails');

    // Order Invoice

    Route::get('/admin/view-order-invoice/{id}', 'ProductController@viewOrderInvoice');

    // Update Order Status Route
    Route::post('/admin/update-order-status', 'ProductController@updateOrderStatus');

    // View Users Route
    Route::get('/admin/view-users', 'UserController@viewUsers');

    Route::match(['get', 'post'], '/admin/add-cms-page', 'CmsController@addCmsPage');
    Route::get('/admin/view-cms-pages', 'CmsController@viewCmsPages');
    Route::match(['get', 'post'], '/admin/edit-cms-page/{id}', 'CmsController@editCmsPage');
    Route::get('/admin/delete-cms-page/{id}', 'CmsController@deleteCmsPage');
});

Route::get('/logout', 'AdminController@logout');

Route::get('/page/{url}', 'CmsController@cmsPage');
