@extends('layouts.client')
@section('title')
    Trang chủ
@endsection
@section('content')
<div id="main-content-wp" class="clearfix detail-product-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{ url("/") }}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{ route("detail.category", $category_parent->id) }}" title="">{{ $category_parent->category_name }}</a>
                    </li>
                    <li>
                        <a href="{{ route("detail.product",$product->id) }}" title="">{{ $product->product_name }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="detail-product-wp">
                <div class="section-detail clearfix">
                    <div class="thumb-wp fl-left">
                        <a href="" title="" id="main-thumb">
                            <img id="zoom" width="350px" src="{{ url("$product->product_image") }}" data-zoom-image="{{ url("$product->product_image") }}"/>
                        </a>
                        <div id="list-thumb">
                            @if ($product->images->count() > 0) 
                            @foreach ($product->images as $image)
                                <a href="" data-image="{{ url("$image->image_path") }}" data-zoom-image="{{ url("$image->image_path") }}">
                                <img id="zoom" src="{{ url("$image->image_path") }}"/>
                            </a>    
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="thumb-respon-wp fl-left">
                        <img src="{{ url(" $product->product_image ") }}" alt="">
                    </div>
                    <div class="info fl-right">
                        <h3 class="product-name">{{ $product->product_name }}</h3>
                        <div class="desc">
                            {!! $product->product_desc !!}
                        </div>
                        <div class="num-product">
                            <span class="title">Sản phẩm: </span>
                            <span class="status">
                                @if ($product->qty_remain > 0)
                                    Còn hàng
                                @else
                                    Hết hàng
                                @endif
                            </span>
                        </div>
                        <del>{{ number_format($product->price_old, "0",".") }}đ</del>

                        <p class="price">{{ number_format($product->price_new,"0",".") }}đ</p>
                        <form method="get" action="{{ route("cart.add", $product->id) }}">
                        <div id="num-order-wp">
                            <a title="" id="minus"><i class="fa fa-minus"></i></a>
                            <input type="text" name="num-order" value="1" max="{{ $product->qty_remain }}" id="num-order">
                            <a title="" id="plus"><i class="fa fa-plus"></i></a>
                        </div>
                        {{-- <a href="{{ route("cart.add", $product->id) }}" title="Thêm giỏ hàng" class="add-cart">Thêm giỏ hàng</a> --}}
                        <input type="submit" class="bg-success text-white py-2 px-4 font-weight-bold border-0 rounded" name="add_cart" value="Thêm giỏ hàng">
                        <input type="submit" class="bg-danger text-white py-2 px-4 font-weight-bold border-0 rounded" name="buy_now" value="Mua ngay">
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="section" id="post-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Mô tả sản phẩm</h3>
                </div>
                <div class="section-detail">
                 {!! $product->product_detail !!}
                </div>
            </div>
            <div class="section" id="same-category-wp">
                <div class="section-head">
                    <h3 class="section-title">Cùng chuyên mục</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @if ($relatedProduct->count() > 0)
                        @foreach ($relatedProduct as $product)
                            <li>
                            <a href="" title="" class="thumb">
                                <img src="{{ url("$product->product_image") }}">
                            </a>
                            <a href="" title="" class="product-name">{{ $product->product_name }}</a>
                            <div class="price">
                                <span class="new">{{ number_format($product->price_new,"0",".") }}đ</span>
                                <span class="old">{{ number_format($product->price_old,"0",".") }}đ<</span>
                            </div>
                            <div class="action clearfix">
                                <a href="{{ route("cart.add", $product->id) }}" title="" class="add-cart fl-left">Thêm giỏ hàng</a>
                                <a href="{{ route("buy.now", $product->id) }}" title="" class="buy-now fl-right">Mua ngay</a>
                            </div>
                        </li>
                        @endforeach
                         @endif
                        
                        
                    </ul>
                </div>
            </div>
        </div>
        @include('layouts.clientSidebar')
    </div>
</div>
@endsection


