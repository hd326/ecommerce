<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Banner;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        // In Ascending order (by default)
        // $products = Product::get();
        // In Descending order
        // $products = Product::orderBy('id', 'DESC')->get();
        // In Random Order
        $products = Product::inRandomOrder()->where('status', 1)->get();

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $banners = Banner::where('status', '1')->get();
        return view('index', compact('products', 'categories', 'banners'));
    }
}
