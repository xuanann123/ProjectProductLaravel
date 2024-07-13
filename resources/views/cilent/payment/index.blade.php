@extends('layouts.client')
@section('title')
    Thanh Toán
@endsection
@section('content')
<div id="main-content-wp" class="checkout-page">
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{ url("/") }}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{ route("cart.checkout") }}" title="">Thanh toán</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="customer-info-wp">
            <div class="section-head">
                <h1 class="section-title">Thông tin khách hàng</h1>
            </div>
            <div class="section-detail">
                <form action="{{ route("cart.payment") }}" method="post" name="form-checkout">
                    <div class="form-row clearfix">
                        <div class="form-col fl-left">
                            <label for="fullname">Họ tên</label>
                            <input type="text" name="fullname" id="fullname" value="{{ Auth::user()->name }}" readonly>
                            @error('fullname')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                        </div>
                        <div class="form-col fl-right">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" readonly>
                            @error('email')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                        </div>
                    </div>
                    <div class="form-row clearfix">
                        <div class="form-col fl-left">
                            <label for="address">Địa chỉ</label>
                            <input type="text" name="address" id="address" value="{{ Auth::user()->address }}">
                            @error('address')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                        </div>
                        <div class="form-col fl-right">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" name="phone" id="phone" value="0{{ Auth::user()->phone }}" >
                            @error('phone')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label for="notes">Ghi chú</label>
                            <textarea name="note"></textarea>
                        </div>
                    </div>
            </div>
        </div>
        <div class="section" id="order-review-wp">
            <div class="section-head">
                <h1 class="section-title">Thông tin đơn hàng</h1>
            </div>
            <div class="section-detail">
                <table class="shop-table">
                    <thead>
                        <tr>
                            <td>Sản phẩm</td>
                            <td>Tổng</td>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Đổ danh sách cart ra đây --}}
                        @foreach (Cart::content() as $item)
                            <tr class="cart-item">
                            <td class="product-name">{{ $item->name }}<strong class="product-quantity">x {{ $item->qty }}</strong></td>
                            <td class="product-total">{{ number_format($item->total, "0", ".", ".") }}đ</td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                    <tfoot>
                        <tr class="order-total">
                            <td>Tổng đơn hàng:</td>
                            @php
                                $total_order = Cart::total();
                            @endphp
                            <td><strong class="total-price">{{ number_format($total_order, "0",".",".") }}Đ</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <form action="{{ route("cart.payment") }}" method="post">
                    @csrf
                <div id="payment-checkout-wp">
                    <label for="" style="text-transform: uppercase; font-weight: 800; margin-bottom: 20px">Chọn phương thức thanh toán</label>
                    <br>
                    <ul id="payment_methods" style="margin: 20px">
                        <li>
                            <input type="radio" id="direct-store" name="payment-method" value="Thanh toán tại cửa hàng">
                            <label for="direct-payment">Thanh toán khi nhận hàng</label>
                        </li>
                        <li>
                            <input type="radio" id="payment-onlime" name="payment-method" value="Thanh toán online">
                            <label for="payment-home">Thanh toán online</label>
                        </li>
                    </ul>
                    @error('payment-method')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="place-order-wp clearfix">
                    <input type="submit" id="order-now" value="Đặt hàng">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
