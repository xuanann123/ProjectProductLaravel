@extends('layouts.admin')
@section('title')
    Tạo mới quyền
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
                    Thêm quyền
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route("permission.store") }}">
                        @csrf
                        @method('post')
                        <div class="form-group">
                            <label for="name">Tên quyền</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old("name") }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <small class="form-text text-muted pb-2">Ví dụ: post.add</small>                            
                            <input class="form-control" type="text" name="slug" id="slug" value="{{ old("slug") }}">
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" type="text" name="description" id="description"> </textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách quyền
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên quyền</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Action</th>
                                <!-- <th scope="col">Mô tả</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            {{-- Phải duyệt qua hai vòng lập bởi khi dạng đa cấp key và vale key là module và value là mảng Per --}}
                            @foreach ($permissions as $moduleName => $modulePermissions)
                                <tr>
                                <td scope="row"></td>
                                <td><strong>Module {{ ucfirst($moduleName)  }}</strong></td>
                                <td></td>
                                <td></td>
                            </tr> 
                            @foreach ($modulePermissions as $permission)
                            @php
                                $i++;
                            @endphp
                                <tr>
                                <td scope="row">{{ $i }}</td>
                                <td>|---{{ $permission ->name }}</td>
                                <td>{{ $permission ->slug }}</td>
                                <td>
                                   <a href="{{ route("permission.edit", $permission ->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>                              
                                  <a href="{{ route("permission.delete", $permission ->id) }}" onclick="return confirm('Bạn có muốn xoá quyền này này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                   
                                </td>
                            </tr>
                            @endforeach
                            
                            @endforeach
                            
                           
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection