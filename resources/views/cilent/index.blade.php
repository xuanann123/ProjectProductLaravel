@extends('layouts.client')
@section('title')
    Trang chủ
@endsection
@section('content')
    <div id="main-content-wp" class="home-page clearfix">
    <div class="wp-inner">
        @if (session('status'))
           <div class="alert alert-danger">
            {{ session('status') }}
        </div> 
        @endif
        <div class="main-content fl-right">
            <div class="section" id="slider-wp">
                <div class="section-detail">
                    {{-- Danh sách slide ở đây --}}
                    @if ($slides->count() > 0)
                      @foreach ($slides as $slide)
                          <div class="item">
                                 <img src="{{ url("$slide->image") }}" alt="">
                        </div>
                      @endforeach  
                    @endif
                </div>
            </div>
            {{-- <div class="section" id="support-wp">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <div class="thumb">
                                <img src="{{ asset("client/images/icon-1.png") }}">
                            </div>
                            <h3 class="title">Miễn phí vận chuyển</h3>
                            <p class="desc">Tới tận tay khách hàng</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{ asset("client/images/icon-2.png") }}">
                            </div>
                            <h3 class="title">Tư vấn 24/7</h3>
                            <p class="desc">1900.9999</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{ asset("client/images/icon-3.png") }}">
                            </div>
                            <h3 class="title">Tiết kiệm hơn</h3>
                            <p class="desc">Với nhiều ưu đãi cực lớn</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{ asset("client/images/icon-4.png") }}">
                            </div>
                            <h3 class="title">Thanh toán nhanh</h3>
                            <p class="desc">Hỗ trợ nhiều hình thức</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{ asset("client/images/icon-5.png") }}">
                            </div>
                            <h3 class="title">Đặt hàng online</h3>
                            <p class="desc">Thao tác đơn giản</p>
                        </li>
                    </ul>
                </div>
            </div> --}}
            <div class="section" id="list-product-wp">
                @foreach ($list_phone_cat as $category)
                    <div class="section-head">
                    <h3 class="section-title" style="font-weight: 800">{{ $category->category_name }}</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        @foreach ($category->products as $product)
                        <li>
                            <a href="{{ route("detail.product", $product->id) }}" title="" class="thumb">
                                <img src="{{ url("$product->product_image") }}">
                            </a>
                            <a href="{{ route("detail.product", $product->id) }}" title="" class="product-name">{{ $product->product_name }}</a>
                            <div class="price">
                                <span class="new">{{ number_format($product->price_new, "0", "." )}}đ</span>
                                <span class="old">{{ number_format($product->price_old, "0", ".") }}đ</span>
                            </div>
                            <div class="action clearfix">
                                <a href="{{ route("cart.add", $product->id) }}" title="Thêm giỏ hàng" class="add-cart fl-left">Thêm giỏ hàng</a>
                                <a href="{{ route("buy.now", $product->id) }}" title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                            </div>
                        </li>
                          @endforeach
                    </ul>
                </div> 
                @endforeach
               
            </div>
        </div>
        @include('layouts.clientSidebar')
    </div>
</div>
@endsection




