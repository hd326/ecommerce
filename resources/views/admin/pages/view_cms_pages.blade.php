@extends('layouts.adminLayout.admin_design')

@section('content')

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a
                href="#" class="current">View Products</a> </div>
        <h1>Products</h1>
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
                        <h5>View Products</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>CMS Page ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cmsPages as $cmsPage)
                                <tr class="gradeX">
                                    <td>{{ $cmsPage->id }}</td>
                                    <td>{{ $cmsPage->title }}</td>
                                    <td>{{ $cmsPage->description }}</td>
                                    <td>{{ $cmsPage->url }}</td>
                                    <td>{{ $cmsPage->status }}</td>
                                    <td class="center">
                                        <a href="{{ url('/admin/edit-cms-page/' . $cmsPage->id) }}"
                                            class="btn btn-primary btn-mini" title="Edit Product">Edit</a>
                                        <a id="delCmsPage" href="{{ url('/admin/delete-cms-page/' . $cmsPage->id) }}"
                                            class="btn btn-danger btn-mini" title="Delete Product">Delete</a>
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
