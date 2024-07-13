@extends('layouts.admin')
@section('title')
    Thêm sản phẩm
@endsection
@section('content')
<style>
    label {
        font-weight: bold;
    }
</style>
    <div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm sản phẩm
        </div>
        <div class="card-body">
            <form method="post" action="{{ route("product.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="product_name">Tên sản phẩm</label>
                            <input class="form-control" type="text" name="product_name" id="product_name" value="{{ old("product_name") }}">
                            @error('product_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="product_code">Mã sản phẩm</label>
                            <input class="form-control" type="text" name="product_code" id="product_code" value="{{ old("product_code") }}">
                            @error('product_code')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group d-flex justify-content-start">
                          <div class="form-group mr-5">
                            <label for="qty_remain">Số lượng sản phẩm</label>
                            <input class="form-control" type="number" name="qty_remain" id="qty_remain" value="{{ old("qty_remain") }}">
                            @error('qty_remain')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>  
                        <div class="form-group mr-5">
                            <label for="price_old">Giá cũ</label>
                            <input class="form-control" type="number" name="price_old" id="price_old" value="{{ old("price_old") }}">
                            @error('price_old')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                                  <label for="price_new">Giá mới</label>
                                <input class="form-control" type="number" name="price_new" id="price_new" value="{{ old("price_new") }}">
                            @error('price_new')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                        </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <label for="image" class="form_control">Hình ảnh</label>
                        <br>
                            <input type="file" id="image" class="form-control-file mb-5" name="product_image">
                             @error('product_image')
                                    <small class="text-danger">{{ $message }}</small>
                             @enderror
                            
                            <label for="images" class="form_control mt-5">Hình ảnh chi tiết</label>
                            <br>
                            <input type="file" id="images" class="form-control-file" name="images[]" multiple>
                        


                        
                    </div>
                </div>
                    <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label for="product_detail">Chi tiết sản phẩm</label>
                            <textarea name="product_detail" class="form-control" id="product_detail" cols="30" rows="5">
                                {{ old("product_detail") }}
                            </textarea>
                            @error('product_detail')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="product_desc">Mô tả sản phẩm</label>
                            <textarea name="product_desc" class="form-control" id="product_desc" cols="30" rows="8">
                                {{ old("product_desc") }}
                            </textarea>
                            @error('product_desc')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        </div>
                      
                         
                    </div>
                
                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select class="form-control" multiple name="category_product[]">
                        <option value="0">Chọn danh mục</option>
                        @foreach ($list_category as $cat)
                             <option value="{{  $cat->id }}">{{ str_repeat('---', $cat->level). $cat->category_name }}</option>
                        @endforeach
                
                    </select>
                    @error('category_product')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                </div>
    
                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection