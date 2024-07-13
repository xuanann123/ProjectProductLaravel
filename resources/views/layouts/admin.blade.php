<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="{{ asset("css/style.css") }}">
    <script src="https://cdn.tiny.cloud/1/yrczlu8vtw371hex5ans6vy683h0hi4o6uj09y13o0kcx3ri/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>
    <title>@yield('title')</title>
</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="?">UNITOP ADMIN</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ url('admin/post/add') }}">Thêm bài viết</a>
                        <a class="dropdown-item" href="{{ route("product.list") }}">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="{{ url('admin/order/list') }}">Thêm đơn hàng</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">Thông tin tài khoản</a>
                        <a class="dropdown-item" href="{{ url("/") }}">Giao diện người dùng</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Logout') }}
                            </x-dropdown-link>
                        </form>
                       
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->
        {{ $module_active = session('module_active') }}
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                <ul id="sidebar-menu">
                    <li class="nav-link {{ $module_active == "dashboard"?"active":"" }}">
                        <a href="{{ url('dashboard') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                    </li>
                    @canany(['page.add', 'page.show', 'page.edit', 'page.delete'])
                    <li class="nav-link {{ $module_active == "page"?"active":"" }}">
                        <a href="{{ url('admin/page/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Trang
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('page.add')
                            <li><a href="{{ route("page.add") }}">Thêm mới</a></li>
                            @endcan
                            @can('page.show')
                            <li><a href="{{ route("page.list") }}">Danh sách</a></li>
                            @endcan
                        </ul>
                    </li>
                     @endcanany

                    @canany(['post.add', 'post.show', 'post.edit', 'post.delete'])
                    <li class="nav-link  {{ $module_active == "post"?"active":"" }}">
                        <a href="{{ route("post.list") }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bài viết
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            @can('post.add')
                            <li><a href="{{ url('admin/post/add') }}">Thêm mới</a></li>
                            @endcan
                            @can('post.show')
                            <li><a href="{{  route("post.list") }}">Danh sách</a></li>
                            @endcan
                            @can('post.show')
                            <li><a href="{{ route("post.cat.add") }}">Danh mục</a></li>
                            @endcan
                        </ul>
                    </li>
                     @endcanany

                    @canany(['product.add', 'product.show', 'product.edit', 'product.delete'])
                    <li class="nav-link {{ $module_active == "product"?"active":"" }}" >
                        <a href="{{ route("product.list") }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Sản phẩm
                        </a>
                        <i class="arrow fas fa-angle-down"></i>
                        <ul class="sub-menu">
                            @can('product.add')
                            <li><a href="{{ route("product.add") }}">Thêm mới</a></li>
                            @endcan
                            @can('product.show')
                            <li><a href="{{ route("product.list") }}">Danh sách</a></li>
                            @endcan
                            @can('product.show')
                            <li><a href="{{ route("product.cat.add") }}">Danh mục</a></li>
                            @endcan

                        </ul>
                    </li>
                     @endcanany

                    <li class="nav-link {{ $module_active == "order"?"active":"" }}">
                        <a href="{{ url('admin/order/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bán hàng
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ route("order.list") }}">Đơn hàng</a></li>
                        </ul>
                    </li>

                     @canany(['user.add', 'user.show', 'user.edit', 'user.delete'])
                    <li class="nav-link {{ $module_active == "user"?"active":"" }}">
                        <a href="{{ url('admin/user/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Users
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('user.add')
                                <li><a href="{{ url('admin/user/add') }}">Thêm mới</a></li>
                            @endcan
                             @can('user.show')
                            <li><a href="{{ url('admin/user/list') }}">Danh sách</a></li>
                             @endcan
                        </ul>
                    </li>
                     @endcanany
                     @canany(['slide.add', 'slide.show', 'slide.edit', 'slide.delete'])
                    <li class="nav-link {{ $module_active == "slide"?"active":"" }}">
                        <a href="{{ url('admin/slide/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Slide
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('slide.add')
                            <li><a href="{{ route("slide.add") }}">Thêm mới</a></li>
                            @endcan
                            @can('slide.show')
                            <li><a href="{{ route("slide.list") }}">Danh sách</a></li>
                            @endcan
                        </ul>
                    </li>
                     @endcanany
                    @canany(['role.add', 'role.show', 'role.edit', 'role.delete'])
                    <li class="nav-link {{ $module_active == "permission"?"active":"" }}">
                        <a href="{{ route("role.list") }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Phân quyền
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                             @can('permission.add')
                            <li><a href=" {{ url('admin/permission/add') }}">Quyền</a></li>
                            @endcan

                            @can('role.show')
                            <li><a href="{{ route("role.list") }}">Danh sách vai trò</a></li>  
                            @endcan

                            @can('role.add')
                                <li><a href="{{ route("role.add") }}">Thêm vai trò</a></li>
                            @endcan
                            
                        </ul>
                    </li>
                    @endcanany
                  


                </ul>
            </div>
            <div id="wp-content">
                {{-- Xử lý phần content --}}
                @yield('content')
            </div>
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset("js/app.js") }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>