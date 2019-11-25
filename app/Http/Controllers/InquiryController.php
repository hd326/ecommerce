<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inquiry;

class InquiryController extends Controller
{
    public function viewInquiries()
    {
        $inquiries = Inquiry::get();
        return view('admin.inquiries.view_inquiries', compact('inquiries'));
    }

    public function viewInquiry($id)
    {
        $inquiry = Inquiry::find($id);
        return view('admin.inquiries.inquiry_details', compact('inquiry'));
    }

    public function deleteInquiry($id){
        Inquiry::where('id', $id)->delete();
        return back()->with('flash_message_success', 'Inquiry has been deleted!');
    }
}
