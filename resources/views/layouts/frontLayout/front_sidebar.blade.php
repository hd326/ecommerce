<?php use App\Product; ?>
<!-- productCount lives in App\Product -->
<form action="{{ url('/products-filter') }}" method="POST">{{ csrf_field() }}
    @if(!empty($url))
    <input name="url" value="{{ $url }}" type="hidden">
    @endif
    <div class="left-sidebar">
        <h2>Category</h2>
        <div class="panel-group category-products" id="accordian">
            <!--category-productsr-->
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
                            <li><a href="/products/{{ $subcategory->url }}">{{ $subcategory->name }}</a>
                                ({{ $productCount }})</li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        <!--/category-products-->
        @if(!empty($url))
        <h2>Colors</h2>
        <div class="panel-group">
            <!--category-productsr-->
            @foreach($colorArray as $color)
            @if(!empty($_GET['color']))
            <?php $colorArr = explode('-', $_GET['color']); ?>
            @if(in_array($color, $colorArr))
            <?php $colorcheck = "checked"; ?>
            @else
            <?php $colorcheck = "" ?>
            @endif
            @else
            <?php $colorcheck = ""; ?>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="{{ $color }}"
                            value="{{ $color }}" type="checkbox" {{ $colorcheck }}>&nbsp;&nbsp;<span
                            class="product-colors">{{ $color }}</span>
                    </h4>
                </div>
            </div>
            @endforeach
        </div>

        <h2>Sleeves</h2>
        <div class="panel-group">
            <!--category-productsr-->
            @foreach($sleeveArray as $sleeve)
            @if(!empty($_GET['sleeve']))
            <?php $sleeveArr = explode('-', $_GET['sleeve']); ?>
            @if(in_array($sleeve, $sleeveArr))
            <?php $sleevecheck = "checked"; ?>
            @else
            <?php $sleevecheck = "" ?>
            @endif
            @else
            <?php $sleevecheck = ""; ?>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="sleeveFilter[]" onchange="javascript:this.form.submit();" id="{{ $sleeve }}"
                            value="{{ $sleeve }}" type="checkbox" {{ $sleevecheck }}>&nbsp;&nbsp;<span
                            class="product-colors">{{ $sleeve }}</span>
                    </h4>
                </div>
            </div>
            @endforeach
        </div>

        <h2>Patterns</h2>
        <div class="panel-group">
            <!--category-productsr-->
            @foreach($patternArray as $pattern)
            @if(!empty($_GET['pattern']))
            <?php $patternArr = explode('-', $_GET['pattern']); ?>
            @if(in_array($pattern, $patternArr))
            <?php $patterncheck = "checked"; ?>
            @else
            <?php $patterncheck = "" ?>
            @endif
            @else
            <?php $patterncheck = ""; ?>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="patternFilter[]" onchange="javascript:this.form.submit();" id="{{ $pattern }}"
                            value="{{ $pattern }}" type="checkbox" {{ $patterncheck }}>&nbsp;&nbsp;<span
                            class="product-colors">{{ $pattern }}</span>
                    </h4>
                </div>
            </div>
            @endforeach
        </div>

        <h2>Size</h2>
        <div class="panel-group">
            <!--category-productsr-->
            @foreach($sizeArray as $size)
            @if(!empty($_GET['size']))
            <?php $sizeArr = explode('-', $_GET['size']); ?>
            @if(in_array($size, $sizeArr))
            <?php $sizecheck = "checked"; ?>
            @else
            <?php $sizecheck = "" ?>
            @endif
            @else
            <?php $sizecheck = ""; ?>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="sizeFilter[]" onchange="javascript:this.form.submit();" id="{{ $size }}"
                            value="{{ $size }}" type="checkbox" {{ $sizecheck }}>&nbsp;&nbsp;<span
                            class="product-colors">{{ $size }}</span>
                    </h4>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</form>
