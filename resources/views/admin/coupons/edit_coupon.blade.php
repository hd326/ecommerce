@extends('layouts.adminLayout.admin_design')

@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                Home</a> <a href="#">Form elements</a> <a href="#" class="current">Validation</a> </div>
        <h1>Edit Product</h1>
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
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>Edit Product</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post"
                            action="{{ url('/admin/edit-coupon/'.$coupon->id) }}" name="edit_coupon" id="edit_coupon"
                            novalidate="novalidate">
                            {{ csrf_field() }}

                            <div class="control-group">
                                <label class="control-label">Coupon Code:</label>
                                <div class="controls">
                                    <input type="text" name="coupon_code" id="name"
                                        value="{{ $coupon->coupon_code }}" minlength="5" maxlength="15" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Amount:</label>
                                <div class="controls">
                                    <input type="number" name="amount" id="name"
                                        value="{{ $coupon->amount }}" min="0" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Amount Type:</label>
                                <div class="controls">
                                    <select name="amount_type" style="width: 220px">
                                        <option value="Percentage" @if($coupon->amount_type == 'Percentage') selected @endif>Percentage</option>
                                        <option value="Fixed" @if($coupon->amount_type == 'Fixed') selected @endif>Fixed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Expiry Date:</label>
                                <div class="controls">
                                    <input type="date" name="expiry_date" id="expiry_date" value="{{ $coupon->expiry_date }}" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Enable:</label>
                                <div class="controls">
                                    <input type="checkbox" name="status" id="status" @if($coupon->status == 1) checked
                                    @endif
                                    value="1">
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="submit" value="Edit Product" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
