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
                    <form action="{{ route("slide.update", $slide->id) }}" method='POST' enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="fw-550">Tiêu đề</label>
                            <input class="form-control" type="text" name="title" id="name"
                                value="{{ $slide->title }}">
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control-file" name="image" id="image">
                                    <img width="200px" src="{{ url("$slide->image")  }}" alt="">
                                    @error('image')
                                     <small class="text-danger">{{ $message }}</small>
                                @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="fw-550">Mô tả</label>
                            <textarea class="form-control" name="description" id="description">{!! $slide->description !!}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                         <div class="form-group">
                    <label for="">Trạng thái</label>
                    {{-- Cần tối ưu cái này ngoài view --}}
                    <div class="form-check">
                        {{-- Cái này dúng để định nghĩa cấp quyền cho một sản phẩm --}}
                        <input class="form-check-input" type="radio" name="status"  {{ $slide->status =="Hoạt động"?"checked":""  }}  id="status"  value="Hoạt động" >
                        <label class="form-check-label" for="status">
                            Hoạt động
                        </label>
                    </div>
                    <div class="form-check">
                        {{-- Cái này dúng để định nghĩa cấp quyền cho một sản phẩm --}}
                        <input class="form-check-input" type="radio" name="status"  id="status" {{ $slide->status =="Chờ duyệt"?"checked":""  }} value="Chờ duyệt">
                        {{-- {{ $slide->status =="Chưa phê duyệt"?"checked":""  }} --}}
                        <label class="form-check-label" for="status">
                            Chưa giải quyết
                        </label>
                    </div>
                     @error('status')
                            <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                        

                        <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
                    </form>
                </div>
            </div>
        </div>
    @endsection
