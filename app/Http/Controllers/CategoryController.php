<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        // this could be index to follow REST
        if($request->isMethod('post')) {
            if(empty($request->status)){
                $status = 0;
            } else {
                $status = 1;
            }
            $category = new Category();
            $category->name = $request->category_name;
            $category->parent_id = $request->parent_id;
            $category->description = $request->description;
            $category->url = $request->url;
            $category->status = $status;
            $category->save();
            return redirect('/admin/view-categories')->with('flash_message_success', 'Category added Successfully!');
        }
        $levels = Category::where('parent_id', 0)->get();
        // why parent_id should be 0?
        return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function editCategory(Request $request, $id = null)
    {
        if($request->isMethod('post')){
            if(empty($request->status)){
                $status = 0;
            } else {
                $status = 1;
            }
            Category::where('id', $id)->update([
                'name' => $request->category_name,
                'description' => $request->description,
                'url' => $request->url,
                'status' => $status
            ]);
            return redirect('/admin/view-categories')->with('flash_message_success', 'Category updated Successfully!');
        }
        $category = Category::where('id', $id)->first();
        $levels = Category::where('parent_id', 0)->get();
        return view('admin.categories.edit_category')->with(compact('category', 'levels'));
    }

    public function deleteCategory(Request $request, $id = null)
    {
        if (!empty($id)){
            Category::where('id', $id)->delete();
            return redirect()->back()->with('flash_message_success', 'Category deleted Successfully!');
        }
    }

    public function viewCategories()
    {
        $categories = Category::get();
        return view('admin.categories.view_categories')->with(compact('categories'));
    }

    
}
