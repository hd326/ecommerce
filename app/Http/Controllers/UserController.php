<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Country;
use Auth;
use Session;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userLoginRegister(Request $request)
    {
        return view('users.login_register');
    }

    public function register(Request $request)
    {
        $userCount = User::where('email', $request->email)->count();
        if($userCount>0){
            return redirect()->back()->with('flash_message_error', ' Email already exists!');
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                Session::put('frontSession', $request->email);
                // unique email
                return redirect('/cart');
            }
        }
    }
    
    public function logout()
    {
        Auth::logout();
        Session::forget('frontSession');
        return redirect('/');
    }

    public function login(Request $request)
    {
        if($request->isMethod('post')){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                Session::put('frontSession', $request->email);
                return redirect('/cart');
            } else {
                return redirect()->back()->with('flash_message_error', 'Invalid Username or Password');
            }
        }
    }

    public function account(Request $request)
    {
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        if($request->isMethod('post')){
            if(empty($request->name)) {
                return redirect()->back()->with('flash_message_error', 'Please enter your name to update your account details!');
            }

            if(empty($request->address)){
                $request->address = '';
            }

            if(empty($request->city)){
                $request->city = '';
            }

            if(empty($request->state)){
                $request->state = '';
            }

            if(empty($request->zipcode)){
                $request->zipcode = '';
            }

            if(empty($request->country)){
                $request->country = '';
            }

            if(empty($request->mobile)){
                $request->mobile = '';
            }

            $user = User::find($user_id);
            $user->name = $request->name;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->country = $request->country;
            $user->zipcode = $request->zipcode;
            $user->mobile = $request->mobile;
            $user->save();
            return redirect()->back()->with('flash_message_success', 'Your account details has been successfully updated!');
        }

        return view('users.account', compact('countries', 'userDetails'));
    }

    public function chkUserPassword(Request $request)
    {
        $current_password = $request->current_pwd;
        $user_id = Auth::user()->id;
        $check_password = User::where('id', $user_id)->first();
        if(Hash::check($current_password, $check_password->password)){
            echo "true"; die;
        } else {
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request)
    {
        if($request->isMethod('post')){
            $old_password = User::where('id', Auth::User()->id)->first();
            $current_password = $request->current_pwd;
            
            if(Hash::check($current_password, $old_password->password)){
                $new_pwd = bcrypt($request->new_pwd);
                User::where('id', Auth::User()->id)->update(['password' => $new_pwd]);
                return redirect()->back()->with('flash_message_success', 'Password updated Successfully!');
            } else {
                return redirect()->back()->with('flash_message_error', 'Current password is incorrect!');
            }
        }
    }

    public function checkEmail(Request $request)
    {
        $userCount = User::where('email', $request->email)->count();
            if($userCount>0){
                echo "false";
            } else {
                echo "true"; die;
            }
    }


}
