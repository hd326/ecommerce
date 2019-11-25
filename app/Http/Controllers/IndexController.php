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
        $products = Product::inRandomOrder()->where('status', 1)->where('feature_item', 1)->paginate(3);

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $banners = Banner::where('status', '1')->get();

        // Meta Tags
        $meta_title = "E-shop Sample Website";
        $meta_description = "Online Shopping Site for Men, Women, and Kids Clothing";
        $meta_keywords = "Eshop website, online shopping, men clothing";
        return view('index', compact('products', 'categories', 'banners', 'meta_title', 'meta_description', 'meta_keywords'));
    }
}
