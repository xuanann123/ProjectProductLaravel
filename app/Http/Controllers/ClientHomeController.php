<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientHomeController extends Controller
{
    //
    function index()
    {
        $slides = Slide::where("status", "Hoạt động")->get();
        $list_product_sold_most = Product::where("product_status", "Hoạt động")->orderByDesc("qty_sold")->limit(6)->get();
        //Chỉ những thằng danh mục cha thì được đổ ra ngoài này nè nè
        $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        return view("cilent.index", compact("list_phone_cat", "list_product_sold_most", "slides"));
    }
    function detailProduct(Product $product)
    {
        //Lấy breadcumb của parent id bằng 0
        foreach ($product->categories()->get() as $category) {
            if ($category->parent_id == "0") {
                $category_parent = $category;
            }
        }

        $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        $list_product_sold_most = Product::orderByDesc("qty_sold")->limit(6)->get();
        // Lấy các sản phẩm liên quan thuộc cùng danh mục
        foreach ($product->categories as $category) {
            $relatedProduct = $category->products()->get();
        }
        return view("cilent.detail", compact("list_phone_cat", "list_product_sold_most", "product", "relatedProduct", "category_parent"));
    }
    function listProduct()
    {
        //Lấy toàn bộ danh sách sản phẩm để rồi ra bên ngoài
        //Bây giờ thử đi làm nút search ở đây trước đã
        $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        $list_product = Product::simplePaginate(10);
        $list_brand = [];
        foreach ($list_phone_cat as $list_brand_item) {
            foreach ($list_brand_item->categoryChild as $item) {
                $list_brand[] = $item;
            }
        }
        //Đổ danh sách bộ lọc ra đây 
        return view("cilent.category", compact("list_phone_cat", "list_product", "list_brand"));
    }
    function detailCategory(Category $category)
    {
        //Bây giờ thử đi làm nút search ở đây trước đã
        $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        $list_product = $category->products()->simplePaginate(10);
        $list_brand = [];
        foreach ($list_phone_cat as $list_brand_item) {
            foreach ($list_brand_item->categoryChild as $item) {
                $list_brand[] = $item;
            }
        }
        //Đổ danh sách bộ lọc ra đây 

        return view("cilent.category", compact("list_phone_cat", "category", "list_product", "list_brand"));
    }
    function search(Request $request)
    {

        $keyword = "";
        if ($request->input("keyword")) {
            $keyword = $request->input("keyword");
        }
        $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        $list_brand = [];
        foreach ($list_phone_cat as $list_brand_item) {
            foreach ($list_brand_item->categoryChild as $item) {
                $list_brand[] = $item;
            }
        }
        $list_product = Product::where("product_name", "like", "%$keyword%")->simplePaginate(20);
        return view("cilent.category", compact("list_phone_cat", "list_product", "list_brand"));
    }
    function action(Request $request)
    {

        $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        $list_product = Product::simplePaginate(20);

        //Lấy ra danh sách hãng

        $list_brand = [];
        foreach ($list_phone_cat as $list_brand_item) {
            foreach ($list_brand_item->categoryChild as $item) {
                $list_brand[] = $item;
            }
        }
        $priceRange = $request->input("price-range");
        $brand_id = $request->input("brand_id");
        $category_id = $request->input("category_filter");


        // return $priceRange;
        // Lấy giá trị lọc từ request
        // Khởi tạo query truy vấn đến các where
        $query = Product::query();

        // Áp dụng bộ lọc giá nếu có
        if ($priceRange) {
            switch ($priceRange) {
                case '500000-':
                    $query->where('price_new', '<', 500000);
                    break;
                case '500000-1000000':
                    $query->whereBetween('price_new', [500000, 1000000]);
                    break;
                case '1000000-5000000':
                    $query->whereBetween('price_new', [1000000, 5000000]);
                    break;
                case '5000000-10000000':
                    $query->whereBetween('price_new', [5000000, 10000000]);
                    break;
                case '10000000+':
                    $query->where('price_new', '>', 10000000);
                    break;
            }
        }
        if ($brand_id) {
            $query->whereHas('categories', function ($q) use ($brand_id) {
                $q->where('categories.id', $brand_id);
            });
        }
        if ($category_id) {
            $query->whereHas('categories', function ($q) use ($category_id) {
                $q->where('categories.id', $category_id);
            });
        }
        // Lấy danh sách sản phẩm thông qua điều kiện query ở trên
        $list_product = $query->simplePaginate(10);
        return view("cilent.category", compact("list_phone_cat", "list_product", "list_brand", "priceRange", "brand_id", "category_id"));
    }
    function listPost()
    {
        // $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        //Mình sẽ đi lấy danh sách danh mục bài viết
        $list_category_post = Cat::where("cat_parent_id", "0")->where("post_cat_status", "Hoạt động")->get();
        $list_product_sold_most = Product::orderByDesc("qty_sold")->limit(6)->get();
        // return $list_category_post;
        // Lấy các sản phẩm liên quan thuộc cùng danh mục
        //Đổ danh mục bài viết ra đây
        $posts = Post::where("post_status", "Hoạt động")->simplePaginate(10);
        return view("cilent.post.list", compact("list_category_post", "list_product_sold_most", "posts"));
    }
    function detailPost(Post $post) {
        $list_category_post = Cat::where("cat_parent_id", "0")->where("post_cat_status", "Hoạt động")->get();
        $list_product_sold_most = Product::orderByDesc("qty_sold")->limit(6)->get();
        // return $list_category_post;
        // Lấy các sản phẩm liên quan thuộc cùng danh mục
        //Đổ danh mục bài viết ra đây
        return view("cilent.post.detail", compact("list_category_post", "list_product_sold_most", "post"));
    }
    function catPost(Cat $cat) {
        // $list_phone_cat = Category::where("parent_id", "0")->where("category_status", "licensed")->get();
        //Mình sẽ đi lấy danh sách danh mục bài viết
        $list_category_post = Cat::where("cat_parent_id", "0")->where("post_cat_status", "Hoạt động")->get();
        $list_product_sold_most = Product::orderByDesc("qty_sold")->limit(6)->get();
        // return $list_category_post;
        // Lấy các sản phẩm liên quan thuộc cùng danh mục
        //Đổ danh mục bài viết ra đây
        $posts = $cat->posts()->simplePaginate(10);
        return view("cilent.post.list", compact("list_category_post", "list_product_sold_most", "posts"));
    }


}
