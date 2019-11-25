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
                        <form enctype="multipart/form-data" class="form-horizontal" method="post"
                            action="{{ url('/admin/edit-cms-page/' . $cmsPage->id) }}" name="edit_cms_page"
                            id="edit_cms_page" novalidate="novalidate">
                            {{ csrf_field() }}


                            <div class="control-group">
                                <label class="control-label">Title:</label>
                                <div class="controls">
                                    <input type="text" name="title" id="title" value="{{ $cmsPage->title }}">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Description:</label>
                                <div class="controls">
                                    <input type="text" name="description" id="description"
                                        value="{{ $cmsPage->description }}">
                                </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">Meta Title:</label>
                                    <div class="controls">
                                        <input type="text" name="meta_title" id="meta_title"
                                            value="{{ $cmsPage->meta_title }}">
                                    </div>
                                </div>
                                <div class="control-group">
                                        <label class="control-label">Meta Description:</label>
                                        <div class="controls">
                                            <textarea name="meta_description" id="meta_description">{{ $cmsPage->meta_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                            <label class="control-label">Meta Keywords:</label>
                                            <div class="controls">
                                                <input type="text" name="meta_keywords" id="meta_keywords"
                                                    value="{{ $cmsPage->meta_keywords }}">
                                            </div>
                                        </div>
                            <div class="control-group">
                                <label class="control-label">URL:</label>
                                <div class="controls">
                                    <input type="text" name="url" id="url"
                                        value="{{ $cmsPage->url }}">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Enable:</label>
                                <div class="controls">
                                    <input type="checkbox" name="status" id="status" @if($cmsPage->status == 1) checked
                                    @endif
                                    value="1">
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="submit" value="Edit CMS Page" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
