@extends('layouts.frontLayout.front_design')

@section('content')
<section id="form" style="margin-top: 20px">
    <!--form-->
    <div class="container">
            @if(Session::has('flash_message_error'))
            <div class="alert alert-danger alert-block">
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
        <form action="{{ url('/checkout') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form">
                        <!--login form-->
                        <h2>Bill To</h2>
                        <div class="form-group">
                            <input name="billing_name" id="billing_name" value="{{ $userDetails->name }}" type="text"
                                placeholder="Billing Name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_address" id="billing_address" value="{{ $userDetails->address }}"
                                type="text" placeholder="Billing Address" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_city" id="billing_city" value="{{ $userDetails->city }}" type="text"
                                placeholder="Billing City" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_state" id="billing_state" value="{{ $userDetails->state }}" type="text"
                                placeholder="Billing State" class="form-control" />
                        </div>
                        <div class="form-group">
                            <select id="billing_country" name="billing_country" class="form-control">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->country_name }}" @if($country->country_name ==
                                    $userDetails->country) selected @endif>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="billing_zipcode" id="billing_zipcode" value="{{ $userDetails->zipcode }}"
                                type="text" placeholder="Billing Zip Code" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_mobile" id="billing_mobile" value="{{ $userDetails->mobile }}"
                                type="text" placeholder="Billing Mobile" class="form-control" />
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="billToShip">
                            <label class="form-check-label" for="billToShip">Shipping Address same as Billing
                                Address</label>
                        </div>
                    </div>
                    <!--/login form-->
                </div>
                <div class="col-sm-1">
                    <h2></h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form">
                        <!--sign up form-->
                        <h2>Ship To</h2>
                        <div class="form-group">
                            <input type="text" name="shipping_name" id="shipping_name" placeholder="Shipping Name"
                                class="form-control" value="{{ $shippingDetails->name }}"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="shipping_address" id="shipping_address"
                                placeholder="Shipping Address" class="form-control" value="{{ $shippingDetails->address }}"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="shipping_city" id="shipping_city" placeholder="Shipping City"
                                class="form-control" value="{{ $shippingDetails->city }}"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="shipping_state" id="shipping_state" placeholder="Shipping State"
                                class="form-control" value="{{ $shippingDetails->state }}"/>
                        </div>
                        <div class="form-group">
                            <select id="shipping_country" name="shipping_country" class="form-control">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->country_name }}" @if($country->country_name == $shippingDetails->country) selected @endif>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="shipping_zipcode" id="shipping_zipcode"
                                placeholder="Shipping Zipcode" class="form-control" value="{{ $shippingDetails->zipcode }}"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="shipping_mobile" id="shipping_mobile" placeholder="Shipping Mobile"
                                class="form-control" value="{{ $shippingDetails->mobile }}"/>
                        </div>
                        <button type="submit" class="btn btn-default">Checkout</button>
                    </div>
                    <!--/sign up form-->
                </div>
            </div>
        </form>
    </div>
</section>
<!--/form-->
@endsection
