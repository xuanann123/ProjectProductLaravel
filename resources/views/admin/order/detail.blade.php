@extends('layouts.admin')
@section('title')
Chi tiết đơn hàng
    @endsection

@section('content')
    <div id="content" class="container-fluid">

        <div class="card">
            <div class="card-header font-weight-bold">
                Sản phẩm đơn hàng
            </div>
            <div class="card-body" id="information-order">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh sản phẩm</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($products))
                            @php
                                $sum_price = 0;
                                $sum_qty = 0;
                                $cnt = 0;
                                $order_total_product = 0;
                            @endphp
                            @foreach ($products as $product)

                                @php
                                //Lấy đơn giá của sản phẩm ra
                                $order_total_product = $product->pivot->number_order;
                                $cnt++;
                                @endphp
                                <tr class="row-in-list">
                                    <th scope=" row">{{ $cnt }}</th>
                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}" class="product_image">
                                            <img src="{{ url("$product->product_image") }}"
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
{{-- Thông tin về đơn hàng đó --}}
        <div class="infor-order-detail">
            <div class="card total-price-order">
                <div class="card-header font-weight-bold">
                    Giá trị đơn hàng
                </div>
                <div class="card-body" id="information-order">
                    <div class="card text-white bg-info mb-3 mr-5 d-inline-block" style="max-width: 18rem;">
                        <div class="card-header">Tổng số lượng</div>
                        <div class="card-body">
                            <h5 class="card-title text-center">{{ $sum_qty }}</h5>
                            <p class="card-text text-center text-uppercase">sản phẩm</p>
                        </div>
                    </div>

                    <div class="card text-white bg-success mb-3 d-inline-block" style="max-width: 18rem;">
                        <div class="card-header">Tổng tiền đơn hàng</div>
                        <div class="card-body">
                            <h5 class="card-title text-center">{!! number_format($sum_price) !!}</h5>
                            <p class="card-text text-center text-uppercase">vnđ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card detail-infor-order">
                <div class="card-header font-weight-bold">
                    Thông tin đơn hàng
                </div>
                <div class="card-body" id="information-order">
                    <form action="{{ url("admin/order/detail/update/{$order->id}") }}" method='POST'>
                        @csrf
                        <div class="form-group">
                            <h6 class="order-code">
                                <i class="fas fa-barcode mr-2"></i>Mã đơn hàng
                            </h6>
                            <span class="detail">{{ $order->order_code }}</span>
                        </div>

                        <div class="form-group">
                            <h6 class="address-delivery">
                                <i class="fas fa-map-marker-alt mr-2"></i>Địa chỉ nhận hàng / Số điện thoại
                            </h6>
                            <span class="detail">{{ $order->user->address }} / </span><span
                                class="detail">{{ $order->user->phone }}</span>
                        </div>

                        <div class="form-group">
                            <h6 class="payment-method">
                                <i class="fab fa-opencart mr-2"></i>
                                Thông tin vận chuyển
                            </h6>
                            {!! $order->payment_method !!}
                        </div>

                        <div class="form-group">
                            <h6 class="order-status">
                                <label for="status" class="fw-550">
                                    <i class="fas fa-chart-area mr-2"></i>Trạng thái đơn hàng
                                </label>
                            </h6>
                            {!! $order->order_status !!}
                            @error('order_status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- <input type="submit" name="btn_update" class="btn btn-primary btn-add-detail-order"
                            value="Cập nhật"> --}}
                    </form>
                </div>
            </div>

        </div>




    @endsection
