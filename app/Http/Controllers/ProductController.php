<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use Auth;
use Session;
use Illuminate\Support\Facades\Input;
use Image;

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

            Product::where('id', $id)->update([
                'category_id' => $request->category_id,
                'product_name' => $request->product_name,
                'product_code' => $request->product_code,
                'product_color' => $request->product_color,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $filename
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
        $products = Product::all();
        $products = json_decode(json_encode($products));
        foreach($products as $key => $val) {
            $category_name = Category::where('id', $val->category_id)->first();
            $products[$key]->category_name = $category_name->name;
        }
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProductImage($id = null)
    {
        Product::where('id', $id)->update(['image' => '']);
        return redirect()->back()->with('flash_message_success', 'Product Image has been deleted successfully!');
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
        $product = Product::where('id', $id)->first();
        if($request->isMethod('post')){
            foreach($request->sku as $key => $value) {
                if(!empty($value)) {
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
}
