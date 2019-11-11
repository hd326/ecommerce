@extends('layouts.adminLayout.admin_design')

@section('content')

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a
                href="#" class="current">View Coupons</a> </div>
        <h1>Coupons</h1>
        @if(Session::has('flash_message_error'))
        <div class="alert alert-error alert-block">
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
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>View Coupons</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>Coupon ID</th>
                                    <th>Coupon Code</th>
                                    <th>Amount</th>
                                    <th>Amount Type</th>
                                    <th>Expiry Date</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $coupon)
                                <tr class="gradeX">
                                    <td>{{ $coupon->id }}</td>
                                    <td>{{ $coupon->coupon_code }}</td>
                                    <td>
                                        {{ $coupon->amount }}
                                        @if($coupon->amount_type == "Percentage") % 
                                        @else USD
                                        @endif
                                    </td>
                                    <td>{{ $coupon->amount_type }}</td>
                                    <td>{{ $coupon->expiry_date }}</td>
                                    <td>{{ $coupon->created_at }}</td>
                                    <td>
                                        @if($coupon->status == 1) Active
                                        @else Inactive
                                        @endif
                                    </td>
                                    <td class="center">
                                        <a href="#myModal{{ $coupon->id }}" data-toggle="modal"
                                            class="btn btn-success btn-mini" title="View Coupon">View</a>
                                        <a href="{{ url('/admin/edit-coupon/' . $coupon->id) }}"
                                            class="btn btn-primary btn-mini" title="Edit Coupon">Edit</a>   
                                        <a id="delCoupon" href="{{ url('/admin/delete-coupon/' . $coupon->id) }}"
                                            class="btn btn-danger btn-mini" title="Delete Coupon">Delete</a>
                                    </td>
                                </tr>
                                <div id="myModal{{ $coupon->id }}" class="modal hide">
                                    <div class="modal-header">
                                        <button data-dismiss="modal" class="close" type="button">×</button>
                                        <h3>{{ $coupon->product_name }} Full Details</h3>
                                    </div>
                                    <div class="modal-body">
                                        <p>Coupon ID: {{ $coupon->id }}</p>
                                        <p>Coupon Code: {{ $coupon->coupon_code }}</p>
                                        <p>Amount: {{ $coupon->amount }}</p>
                                        <p>Amount Type: {{ $coupon->amount_type }}</p>
                                        <p>Expiry Date: {{ $coupon->expiry_date }}</p>
                                        <p>Created at: {{ $coupon->created_at }}</p>
                                        <p>Status: {{ $coupon->status }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>

                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
