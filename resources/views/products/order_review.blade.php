@extends('layouts.frontLayout.front_design')
@section('content')

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
                                        @else 0
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td><span>{{ $grand_total = $total_amount - Session::get('CouponAmount') }}</span></td>
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
