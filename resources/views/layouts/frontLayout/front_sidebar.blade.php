<?php use App\Product; ?>
<!-- productCount lives in App\Product -->
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
        </div>
 