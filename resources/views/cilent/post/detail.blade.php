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
            <div class="section" id="detail-blog-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title">{{ $post->post_title }}</h3>
                </div>
                <div class="section-detail">
                    <span class="create-date">{{ $post->created_at }}</span>
                    <div class="detail">
                        {!! $post->post_content !!}
                    </div>
                </div>
            </div>
            <div class="section" id="social-wp">
                <div class="section-detail">
                    <div class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
                    <div class="g-plusone-wp">
                        <div class="g-plusone" data-size="medium"></div>
                    </div>
                    <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
                </div>
            </div>
        </div>
        @include('layouts.clientSidebarBlog')
    </div>
</div>
@endsection
