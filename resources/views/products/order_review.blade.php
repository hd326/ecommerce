@extends('layouts.frontLayout.front_design')
@section('content')
<?php use App\Product; ?>
<section id="cart_items">
    <div class="container">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Order Review</li>
                </ol>
            </div>
            <div class="row">
                    @if(Session::has('flash_message_error'))
                    <div class="alert alert-error alert-block" style="background-color:#d9534f">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{!! session('flash_message_error') !!} </strong>
                    </div>
                    @endif
                    @if(Session::has('flash_message_success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{!! session('flash_message_success') !!} </strong>
                    </div>
                    @endif
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form">
                        <h2>Billing Details</h2>
                        <div class="form-group">
                            {{ $userDetails->name }}
                        </div>
                        <div class="form-group">
                            {{ $userDetails->address }}
                        </div>
                        <div class="form-group">
                            {{ $userDetails->city }}
                        </div>
                        <div class="form-group">
                            {{ $userDetails->state }}
                        </div>
                        <div class="form-group">
                            {{ $userDetails->country }}
                        </div>
                        <div class="form-group">
                            {{ $userDetails->zipcode }}
                        </div>
                        <div class="form-group">
                            {{ $userDetails->mobile }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-1">
                    <h2></h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form">
                        <!--sign up form-->
                        <h2>Shipping Details</h2>
                        <div class="form-group">
                            {{ $shippingDetails->name }}
                        </div>
                        <div class="form-group">
                            {{ $shippingDetails->address }}
                        </div>
                        <div class="form-group">
                            {{ $shippingDetails->city }}
                        </div>
                        <div class="form-group">
                            {{ $shippingDetails->state }}
                        </div>
                        <div class="form-group">
                            {{ $shippingDetails->country }}
                        </div>
                        <div class="form-group">
                            {{ $shippingDetails->zipcode }}
                        </div>
                        <div class="form-group">
                            {{ $shippingDetails->mobile }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="review-payment">
            <h2>Review & Payment</h2>
        </div>

        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Item</td>
                        <td class="description"></td>
                        <td class="price">Price</td>
                        <td class="quantity">Quantity</td>
                        <td class="total">Total</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php $total_amount = 0; ?>
                    @foreach($cartDetails as $cart)
                    <tr>
                        <td class="cart_product">
                            <a href=""><img src="{{ asset('/images/backend_images/products/small/'.$cart->image) }}"
                                    alt=""></a>
                        </td>
                        <td class="cart_description">
                            <h4><a href="">{{ $cart->product_name }}</a></h4>
                            <p>{{ $cart->product_code }} | {{ $cart->size }}</p>
                        </td>
                        <td class="cart_price">
                            <p>US {{ $cart->price }}</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                {{ $cart->quantity }}
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">US {{ $cart->price * $cart->quantity }}</p>
                        </td>

                    </tr>
                    <?php $total_amount = $total_amount + ($cart->price * $cart->quantity); ?>
                    @endforeach

                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">
                            <table class="table table-condensed total-result">
                                <tr>
                                    <td>Cart Sub Total</td>
                                    <td>USD {{ $total_amount }}</td>
                                </tr>
                                <tr class="shipping-cost">
                                    <td>Shipping Cost</td>
                                    <td>Free</td>
                                </tr>
                                <tr class="shipping-cost">
                                    <td>Discount Amount</td>
                                    <td>
                                        @if(!empty(Session::get('CouponAmount')))
                                        USD {{ Session::get('CouponAmount') }}
                                        @else USD 0
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Grand Total</td>
                                    <?php $grand_total = $total_amount - Session::get('CouponAmount'); ?>
                                    <?php $getCurrencyRates = Product::getCurrencyRates($total_amount); ?>
                                    <td><span class="btn-secondary" data-toggle="tooltip" data-html="true" title="
                                        INR {{ $getCurrencyRates['INR_Rate'] }}<br>
                                        GBP {{ $getCurrencyRates['GBP_Rate'] }}<br>
                                        EUR {{ $getCurrencyRates['EUR_Rate'] }}">{{ $grand_total }}</span></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <form name="paymentForm" id="paymentForm" action="{{ url('/place-order') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="grand_total" value="{{ $grand_total }}">
        <div class="payment-options">
            <span>
                <label><strong>Select Payment Method: </strong></label>
            </span>
            <!-- consult with 121 -->
            <span>
                <label><input type="radio" name="payment_method" id="COD" value="COD"> <strong>COD</strong></label>
            </span>
            <span>
                <label><input type="radio" name="payment_method" id="Paypal" value="Paypal"> <strong>Paypal</strong></label>
            </span>
            <span style="float:right;">
                <button type="submit" class="btn btn-default" onclick="return selectPaymentMethod();">Place Order</button>
            </span>
        </div>
    </form>
    </div>
</section>
<!--/#cart_items-->
@endsection
