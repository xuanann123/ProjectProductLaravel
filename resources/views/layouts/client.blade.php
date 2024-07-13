<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset("client/css/bootstrap/bootstrap-theme.min.css") }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset("client/css/bootstrap/bootstrap.min.css") }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset("client/reset.css") }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset("client/css/carousel/owl.carousel.css") }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset("client/css/carousel/owl.theme.css") }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset("client/css/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset("client/style.css") }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset("client/responsive.css") }}" rel="stylesheet" type="text/css"/>
        <script src="{{ asset("client/js/jquery-2.2.4.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("client/js/elevatezoom-master/jquery.elevatezoom.js") }}" type="text/javascript"></script>
        <script src="{{ asset("client/js/bootstrap/bootstrap.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("client/js/carousel/owl.carousel.js") }}" type="text/javascript"></script>
        <script src="{{ asset("client/js/main.js") }}" type="text/javascript"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
        @yield('links')
    </head>
    <body>
        <div id="site">
            <div id="container">
                <div id="header-wp">
                    <div id="head-top" class="clearfix">
                        <div class="wp-inner">
                            <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                            <div id="main-menu-wp" class="fl-right">
                                <ul id="main-menu" class="clearfix">
                                    <li>
                                        <a href="{{ url("/") }}" title="">Trang chủ</a>
                                    </li>
                                    <li>
                                        <a href="{{ route("all.product") }}" title="">Sản phẩm</a>
                                    </li>
                                    <li>
                                        <a href="{{ route("all.post") }}" title="">Blog</a>
                                    </li>
                                    <li>
                                        <a href="?page=detail_blog" title="">Giới thiệu</a>
                                    </li>
                                    <li>
                                        <a href="?page=detail_blog" title="">Liên hệ</a>
                                    </li>
                                    <li>
                         @if (Route::has('login'))
                            <div class="justify-center d-flex" style="display: flex">
                                @auth
                                  @if (Auth::check() && Auth::user()->check_role == 1)
                                     <div class="btn-group">
                                    <a class=" border-0" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right bg-danger text-dark">
                                        <a class="dropdown-item" style="color: rgb(0, 0, 0)" href="{{ route('dashboard') }}">Trang quản trị</a>
                                        <a class="dropdown-item" style="color: rgb(0, 0, 0)"  href="{{ route('user.order') }}">Lịch sử mua hàng</a>
                                        
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                Đăng xuất
                                            </x-dropdown-link>
                                        </form>
                                    </div>
                                </div>
                                  @else
                                      <div class="btn-group">
                                    <a class=" border-0" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right bg-danger text-dark">
                                        <a class="dropdown-item" style="color: rgb(0, 0, 0)"  href="{{ route('user.order') }}">Lịch sử mua hàng</a>
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
                                  @endif
            
                                    
                         @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="head-body" class="clearfix">
                        <div class="wp-inner">
                            <a href="{{ url("/") }}" title="" id="logo" class="fl-left"><img src="{{ asset("client/images/logo.png") }}"/></a>
                            <div id="search-wp" class="fl-left">
                                <form method="get" action="{{ route("search") }}">
                                    @csrf
                                    <input type="text" name="keyword" id="s" value="{{ request()->input("keyword") }}" placeholder="Nhập từ khóa tìm kiếm tại đây!">
                                    <button type="submit" id="sm-s">Tìm kiếm</button>
                                </form>
                            </div>
                            <div id="action-wp" class="fl-right">
                                <div id="advisory-wp" class="fl-left">
                                    <span class="title">Tư vấn</span>
                                    <span class="phone">0348.808.722</span>
                                </div>
                                <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
                                <a href="{{ route("cart.show") }}" title="giỏ hàng" id="cart-respon-wp" class="fl-right">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span id="num">2</span>
                                </a>
                                <div id="cart-wp" class="fl-right">
                                    <div id="btn-cart">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span id="num">{{ Cart::count() }}</span>
                                    </div>
                                     @if (Cart::count() > 0)
                                    <div id="dropdown">
                                        <p class="desc">Có <span>{{ Cart::count() }} sản phẩm</span> trong giỏ hàng</p>
                                        <ul class="list-cart">
                                            @if (Cart::count() > 0)
                                            @foreach (Cart::content() as $item)
                                            @php
                                                $image = $item->options->image
                                            @endphp
                                                <li class="clearfix">
                                                            <a href="{{ route("detail.product", $item->id) }}" title="" class="thumb fl-left">
                                                                <img src="{{ url("$image") }}" alt="">
                                                            </a>
                                                            <div class="info fl-right">
                                                                <a href="{{ route("detail.product", $item->id) }}" title="" class="product-name">{{ $item->name }}</a>
                                                                <p class="price">{{ number_format("$item->price","0",".",".") }}đ</p>
                                                                <p class="qty">Số lượng: <span>{{ $item->qty }}</span></p>
                                                            </div>
                                                </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                        <div class="total-price clearfix">
                                            <p class="title fl-left">Tổng:</p>
                                            <p class="price fl-right">
                                                
                                                 @if (Cart::count() > 0)
                                                 @php
                                                     $total_price = Cart::total();
                                                 @endphp
                                                {{ number_format($total_price, 0, ".", ".") }} đ
                                            @endif
                                            </p>
                                        </div>
                                        <div class="action-cart clearfix">
                                            <a href="{{ route("cart.show") }}" title="Giỏ hàng" class="view-cart fl-left" style="background-color: red; color: white">Giỏ hàng</a>
                                            <a href="{{ route("cart.checkout") }}" title="Giỏ hàng" class="view-cart fl-right" style="background-color: red; color: white">Thanh Toán</a>
{{-- 
                                            <form action="{{ route("vnpay.payment") }}" method="POST">
                                                @csrf
                                              <input type="hidden" name="total" value="{{ Cart::total() }}">
                                              <input  type="submit" name="redirect" title="Thanh toán" class="fl-right" style="background-color: red; border: red;padding: 5px; font-weight: 600" value="Thanh toán VN">
                                            </form> --}}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@yield('content')
                <div id="footer-wp">
    <div id="foot-body">
        <div class="wp-inner clearfix">
            <div class="block" id="info-company">
                <h3 class="title">ISMART</h3>
                <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ ràng, chính sách ưu đãi cực lớn cho khách hàng có thẻ thành viên.</p>
                <div id="payment">
                    <div class="thumb">
                        <img src="public/images/img-foot.png" alt="">
                    </div>
                </div>
            </div>
            <div class="block menu-ft" id="info-shop">
                <h3 class="title">Thông tin cửa hàng</h3>
                <ul class="list-item">
                    <li>
                        <p>106 - Trần Bình - Cầu Giấy - Hà Nội</p>
                    </li>
                    <li>
                        <p>0987.654.321 - 0989.989.989</p>
                    </li>
                    <li>
                        <p>vshop@gmail.com</p>
                    </li>
                </ul>
            </div>
            <div class="block menu-ft policy" id="info-shop">
                <h3 class="title">Chính sách mua hàng</h3>
                <ul class="list-item">
                    <li>
                        <a href="" title="">Quy định - chính sách</a>
                    </li>
                    <li>
                        <a href="" title="">Chính sách bảo hành - đổi trả</a>
                    </li>
                    <li>
                        <a href="" title="">Chính sách hội viện</a>
                    </li>
                    <li>
                        <a href="" title="">Giao hàng - lắp đặt</a>
                    </li>
                </ul>
            </div>
            <div class="block" id="newfeed">
                <h3 class="title">Bảng tin</h3>
                <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                <div id="form-reg">
                    <form method="POST" action="">
                        <input type="email" name="email" id="email" placeholder="Nhập email tại đây">
                        <button type="submit" id="sm-reg">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="foot-bot">
        <div class="wp-inner">
            <p id="copyright">© Bản quyền thuộc về unitop.vn | Php Master</p>
        </div>
    </div>
</div>
</div>
{{-- <div id="menu-respon">
    <a href="?page=home" title="" class="logo">ISMARTSHOP</a>
    <div id="menu-respon-wp">
        <ul class="" id="main-menu-respon">
            <li>
                <a href="{{ url("/") }}" title>Trang chủ</a>
            </li>
            <li>
                <a href="?page=category_product" title>Điện thoại</a>
                <ul class="sub-menu">
                    <li>
                        <a href="?page=category_product" title="">Iphone</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title="">Samsung</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?page=category_product" title="">Iphone X</a>
                            </li>
                            <li>
                                <a href="?page=category_product" title="">Iphone 8</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="?page=category_product" title="">Nokia</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="?page=category_product" title>Máy tính bảng</a>
            </li>
            <li>
                <a href="?page=category_product" title>Laptop</a>
            </li>
            <li>
                <a href="?page=category_product" title>Đồ dùng sinh hoạt</a>
            </li>
            <li>
                <a href="?page=blog" title>Blog</a>
            </li>
            <li>
                <a href="#" title>Liên hệ</a>
            </li>
        </ul>
    </div>
</div>
<div id="btn-top"><img src="public/images/icon-to-top.png" alt=""/></div>
<div id="fb-root"></div> --}}
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
</body>
</html>