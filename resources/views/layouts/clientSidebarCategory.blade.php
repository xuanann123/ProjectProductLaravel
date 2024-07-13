 <div class="sidebar fl-left">
           <div class="section" id="category-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Danh mục sản phẩm</h3>
                </div>
                <div class="secion-detail">
                    <ul class="list-item">
                        @foreach ($list_phone_cat as $categoryParent)
                             <li>
                            <a href="{{ route("detail.category", $categoryParent->id) }}" title="">{{ $categoryParent->category_name }}</a>
                            @include('components.child_menu', ["categoryParent" => $categoryParent])
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="section" id="filter-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Bộ lọc</h3>
                </div>
                <div class="section-detail">
                    <form method="GET" action="{{ route("filter.action") }}">
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">Giá</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="radio" name="price-range" @isset($priceRange)
                                        {{ $priceRange== "500000-"?"checked":""}}
                                    @endisset value="500000-"></td>
                                    <td>Dưới 500.000đ</td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="price-range"  value="500000-1000000" @isset($priceRange)
                                        {{ $priceRange== "500000-1000000"?"checked":""}}
                                    @endisset ></td>
                                    <td>500.000đ - 1.000.000đ</td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="price-range"  value="1000000-5000000" @isset($priceRange)
                                        {{ $priceRange== "1000000-5000000"?"checked":""}}
                                    @endisset></td>
                                    <td>1.000.000đ - 5.000.000đ</td>
                                </tr>
                                <tr> 
                                    <td><input type="radio" name="price-range"  value="5000000-10000000" @isset($priceRange)
                                        {{ $priceRange== "5000000-10000000"?"checked":""}}
                                    @endisset></td>
                                    <td>5.000.000đ - 10.000.000đ</td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="price-range"  value="10000000+" @isset($priceRange)
                                        {{ $priceRange== "10000000+"?"checked":""}}
                                    @endisset></td>
                                    <td>Trên 10.000.000đ</td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">Hãng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_brand as $brand)
                                    <tr>
                                    <td><input type="radio" name="brand_id" value="{{ $brand->id }}" @isset($brand_id)
                                        {{ $brand_id== $brand->id?"checked":""}}
                                    @endisset ></td>
                                    <td>{{ $brand->category_name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">Loại</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_phone_cat as $category)
                                    <tr>
                                    <td><input type="radio" name="category_filter" value="{{ $category->id }}" @isset($category_id)
                                        {{ $category_id== $category->id?"checked":""}}
                                    @endisset></td>
                                    <td>{{ $category->category_name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="submit" value="Lọc sản phẩm" style="color: white; background: rgb(220, 83, 83); border: none">
                    </form>
                </div>
            </div>
            <div class="section" id="banner-wp">
                <div class="section-detail">
                    <a href="?page=detail_product" title="" class="thumb">
                        <img src="public/images/banner.png" alt="">
                    </a>
                </div>
            </div>
        </div>