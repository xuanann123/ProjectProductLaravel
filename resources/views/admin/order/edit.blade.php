@extends('layouts.admin')
@section('title')
    Chỉnh sửa đơn hàngg
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin đơn hàng
            </div>
            <div class="card-body">
                <form action="{{ route("order.update", $order->id) }}" method='POST'>
                    @csrf
                    <div class="form-group">
                        <label for="order-code">Mã đơn hàng</label>
                        <input class="form-control no-edit" type="text" name="order_code" id="order-code"
                            readonly="readonly" value="{{ $order->order_code }}">
                        @error('order_code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Tên khách hàng</label>
                        <input class="form-control" type="text" name="customer_name" id="name"
                            value="{{ $order->customer->customer_name }}">
                        @error('customer_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number-phone">Số điện thoại</label>
                        <input class="form-control" type="text" name="customer_phone" id="number-phone"
                            value="{{ $order->customer->customer_phone }}">
                        @error('customer_phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- protected $fillable = ['order_code', 'address_delivery', 'payment_method', 'notes', 'order_status', "customer_id" ]; --}}

                    <div class="form-group">
                        <label for="customer_email">Email</label>
                        <input class="form-control" type="email" name="customer_email" id="customer_email"
                            value="{{ $order->customer->customer_email }}" readonly>
                        @error('customer_email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group"> 
                        <label for="address">Địa chỉ nhận hàng</label>
                        <input class="form-control" type="text" name="customer_address" id="address"
                            value="{{ $order->customer->customer_address }}">
                        @error('customer_address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Ghi chú từ khách hàng</label>
                        <textarea class="form-control no-edit" name="notes" readonly="readonly" id="notes" cols="30" rows="10">{{ $order->notes }}</textarea>
                        @error('notes')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                            <label for="">Trạng thái đơn hàng</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_status" id="exampleRadios1" value="Giao hàng thành công" >
                                <label class="form-check-label" for="exampleRadios1">
                                    Giao hàng thành công
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_status" id="exampleRadios2" value="Đang vận chuyển" >
                                <label class="form-check-label" for="exampleRadios2">
                                    Đang vận chuyển
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_status" id="exampleRadios3" value="Chưa xác nhận" checked>
                                <label class="form-check-label" for="exampleRadios3">
                                    Chưa xác nhận
                                </label>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_status" id="exampleRadios4" value="Bị huỷ" >
                                <label class="form-check-label" for="exampleRadios4">
                                    Bị huỷ
                                </label>
                            </div>
                            
                            @error('order_status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                     </div>

                    <input type="submit" name="btn_update" class="btn btn-primary" value="Cập nhật">
                </form>
            </div>
        </div>
    </div>
@endsection
