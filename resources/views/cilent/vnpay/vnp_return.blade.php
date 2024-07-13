
@extends('layouts.client')
@section('links')
    <link href="{{ asset("/vnpay_php/assets/bootstrap.min.css") }}" rel="stylesheet"/>
        <!-- Custom styles for this template -->
    <link href="{{ asset("/vnpay_php/assets/jumbotron-narrow.css") }}" rel="stylesheet">  
    <script src="{{ asset("/vnpay_php/assets/jquery-1.11.3.min.js") }}"></script>
@endsection
@section('title')
   Kết quả thanh toán
@endsection
@section('content')
<div class="container">
        <div class="container">
            <div class="header clearfix">
                <h3 class="text-muted">KẾT QUẢ THANH TOÁN</h3>
            </div>
            <div class="table-responsive">
                <div class="form-group">
                    <label >Mã đơn hàng:</label>
                    <label>{{  $vnpayData['vnp_TxnRef'] }} </label>
                </div>    
                <div class="form-group">

                    <label >Số tiền:</label>
                    <label>{{$vnpayData['vnp_Amount']  }}</label>
                </div>  
                <div class="form-group">
                    <label >Nội dung thanh toán:</label>
                    <label>{{ $vnpayData['vnp_OrderInfo'] }}</label>
                </div> 
                <div class="form-group">
                    <label >Mã phản hồi (vnp_ResponseCode):</label>
                    <label>{{ $vnpayData['vnp_ResponseCode'] }}</label>
                </div> 
                <div class="form-group">
                    <label >Mã GD Tại VNPAY:</label>
                    <label>{{ $vnpayData['vnp_TransactionNo'] }}</label>
                </div> 
                <div class="form-group">
                    <label >Mã Ngân hàng:</label>
                    <label>{{$vnpayData['vnp_BankCode']  }}</label>
                </div> 
                <div class="form-group">
                    <label >Thời gian thanh toán:</label>
                    <label>{{ date("Y-m-d H:i", strtotime($vnpayData['vnp_PayDate'])) }} </label>
                </div> 
                <div class="form-group">
                    <label >Kết quả:</label>
                    <label>
                        @php
                            if ($vnpayData['vnp_ResponseCode'] == '00') {
                                echo "<span style='color:blue'>GD Thanh cong</span>";
                            } else {
                                echo "<span style='color:red'>GD Khong thanh cong</span>";
                            }
                        @endphp
                        <br>
                        
                
                    </label>
                    <br>
                    <a href="{{ url("/") }}">
                        <button class="btn btn-default">Quay lại</button>
                    </a>

                </div> 
            </div> 
        </div>  
@endsection




