@extends('layouts.admin')
@section('title')
    Thêm danh mục bài viết
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
                    Danh mục bài viết
                </div>
                <div class="card-body">
                    <form action="{{ route("post.cat.store") }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên danh mục</label>
                            <input class="form-control" type="text" name="post_cat_name" id="name" value="{{ old("post_cat_name") }}">
                            @error('post_cat_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input class="form-control" type="text" name="slug" id="name" value="{{ old("slug") }}" readonly>
                               @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Danh mục cha</label>
                            <select class="form-control" id="" name="cat_parent_id">
                                <option value="0">Chọn danh mục</option>
                                @foreach ($list_category as $cat)
                                    <option value="{{ $cat->id }}">{{ str_repeat('---', $cat->level) . $cat->post_cat_name }}</option>
                                @endforeach
                            </select>
                            @error('cat_parent_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="post_cat_status" id="exampleRadios1"  value="Hoạt động" checked>
                                <label class="form-check-label" for="exampleRadios1">
                                    Hoạt động
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="post_cat_status" id="exampleRadios2" value="Chưa phê duyệt">
                                <label class="form-check-label" for="exampleRadios2">
                                    Chưa phê duyệt
                                </label>
                            </div>
                            @error('post_cat_status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>



                        <button type="submit" class="btn btn-primary">Thêm mới</button>
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
                                <td>{{ str_repeat('---', $item->level). $item->post_cat_name }}</td>
                                <td>{{ $item->slug }}</td>
                                <td> <span class="badge badge-warning"> {{ $item->post_cat_status }}</span></td>
                               
                                <td>
                            <a href="{{ route("post.cat.detail", $item->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chi tiết danh mục"><i class="fa fa-info-circle"></i></a>
                                <a href="{{ route("post.cat.edit", $item->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>
                                <a href="{{ route("post.cat.delete", $item->id) }}" onclick=" return confirm('Bạn có muốn xoá bài viết {{ $item->post_cat_name }} không')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip"
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