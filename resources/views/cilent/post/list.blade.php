@extends('layouts.client')
@section('title')
    Bài viết
@endsection
@section('content')
<div id="main-content-wp" class="clearfix blog-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{ url("/") }}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{ url("all.post") }}" title="">Blog</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="list-blog-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title">Bài viết</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @foreach ($posts as $post)
                            <li class="clearfix">
                            <a href="{{ route("detail.post", $post->id) }}" title="" class="thumb fl-left">
                                <img src="{{ url("$post->post_image") }}" alt="">
                            </a>
                            <div class="info fl-right">
                                <a href="{{ route("detail.post", $post->id) }}" title="" class="title">{!! $post->post_title !!}</a>
                                <span class="create-date">{!! $post->created_at !!}</span>
                                <p class="desc">{!! $post->post_description !!}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="section" id="paging-wp">
                {{ $posts->links() }}
            </div>
        </div>
        @include('layouts.clientSidebarBlog')
    </div>
</div>
@endsection
