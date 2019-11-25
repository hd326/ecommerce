<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\CmsPage;
use App\Category;

class CmsController extends Controller
{
    public function addCmsPage(Request $request)
    {
        if($request->isMethod('post')){
            if(empty($request->status)){
                $status = 0;
            } else {
                $status = 1;
            }
            $cmspage = new CmsPage;
            $cmspage->title = $request->title;
            $cmspage->description = $request->description;
            $cmspage->meta_title = $request->meta_title;
            $cmspage->meta_description = $request->meta_description;
            $cmspage->meta_keywords = $request->meta_keywords;
            $cmspage->url = $request->url;
            $cmspage->status = $status;
            $cmspage->save();
            return redirect('/admin/view-cms-pages')->with('flash_message_success', 'CMS Page has been added successfully!');
        }
        return view('admin.pages.add_cms_page');
    }

    public function viewCmsPages()
    {
        $cmsPages = CmsPage::get();
        return view('admin.pages.view_cms_pages', compact('cmsPages'));
    }

    public function editCmsPage(Request $request, $id)
    {
        $cmsPage = CmsPage::find($id);

        if($request->isMethod('post')){
            if(empty($request->status)){
                $status = 0;
            } else {
                $status = 1;
            }
            CmsPage::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'url' => $request->url,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'status' => $status
            ]);
            return redirect()->back()->with('flash_message_success', 'CMS Page has been updated successfully!');
        }
        return view('admin.pages.edit_cms_page', compact('cmsPage'));
    }

    public function deleteCmsPage($id)
    {
        CmsPage::where('id', $id)->delete();
        return redirect()->back()->with('flash_message_success', 'CMS Page has been deleted successfully!');
    }

    public function cmsPage($url)
    {
        $cmsPageCount = CmsPage::where(['url' => $url, 'status' => 1])->count();
        if($cmsPageCount > 0){
            $cmsPageDetails = CmsPage::where('url', $url)->first();
            $meta_title = $cmsPageDetails->meta_title;
            $meta_description = $cmsPageDetails->meta_description;
            $meta_keywords = $cmsPageDetails->meta_keywords;
        } else {
            abort(404);
        }
        
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        return view('pages.cms_page', compact('cmsPageDetails', 'categories','meta_title', 'meta_description', 'meta_keywords'));
    }

    public function contact(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required'
            ]);

            $email = "tkss4499@yopmail.com";
            $messageData = [
                'name' => $request->name, 
                'email' => $request->email, 
                'subject' => $request->subject, 
                'comment' => $request->message 
            ];

            Mail::send('emails.inquiry', $messageData, function($message) use ($email){
                $message->to($email)->subject('A User has contacted you! - E Commerce Website');
            });

            return redirect()->back()->with('flash_message_success', 'Thanks for your inquiry. We will get back to you soon.');
        }
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        return view('pages.contact', compact('categories'));
    }
}
