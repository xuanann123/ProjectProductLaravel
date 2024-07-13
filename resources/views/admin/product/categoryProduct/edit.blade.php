@extends('layouts.admin')
@section('title')
    Sửa danh mục sản phẩm
@endsection
@section('content')
    <div id="content" class="container-fluid">
                @if (session('status'))
           <div class="alert alert-danger">
            {{ session('status') }}
        </div> 
        @endif
    <div class="row">

        <div class="col-4">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh mục sản phẩm
                </div>
                <div class="card-body">
                    <form action="{{ route("product.cat.update", $category->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên danh mục</label>
                            <input class="form-control" type="text" name="category_name" id="name" value="{{ $category->category_name }}">
                            @error('category_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input class="form-control" type="text" name="slug" id="name" value="{{ $category->slug }}">
                               @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Danh mục cha</label>
                            <select class="form-control" id="" name="parent_id" multiple>
                                <option value="">Chọn danh mục</option>
                                <option value="0">Danh mục cha</option>
                                @foreach ($list_category as $cat)
                                    <option  value="{{ $cat->id }}" {{ ($category->parent_id == $cat->id)?"selected":"" }}>{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label" >Mô tả</label>
                            <textarea name="category_description" id="" class="form-control" cols="10" rows="6">{!! $category->category_description !!}</textarea>
                            @error('category_description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category_status" id="exampleRadios1" value="pending" {{ $category->category_status == "pending"? "checked":"" }}>
                                <label class="form-check-label" for="exampleRadios1">
                                    Chờ duyệt
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category_status" id="exampleRadios2" value="licensed" {{ $category->category_status == "licensed"? "checked":"" }}>
                                <label class="form-check-label" for="exampleRadios2">
                                    Công khai
                                </label>
                            </div>
                            @error('category_status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col">Đường dẫn(slug)</th>
                                <th scope="col">Trạng thái</th>

                                <th scope="col">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @if (count($list_category) > 0) 
                            @foreach ($list_category as $item)
                                @php
                                $i ++;
                                @endphp
                                <tr>
                                <th scope="row">{{ $i }}</th>
                                <td>{{ str_repeat('---', $item->level). $item->category_name }}</td>
                                <td>{!!  $item->category_description !!}</td>
                                <td>{{ $item->slug }}</td>
                                <td>
                                <span class="badge badge-warning"> {{ $item->category_status }}</span>
                                </td>
                                 <td>
                               <a href="{{ route("product.cat.detail", $item->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chi tiết danh mục"><i class="fa fa-info-circle"></i></a>
                                <a href="{{ route("product.cat.edit", $item->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>
                                <a href="{{ route("product.cat.delete", $item->id) }}" onclick=" return confirm('Bạn có muốn xoá danh mục {{ $item->category_name }} này không')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip"
                                 data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </td>

                            </tr>
                               @endforeach
                            @else
                                <tr>
                                    <td colspan="4">Không có bản ghi nào</td>
                                </tr>
                            @endif
                             
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection