
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

// Get product attribute price from DB AJAX
Route::get('/get-product-price', 'ProductController@getProductPrice');

// Add To Cart Route
Route::match(['get', 'post'], '/add-cart', 'ProductController@addtocart');

// Cart Page
Route::match(['get', 'post'], '/cart', 'ProductController@cart');

// Delete Product from Cart Page
Route::get('/cart/delete-product/{id}', 'ProductController@deleteCartProduct');

// Update Product Quantity in Cart
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductController@updateCartQuantity');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin/dashboard', 'AdminController@dashboard')->middleware('auth');
    Route::get('/admin/settings', 'AdminController@settings')->middleware('auth');
    Route::get('/admin/check-pwd', 'AdminController@chkPassword')->middleware('auth');
    Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword')->middleware('auth');

    // Category Routes (Admin)
    Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@addCategory')->middleware('auth');
    Route::match(['get', 'post'], '/admin/edit-category/{id}', 'CategoryController@editCategory')->middleware('auth');
    Route::match(['get', 'post'], '/admin/delete-category/{id}', 'CategoryController@deleteCategory')->middleware('auth');
    Route::get('/admin/view-categories', 'CategoryController@viewCategories')->middleware('auth');

    // Product Routes
    Route::match(['get', 'post'], '/admin/add-product', 'ProductController@addProduct')->middleware('auth');
    Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductController@editProduct')->middleware('auth');
    Route::get('/admin/view-products', 'ProductController@viewProducts')->middleware('auth');
    Route::get('/admin/delete-product/{id}', 'ProductController@deleteProduct')->middleware('auth');
    Route::get('/admin/delete-product-image/{id}', 'ProductController@deleteProductImage')->middleware('auth');
    Route::get('/admin/delete-alt-image/{id}', 'ProductController@deleteAltImage')->middleware('auth');

    // Products Attributes Routes
    Route::match(['get', 'post'], 'admin/add-attributes/{id}', 'ProductController@addAttributes')->middleware('auth');
    Route::match(['get', 'post'], 'admin/edit-attributes/{id}', 'ProductController@editAttributes')->middleware('auth');
    Route::match(['get', 'post'], 'admin/add-images/{id}', 'ProductController@addImages')->middleware('auth');
    Route::get('/admin/delete-attribute/{id}', 'ProductController@deleteAttribute')->middleware('auth');
});

Route::get('/logout', 'AdminController@logout');
