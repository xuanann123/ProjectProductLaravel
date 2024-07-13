@extends('layouts.admin')
@section('title')
    Danh sách đơn hàng
@endsection
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách đơn hàng</h5>
            <div class="form-search form-inline">
                 <form action="">
                    <div class="d-flex">
                    <input type="" name="keyword" class="form-control form-search mr-3" placeholder="Tìm kiếm" value="{{ request()->input("keyword") }}">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    {{-- Thêm input này để kiểm tra xem thằng đó có tồn tại thằng trạng thái hay không nếu như tôi không thì autoset nó là active còn có thì set cho nó là đường dẫn từ trước --}}
                    <input type="hidden" name="status" value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                 <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $count['0'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'delivery_successful']) }}"
                        class="text-primary">Giao hàng thành công<span
                            class="text-muted">({{ $count['1'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'shipping']) }}" class="text-primary">Đang vận
                        chuyển
                        <span class="text-muted">({{ $count['2'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chưa xác nhận
                        <span class="text-muted">({{ $count['3'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count['4'] }})</span></a>
                    <span class="badge badge-warning sales">Doanh thu:{{ number_format("$total_all_product")}} VNĐ</span>
            </div>
            <form action="{{ route("order.action") }}" method="post">
                @csrf
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" id="" name="act">
                    <option value=""">Chọn tác vụ</option>
                    @foreach ($list_act as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                        
                    @endforeach
  
                </select>
                <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" name="checkall">
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">Mã đơn hàng</th>
                        <th scope="col">Tên khách hàng</th>
                        <th scope="col">Tổng giá</th>
                        <th scope="col">Phương thức thanh toán</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Thời gian đặt đơn hàng</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $t = 0;
                        
                    @endphp
                    @if (count($orders) > 0) 
                    @foreach ($orders as $order)
                    @php
                    // Sau mỗi thằng order thì số tiền này reset về 0 để tính tiền cho đơn hàng khách
                        $total_price = 0;
                    @endphp
                    @foreach ($order->products as $product)
                    @php
                        $total_price += ($product->price_new * $product->pivot->number_order);
                    @endphp
                    @endforeach
                    @php
                        $t ++;
                    @endphp
                    <tr>
                        <td>
                            <input type="checkbox" name="listCheck[]" value="{{ $order->id }}">
                        </td>
                        <td>{{ $t }}</td>
                        <td><a href="{{ route("order.edit", $order->id) }}">{{ $order->order_code }}</a></td>
                        <td>
                           <a href="thongtincustomer"> {{ $order->user->name }}</a><br>
                            {{  $order->user->phone }}
                        </td>
                        <td>
                        {{ number_format($total_price, 0, ".",".") }}đ
                            </td>
                            <td>
                                @if ($order->payment)
                                    <ul>
                                        <li>Ngân hàng: {{ $order->payment->p_code_bank }}</li>
                                        <li>Mã thanh toán: {{ $order->payment->p_code_vnp }}</li>
                                        <li>Tổng tiền: {{ $order->payment->p_money }}</li>
                                        <li>Nội dung: {{ $order->payment->note }}</li>
                                        <li>Thời gian: {{ date("Y-m-d H:i", strtotime($order->payment->p_time)) }}</li:>
                                    </ul>
                                @else
                                    Thanh toán tại khi nhận hàng
                                @endif
                            </td>
                        <td><span class="badge badge-warning">{{ $order->order_status }}</span></td>
                        <td>{{ $order->created_at }}</td>
                        @if (request()->input("status") != "trashed") 
                        
                        <td>
                            <a href="{{ route("order.detail", $order->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chi tiết đơn hàng"><i class="fa fa-info-circle"></i></a>
                            <a href="{{ route("order.edit", $order->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                            <a href="{{ route("order.delete", $order->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xoá đơn hàng {{ $order->order_code }}?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                        </td>
                        @else
                            <td>
                                   <a href="{{ route("order.restore", $order->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục đơn hàng {{ $order->order_code }}?')"
                                                    data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                    <a href="{{ route("order.deleteForce", $order->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn đơn hàng {{ $order->order_code }}?')"
                                                   data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                 </td>
                        @endif
                    
                     </tr>
                    
                    @endforeach
                        
                    @else
                        <tr>
                            <td colspan="9">Không tồn tại đơn hàng nào</td>
                        </tr>
                    @endif
                    


                </tbody>
            </table>
            </form>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
