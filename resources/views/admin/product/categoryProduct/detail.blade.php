@extends('layouts.admin')
@section('title')
    Chi tiết danh mục bài viết
@endsection
@section('content')
<div id="content" class="container-fluid">

        <div class="card">
            <div class="card-header font-weight-bold">
                {{-- Danh mục {{ $cat->product_cat_name }} có số bài viết là: {{ $product_cat_number }} --}}
            </div>
            <div class="card-body" id="information-order">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã sản phẩm</th>
                            <th scope="col">Ảnh sản phẩm/th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Mô tả</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($products) > 0)
                            @php                     
                                $cnt = 0;
                        @endphp
                            @foreach ($products as $product)
                     
                                @php
                   
                                $cnt++;
                                @endphp
                                <tr class="row-in-list">
                                    <td scope=" row">{{ $cnt }}</td>
                                    <td>{{  $product->product_code }}</td>

                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}" class="product_image">
                                            <img src="{{ url("$product->product_image") }}"
                                                alt="Ảnh của sản phẩm {{ $product->product_name }}"
                                                title="Ảnh của sản phẩm {{ $product->product_name }}" id="product_image" width="80px">
                                        </a>
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                          
            
                                    <td>{{  $product->product_desc }}</td>
                                    <td>
                                        <span class="badge badge-warning">   {{  $product->product_status }}</span>
                                    </td>
                                     <td>
                                                <a href="{{ route("product.edit", $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route("product.delete", $product->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá bài viết {{ $product->product_title }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                
                                </tr>
                             
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="bg-white">Không tìm thấy trang nào!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        
                </div>
           


@endsection
