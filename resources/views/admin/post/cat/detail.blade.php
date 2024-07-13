@extends('layouts.admin')
@section('title')
    Chi tiết danh mục bài viết
@endsection
@section('content')
<div id="content" class="container-fluid">

        <div class="card">
            <div class="card-header font-weight-bold">
                Danh mục {{ $cat->post_cat_name }} có số bài viết là: {{ $post_cat_number }}
            </div>
            <div class="card-body" id="information-order">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh bài viết</th>
                            <th scope="col">Tên bài viết</th>
                            <th scope="col">Mô tả</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($list_posts_in_category) > 0)
                            @php                     
                                $cnt = 0;
                        @endphp
                            @foreach ($list_posts_in_category as $post)
                     
                                @php
                   
                                $cnt++;
                                @endphp
                                <tr class="row-in-list">
                                    <th scope=" row">{{ $cnt }}</th>
                                    <td>
                                        <a href="{{ route('post.edit', $post->id) }}" class="post_image">
                                            <img src="{{ url("$post->post_image") }}"
                                                alt="Ảnh của sản phẩm {{ $post->post_name }}"
                                                title="Ảnh của sản phẩm {{ $post->post_name }}" id="post_image" width="80px">
                                        </a>
                                    </td>
                                    <td>{{ $post->post_title }}</td>
                          
            
                                    <td>{{  $post->post_description }}</td>
                                    <td>
                                        <span class="badge badge-warning">   {{  $post->post_status }}</span>
                                    </td>
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
                                
                                </tr>
                             
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="bg-white">Không tìm thấy trang nào!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        
                </div>
           


@endsection
