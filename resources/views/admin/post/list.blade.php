@extends('layouts.admin')
@section('title')
    Danh sách bài viết
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
            <h5 class="m-0 ">Danh sách bài viết</h5>
            <div class="form-search form-inline">
                <form action="">
                    <div class="d-flex">
                    <input type="" name="keyword" class="form-control form-search mr-3" placeholder="Tìm kiếm" value="{{ request()->input("keyword") }}">
                    <input type="hidden" name="status" value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <a href="{{ route("post.add") }}" class="btn btn-primary float-right">THÊM MỚI</a>
            <div class="analytic">
                <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $count['0'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'licensed']) }}" class="text-primary">Đã cấp quyền
                        <span class="text-muted">({{ $count['1'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ xét duyệt
                        <span class="text-muted">({{ $count['2'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count['3'] }})</span></a>
            </div>
            <form action="{{ route("post.action") }}" method="post">
                @csrf
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" id="" name="act">
                    <option value="">Chọn</option>
                    @foreach ($list_act as $key => $value)
                         <option value=" {{ $key }}">{{ $value }}</option>
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
                        <th scope="col">Ảnh bài viết</th>
                        <th scope="col">Tiêu đề bài viết</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tác vụ</th>
                               
                    </tr>
                </thead>
                <tbody>
                   @if (count($posts) > 0 )
                   @php
                       $i = 0;
                   @endphp
                   @foreach ($posts as $post)
                   @php
                       $i ++;
                   @endphp
                       <tr>
                        <td>
                            <input type="checkbox"  name="listCheckPost[]" value="{{ $post->id }}">
                        </td>
                        <td scope="row">{{ $i }}</td>
                        <td><img width="80px" src="{{ url("$post->post_image") }}"  alt=""></td>
                        <td><a href="{{ route("post.edit", $post->id) }}">{{ $post->post_title }}</a></td>
                       <td>@foreach ($post->cats as $cat)
                            <span class="badge badge-warning"> {{ $cat->post_cat_name }}</span>
                        @endforeach</td>
                        <td>{{ $post->created_at }}</td>
                        <td>
                            <span class="badge badge-danger"> {{ $post->post_status }}</span>
                            
                        </td>
                        @if (request()->status != 'trashed')
                                            <td>
                                                <a href="{{ route("post.edit", $post->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route("post.delete", $post->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá bài viết {{ $post->post_title }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route("post.restore", $post->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục trang {{ $post->post_title }}?')"
                                                    data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route("post.forcedelete", $post->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn trang {{ $post->post_title }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif

                    </tr> 
                   @endforeach
                       
                   @else
                       <tr>
                        <td colspan="8">Không tồn tại bản ghi nào</td>
                       </tr>
                   @endif
                      


                </tbody>
            </table>
            </form>
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection