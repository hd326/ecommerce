@extends('layouts.adminLayout.admin_design')

@section('content')

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a
                href="#" class="current">View Orders</a> </div>
        <h1>Orders</h1>
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
                        <h5>View Orders</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>Inquiry ID:</th>
                                    <th>Name:</th>
                                    <th>Email:</th>
                                    <th>Subject:</th>
                                    <th>Message:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inquiries as $inquiry)
                                <tr class="gradeX">
                                    <td>{{ $inquiry->id }}</td>
                                    <td>{{ $inquiry->name }}</td>
                                    <td>{{ $inquiry->email }}</td>
                                    <td>{{ $inquiry->subject }}</td>
                                    
                                    <td>
                                        {{ $inquiry->message }}
                                    </td>
                                    <td class="center">
                                        <a href="{{ url('/admin/view-inquiry/'.$inquiry->id) }}" class="btn btn-success btn-mini">View Order Details</a>
                                    </td>
                                </tr>

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
