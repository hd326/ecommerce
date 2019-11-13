<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

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
                return redirect('/cart');
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
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
