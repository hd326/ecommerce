<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\Coupon;
use Auth;
use Session;
use Illuminate\Support\Facades\Input;
use Image;
use DB;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        if($request->isMethod('post')){
            $product = new Product;
            $data = $request->all();
            //echo "<pre>";print_r($request->all());die;
            if(empty($request->category_id)){
                return redirect()->back()->with('flash_message_error', 'Under Category is missing!');
            }
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

            if(empty($request->status)){
                $status = 0;
                // if there is no status, 0
            } else {
                // if there is a status, 1
                $status = 1;
            }
            $product->status = $status;
            
            $product->save();
            //return redirect()->back()->with('flash_message_success', 'Product has been added Successfully!');
            return redirect('/admin/view-products')->with('flash_message_success', 'Product has been added Successfully!');
        }
        // Categories drop down start
        $categories = Category::where('parent_id', 0)->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat) {
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where('parent_id', $cat->id)->get();
            foreach($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value='".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        // Categories drop down ends
        return view('admin.products.add_product')->with(compact('categories_dropdown'));
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
            } else {
                $filename = $request->current_image; 
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

            Product::where('id', $id)->update([
                'category_id' => $request->category_id,
                'product_name' => $request->product_name,
                'product_code' => $request->product_code,
                'product_color' => $request->product_color,
                'description' => $request->description,
                'care' => $request->care,
                'price' => $request->price,
                'image' => $filename,
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

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $categoryDetails = Category::where(['url' => $url])->first();

        if($categoryDetails->parent_id == 0) {
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            foreach($subCategories as $subcategory){
                $cat_ids[] = $subcategory->id;
            }
            $products = Product::whereIn('category_id', $cat_ids)->where('status', 1)->get();
        } else {
            $products = Product::where(['category_id' => $categoryDetails->id])->where('status', 1)->get();
        }
        return view('products.listing', compact('categories', 'categoryDetails', 'products'));
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
        return view('products.detail', compact('product', 'categories', 'productAltImages', 'total_stock', 'relatedProducts'));
    }

    public function getProductPrice(Request $request)
    {
        //echo "<pre>"; print_r($request->all()); die;
        $proArr = explode("-", $request->idSize);
        //echo $proArr[0]; echo $proArr[1]; die;
        //explode takes the first parameter as the divisor for values in a new array
        $productsAttribute = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        //we get the id and size from the value, use the value to send an ajax, split those values, and pull what we need
        echo $productsAttribute->price;
        echo "#";
        echo $productsAttribute->stock;
    }

    public function addtocart(Request $request)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        if(empty($request->user_email)){
            $request->user_email = '';
        }

        //if(empty($request->session_id)){
        //    $request->session_id = '';
        //}
        $session_id = Session::get('session_id');
        if(empty($session_id)) {
            $session_id = str_random(40);
            Session::put('session_id', $session_id);
        }

        $sizeArr = explode("-", $request['size']);

        $countProducts = DB::table('cart')->where([
            'product_id' => $request->product_id,
            'product_color' => $request->product_color,
            'size' => $sizeArr[1],
            'session_id' => $session_id
        ])->count();

        if($countProducts > 0)
        {
            return redirect()->back()->with('flash_message_error', 'Product already exists in Cart!');
        } else {

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
        }

        return redirect('cart')->with('flash_message_success', 'Product has been added in Cart!');
    }

    public function cart()
    {

        $session_id = Session::get('session_id');
        $userCart = DB::table('cart')->where('session_id', $session_id)->get();
        foreach($userCart as $key => $product){
            $product = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $product->image;
        }
        return view('products.cart', compact('userCart'));
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

            $session_id = Session::get('session_id');

            $userCart = DB::table('cart')->where('session_id', $session_id)->get();

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
}
