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
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="info-cart-wp">
@if (session('status'))
           <div class="alert alert-danger">
            {{ session('status') }}
        </div> 
        @endif
            {{-- Trạng thái ở đây --}}
            <div class="section-detail table-responsive">
                     <table class="table mt-2">
                    <thead>
                        <tr>
                            <td>STT</td>
                            <td>Mã đơn hàng</td>
                            <td>Phương thức thanh toán</td>
                            <td>Trạng thái đơn hàng</td>
                            <td>Số lượng</td>
                            <td>Giá đơn hàng</td>
                            <td>Tác vụ</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $t = 0;
                            
                        @endphp
                        @foreach ($user->orders as $order)
                        @php
                            $t++;
                            //Mỗi lần tính đơn hàng tiếp sẽ reset này về không  
                            $quantity_product_order = 0;
                            $total_price = 0;
                            foreach ($order->products as $product) {
                                    $total_price += ($product->pivot->number_order * $product->price_new);
                            }
                        @endphp
                           <tr>
                            <td>{{ $t }}</td>
                            <td>{{ $order->order_code }}</td>
                            @php

                            foreach ($order->products as $product) {
                                    $quantity_product_order += $product->pivot->number_order;
                            }
                            @endphp
                            <td>{{ $order->payment_method }}</td>
                            <td><span class="badge badge-warning">{{ $order->order_status }}</span></td>
                           

                            <td>{{  $quantity_product_order  }}</td>
                            <td>{{ number_format($total_price, 0,".",".") }}đ</td>
                            <td>
                                <a onclick="return confirm('Bạn muốn huỷ đơn hàng {{ $order->order_code }} không ?')" href="{{ route("user.order.delete", $order->id) }}" title="Huỷ đơn" class="del-product"><i class="fa fa-trash"></i></a>
                                <a  href="{{ route("user.detail.order", $order->id) }}" title="Chi tiết đơn hàng" class="del-product"><i class="fa fa-eye ml-3"></i></a>
                            </td>
                        </tr>  
                        @endforeach
                       
                        
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
