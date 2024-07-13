@extends('layouts.admin')
@section('title')
    Cập nhật thành viên
@endsection
@section('content')
    <div id="content" class="container-fluid">
       
        
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật thành viên
        </div>
        <div class="card-body">
            <form action="{{ route("user.update", $user->id) }}" method="post">
                @csrf
                @method('post')
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ $user->name }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" readonly  id="email" value="{{ $user->email }}">
                     @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
               
                <div class="form-group">
                    <label for="">Nhóm quyền</label>
                    <select class="form-control" multiple id="roles" name="roles[]">
                        @foreach ($roles as $role)
                            <option {{ in_array($role->id, $roles_user)?"selected":"" }} value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection