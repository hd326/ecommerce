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
                            action="{{ url('/admin/edit-product/' . $product->id) }}" name="edit_product"
                            id="edit_product" novalidate="novalidate">
                            {{ csrf_field() }}
                            <div class="control-group">
                                <label class="control-label">Under Category:</label>
                                <div class="controls">
                                    <select name="category_id" id="category_id" style="width: 220px;">
                                        <?php echo $categories_dropdown ?>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="control-group">
                                <label class="control-label">Category ID:</label>
                                <div class="controls">
                                    <input type="text" name="product_name" id="name"
                                        value="{{ $product->category_id }}">
                    </div>
                </div> --}}
                <div class="control-group">
                    <label class="control-label">Product Name:</label>
                    <div class="controls">
                        <input type="text" name="product_name" id="name" value="{{ $product->product_name }}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Product Code:</label>
                    <div class="controls">
                        <input type="text" name="product_code" id="name" value="{{ $product->product_code }}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Product Color:</label>
                    <div class="controls">
                        <input type="text" name="product_color" id="name" value="{{ $product->product_color }}">
                    </div>
                </div>

                {{-- <div class="control-group">
                                <label class="control-label">Category Level:</label>
                                <div class="controls">
                                    <select name="parent_id" style="width: 220px">
                                        <option value="0">Main Category</option>
                                        @foreach ($levels as $level)
                                        <option value="{{ $level->id }}" @if($level->id == $product->parent_id)
                selected @endif>{{ $level->name }}</option>
                @endforeach
                </select>
            </div>
        </div> --}}
        <div class="control-group">
            <label class="control-label">Description:</label>
            <div class="controls">
                <textarea name="description" id="description">{{ $product->description }}</textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Product Price:</label>
            <div class="controls">
                <input type="text" name="price" id="name" value="{{ $product->price }}">
            </div>
        </div>
        <div class="control-group">
                <label class="control-label">Image:</label>
                <div class="controls">
                    <input type="file" name="image" id="image">
                    <input type="hidden" name="current_image" value="{{ $product->image }}"> 
                    @if(!empty($product->image))
                    <img style="width: 40px;" src="{{ asset('images/backend_images/products/small/'.$product->image) }}"> | <a href="{{ url('/admin/delete-product-image/'.$product->id) }}">Delete</a>
                    @endif
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