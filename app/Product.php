<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function attributes()
    {
        return $this->hasMany('App\ProductsAttribute', 'product_id');
    }

    //public static function cartCount()
    //{
    //    if (Auth::check()){
    //        // User is logged in; we will used auth
    //        $cartCount = DB::table('cart')->where('user_email', auth()->user()->email)->sum('quantity');
    //    } else {
    //        // User is not logged in; we will use session
    //        $session_id = Session::get('session_id');
    //        $cartCount = DB::table('cart')->where('session_id', $session_id)->sum('quantity');
    //    }
    //    return $cartCount;
    //}
    public static function productCount($category_id)
    {
        $catCount = Product::where(['category_id' => $category_id, 'status' => 1])->count();
        return $catCount;
    }

    public static function getCurrencyRates($price)
    {
        $getCurrencies = Currency::where('status', 1)->get();
        foreach($getCurrencies as $currency)
            if($currency->currency_code == "INR"){
                $INR_Rate = round($price/$currency->exchange_rate, 2);
            }
            else if($currency->currency_code == "GBP"){
                $GBP_Rate = round($price/$currency->exchange_rate, 2);
            }
            else if($currency->currency_code == "EUR"){
                $EUR_Rate = round($price/$currency->exchange_rate, 2);
            }
            $currenciesArr = array('INR_Rate' => $INR_Rate, 'GBP_Rate' => $GBP_Rate, 'EUR_Rate' => $EUR_Rate);
            return $currenciesArr;
    }

}
