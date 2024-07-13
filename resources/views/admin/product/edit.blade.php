@extends('layouts.admin')
@section('title')
    Sửa sản phẩm
@endsection
@section('content')
    <div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Sửa sản phẩm
        </div>
        <div class="card-body">
            <form method="post" action="{{ route("product.update", $product->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="product_name">Tên sản phẩm</label>
                            <input class="form-control" type="text" name="product_name" id="product_name" value="{{ $product->product_name }}">
                            @error('product_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="product_code">Mã sản phẩm</label>
                            <input class="form-control" type="text" name="product_code" id="product_code" value="{{ $product->product_code }}">
                            @error('product_code')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group d-flex justify-content-start">
                          <div class="form-group mr-5">
                            <label for="qty_remain">Số lượng sản phẩm</label>
                            <input class="form-control" type="number" name="qty_remain" id="qty_remain" value="{{ $product->qty_remain }}">
                            @error('qty_remain')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>  
                        <div class="form-group mr-5">
                            <label for="price_old">Giá cũ</label>
                            <input class="form-control" type="number" name="price_old" id="price_old" value="{{ $product->price_old }}">
                            @error('price_old')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                                  <label for="price_new">Giá mới</label>
                                <input class="form-control" type="number" name="price_new" id="price_new" value="{{ $product->price_new }}">
                            @error('price_new')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                        </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="product_desc">Mô tả sản phẩm</label>
                            <textarea name="product_desc" class="form-control" id="product_desc" cols="30" rows="8">{{ $product->product_desc }}</textarea>
                            @error('product_desc')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                            <div class="col-md-9">
                            <div class="form-group">
                            <label for="product_detail">Chi tiết sản phẩm</label>
                            <textarea name="product_detail" class="form-control" id="product_detail" cols="30" rows="12">
                                {{ $product->product_detail }}
                            </textarea>
                            @error('product_detail')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        </div>
                        <div class="col-md-3">
                            <label for="image" class="form_control">Hình ảnh</label>
                            <input type="file" id="image" class="form-control-file" name="product_image">
                            <img src="{{ url("$product->product_image") }}" class="img-fluid rounded-top mt-3"/>
                             @error('product_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                        </div>
                    </div>



                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select class="form-control" multiple name="category_product[]">
                        @foreach ($list_category as $cat)
                             <option value="{{  $cat->id }}" {{ in_array($cat->id, $list_category_id)?"selected":"" }}>{{  str_repeat('---', $cat->level). $cat->category_name }}</option>
                        @endforeach
                
                    </select>
                    @error('category_product')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                </div>
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    {{-- Cần tối ưu cái này ngoài view --}}
                    <div class="form-check">
                        {{-- Cái này dúng để định nghĩa cấp quyền cho một sản phẩm --}}
                        <input class="form-check-input" type="radio" name="product_status" {{ $product->product_status =="Hoạt động"?"checked":""  }} id="product_status" value="Hoạt động" >
                        <label class="form-check-label" for="product_status">
                            Được cấp phép
                        </label>
                    </div>
                    <div class="form-check">
                        {{-- Cái này dúng để định nghĩa cấp quyền cho một sản phẩm --}}
                        <input class="form-check-input" type="radio" name="product_status" {{ $product->product_status =="Chưa phê duyệt"?"checked":""  }} id="product_status" value="Chưa phê duyệt">
                        <label class="form-check-label" for="product_status">
                            Chưa giải quyết
                        </label>
                    </div>
                     @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection