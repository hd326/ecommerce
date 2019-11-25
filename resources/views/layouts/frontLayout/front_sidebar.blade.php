<?php use App\Product; ?>
<!-- productCount lives in App\Product -->
<form action="{{ url('/products-filter') }}" method="POST">{{ csrf_field() }}
    <input name="url" value="{{ $url }}" type="hidden">
        <div class="left-sidebar">
            <h2>Category</h2>
            <div class="panel-group category-products" id="accordian"><!--category-productsr-->
                <div class="panel panel-default">
                    @foreach($categories as $category)
                    @if($category->status == "1")
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordian" href="#{{ $category->id }}">
                                <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                {{ $category->name }}
                            </a>
                        </h4>
                    </div>
                    <div id="{{ $category->id }}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul>
                                @foreach($category->categories as $subcategory)
                                <?php $productCount = Product::productCount($subcategory->id); ?>
                                @if($subcategory->status == "1")
                                <li><a href="/products/{{ $subcategory->url }}">{{ $subcategory->name }}</a> ({{ $productCount }})</li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div><!--/category-products-->
            <h2>Colors</h2>
            @if(!empty($_GET['color']))
            <?php $colorArray = explode('-', $_GET['color']); ?>
            @endif
            <div class="panel-group"><!--category-productsr-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            
                                <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="blue" value="blue" type="checkbox" @if(!empty($colorArray) && in_array("blue", $colorArray)) checked="" @endif>&nbsp;&nbsp;<span class="product-colors">Blue</span>
                        
                        </h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            
                                <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="black" value="black" type="checkbox" @if(!empty($colorArray) && in_array("black", $colorArray)) checked="" @endif>&nbsp;&nbsp;<span class="product-colors">Black</span>
                        
                        </h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            
                                <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="red" value="red" type="checkbox" @if(!empty($colorArray) && in_array("red", $colorArray)) checked="" @endif>&nbsp;&nbsp;<span class="product-colors">Red</span>
                        
                        </h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            
                                <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="green" value="green" type="checkbox" @if(!empty($colorArray) && in_array("green", $colorArray)) checked="" @endif>&nbsp;&nbsp;<span class="product-colors">Green</span>
                        
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </form>
 