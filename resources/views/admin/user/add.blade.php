@extends('layouts.admin')
@section('title')
    Thêm user
@endsection
@section('content')
    <div id="content" class="container-fluid">
       
        
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm người dùng
        </div>
        <div class="card-body">
            <form action="{{ route("user.store") }}" method="POST">
                @csrf
                @method('post')
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ old("name") }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="{{ old("email") }}">
                     @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input class="form-control" type="password" name="password" id="password" value="{{ old("password") }}">
                     @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
               <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation">
                    {{-- Phần password_confirmation sẽ được dính liền với phần confirm của password --}}
                    @error('password_confirmation')
                        <div>{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Nhóm quyền</label>
                    <select class="form-control" id="" name="list_roles[]" multiple>
                        {{-- Chọn quyền đi nè --}}
                        @foreach ($roles as $role)
                             <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection