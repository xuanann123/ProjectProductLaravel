<div class="sidebar fl-left">
            <div class="section" id="category-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Danh mục sản phẩm</h3>
                </div>
                <div class="secion-detail">
                    <ul class="list-item">
                        @foreach ($list_phone_cat as $categoryParent)
                             <li>
                            <a href="{{ route("detail.category", $categoryParent->id) }}" title="">{{ $categoryParent->category_name }}</a>
                            @include('components.child_menu', ["categoryParent" => $categoryParent])
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="section" id="selling-wp">
                <div class="section-head">
                    <h3 class="section-title">Sản phẩm bán chạy</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @foreach ($list_product_sold_most as $product)
                           <li class="clearfix">
                            <a href="{{ route("detail.product", $product->id) }}" title="" class="thumb fl-left">
                                <img src="{{ url("$product->product_image") }}" alt="">
                            </a>
                            <div class="info fl-right">
                                <a href="{{ route("detail.product", $product->id) }}" title="" class="product-name">{{ $product->product_name }}</a>
                                <div class="price">
                                    <span class="new">{{ $product->price_new }}</span>
                                    <span class="old">{{ $product->price_old }}</span>
                                </div>
                                <a href="{{ route("buy.now", $product->id) }}" title="" class="buy-now">Mua ngay</a>
                            </div>
                        </li> 
                        @endforeach
                        
                        
                    </ul>
                </div>
            </div>
            <div class="section" id="banner-wp">
                <div class="section-detail">
                    <a href="" title="" class="thumb">
                        <img src="public/images/banner.png" alt="">
                    </a>
                </div>
            </div>
        </div>