@extends('layouts.client')
@section('links')
    <link href="{{ asset("/vnpay_php/assets/bootstrap.min.css") }}" rel="stylesheet"/>
        <!-- Custom styles for this template -->
    <link href="{{ asset("/vnpay_php/assets/jumbotron-narrow.css") }}" rel="stylesheet">  
    <script src="{{ asset("/vnpay_php/assets/jquery-1.11.3.min.js") }}"></script>
@endsection
@section('title')
    Thanh toán đơn hàng
@endsection
@section('content')
<div class="container">
        <h3>Tạo mới đơn hàng</h3>
            <div class="table-responsive">
                <form action="{{ route("payment.online") }}" method="post"  > 
                    @csrf
                    @php
                         $total_order = Cart::total();
                    @endphp      
                    <div class="form-group">
                        <label for="amount">Số tiền</label>
                        <input class="form-control" data-val="true" data-val-number="The field Amount must be a number." data-val-required="The Amount field is required." id="amount" max="100000000" min="1" name="amount" type="text" value="{{ number_format($total_order, "0",".",".") }}" />
                    </div>
                     <h4>Chọn phương thức thanh toán</h4>
                    <div class="form-group">
                        <h5>Cách 1: Chuyển hướng sang Cổng VNPAY chọn phương thức thanh toán</h5>
                       <input type="radio" Checked="True" id="bankCode" name="bankCode" value="NCB">
                       <label for="bankCode">Cổng thanh toán VNPAYQR</label><br>
                       
                       <h5>Cách 2: Tách phương thức tại site của đơn vị kết nối</h5>
                       <input type="radio" id="bankCode" name="bankCode" value="VNPAYQR">
                       <label for="bankCode">Thanh toán bằng ứng dụng hỗ trợ VNPAYQR</label><br>
                       
                       <input type="radio" id="bankCode" name="bankCode" value="VNBANK">
                       <label for="bankCode">Thanh toán qua thẻ ATM/Tài khoản nội địa</label><br>
                       
                       <input type="radio" id="bankCode" name="bankCode" value="INTCARD">
                       <label for="bankCode">Thanh toán qua thẻ quốc tế</label><br>
                       
                    </div>
                    <div class="form-group">
                        <h5>Chọn ngôn ngữ giao diện thanh toán:</h5>
                         <input type="radio" id="language" Checked="True" name="language" value="vn">
                         <label for="language">Tiếng việt</label><br>
                         <input type="radio" id="language" name="language" value="en">
                         <label for="language">Tiếng anh</label><br>
                         
                    </div>
                    <button type="submit" class="btn btn-default" >Thanh toán</button>
                    <a href="{{ route("cart.checkout") }}" class="btn btn-default" >Quay trở lại</a>

                </form>
            </div>
        </div>  
@endsection

