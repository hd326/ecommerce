<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\Coupon;
use App\User;
use App\Country;
use App\DeliveryAddress;
use App\Order;
use App\OrderProduct;
use App\Zipcode;
use Auth;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Image;
use DB;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        if($request->isMethod('post')){
            if(empty($request->category_id)){
                return redirect()->back()->with('flash_message_error', 'Under Category is missing!');
            }
            
            $product = new Product;
            //echo "<pre>";print_r($request->all());die;

            $product->category_id = $request->category_id;
            $product->product_name = $request->product_name;
            $product->product_code = $request->product_code;
            $product->product_color = $request->product_color;
            if(!empty($request->description)){
                $product->description = $request->description;
            } else {
                $product->description = '';
            }
            
            if(!empty($request->care)){
                $product->care = $request->care;
            } else {
                $product->care = '';
            }
            
            $product->price = $request->price;
            
            // Upload Image
            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    // Resize Image code
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                    // Store image name in products table
                    $product->image = $filename;
                }
            }

            // Upload Video
            if($request->hasFile('video')){
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                // this is assumed to be in public folder
                $video_path = '/videos/';
                $video_tmp->move($video_path, $video_name);
                $product->video = $video_name;
            }

            if(empty($request->status)){
                $status = 0;
                // if there is no status, 0
            } else {
                // if there is a status, 1
                $status = 1;
            }

            if(empty($request->feature_item)){
                $feature_item = 0;
                // if there is no status, 0
            } else {
                // if there is a status, 1
                $feature_item = 1;
            }
            $product->feature_item = $feature_item;

            $product->status = $status;
            
            $product->save();
            //return redirect()->back()->with('flash_message_success', 'Product has been added Successfully!');
            return redirect('/admin/view-products')->with('flash_message_success', 'Product has been added Successfully!');
        }
        // Categories drop down start
        $categories = Category::where('parent_id', 0)->get();
        //$categories = Category::where('parent_id', 0)->get();
        //$categories_dropdown = "<option value='' selected disabled>Select</option>";
        //foreach($categories as $cat) {
        //    $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
        //    $sub_categories = Category::where('parent_id', $cat->id)->get();
        //    // getting 0 parent id, in consecutive order?
        //    foreach($sub_categories as $sub_cat) {
        //        $categories_dropdown .= "<option value='".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
        //    }
        //}
        // Categories drop down ends
        return view('admin.products.add_product')->with(compact('categories'));
    }

    public function editProduct(Request $request, $id = null)
    {
        if($request->isMethod('post')){

            // Upload Image
            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    // Resize Image code
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                    // Store image name in products table
                }
            } elseif (!empty($request->current_image)) {
                $filename = $request->current_image; 
            } else {
                $filename = "";
            }

            if($request->hasFile('video')){
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                // this is assumed to be in public folder
                $video_path = 'videos/';
                $video_tmp->move($video_path, $video_name);
                $videoName = $video_name;
            } elseif (!empty($request->current_video)) {
                $videoName = $request->current_video; 
            } else {
                $videoName = "";
            }

            if(empty($request->description)) {
                $request->description = '';
            }

            if(empty($request->care)) {
                $request->care = '';
            }

            if(empty($request->status)){
                $status = 0;
                // if there is no status, 0
            } else {
                // if there is a status, 1
                $status = 1;
            }

            if(empty($request->feature_item)){
                $feature_item = 0;
                // if there is no status, 0
            } else {
                // if there is a status, 1
                $feature_item = 1;
            }

            Product::where('id', $id)->update([
                'category_id' => $request->category_id,
                'product_name' => $request->product_name,
                'product_code' => $request->product_code,
                'product_color' => $request->product_color,
                'description' => $request->description,
                'care' => $request->care,
                'price' => $request->price,
                'image' => $filename,
                'video' => $videoName,
                'feature_item' => $feature_item,
                'status' => $status
            ]);
            return redirect()->back()->with('flash_message_success', 'Product has been updated successfully!');
        }


        // Get Product Details
        $product = Product::where('id', $id)->first();

         // Categories drop down start
        $categories = Category::where('parent_id', 0)->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat) {
            if($cat->id == $product->category_id){
                $selected = "selected";
            } else {
                $selected = "";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where('parent_id', $cat->id)->get();
            foreach($sub_categories as $sub_cat) {
               if($sub_cat->id == $product->category_id){
                   $selected = "selected";
               } else {
                   $selected = "";
               }
                $categories_dropdown .= "<option value='".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
         // Categories drop down ends
        return view('admin.products.edit_product')->with(compact('product', 'categories_dropdown'));
    }

    public function viewProducts()
    {
        $products = Product::orderBy('id', 'desc')->get();
        
        foreach($products as $key => $val) {
            $category_name = Category::where(['id' => $val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProductImage($id = null)
    {
        // Get Product Image Name
        $productImage = Product::where(['id' => $id])->first();

        // Get Product Image Paths
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        // Delete Images if exists in folder
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

        Product::where('id', $id)->update(['image' => '']);
        return redirect()->back()->with('flash_message_success', 'Product Image has been deleted successfully!');
    }

    public function deleteProductVideo($id = null)
    {
        $productVideo = Product::where('id', $id)->first();
        $video_path = 'videos/';
        if(file_exists($video_path.$productVideo->video)){
            unlink($video_path.$productVideo->video);
        }
        Product::where('id', $id)->update(['video' => '']);
        return redirect()->back()->with('flash_message_success', 'Product Video has been deleted successfully!');
    }

    public function deleteAltImage($id = null)
    {
        $productImage = ProductsImage::where(['id' => $id])->first();
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

        ProductsImage::where('id', $id)->delete();
        return redirect()->back()->with('flash_message_success', 'Product Alternate Image(s) has been deleted successfully!');
    }

    public function deleteProduct($id = null)
    {
        if (!empty($id)){
            Product::where('id', $id)->delete();
            return redirect()->back()->with('flash_message_success', 'Product has been deleted Successfully!');
        }
    }

    public function addAttributes(Request $request, $id = null)
    {
        $product = Product::with('attributes')->where('id', $id)->first();
        // adding with for the relationship
        if($request->isMethod('post')){
            foreach($request->sku as $key => $value) {
                if(!empty($value)) {
                    // Prevent duplicate SKU Check
                    $attrCountSKU = ProductsAttribute::where('sku', $value)->count();
                    if($attrCountSKU > 0){
                        return redirect('admin/add-attributes/'.$id)->with('flash_message_error', 'SKU already exists! Please add another SKU.');
                    }
                    // Prevent duplicate Size Check
                    $attrCountSizes = ProductsAttribute::where(['product_id' => $id, 'size' => $request->size[$key]])->count();
                    if($attrCountSizes > 0){
                        return redirect('admin/add-attributes/'.$id)->with('flash_message_error', '"'.$request->size[$key].'"'.' Size already exists! Please add another Size.');
                    }
                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->size = $request->size[$key];
                    $attribute->price = $request->price[$key];
                    $attribute->stock = $request->stock[$key];
                    $attribute->save();
                }
            }
            return redirect('admin/add-attributes/'.$id)->with('flash_message_success', 'Product Attributes has been added successfully!');
        }
        return view('admin.products.add_attributes')->with(compact('product'));
    }

    public function editAttributes(Request $request, $id = null)
    {
        if($request->isMethod('post')){
            //echo '<pre>'; print_r($request->all()); die;
            foreach($request->idAttr as $key => $attr){
                ProductsAttribute::where(['id' => $request->idAttr[$key]])
                    ->update(['price' => $request->price[$key], 
                              'stock' => $request->stock[$key]]);
            }
            return redirect()->back()->with('flash_message_success', 'Products Attributes has been updated successfully!');
        }
    }

    public function addImages(Request $request, $id = null)
    {
        $product = Product::with('attributes')->where('id', $id)->first();

        if($request->isMethod('post')){
            //echo '<pre>'; print_r($request->all()); die;a
            if($request->hasFile('image')){
                $files = $request->file('image');
                foreach($files as $file){
                // Upload Images after resize
                $image = new ProductsImage;
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(111,99999).'.'.$extension;
                $large_image_path = 'images/backend_images/products/large/'.$fileName;
                $medium_image_path = 'images/backend_images/products/medium/'.$fileName;
                $small_image_path = 'images/backend_images/products/small/'.$fileName;
                Image::make($file)->save($large_image_path);
                Image::make($file)->resize(600,600)->save($medium_image_path);
                Image::make($file)->resize(300,300)->save($small_image_path);
                $image->image = $fileName;
                $image->product_id = $request->product_id;
                $image->save(); 
                }
            }
            return redirect('/admin/add-images/'.$id)->with('flash_message_success', 'Product Images has been added successfully');
        }

        $productImages = ProductsImage::where(['product_id' => $id])->get();

        return view('admin.products.add_images')->with(compact('product', 'productImages'));
    }

    public function deleteAttribute($id = null){
        ProductsAttribute::where('id', $id)->delete();
        return redirect()->back()->with('flash_message_success', 'Attribute has been deleted successfully!');
    }

    public function products($url = null)
    {
        // Show 404 page is Category URL does not exist
        $countCategory = Category::where(['url' => $url, 'status' => 1])->count();
        if($countCategory == 0) {
            abort(404);
        }
        // For sidebar
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        // Get where URL
        $categoryDetails = Category::where(['url' => $url])->first();
        if($categoryDetails->parent_id == 0) {
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            // I think it's because the relationship that categories has that we are able to do this.
            foreach($subCategories as $subcategory){
                // Subcategory is all the children
                $cat_ids[] = $subcategory->id;
            }
            $products = Product::whereIn('category_id', $cat_ids)->where('status', 1)->orderBy('id', 'desc')->paginate(6);
        } else {
            $products = Product::where(['category_id' => $categoryDetails->id])->orderBy('id', 'desc')->where('status', 1)->paginate(6);
        }

        if(!empty($_GET['color'])){
            $colorArray = explode('-', $_GET['color']);
            $products = $products->whereIn('product_color', $colorArray);
        }

        $products = $products->paginate(6);

        $meta_title = $categoryDetails->meta_title;
        $meta_description = $categoryDetails->meta_description;
        $meta_keywords = $categoryDetails->meta_keywords;

        return view('products.listing', compact('categories', 'categoryDetails', 'products', 'meta_title', 'meta_description', 'meta_keywords', 'url'));
    }

    public function filter(Request $request)
    {
        $colorUrl = '';
        if(!empty($request->colorFilter)){
            foreach($request->colorFilter as $color){
                if(empty($colorUrl)){
                    $colorUrl = "&color=".$color;
                } else {
                    $colorUrl .= "-".$color;
                }
                
            }
        }
        $finalUrl = "products/".$request->url."?".$colorUrl;
        return redirect::to($finalUrl);
    }

    public function searchProduct(Request $request)
    {
        if($request->isMethod('post')){
            $categories = Category::with('categories')->where(['parent_id' => 0])->get();
            $search_product = $request->product;
            //$products = Product::where('product_name', 'like', '%'.$search_product.'%')
            //    ->orWhere('product_code', $search_product)->where('status', 1)->get();
            $products = Product::where(function($query) use ($search_product){
                $query->where('product_name', 'like', '%'.$search_product.'%')
                    ->orWhere('product_code', 'like', '%'.$search_product.'%')
                    ->orWhere('description', 'like', '%'.$search_product.'%')
                    ->orWhere('product_color', 'like', '%'.$search_product.'%');
            })->where('status', 1)->get();
            return view('products.listing', compact('categories', 'products', 'search_product'));
        }
    }

    public function product($id = null)
    {
        $productStatus = Product::where(['id' => $id, 'status' => 1])->count();
        if($productStatus == 0){
            abort(404);
        }
        $product = Product::with('attributes')->where('id', $id)->first();
        $productAltImages = ProductsImage::where(['product_id' => $id])->get();
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $relatedProducts = Product::where('id', '!=', $id)->where(['category_id' => $product->category_id])->where('status', 1)->get();
        $total_stock = ProductsAttribute::where('product_id', $id)->sum('stock');

        $meta_title = $product->product_name;
        $meta_description = $product->description;
        $meta_keywords = $product->product_name;
        return view('products.detail', compact('product', 'categories', 'productAltImages', 'total_stock', 'relatedProducts','meta_title', 'meta_description', 'meta_keywords'));
    }

    public function getProductPrice(Request $request)
    {
        //echo "<pre>"; print_r($request->all()); die;
        $proArr = explode("-", $request->idSize);
        //echo $proArr[0]; echo $proArr[1]; die;
        //explode takes the first parameter as the divisor for values in a new array
        $productsAttribute = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        //we get the id and size from the value, use the value to send an ajax, split those values, and pull what we need

        $getCurrencyRates = Product::getCurrencyRates($productsAttribute->price);
        echo $productsAttribute->price."-".$getCurrencyRates['INR_Rate']."-".$getCurrencyRates['GBP_Rate']."-".$getCurrencyRates['EUR_Rate'];
        echo "#";
        echo $productsAttribute->stock;
    }

    public function addtocart(Request $request)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        // Check if Product Stock is available
        $product_size = explode('-', $request->size);
        $getProductStock = ProductsAttribute::where([
            'product_id' => $request->product_id, 
            'size' => $product_size[1]])->first();

        if($getProductStock->stock < $request->quantity){
            return redirect()->back()->with('flash_message_error', 'Quantity is not available!');
        }

        if(empty(Auth::user()->email)){
            $request->user_email = '';
        } else {
            $request->user_email = Auth::user()->email;
        }

        //if(empty($request->session_id)){
        //    $request->session_id = '';
        //}

        // A session is initiated when we add to cart and is persisted throughout login/register
        $session_id = Session::get('session_id');
        if(empty($session_id)) {
            $session_id = str_random(40);
            Session::put('session_id', $session_id);
        }
        // Explode the product ID and product Size

        $sizeArr = explode("-", $request['size']);

        if(empty(Auth::check())){
            $countProducts = DB::table('cart')->where([
                'product_id' => $request->product_id,
                'product_color' => $request->product_color,
                'size' => $sizeArr[1],
                'session_id' => $session_id
            ])->count();
    
            if($countProducts > 0)
            {
                return redirect()->back()->with('flash_message_error', 'Product already exists in Cart!');
            } 
        } else {
            $countProducts = DB::table('cart')->where([
                'product_id' => $request->product_id,
                'product_color' => $request->product_color,
                'size' => $sizeArr[1],
                'user_email' => auth()->user()->email
            ])->count();
    
            if($countProducts > 0)
            {
                return redirect()->back()->with('flash_message_error', 'Product already exists in Cart!');
            } 
        }
        // Check to see if product exists in cart
        
            // Add to Cart
        $getSKU = ProductsAttribute::select('sku')->where([
            'product_id' => $request->product_id,
            'size' => $sizeArr[1]
            ])->first();

        DB::table('cart')->insert([
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'product_code' => $getSKU->sku,
            'product_color' => $request->product_color,
            'price' => $request->price,
            'size' => $sizeArr[1],
            'quantity' => $request->quantity,
            'user_email' => $request->user_email,
            'session_id' => $session_id
        ]);


        return redirect('cart')->with('flash_message_success', 'Product has been added in Cart!');
    }

    public function cart()
    {
        // Grab all items from session
        if(Auth::check()) {
            $user_email = Auth::user()->email;
            $userCart = DB::table('cart')->where('user_email', $user_email)->get();
        } else {
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where('session_id', $session_id)->get();
        }
        
        // Get images for cart items
        foreach($userCart as $key => $product){
            $product = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $product->image;
        }
        $meta_title = "Shopping Cart - E-com Website";
        $meta_description = "View Shopping Cart of E-com Website";
        $meta_keywords = "Shopping Cart - E-com Website";
        return view('products.cart', compact('userCart', 'meta_title','meta_description', 'meta_keywords'));
    }

    public function deleteCartProduct($id = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        DB::table('cart')->where('id', $id)->delete();
        return redirect('cart')->with('flash_message_success', 'Product has been deleted from Cart!');
    }

    public function updateCartQuantity($id = null, $quantity = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $getCartDetails = DB::table('cart')->where('id', $id)->first();
        // Get our Cart details to compare
        $getAttributeStock = ProductsAttribute::where('sku', $getCartDetails->product_code)->first();
        // Get our Stock details to compare against Cart
        $updated_quantity = $getCartDetails->quantity+$quantity;
        // Comparison value of +1 into cart
        if($getAttributeStock->stock >= $updated_quantity){
        // If Database stock >= Cart stock, increment
        DB::table('cart')->where('id', $id)->increment('quantity', $quantity);
        return redirect('cart')->with('flash_message_success', 'Product Quantity has been updated from Successfully!');
        } else {
            return redirect('cart')->with('flash_message_error', 'Required Product Quantity is not available!');
        }
    }

    public function applyCoupon(Request $request)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        // Check if coupon is valid
        $couponCount = Coupon::where('coupon_code', $request->coupon_code)->count();
        if($couponCount == 0){
            return redirect()->back()->with('flash_message_error', 'Coupon is not valid');
        } else {

            $couponDetails = Coupon::where('coupon_code', $request->coupon_code)->first();

            // If coupon is Inactive
            if($couponDetails->status == 0) {
                return redirect()->back()->with('flash_message_error', 'This coupon is not active!');
            }

            // If coupon is Expired
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if($expiry_date < $current_date){
                return redirect()->back()->with('flash_message_error', 'This coupon is expired!');
            }

            // Coupon is Valid for Discount

            // Get Cart Total Amount

            //$session_id = Session::get('session_id');
            //$userCart = DB::table('cart')->where('session_id', $session_id)->get();
            // We need the user cart from when the user is logged in
            if(Auth::check()) {
                $user_email = Auth::user()->email;
                $userCart = DB::table('cart')->where('user_email', $user_email)->get();
            } else {
                $session_id = Session::get('session_id');
                $userCart = DB::table('cart')->where('session_id', $session_id)->get();
            }
            
            $total_amount = 0;
            foreach($userCart as $item){
                $total_amount = $total_amount + ($item->price * $item->quantity);
            }

            // If amount type is Fixed or Percentage
            if($couponDetails->amount_type == "Fixed"){
                $couponAmount = $couponDetails->amount;
            } else {
                $couponAmount = $total_amount * ($couponDetails->amount/100);
            }

            // Add Coupon Code & Amount
            Session::put('CouponAmount', $couponAmount);
            Session::put('CouponCode', $request->coupon_code);

            return redirect()->back()->with('flash_message_success', 'Coupon code successfully applied.');
        }
    }

    public function checkout(Request $request)
    {
        $userDetails = User::where('id', auth()->id())->first();
        $countries = Country::get();
        // Check Delivery Address for existing user data
        $shippingCount = DeliveryAddress::where('user_id', auth()->id())->count();
        if($shippingCount > 0) {
            // Pull data for existing address
            $shippingDetails = DeliveryAddress::where('user_id', auth()->id())->first();
        } else {
            $shippingDetails = "";
        }

        // Update cart with user email
        $session_id = Session::get('session_id');
        DB::table('cart')->where(['session_id' => $session_id])->update(['user_email'=> auth()->user()->email]);

        if($request->isMethod('post')){
            if(empty($request->billing_name) || 
            empty($request->billing_address) || 
            empty($request->billing_city) ||
            empty($request->billing_state) ||
            empty($request->billing_country) ||
            empty($request->billing_zipcode) ||
            empty($request->billing_mobile) ||
            empty($request->shipping_name) || 
            empty($request->shipping_address) || 
            empty($request->shipping_city) ||
            empty($request->shipping_state) ||
            empty($request->shipping_country) ||
            empty($request->shipping_zipcode) ||
            empty($request->shipping_mobile)){
                return redirect()->back()->with('flash_message_error', 'Please fill out all fields to Checkout!');
            }

            $zipcodeCount = Zipcode::where('code', $request->shipping_zipcode)->count();
            if($zipcodeCount == 0){
                return redirect()->back()->with('flash_message_error', 'Your location is not available for delivery, please select another location.');
            } 

            // User gets updated with billing information (it's not necessary when user signs up)
            User::where('id', auth()->id())->update([
                'name' => $request->billing_name,
                'address' => $request->billing_address,
                'city' => $request->billing_city,
                'state' => $request->billing_state,
                'country' => $request->billing_country,
                'zipcode' => $request->billing_zipcode,
                'mobile' => $request->billing_mobile
                ]);
                // If a DeliveryAddress exists for our user
            if($shippingCount > 0){
                // Update Shipping Address
                DeliveryAddress::where('user_id', auth()->id())->update([
                'name' => $request->shipping_name,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'country' => $request->shipping_country,
                'zipcode' => $request->shipping_zipcode,
                'mobile' => $request->shipping_mobile
                ]);
            } else {
                // Add new Shipping Address
                $shipping = new DeliveryAddress;
                $shipping->user_id = auth()->id();
                $shipping->user_email = auth()->user()->email;
                $shipping->name = $request->shipping_name;
                $shipping->address = $request->shipping_address;
                $shipping->city = $request->shipping_city;
                $shipping->state = $request->shipping_state;
                $shipping->zipcode = $request->shipping_zipcode;
                $shipping->country = $request->shipping_country;
                $shipping->mobile = $request->shipping_mobile;
                $shipping->save();
            } 
            return redirect('/order-review');
        }
        $meta_title = "Checkout - E-com Website";
        return view('products.checkout', compact('userDetails', 'countries', 'shippingDetails', 'meta_title'));
    }

    public function orderReview()
    {
        $userDetails = User::where('id', auth()->id())->first();
        $shippingDetails = DeliveryAddress::where('user_id', auth()->id())->first();
        $cartDetails = DB::table('cart')->where('user_email', auth()->user()->email)->get();
        foreach($cartDetails as $key => $product) {
            $product = Product::where('id', $product->product_id)->first();
            $cartDetails[$key]->image = $product->image;
        }
        // $codZipcodeCount = DB::table('cod_zipcodes')->where('code', $shippingDetails->zipcode)->count();
        // $prepaidZipcodeCount = DB::table('prepaid_zipcodes')->where('code', $shippingDetails->zipcode)->count();
        $meta_title = "Order Review - E-com Website";
        return view('products.order_review', compact('userDetails', 'shippingDetails', 'cartDetails', 'meta_title'));
    }

    public function placeOrder(Request $request)
    {
        if($request->isMethod('post')){
            // By this point, User information has been populated in checkout post
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;

            if(empty(Session::get('CouponCode'))){
                $request->coupon_code = '0.00';
            } else {
                $request->coupon_code = Session::get('CouponCode');
            }

            if(empty(Session::get('CouponAmount'))){
                $request->coupon_amount = '0.00';
            } else {
                $request->coupon_amount = Session::get('CouponAmount');
            }

            if(empty($request->shipping_charges)){
                $request->shipping_charges = '0.00';
            }
            // Order consist of customer shipping details from the Delivery Address as instantiated in checkout
            // We use the order for shipping details by virtue of Delivery Address 
            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();

            $zipcodeCount = Zipcode::where('code', $shippingDetails->zipcode)->count();
            if($zipcodeCount == 0){
                return redirect()->back()->with('flash_message_error', 'Your location is not available for delivery, please select another location.');
            } 

            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->zipcode = $shippingDetails->zipcode;
            $order->country = $shippingDetails->country;
            $order->mobile = $shippingDetails->mobile;
            $order->shipping_charges = $request->shipping_charges;
            $order->coupon_code = $request->coupon_code;
            $order->coupon_amount = $request->coupon_amount;
            $order->order_status = "New";
            $order->payment_method = $request->payment_method;
            $order->grand_total = $request->grand_total;
            $order->save();

            // Get the last insert ID for our order
            $order_id = DB::getPdo()->lastInsertId();

            // When customer places order, there has to be an email in the Cart, we update the cart with session => email when the customer logs in
            $cartProducts = DB::table('cart')->where(['user_email' => $user_email])->get();
            foreach($cartProducts as $product){
                $cartProduct = new OrderProduct;
                // From last insert ID of Order to OrderProduct
                $cartProduct->order_id = $order_id;
                // Can be auth()->id();
                $cartProduct->user_id = $user_id;
                // From Cart to OrderProduct
                $cartProduct->product_id = $product->product_id;
                $cartProduct->product_code = $product->product_code;
                $cartProduct->product_name = $product->product_name;
                $cartProduct->product_color = $product->product_color;
                $cartProduct->product_size = $product->size;
                $cartProduct->product_price = $product->price;
                $cartProduct->product_qty = $product->quantity;
                $cartProduct->save();
            }
            Session::put('order_id', $order_id);
            Session::put('grand_total', $request->grand_total);
            if($request->payment_method == "COD"){
                // Order Email
                $productDetails = Order::with('orders')->where('id', $order_id)->first();
                $userDetails = User::where('id', auth()->id())->first();
                $email = auth()->user()->email;
                $messageData = [
                    'email' => auth()->user()->email, 
                    'name' => auth()->user()->name,
                    'order_id' => $order_id,
                    'productDetails' => $productDetails,
                    'userDetails' => $userDetails
                    // We can add additional details like order/product information
                ];

                Mail::send('emails.order', $messageData, function($message) use ($email){
                    $message->to($email)->subject('Order Place - E-Commerce Website');
                });

                // COD - redirect user to thanks page after saving order
                return redirect('/thanks');
            } else {
                return redirect('/paypal');
            }
            
            
        }
    }
    public function thanks(Request $request)
    {
        DB::table('cart')->where('user_email', auth()->user()->email)->delete();
        return view('orders.thanks');
    }

    public function thanksPaypal()
    {
        return view('orders.thanks_paypal');
    }

    public function paypal(Request $request)
    {
        DB::table('cart')->where('user_email', auth()->user()->email)->delete();
        return view('orders.paypal');
    }

    public function cancelPaypal()
    {
        return view('orders.cancel_paypal');
    }

    public function userOrders()
    {
        $orders = Order::where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        return view('orders.user_orders', compact('orders'));
    }

    public function userOrderDetails($order_id)
    {
        $orderDetails = Order::with('orders')->where('user_id', auth()->id())->where('id', $order_id)->first();
        return view('orders.user_order_details', compact('orderDetails'));
    }

    public function viewOrders()
    {
        $orders = Order::with('orders')->orderBy('id', 'desc')->get();
        return view('admin.orders.view_orders', compact('orders'));
    }

    public function viewOrderDetails($order_id)
    {
        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        $userDetails = User::where('id', $orderDetails->user_id)->first();
        return view('admin.orders.order_details', compact('orderDetails', 'userDetails'));
    }

    public function updateOrderStatus(Request $request)
    {
        if($request->isMethod('post')){
            Order::where('id', $request->order_id)->update(['order_status' => $request->order_status]);
            return redirect()->back()->with('flash_message_success', 'Order Status has been updated successfully!');
        }
    }

    public function viewOrderInvoice($order_id)
    {
        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        $userDetails = User::where('id', $orderDetails->user_id)->first();
        return view('admin.orders.order_invoice', compact('orderDetails', 'userDetails'));
    }
}
 