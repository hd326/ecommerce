<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        } else {
            abort(404);
        }
        
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        return view('admin.pages.cms_page', compact('cmsPageDetails', 'categories'));
    }
}
