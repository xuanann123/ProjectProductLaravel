@extends('layouts.admin')
@section('title')
    Danh sách user
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
            <h5 class="m-0 ">Danh sách thành viên</h5>
            <div class="form-search form-inline">
                <form action="{{ url("admin/user/list") }}">
                    <div class="d-flex">
                    <input type="" name="search" class="form-control form-search mr-3" placeholder="Tìm kiếm" value="{{ request()->input('search') }}">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    <input type="hidden" name="status" value="{{ empty(request()->input("status"))?"active":request()->input("status") }}" class="btn btn-primary">

                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{ request()->fullUrlWithQuery(['status'=>"active"]) }}" class="text-primary">Kích hoạt<span class="text-muted">({{ $count['0'] }})</span></a>
                <a href="{{ request()->fullUrlWithQuery(["status"=>"trash"]) }}" class="text-primary">Vô hiệu hoá<span class="text-muted">({{ $count['1'] }})</span></a>
                
            </div>
         <form action="{{ url("admin/user/action") }}">
            @csrf
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" name="act" id="">
                    <option>Chọn</option>
                    @foreach ($list_act as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                    
                 
                </select>
                <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" name="checkall">
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">Họ tên</th>
                        <th scope="col">Email</th>
                        <th scope="col">Quyền</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($users->items()) >0) 
                         @php
                        $i = 0;
                    @endphp
                    @foreach ($users as $user)
                    @php
                        $i++;
                    @endphp
                        <tr>
                        <td>
                            <input type="checkbox" name="listCheck[]" value="{{ $user->id }}">
                        </td>
                        <th scope="row">{{ $i }}</th>
                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <a href="{{ route("role.edit", $role->id) }}"><span class="badge badge-warning">{{ $role->name }}</span></a>
                            @endforeach
                        </td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            @if (request()->input("status") != "trash")
                               <a href="{{ route("user.edit", $user->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                            {{-- Chỉ có user ngoài chúng ta thì mới có quyền xoá --}}
                            @if (Auth::id() != $user->id)
                            <a href="{{ route("user.delete", $user->id) }}" onclick="return confirm('Bạn có muốn xoá {{ $user->name }} này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                            @endif 
                            @else
                                <a href="{{ route("user.restore", $user->id) }}" onclick=" return confirm('Bạn có muốn khôi phục {{ $user->name }} này không')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"><i class="fas fa-undo-alt"></i></a>
                            <a href="{{ route("user.forceDelete", $user->id) }}" onclick=" return confirm('Bạn có muốn xoá vĩnh viễn user {{ $user->name }} này không')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip"
                            data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></a>
                            @endif
                            
                        </td>
                    </tr> 
                    @endforeach
                    @else
                        <tr>
                            <td colspan="7">Không có bản ghi nào trên hệ thống</td>
                        </tr>
                    @endif
                   
                   
                    
                </tbody>
            </table>
        </form>   
           {{ $users->links() }}
        </div>
    </div>
</div>
@endsection