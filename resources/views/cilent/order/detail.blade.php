@extends('layouts.client')
@section('title')
    Lịch sử mua hàng
@endsection
@section('content')
<div id="main-content-wp" class="cart-page">
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{ url("/") }}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{ route("user.order") }}" title="">Lịch sử mua hàng</a>
                    </li>
                    <li>
                        <a href="" title="">Lịch sử mua hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="info-cart-wp">
            <div class="section-detail table-responsive">
                     <table class="table table-striped table-checkall text-center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($order->products))
                            @php
                                $sum_price = 0;
                                $sum_qty = 0;
                                $cnt = 0;
                                $order_total_product = 0;
                                //Tổng tiền ở đây đang không thay đổi luôn nè
                            @endphp
                            @foreach ($order->products as $product)
                                @php
                                //Lấy đơn giá của sản phẩm ra
                                $order_total_product = $product->pivot->number_order;
                                $cnt++;
                                @endphp
                                <tr class="row-in-list">
                                    <th scope=" row">{{ $cnt }}</th>
                                    <td class="">
                                        <a href="{{ route('product.edit', $product->id) }}" class="product_image">
                                            <img style="margin-left: 63px" src="{{ url("$product->product_image") }}"
                                                alt="Ảnh của sản phẩm {{ $product->product_name }}"
                                                title="Ảnh của sản phẩm {{ $product->product_name }}" id="product_image" width="80px">
                                        </a>
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    @php
                                    //Kiểm tra xem thằng này có giá mới hay hay không nếu không có thì cho giá cũ
                                        $product_price = !empty($product->price_new) ? $product->price_new : $product->price_old;
                                    @endphp
                                    <td>{!! number_format($product_price, 0, ".", ".") !!}vnđ</td>
                                    {{-- Số lượng sản phẩm đơn hàng --}}
                                    <td>{{ $order_total_product }}</td>
                                    @php
                                    // Tổng số tiền của một đơn hàng
                                        $price_total = $order_total_product * $product_price;
                                    @endphp
                                    <td><b>{!! number_format($price_total) !!}vnđ</b></td>
                                    @php
                                    //Tổng số tiền của tất cả sản phẩm
                                        $sum_price += $price_total;
                                    //Tổng số lượng của tất cả các sản phẩm
                                        $sum_qty += $order_total_product;
                                    @endphp
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="bg-white">Không tìm thấy trang nào!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div class="section" id="action-cart-wp">
            <div class="section-detail">
                <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng. Nhập vào số lượng <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh toán để hoàn tất mua hàng.</p>
                <a href="{{ url("/") }}" title="" id="buy-more">Mua tiếp</a><br/>
            </div>
        </div> --}}
    </div>
</div>
@endsection
