<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Country;
use DB;
use Auth;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            return redirect()->back()->with('flash_message_error', 'Email already exists!');
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            // Send Register Email
            //$email = $request->email;
            //$messageData = ['email' => $request->email, 'name' => $request->name];
            //// This is the data we are able to use within the email itself
            //Mail::send('emails.register', $messageData, function($message) use ($email){
            //    $message->to($email)->subject('Registration with E-Commerce Website');
            //});

            // Send Confirmation Email
            $email = $request->email;
            $messageData = ['email' => $request->email, 'name' => $request->name, 'code' => base64_encode($request->email)];
            Mail::send('emails.confirmation', $messageData, function($message) use ($email){
                $message->to($email)->subject('Confirm your E-Commerce Account');
            });

            return redirect()->back()->with('flash_message_success','Please confirm your email to activate your account!');

            //if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            //    Session::put('frontSession', $request->email);
            //    // unique email
            //    if(!empty(Session::get('session_id'))){
            //        $session_id = Session::get('session_id');
            //        DB::table('cart')->where('session_id',$session_id)->update(['user_email' => $request->email]);
            //    }
            //    return redirect('/cart');
            //}
        }
    }

    public function forgotPassword(Request $request)
    {
        if($request->isMethod('post')){
            $email = $request->email;
            $userCount = User::where('email', $email)->count();
            if($userCount == 0){
                return redirect()->back()->with('flash_message_error', 'Email does not exist!');
            } else {
                $userDetails = User::where('email', $email)->first();

                $random_password = str_random(8);

                $new_password = bcrypt($random_password);

                User::where('email', $email)->update(['password' => $new_password]);

                $messageData = ['name' => $userDetails->name, 'new_password' => $random_password];

                Mail::send('emails.forgot_password', $messageData, function($message) use ($email){
                    $message->to($email)->subject('Your temporary password for E-Commerce Website');
                });

                return redirect('/login-register')->with('flash_message_success', 'Your new password has been sent to your email!');
            }
        }
        return view('users.forgot_password');
    }
    
    public function logout()
    {
        Auth::logout();
        Session::forget('frontSession');
        Session::forget('session_id');
        return redirect('/');
    }

    public function login(Request $request)
    {
        if($request->isMethod('post')){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $userStatus = User::where('email', $request->email)->first();
                if($userStatus->status == 0){
                    return redirect()->back()->with('flash_message_error', 'Your account is not activated! Please confirm your email to activate.');
                }
                // once we auth attempt the email / password, we initiate a session used by our middleware as authorization to routes
                Session::put('frontSession', $request->email);
                if(!empty(Session::get('session_id'))){
                    $session_id = Session::get('session_id');
                    DB::table('cart')->where('session_id',$session_id)->update(['user_email' => $request->email]);
                }
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

    public function confirmAccount($email)
    {
        $email = base64_decode($email);
        // make the email token back into our email
        $userCount = User::where('email',$email)->count();
        // confirm that there is a user with such an email in our system and return a value
        if($userCount > 0){
            $userDetails = User::where('email',$email)->first();
            // check if status is already 1
            if($userDetails->status == 1){
                return redirect('login-register')->with('flash_message_success','Your Email account is already activated. You can login now.');
            }else{
                // update user status to 1 with where our decoded email
                User::where('email', $email)->update(['status' => 1]);
                // Send Welcome Email

                $messageData = ['email' => $email,'name' => $userDetails->name];
                Mail::send('emails.welcome',$messageData,function($message) use($email){
                    $message->to($email)->subject('Welcome to E-Commerce Website');
                });

                return redirect('login-register')->with('flash_message_success','Your Email account is activated. You can login now.');
            }
        } else {
            abort(404);
        }
    }

    public function viewUsers()
    {
        $users = User::all();
        return view('admin.users.view_users', compact('users'));
    }
}
