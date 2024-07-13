@extends('layouts.admin')
@section('title')
    Thêm trang 
@endsection
@section('content')
        <div id="content" class="container-fluid">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thêm trang
                </div>
                <div class="card-body">
                    <form action="{{ route("page.store") }}" method='POST' enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="fw-550">Tiêu đề</label>
                            <input class="form-control" type="text" name="page_title" id="name"
                                value="{{ Old('page_title') }}">
                            @error('page_title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug" class="fw-550">Slug (Friendly Url)</label>
                            <input class="form-control" type="text" name="slug" id="slug"
                                value="{{ Old('slug') }}">
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="page_description" class="fw-550">Mô tả</label>
                            <textarea class="form-control" name="page_description" id="page_description">{{ Old('page_description') }}</textarea>
                            @error('page_description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="page-content" class="fw-550">Nội dung trang</label>
                            <textarea class="form-control" name="page_content" id="page-content">{{ Old('page_content') }}</textarea>
                            @error('page_content')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
                    </form>
                </div>
            </div>
        </div>
    @endsection
