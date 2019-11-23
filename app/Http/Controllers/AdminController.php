<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->input();
            $adminCount = Admin::where(['username' => $request->username, 'password' => md5($request->password), 'status' => 1])->count();
            //if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'admin' => '1'])){
            if($adminCount > 0) {
                //echo "Sucess"; die;
                Session::put('adminSession', $request->username);
                return redirect('/admin/dashboard');
            } else {
                //echo "Failed"; die;
                return redirect('/admin/')->with('flash_message_error', 'Invalid Username or Password');
            }
        }
        return view('admin.admin_login');
    }

    public function dashboard()
    {
        //if(Session::has('adminSession')){
        //} else {
        //    return redirect('/admin')->with('flash_message_error', 'Please login to access');
        //}
        //we have used middleware along with RedirectIfAuthenticated
        return view('admin.dashboard');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function chkPassword(Request $request)
    {
        //$data = $request->all();
        $adminCount = Admin::where(['username' => Session::get('adminSession'), 'password' => md5($request->current_pwd), 'status' => 1])->count();
        if($adminCount == 1){
            return response()->json('true');
        } else {
            return response()->json('false');
        }
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            //$data = $request->all();
            $adminCount = Admin::where(['username' => Session::get('adminSession'), 'password' => md5($request->current_pwd), 'status' => 1])->count();
            if($adminCount == 1){
                $password = md5($request->new_pwd);
                Admin::where('username', Session::get('adminSession'))->update(['password' => $password]);
                return redirect('/admin/settings')->with('flash_message_success','Password updated Successfully!');
            } else {
                return redirect('/admin/settings')->with('flash_message_error','Incorrect Current Password!');
            }
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/admin/')->with('flash_message_success', 'Logged Out Successfully');
    }
}
