@extends('layouts.admin')
@section('title')
    Sửa trang
@endsection
@section('content')
        <div id="content" class="container-fluid">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Sửa trang
                </div>
                <div class="card-body">
                    <form action="{{ route("page.update", $page->id) }}" method='POST' enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="fw-550">Tiêu đề</label>
                            <input class="form-control" type="text" name="page_title" id="name"
                                value="{{ $page->page_title }}">
                            @error('page_title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug" class="fw-550">Slug (Friendly Url)</label>
                            <input class="form-control" type="text" name="slug" id="slug"
                                value="{{ $page->slug }}" readonly>
                      
                        </div>

                        <div class="form-group">
                            <label for="page_description" class="fw-550">Mô tả</label>
                            <textarea class="form-control" name="page_description" id="page_description">{{ $page->page_description }}</textarea>
                            @error('page_description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="page-content" class="fw-550">Nội dung trang</label>
                            <textarea class="form-control" name="page_content" id="page-content">{{ $page->page_content }}</textarea>
                            @error('page_content')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                         <div class="form-group">
                            <label for="">Trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="page_status" id="exampleRadios1" value="Chờ duyệt" {{ $page->page_status == "Chờ duyệt"?"checked":"" }}>
                                <label class="form-check-label" for="exampleRadios1">
                                    Chờ duyệt
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="page_status" id="exampleRadios2" value="Công khai" {{ $page->page_status == "Công khai"?"checked":"" }}>
                                <label class="form-check-label" for="exampleRadios2">
                                    Công khai
                                </label>
                            </div>
                            @error('page_status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <input type="submit" name="btn_add" class="btn btn-success" value="Sửa">
                    </form>
                </div>
            </div>
        </div>
    @endsection
