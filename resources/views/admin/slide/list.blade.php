@extends('layouts.admin')
@section('title')
    Danh sách trang
@endsection
@section('content')
    <div id="content" class="container-fluid">
        
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <div id="title-btn-add d-flex">
                    <h5 class="m-0 ">Danh sách trang</h5>
                </div>
                <div class="form-search form-inline">
                    <form action="">
                    <div class="d-flex">
                    <input type="" name="keyword" class="form-control form-search mr-3" placeholder="Tìm kiếm" value="{{ request()->input("keyword") }}">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    <input type="hidden" name="status" value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    </div>
                </form>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route("slide.add") }}" class="btn btn-primary float-right">THÊM MỚI</a>
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $count["0"] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'licensed']) }}" class="text-primary">Đã cấp quyền
                        <span class="text-muted">({{ $count["1"] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ xét duyệt
                        <span class="text-muted">({{ $count["2"] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count["3"] }})</span></a>
                </div>
                <form action="{{ route("slide.action") }}" method="POST">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act" id="">
                            <option value="">Chọn</option>
                            @foreach ($list_act as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    {{-- @if (!empty(request()->key_word))
                        <div class="count-page"><span>Kết quả tìm kiếm: <b>{{ $count_page }}</b> trang</span></div>
                    @endif --}}
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Tiêu đề trang</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($slides) > 0)
                                @php
                                    $cnt = 0;
                                @endphp
                                @foreach ($slides as $slide)
                                    @php
                                        $cnt++;
                                    @endphp
                                    <tr class="">
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $slide->id }}"">
                                        </td>
                                        <td scope="row">{{ $cnt }}</td>
                                        <td><a class="text-primary"
                                                href="{{ route("slide.edit", $slide->id) }}">{{ $slide->title }}</a>
                                        </td>
                                        <td><img width="200px" src="{{  url("$slide->image") }}" alt=""></td>
                                        <td>{!! date("H:i:s-d/m/Y", strtotime($slide->created_at)) !!}</td>
                                        <td>
                                             <span class="badge badge-warning"> {!! $slide->status !!}</span>
                                            </td>
                                        @if (request()->status != 'trashed')
                                            <td>
                                                <a href="{{ route("slide.edit", $slide->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route("slide.delete", $slide->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá slide {{ $slide->title }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route("slide.restore", $slide->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục slide {{ $slide->title }}?')"
                                                    data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route("slide.forcedelete", $slide->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn slide {{ $slide->title }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy trang nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{-- {{ $pages->links() }} --}}
            </div>
        </div>
    </div>

@endsection
