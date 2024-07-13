@extends('layouts.client')
@section('title')
    Giỏ hàng
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
                        <a href="{{ route("cart.show") }}" title="">Giỏ hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="info-cart-wp">
            <div class="section-detail table-responsive">
                <i >Hiện tại có {{ Cart::count() }} sản phẩm trong giỏ hàng</i>
                <form action="{{ route("cart.update") }}" method="post">
                    @csrf
                @if (Cart::count() > 0)
                     <table class="table mt-2">
                    <thead>
                        <tr>
                            <td>STT</td>
                            <td>Mã sản phẩm</td>
                            <td>Ảnh sản phẩm</td>
                            <td>Tên sản phẩm</td>
                            <td>Giá sản phẩm</td>
                            <td>Số lượng</td>
                            <td colspan="2">Thành tiền</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $t = 0;
                        @endphp
                        @foreach (Cart::content() as $item)
                        @php
                            $t++;
                        @endphp
                           <tr>
                            <td>{{ $t }}</td>
                            <td>{{ $item->options->code }}</td>
                            @php
                                $thumbnail =  $item->options->image;
                                $qty_max = $item->options->qty_max;
                            @endphp
                            <td>
                                <a href="" title="" class="thumb">
                                    <img src="{{ url("$thumbnail") }} " alt="">
                                </a>
                            </td>
                            <td>
                                <a href="" title="" class="name-product">{{ $item->name }}</a>
                            </td>
                            <td>{{ number_format("$item->total", 0,".",".") }}đ</td>
                            <td>
                                <input type="number" min="1" max="{{ $qty_max }}" name="qty[{{ $item->rowId }}]" value="{{ $item->qty }}" class="num-order">
                            </td>
                            <td>{{ number_format("$item->total", 0,".",".") }}đ</td>
                            <td>
                                <a href="{{ route("cart.delete", $item->rowId) }}" title="" class="del-product"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>  
                        @endforeach
                       
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <div class="clearfix">
                                    <p id="total-price" class="fl-right">Tổng giá: <span>{{ Cart::total() }}đ</span></p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <div class="clearfix">
                                    <div class="fl-right">
                                        <a href="{{ route("cart.destroy") }}" title="" id="update-cart">Xoá giỏ hàng</a>
                                        <input type="submit" value="Cập nhật giỏ hàng" id="checkout-cart">
                                        <a href="{{ route("cart.checkout") }}" title="" id="checkout-cart">Thanh toán</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                @else
  </form>
                <div style="display: flex; justify-content: center">
                    <img width="50%" src="{{ url("public/uploads/emptytrash.png") }}" alt="">
                </div>
                    
                @endif
               
            </div>
        </div>
        <div class="section" id="action-cart-wp">
            <div class="section-detail">
                <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng. Nhập vào số lượng <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh toán để hoàn tất mua hàng.</p>
                <a href="{{ url("/") }}" title="" id="buy-more">Mua tiếp</a><br/>
            </div>
        </div>
    </div>
</div>
@endsection
