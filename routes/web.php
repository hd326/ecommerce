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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/admin', 'AdminController@login');
Route::match(['get', 'post'], '/admin', 'AdminController@login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/** these routes can be grouped */

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

// Products Attributes Routes
Route::match(['get', 'post'], 'admin/add-attributes/{id}', 'ProductController@addAttributes');

/** these routes can be grouped*/

Route::get('/logout', 'AdminController@logout');
