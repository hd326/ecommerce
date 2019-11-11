<?php

namespace App\Http\Controllers;
use App\Coupon;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function addCoupon(Request $request)
    {
        if($request->isMethod('post')){
            if(empty($request->status)){
                $status = 0;
                // if there is no status, 0
            } else {
                // if there is a status, 1
                $status = 1;
            }
            $coupon = new Coupon;
            $coupon->coupon_code = $request->coupon_code;
            $coupon->amount = $request->amount;
            $coupon->amount_type = $request->amount_type;
            $coupon->expiry_date = $request->expiry_date;
            $coupon->status = $request->status;
            $coupon->save();
            return redirect('/admin/view-coupons')->with('flash_message_success', 'Coupon has been added Successfully!');
        }
        return view('admin.coupons.add_coupon');
    }

    public function viewCoupons()
    {
        $coupons = Coupon::get();
        return view('admin.coupons.view_coupons', compact('coupons'));
    }

    public function editCoupon(Request $request, $id = null)
    {
        if($request->isMethod('post')){
            if(empty($request->status)){
                $status = 0;
            } else {
                $status = 1;
            }
            $coupon = Coupon::find($id);
            $coupon->coupon_code = $request->coupon_code;
            $coupon->amount = $request->amount;
            $coupon->amount_type = $request->amount_type;
            $coupon->expiry_date = $request->expiry_date;
            $coupon->status = $status;
            $coupon->save();
            return redirect('/admin/view-coupons')->with('flash_message_success', 'Coupon has been updated Successfully!');
        }

        $coupon = Coupon::find($id);
        return view('admin.coupons.edit_coupon', compact('coupon'));
    }

    public function deleteCoupon($id = null)
    {
        Coupon::where('id', $id)->delete();
        return redirect('/admin/view-coupons')->with('flash_message_success', 'Coupon has been deleted Successfully!');
    }
}
