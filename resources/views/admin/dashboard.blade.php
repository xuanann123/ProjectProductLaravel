@extends('layouts.admin')
@section('title')
    Trang chủ admin
@endsection
@section('content')

    <div class="container-fluid py-5">
        <div class="row">
            <div class="col pr-8">
                <div class="card card-dasboard text-white bg-info mb-3">
                    <div class="card-header card-header-dasboard">SẢN PHẨM TRONG KHO</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $total_quantity_remain }}</h5>
                        <p class="card-text card-text-dasboard">Số lượng sản phẩm kho</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-dark bg-light mb-3">
                    <div class="card-header card-header-dasboard">SẢN PHẨM BÁN RA</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $total_quantity_sold }}</h5>
                        <p class="card-text card-text-dasboard">Số lượng sản phẩm đã bán</p>
                    </div>
                </div>
            </div>

            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-warning mb-3">
                    <div class="card-header card-header-dasboard">DOANH SỐ</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center">{{ number_format($total_product_sales,"0",".",".") }}</h5>
                        <p class="card-text card-text-dasboard">(VNĐ) - Doanh số hệ thống </p>
                    </div>
                </div>
            </div>

            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-success mb-3">
                    <div class="card-header card-header-dasboard max-width-13r ">ĐƠN HÀNG THÀNH CÔNG</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $status_order["0"]}}</h5>
                        <p class="card-text card-text-dasboard">Đơn hàng thành công</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-primary mb-3">
                    <div class="card-header card-header-dasboard max-width-10r">ĐANG XỬ LÝ</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $status_order["2"]}}</h5>
                        <p class="card-text card-text-dasboard">Đơn hàng đang xử lý</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8">
                <div class="card card-dasboard text-white bg-danger mb-3">
                    <div class="card-header card-header-dasboard">ĐANG VẬN CHUYỂN</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $status_order["1"]}}</h5>
                        <p class="card-text card-text-dasboard">Đơn hàng đang vận chuyển</p>
                    </div>
                </div>
            </div>

        </div>
        <!-- end analytic  -->
        <div class="card">
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Giá trị</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($orders) > 0)
                            @php
                                $cnt = 0;
                                
                            @endphp
                            @foreach ($orders as $order)
                        
                                @php
                                    $total_price = 0;
                                    $cnt++;
                                    //Tính tổng tiền của thằng
                                    foreach ($order->products as $product) {
                                        $total_price += ($product->pivot->number_order * $product->price_new);
                                    }

                                @endphp
                                <tr>
                                    <th scope=" row">{{ $cnt }}</th>
                                    <td><a class="text-primary"
                                            href="{{ route('order.detail', $order->id) }}">{{ $order->order_code }}</a>
                                    </td>
                                    <td>{{ $order->user->name }}<BR>{{ $order->user->phone }}</td>
                                        {{-- Tính số lượng sản phẩm của một đơn hàng --}}
                                        @php
                                    
                                         $quantity_product_order = 0;
                                        foreach ($order->products as $product) {
                                           $quantity_product_order += $product->pivot->number_order;
                                        }
                                        @endphp

                                    <td>{{ $quantity_product_order }}</td>
                                    <td>{!! number_format($total_price, 0,".", ".") !!}đ</td>
                                    <td>{!! ($order->order_status) !!}</td>
                                    <td>{!! date('H:i:s-d/m/Y', strtotime($order->created_at)) !!}</td>

                                    <td>
                                        <a href="{{ route("order.edit", $order->id) }}"
                                            class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{ route("order.delete",$order->id) }}"
                                            class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip"
                                            onclick="return confirm('Bạn có chắc chắn muốn xoá đơn hàng {{ $order->order_code }}?')"
                                            data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="bg-white">Không tìm thấy đơn hàng nào!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
              {{ $orders -> links() }}
            </div>
        </div>

    </div>
@endsection