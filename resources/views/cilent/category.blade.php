@extends('layouts.client')
@section('title')
    Danh mục sản phẩm
@endsection
@section('content')
<div id="main-content-wp" class="clearfix category-product-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{ url("/") }}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{ url("/") }}" title="">
                            @isset($category->category_name)
                                {{ $category->category_name }}
                            @endisset
                            
                            </a>
                    </li>
                    @if (request()->input("keyword"))
                       <li>
                        <a href="" title="">Kết quả với từ khóa: {{ request()->input("keyword") }}</a>
                    </li> 
                    @endif
                    
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="list-product-wp">
                {{-- <div class="section-head clearfix">
                    <h3 class="section-title fl-left">{{ $category->category_name }}</h3>
                    <div class="filter-wp fl-right">
                        <p class="desc">Hiển thị 45 trên 50 sản phẩm</p>
                        <div class="form-filter">
                            <form method="POST" action="">
                                <select name="select">
                                    <option value="0">Sắp xếp</option>
                                    <option value="1">Từ A-Z</option>
                                    <option value="2">Từ Z-A</option>
                                    <option value="3">Giá cao xuống thấp</option>
                                    <option value="3">Giá thấp lên cao</option>
                                </select>
                                <button type="submit">Lọc</button>
                            </form>
                        </div>
                    </div>
                </div> --}}
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        @if ($list_product->count() > 0)
                        @foreach ($list_product as $product)
                        <li>
                            <a href="{{ route("detail.product", $product->id) }}" title="" class="thumb">
                                <img src="{{ url("$product->product_image") }}">
                            </a>
                            <a href="{{ route("detail.product", $product->id) }}" title="" class="product-name">{{ $product->product_name }}</a>
                            <div class="price">
                                <span class="new">{{ number_format($product->price_new, "0",".") }}đ</span>
                                <span class="old">{{ number_format($product->price_old, "0",".") }}đ</span>
                            </div>
                            <div class="action clearfix">
                                <a href="{{ route("cart.add", $product->id) }}" title="Thêm giỏ hàng" class="add-cart fl-left">Thêm giỏ hàng</a>
                                <a href="{{ route("buy.now", $product->id) }}" title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                            </div>
                        </li>
                        @endforeach
                            
                        @else
                            Không có sản phẩm nào nằm trong danh mục này
                        @endif
                       
                    </ul>
                </div>
            </div>
            <div class="section" id="paging-wp">
                {{ $list_product->links() }}
            </div>
        </div>



       @include('layouts.clientSidebarCategory')
    </div>
</div>
@endsection