@extends('layouts.admin')
@section('title')
    Cập nhật vai trò
@endsection
@section('content')
   <div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Cập nhật vai trò</h5>
            <div class="form-search form-inline">
                <form action="">
                    <div class="d-flex">
                    <input type="" name="search" class="form-control form-search mr-3" placeholder="Tìm kiếm" value="">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route("role.update", $role->id) }}" enctype="multipart/form-data">
@csrf
@method("post")
                <div class="form-group">
                    <label class="text-strong" for="name">Tên vai trò</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ $role->name }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    
                </div>
                <div class="form-group">
                    <label class="text-strong" for="description">Mô tả</label>
                    <textarea class="form-control" type="text" name="description" id="description"> {{  $role->description }}</textarea>
                     @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <strong>Vai trò này có quyền gì?</strong>
                <small class="form-text text-muted pb-2">Check vào module hoặc các hành động bên dưới để chọn quyền.</small>
                <!-- List Permission  -->
                @foreach ($permissions as $moduleName => $modulePermissions)
                    <div class="card my-4 border">
                                    <div class="card-header">
                                        {{-- input id và label for thì name phải trùng nhau -> vì nó sẽ chose được danh sách cần chọ nàl gì --}}
                                        <input type="checkbox" class="check-all" name="" id="{{ $moduleName }}">
                                        <label for="{{ $moduleName }}" class="m-0  font-weight-bold">Module {{ $moduleName }}</label>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($modulePermissions as $permission)
                                                <div class="col-md-3">
                                                <input type="checkbox" class="permission" {{ in_array($permission->id, $list_permissions_id)?"checked":"" }}  value="{{ $permission->id }}" name="permission_id[]" id="{{ $permission->slug }}">
                                                <label for="{{ $permission->slug }}">{{ $permission->name }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                @endforeach
                
              
                <input type="submit" name="btn-add" class="btn btn-primary" value="Cập nhật">
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $('.check-all').click(function () {
        $(this).closest('.card').find('.permission').prop('checked', this.checked)
      })
</script>
@endsection