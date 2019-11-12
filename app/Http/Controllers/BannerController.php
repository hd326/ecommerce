<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use Image;
use Illuminate\Support\Facades\Input;
class BannerController extends Controller
{
    public function addBanner(Request $request)
    {
        if($request->isMethod('post')){
            $banner = new Banner;
            $banner->title = $request->title;
            $banner->link = $request->link;

            // Upload Image
            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    // Resize Image code
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$filename;
                    Image::make($image_tmp)->resize(1140,340)->save($banner_path);
                    $banner->image = $filename;
                }
            }

            if(empty($request->status)){
                $status = 0;
                // if there is no status, 0
            } else {
                // if there is a status, 1
                $status = 1;
            }
            $banner->status = $status;
            
            $banner->save();
            //return redirect()->back()->with('flash_message_success', 'Product has been added Successfully!');
            return redirect('/admin/view-banners')->with('flash_message_success', 'Banner has been added Successfully!');
        }
        return view('admin.banners.add_banner');
    }

    public function editBanner(Request $request, $id = null)
    {
        if($request->isMethod('post')){

            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    // Resize Image code
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$filename;
                    Image::make($image_tmp)->resize(1140,340)->save($banner_path);
                }
            } elseif (!empty($request->current_image)) {
                $filename = $request->current_image; 
            } else {
                $filename = "";
            }
        
            if(empty($request->status)){
                $status = 0;
            } else {
                $status = 1;
            }

            if(empty($request->title)){
                $title = '';
            } 

            if(empty($request->link)){
                $link = '';
            }

            $banner = Banner::find($id);
            $banner->title = $request->title;
            $banner->link = $request->link;
            $banner->image = $filename;
            $banner->status = $status;
            $banner->save();
            return redirect('/admin/view-banners')->with('flash_message_success', 'Banner has been updated Successfully!');
        }
        $banner = Banner::where('id', $id)->first();
        return view('admin.banners.edit_banner', compact('banner'));
    }

    public function viewBanners()
    {
        $banners = Banner::get();
        return view('admin.banners.view_banners', compact('banners'));
    }

    public function deleteBanner($id = null)
    {
        Banner::where('id', $id)->delete();
        return redirect()->back()->with('flash_message_success', 'Banner has been Successfully deleted!');
    }

}
