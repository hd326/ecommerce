@extends('layouts.frontLayout.front_design')
@section('content')
<?php use App\Product; ?>
<section>
    <div class="container">
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
            <div class="col-sm-3">
                @include('layouts.frontLayout.front_sidebar')
            </div>
            <div class="col-sm-9 padding-right">
                <div class="product-details">
                    <!--product-details-->
                    <div class="col-sm-5">
                        <div class="view-product">
                            <div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
                                <a href="{{ asset('images/backend_images/products/large/'.$product->image) }}">
                                    <img style="width: 300px;" class="mainImage"
                                        src="{{ asset('images/backend_images/products/medium/'.$product->image) }}"
                                        alt="" />
                                </a>
                            </div>
                        </div>
                        <div id="similar-product" class="carousel slide" data-ride="carousel">

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <div class="item active thumbnails">
                                    <a href="{{ asset('/images/backend_images/products/large/'.$product->image) }}"
                                        data-standard="{{ asset('/images/backend_images/products/small/'.$product->image) }}">
                                        <img class="changeImage" style="width: 80px; cursor: pointer"
                                            src="{{ asset('/images/backend_images/products/small/'.$product->image) }}"
                                            alt="">
                                    </a>
                                    @foreach($productAltImages as $altImage)
                                    <a href="{{ asset('/images/backend_images/products/large/'.$altImage->image) }}"
                                        data-standard="{{ asset('/images/backend_images/products/small/'.$altImage->image) }}">
                                        <img class="changeImage" style="width: 80px; cursor: pointer"
                                            src="{{ asset('/images/backend_images/products/small/'.$altImage->image) }}"
                                            alt="">
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-7">
                        <form action="{{ url('add-cart') }}" method="post" name="addtocartForm" id="addtocartForm">
                            {{ csrf_field() }}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="product_name" value="{{ $product->product_name }}">
                            <input type="hidden" name="product_code" value="{{ $product->product_code }}">
                            <input type="hidden" name="product_color" value="{{ $product->product_color }}">
                            <input type="hidden" name="price" id="price" value="{{ $product->price }}">
                            <div class="product-information">
                                <!--/product-information-->
                                <img src="images/product-details/new.jpg" class="newarrival" alt="" />
                                <h2>{{ $product->product_name }}</h2>
                                <p>Code: {{ $product->product_code}}</p>
                                <p>Color: {{ $product->product_color }}</p>
                                <p>
                                    <select id="selSize" name="size" style="width: 150px;">
                                        <option value="">Select size</option>
                                        @foreach($product->attributes as $attribute)
                                        <option value="{{ $product->id }}-{{ $attribute->size }}">{{ $attribute->size }}
                                        </option>
                                        @endforeach
                                    </select>
                                </p>
                                <img src="images/product-details/rating.png" alt="" />
                                <span>
                                    <?php $getCurrencyRates = Product::getCurrencyRates($product->price); ?>
                                    <span id="getPrice">
                                        US {{ $product->price }}
                                        <h2>INR {{ $getCurrencyRates['INR_Rate'] }}</h2>
                                        <h2>GBP {{ $getCurrencyRates['GBP_Rate'] }}</h2>
                                        <h2>EUR {{ $getCurrencyRates['EUR_Rate'] }}</h2>
                                    </span>
                                    <label>Quantity:</label>
                                    <input name="quantity" type="text" value="1" />
                                    @if($total_stock>0)
                                    <button type="submit" id="getCart" type="button" class="btn btn-fefault cart">
                                        <i class="fa fa-shopping-cart"></i>
                                        Add to cart
                                    </button>
                                    @endif
                                </span>
                                <p><b>Availability: </b><span id="getAvailability">@if($total_stock>0) In Stock @else
                                        Out of
                                        Stock @endif</span></p>
                                <p><b>Condition:</b> New</p>
                                <a href=""><img src="images/product-details/share.png" class="share img-responsive"
                                        alt="" /></a>
                                <p><b>Delivery</b></p>
                                <input type="text" name="zipcode" id="check_zipcode" placeholder="Check Zipcode">
                                <button type="button" onclick="return checkZipcode();">Go</button><br>
                                <br>
                                <p id="chkZipcode"></p>
                            </div>
                        </form>
                        <!--/product-information-->
                    </div>
                </div>
                <!--/product-details-->

                <div class="category-tab shop-details-tab">
                    <!--category-tab-->
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li><a href="#description" data-toggle="tab">Description</a></li>
                            <li><a href="#care" data-toggle="tab">Material & Care</a></li>
                            <li><a href="#delivery" data-toggle="tab">Material Options</a></li>
                            @if(!empty($product->video))
                            <li><a href="#video" data-toggle="tab">Product Video</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade" id="description">
                            <div class="col-sm-12">
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="care">
                            <div class="col-sm-12">
                                <p>{{ $product->care }}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="delivery">
                            <div class="col-sm-12">
                                <p>100% Original Products<br>
                                    Cash on Delivery
                                </p>
                            </div>
                        </div>
                        @if(!empty($product->video))
                        <div class="tab-pane fade" id="video">
                            <div class="col-sm-12">
                                <video controls width="640" height="480">
                                    <source src="{{ url('videos/'.$product->video) }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <!--/category-tab-->

                <div class="recommended_items">
                    <!--recommended_items-->
                    <h2 class="title text-center">recommended items</h2>

                    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php $count = 1; ?>
                            @foreach($relatedProducts->chunk(3) as $chunk)
                            <div <?php if($count==1) { ?> class="item active" <?php } else { ?> class="item" <?php } ?>>
                                @foreach($chunk as $item)
                                <div class="col-sm-4">
                                    <div class="product-image-wrapper">
                                        <div class="single-products">
                                            <div class="productinfo text-center">
                                                <img src="{{ asset('/images/backend_images/products/small/'.$item->image) }}"
                                                    alt="" />
                                                <h2>{{ $item->price }}</h2>
                                                <p>{{ $item->product_name }}</p>
                                                <a href="{{ url('product/'.$item->id) }}"><button type="button"
                                                        class="btn btn-default add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i>Add to cart</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <?php $count++; ?>
                            @endforeach
                        </div>
                        <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
                <!--/recommended_items-->

            </div>
        </div>
    </div>
</section>

@endsection
