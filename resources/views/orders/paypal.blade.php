@extends('layouts.frontLayout.front_design')

@section('content')
<?php use App\Order; ?>
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Thanks</li>
            </ol>
        </div>

    </div>
</section>


<section id="do_action">
    <div class="container">
        <div class="heading" align="center">
            <h3>YOUR COD ORDER HAS BEEN PLACED</h3>
            <p>Your order number is {{ Session::get('order_id') }} and total payable amount is USD {{ Session::get('grand_total') }}</p>
            <p>Please make a payment by clicking on below Payment Button</p>
            <?php
            $orderDetails = Order::getOrderDetails(Session::get('order_id'));
            $getCountry = Order::getCountryCode($orderDetails->country);
            $nameArr = explode(' ', $orderDetails->name);
            ?>
            <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">

                <!-- Saved buttons use the "secure click" command -->
                <input type="text" name="cmd" value="_xclick">
                <input type="text" name="business" value="r_duong89@yahoo.com">
                <input type="text" name="item_name" value="{{ Session::get('order_id') }}">
                <input type="text" name="currency_code" value="US">
                <input type="text" name="amount" value="{{ Session::get('grand_total') }}">
                <input type="text" name="first_name" value="{{ $orderDetails->name }}">
                <input type="text" name="address1" value="{{ $orderDetails->address }}">
				<input type="text" name="address2" value="">
				<input type="text" name="city" value="{{ $orderDetails->city }}">
				<input type="text" name="state" value="{{ $orderDetails->state }}">
				<input type="text" name="zip" value="{{ $orderDetails->zipcode }}">
				<input type="text" name="email" value="{{ $orderDetails->user_email }}">
				<input type="text" name="country" value="{{ $getCountry->country_code }}">
				<input type="text" name="return" value="{{ url('paypal/thanks') }}">
				<input type="text" name="cancel_return" value="{{ url('paypal/cancel') }}">
              
                <!-- Saved buttons display an appropriate button image. -->
                <input type="image" name="submit"
                  src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
                  alt="PayPal - The safer, easier way to pay online">
                <img alt="" width="1" height="1"
                  src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
              
              </form>
        </div>
    </div>
</section>

@endsection
<?php
// Session::forget('grand_total');
// Session::forget('order_id');
?>


