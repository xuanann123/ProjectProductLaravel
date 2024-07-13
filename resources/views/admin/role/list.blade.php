@extends('layouts.admin')
@section('title')
    Danh sách vai trò
@endsection
@section('content')
   <div id="content" class="container-fluid">
    @if (session('status'))
           <div class="alert alert-danger">
            {{ session('status') }}
        </div> 
        @endif
    <div class="card">

        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách vai trò</h5>
            <div class="form-search form-inline">
               <form action="">
                    <div class="d-flex">
                    <input type="" name="keyword" class="form-control form-search mr-3" placeholder="Tìm kiếm" value="{{ request()->input("keyword") }}">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    <input type="hidden" name="status" value="{{ empty(request()->input("status"))? 'active': request()->input("status") }}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{ request()->fullUrlWithQuery(['status'=>"active"]) }}" class="text-primary">Hoạt động<span class="text-muted">({{ $count[0] }})</span></a>
                <a href="{{ request()->fullUrlWithQuery(['status'=>"trashed"]) }}" class="text-primary">Thùng rác<span class="text-muted">({{ $count[1] }})</span></a>
            </div>
            <form action="{{ route("role.action") }}" method="post">
                @csrf
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" id="" name="act">
                    <option value="">Chọn</option>
                    @foreach ($list_act as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>   
                    @endforeach
    
                </select>
                <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">
                            <input name="checkall" type="checkbox">
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">Vai trò</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                @forelse ($roles as $role)
                 @php
                    $i ++;
                @endphp
                    <tr>
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{ $role->id }}">
                        </td>
                        <td scope="row">{{ $i }}</td>
                        <td><a href="{{ route("role.edit", $role->id) }}">{{ $role -> name }}</a></td>
                        <td>{{ $role -> description }}</td>
                        <td>{{ $role -> created_at }}</td>
                        <td>
                            @if (request()->input("status")!="trashed")
                            @can('role.edit')
                             <a href="{{ route("role.edit", $role->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>    
                            @endcan
                            @can('role.delete')

                            <a href="{{ route("role.delete", $role->id) }}" onclick="return confirm('Bạn có muốn xoá vai trò này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>    
                            @endcan
                            @else
                                <a href="{{ route("role.restore", $role->id) }}" onclick=" return confirm('Bạn có muốn khôi phục vai trò {{ $role -> name }} này không')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"><i class="fas fa-undo-alt"></i></a>
                            <a href="{{ route("role.forceDelete", $role->id) }}" onclick=" return confirm('Bạn có muốn xoá vĩnh viễn vai trò {{ $role -> name }} này không')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip"
                            data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></a>
                            @endif
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="6" class="bg-white">Không tồn tại vai trò nào</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            </form>
            {{ $roles->links() }}
            
        </div>
    </div>
</div>
@endsection