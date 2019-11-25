@extends('layouts.adminLayout.admin_design')

@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                Home</a> <a href="#">CMS Pages</a> <a href="#" class="current">Add CMS Page</a> </div>
        <h1>CMS Pages</h1>
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
                        <h5>Add CMS Page</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form enctype="multipart/form-data" class="form-horizontal" method="post"
                            action="{{ url('/admin/add-cms-page') }}" name="add_cms_page" id="add_cms_page"
                            novalidate="novalidate">
                            {{ csrf_field() }}
                            <div class="control-group">
                                <label class="control-label">Title:</label>
                                <div class="controls">
                                    <input type="text" name="title" id="title" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Description:</label>
                                <div class="controls">
                                    <input type="text" name="description" id="description" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Meta Title:</label>
                                <div class="controls">
                                    <input type="text" name="meta_title" id="meta_title" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Meta Description:</label>
                                <div class="controls">
                                    <textarea name="meta_description" id="meta_description" required></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Meta Keywords:</label>
                                <div class="controls">
                                    <input type="text" name="meta_keywords" id="meta_keywords" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">URL:</label>
                                <div class="controls">
                                    <input type="text" name="url" id="url" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Status:</label>
                                <div class="controls">
                                    <input type="checkbox" name="status" id="status" value="1">
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="submit" value="Add Product" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
