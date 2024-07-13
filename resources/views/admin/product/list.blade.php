@extends('layouts.admin')
@section('title')
    Danh sách sản phẩm
@endsection
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
           <div class="alert alert-danger">
            {{ session('status') }}
        </div> 
        @endif
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách sản phẩm</h5>
            <div class="form-search form-inline">
                <form action="{{ route("product.list") }}">
                    <div class="d-flex">
                    <input type="" name="search" class="form-control form-search mr-3" placeholder="Tìm kiếm" value="{{ request()->input("search") }}">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    <input type="hidden" name="status" value="{{ empty(request()->input("status"))? 'active': request()->input("status") }}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <a href="{{ route("product.add") }}" class="btn btn-primary float-right">THÊM MỚI</a>

            <div class="analytic">
                <a href="{{ request()->fullUrlWithQuery(['status'=>"all"]) }}" class="text-primary">Kích hoạt<span class="text-muted">({{ $count['0'] }})</span></a>
                <a href="{{ request()->fullUrlWithQuery(['status'=>"licensed"]) }}" class="text-primary">Hoạt động<span class="text-muted">({{ $count['1'] }})</span></a>
                <a href="{{ request()->fullUrlWithQuery(['status'=>"pending"]) }}" class="text-primary">Chưa phê duyệt<span class="text-muted">({{ $count['2'] }})</span></a>
                <a href="{{ request()->fullUrlWithQuery(['status'=>"trash"]) }}" class="text-primary">Thùng rác<span class="text-muted">({{ $count['3'] }})</span></a>

            </div>
            <form action="{{ route("product.act") }}" method="post">
                @csrf
                {{-- form này lấy lấy act nó là gì và đi lấy danh sách listCheckProduct()-> danh sách id để đi xoá --}}
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" name="act" id="">
                    <option value="">Chọn</option>
                    @foreach ($list_act as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">
                            <input type="checkbox" name="checkall">
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">Mã sản phẩm</th>
                        <th scope="col">Ảnh</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Giá</th>
                        {{-- <th scope="col">Đã bán</th>
                        <th scope="col">Số lượng kho</th> --}}
                        <th scope="col">Danh mục</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($products) >0) 
                        @php
                        $i = 0;
                    @endphp
                    @foreach ($products as $product)
                    @php
                        $i++;
                    @endphp
                        <tr class="">
        

                        <td>
                            <input type="checkbox" name="listCheckProduct[]" value="{{ $product->id  }}">
                        </td>
                           <td>{{ $i }}</td>
                        <td>{{ $product->product_code }}</td>

                     
                        <td><img width="80px" src="{{ url("$product->product_image ")}}" alt=""></td>
                        <td><a href="{{ route("product.edit", $product->id) }}">{{ $product->product_name }}</a></td>
                        <td>{{ number_format("$product->price_new", 0,',' , '.')  }}đ</td>
                        {{-- <td>{{ $product->qty_sold }}</td>
                        <td>{{ $product->qty_remain }}</td> --}}


                        <td>@foreach ($product->categories as $category)
                            <span class="badge badge-warning"> {{ $category->category_name }}</span>
                        @endforeach</td>
                        <td>{{ $product->created_at }}</td>
                        <td><span class="badge badge-success">{{ $product->product_status }}</span></td>
                        <td class="">
                        
                            @if (request()->status != "trash")
                            <a href="{{ route("product.edit", $product->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>
                            <a href="{{ route("product.delete", $product->id) }}" onclick=" return confirm('Bạn có muốn xoá sản phẩm  {{ $product->product_name }}  này không')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip"
                            data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                            @else
                            <a href="{{ route("product.restore", $product->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"><i class="fas fa-undo-alt"></i></a>
                            <a href="{{ route("product.forcedelete", $product->id) }}" onclick=" return confirm('Bạn có muốn xoá vĩnh viễn sản phẩm {{ $product->product_name }} này không')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip"
                            data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></a>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="10">Không tìm thấy sản phẩm nào</td>
                        </tr>
                    @endif  
                </tbody>
            </table>
            </form>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection