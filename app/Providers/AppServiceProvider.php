<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Category;
use App\Cart;
use Auth;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['layouts.frontLayout.front_header'], function($view){
            $view->with('mainCategories', Category::where('parent_id', 0)->get());
        });

        view()->composer(['layouts.frontLayout.front_header'],  function($view){
            if(Auth::check()){
                $view->with('cartCount', Cart::where('user_email', auth()->user()->email)->sum('quantity'));
            } else {
                $session_id = Session::get('session_id');
                $view->with('cartCount', Cart::where('session_id', $session_id)->sum('quantity'));
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
