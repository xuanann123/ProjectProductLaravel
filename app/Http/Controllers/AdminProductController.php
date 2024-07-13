<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Return_;

class AdminProductController extends Controller
{
    function __construct()
    {
        session(['module_active' => "product"]);
    }
    function index(Request $request)
    {
        //Làm chức năng tìm kiếm
        $status = $request->input("status");
        $list_act = [
            "licensed" => "Đăng bài",
            "pending" => "Chờ xét duyệt",
            'delete' => "Xoá toàn bộ"
        ];
        $keyword = "";
        if ($request->input("search")) {
            $keyword = $request->input("search");
        }
        if ($status == "trash") {
            $list_act = [
                'restore' => "Khôi phục",
                "forceDelete" => "Xoá vĩnh viễn"
            ];
            $products = Product::onlyTrashed()->where("product_name", "like", "%$keyword%")->simplePaginate(10);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                'delete' => "Xoá toàn bộ"
            ];
            $products = Product::where("product_status", "Hoạt động")->where("product_name", "like", "%$keyword%")->simplePaginate(10);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đăng bài",
                'delete' => "Xoá toàn bộ"
            ];
            $products = Product::where("product_status", "Chưa phê duyệt")->where("product_name", "like", "%$keyword%")->simplePaginate(10);
        } else {      
            $products = Product::where("product_name", "like", "%$keyword%")->simplePaginate(10);
        }
        $count_active = Product::all()->count();
        $count_licensed = Product::where("product_status", "Hoạt động")->get()->count();
        $count_pending = Product::where("product_status", "Chưa phê duyệt")->get()->count();
        $count_trash = Product::onlyTrashed()->count();

        $count = [$count_active, $count_licensed, $count_pending, $count_trash];


        //Đổ danh sách sản phẩm ra ngoài 

        return view("admin.product.list", compact("products", "count", "list_act"));
    }
    function add()
    {
        $categories = Category::all();
        // return $categories;
        //Lấy giao diện rồi đổ ra đây luôn nhek
        function data_tree($data, $parent_id = 0, $level = 0)
        {
            $result = [];
            foreach ($data as $item) {
                if ($item->parent_id == $parent_id) {
                    //Lấy level tuân theo cấp bậc paren_id
                    $item['level'] = $level;
                    //Lấy được những danh mục cấp cha
                    $result[] = $item;
                    //Tối ưu vòng lập nếu đã lập qua 1 lần và đẩy nó vào trong mảng rồi thì xoá phần tử đó ở $data đi => tối ưu làm việc nhanh hơn
                    //Đệ quy menu truy vấn vào thằng con để lấy dữ liệu tiếp cho đến hết -> tương đương cấp bậc của thằng con sẽ được +1
                    $child = data_tree($data, $item->id, $level + 1);
                    //Đẩy những thằng con này vào trong mảng đó luôn
                    $result = array_merge($result, $child);
                }
            }
            return $result;
        }
        $list_category = data_tree($categories);
        return view("admin.product.add", compact("list_category"));
    }
    function store(Request $request)
    {
        
        $request->validate(
            [
                'product_name' => 'required',
                'product_code' => 'required|unique:products,product_code',
                'qty_remain' => 'required|numeric|min:0',
                'price_old' => 'required|numeric|min:0',
                'price_new' => 'required|numeric|min:0',
                'product_desc' => 'required',
                'product_detail' => 'required',
                'category_product' => 'required|array',
                'category_product.*' => 'exists:categories,id',
                "product_image" => "required|image|mimes:jpeg,png,jpg,gif"
            ],
            [
                'min' => 'Trường :attribute không được nhỏ hơn 0.',
                'required' => 'Trường :attribute không được để trống.',
                'unique' => "Trường :attribute không được trùng.",
                'numeric' => 'Trường :attribute phải là số.',
                'exists' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
                'in' => 'Trường :attribute phải là một trong các giá trị: :values.',
            ]
        );
        $input = [
            'product_name' => $request->input("product_name"),
            'slug' => Str::slug($request->input("product_name")),
            'product_code' => $request->input("product_code"),
            'price_old' => $request->input("price_old"),
            'qty_remain' => $request->input("qty_remain"),
            'price_new' => $request->input("price_new"),
            'product_desc' => $request->input("product_desc"),
            'product_detail' => $request->input("product_detail"),
        ];
        

        if ($request->hasFile("product_image")) {

            //Nhận giá trị từ request(); -> trỏ thẳng vào thuộc tính name để lấy ra
            $file = $request->file("product_image");
            //Tiếp đó lấy ra tên cua r nó
            $file_name = $file->getClientOriginalName();
            //Chuyển nó vào folder upload
            $path = $file->move("public/uploads", $file_name);
            $product_image = "public/uploads/" . $file_name;
            $input['product_image'] = $product_image;
            
        }

        $product = Product::create($input);
        if($request->hasFile("images")) {
            foreach ($request->file('images') as $image) {
                $file_iamges_detail = $image->getClientOriginalName();
                $path_detail = $image->move("public/uploads", $file_iamges_detail);
                $product_image_detail = "public/uploads/" . $file_iamges_detail;
                Image::create([
                    'product_id' => $product->id,
                    'image_path' => $product_image_detail
                ]);
            }
        }
        $product->categories()->attach($request->input('category_product'));
        return redirect()->route("product.list")->with("status", "Thêm sản phẩm thành công");
    }
    function edit(Product $product, Request $request)
    {
        //Đây là toàn bộ danh mục
        $categories = Category::all();
        // return $categories;
        //Lấy giao diện rồi đổ ra đây luôn nhek
        function data_tree_edit($data, $parent_id = 0, $level = 0)
        {
            $result = [];
            foreach ($data as $item) {
                if ($item->parent_id == $parent_id) {
                    //Lấy level tuân theo cấp bậc paren_id
                    $item['level'] = $level;
                    //Lấy được những danh mục cấp cha
                    $result[] = $item;
                    //Tối ưu vòng lập nếu đã lập qua 1 lần và đẩy nó vào trong mảng rồi thì xoá phần tử đó ở $data đi => tối ưu làm việc nhanh hơn
                    //Đệ quy menu truy vấn vào thằng con để lấy dữ liệu tiếp cho đến hết -> tương đương cấp bậc của thằng con sẽ được +1
                    $child = data_tree_edit($data, $item->id, $level + 1);
                    //Đẩy những thằng con này vào trong mảng đó luôn
                    $result = array_merge($result, $child);
                }
            }
            return $result;
        }
        $list_category = data_tree_edit($categories);
        
        //Lấy ra danh sách id_cat của danh mục đó
        $list_category_id = $product->categories()->pluck("category_id")->toArray();
        // return $product->product_status;
        return view("admin.product.edit", compact("product", "list_category", 'list_category_id'));
    }
    function update(Product $product, Request $request)
    {

        $request->validate(
            [
                'product_name' => 'required|max:255',
                'product_code' => 'required|unique:products,product_code,' . $product->id,
                'qty_remain' => 'required|numeric|min:0',
                'price_old' => 'required|numeric|min:0',
                'price_new' => 'required|numeric|min:0',
                'product_desc' => 'required',
                'product_detail' => 'required',
                'category_product' => 'required|array',
                'category_product.*' => 'exists:categories,id',
            ],
            [
                'min' => 'Trường :attribute không được nhỏ hơn 0.',
                'required' => 'Trường :attribute không được để trống.',
                'unique' => "Trường :attribute không được trùng.",
                'numeric' => 'Trường :attribute phải là số.',
                'exists' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
                'in' => 'Trường :attribute phải là một trong các giá trị: :values.',
            ]
        );
        $input = [
            'product_name' => $request->input("product_name"),
            'slug' => Str::slug($request->input("product_name")),
            'product_code' => $request->input("product_code"),
            'price_old' => $request->input("price_old"),
            'qty_remain' => $request->input("qty_remain"),
            'price_new' => $request->input("price_new"),
            'product_desc' => $request->input("product_desc"),
            'product_detail' => $request->input("product_detail"),
            'product_status' => $request->input("product_status"),
        ];
        if ($file = $request->file("product_image")) {
            $file_name = $file->getClientOriginalName();
            //Chuyển nó vào folder upload
            $path = $file->move("public/uploads", $file_name);
            $product_image = "public/uploads/" . $file_name;
            $input['product_image'] = $product_image;
        } else {
            unset($input['product_image']);
        }
        //update
        $product->update($input);
        $product->categories()->sync($request->input("category_product"));
        return redirect()->route("product.list")->with("status", "Cập nhật sản phẩm thành công");
    }
    function delete(Product $product, Request $request)
    {
        $product->update([
            'product_status' => "Chưa phê duyệt",
        ]);
        $product->delete();
        return redirect()->route("product.list")->with("status", "Xoá sản phẩm thành công");

    }
    function action(Request $request)
    {
        // Thực hiện công việc xoá toàn bộ
        $listCheckProduct = $request->input("listCheckProduct");
        if ($listCheckProduct) {
            $act = $request->input("act");
            if ($act == "") {
                return redirect()->route("product.list")->with("status", "Hãy chọn tác vụ để thao tác");
            }
            if ($act == "pending") {
                Product::whereIn("id", $listCheckProduct)->update([
                    'product_status' => "Chưa phê duyệt"
                ]);
                return redirect()->route("product.list")->with("status", "Chưa phê duyệt toàn bộ bản ghi thành công");
            }
            if ($act == "licensed") {
                Product::whereIn("id", $listCheckProduct)->update([
                    'product_status'=>"Hoạt động"
                ]);
                return redirect()->route("product.list")->with("status", "Phê duyệt toàn bộ bản ghi thành công");
            }
            if ($act == "delete") {
                Product::whereIn("id", $listCheckProduct)->update([
                    'product_status' => "Chưa phê duyệt"
                ]);
                //Xoá toàn bộ danh sách bằng cách
                Product::destroy($listCheckProduct);
               
                return redirect()->route("product.list")->with("status", "Xoá sản phẩm thành công");
            }
            if ($act == "restore") {
                Product::whereIn("id", $listCheckProduct)->restore();
                return redirect()->route("product.list")->with("status", "Khôi phục sản phẩm thành công");
            }
            if ($act == "forceDelete") {
                Product::whereIn("id", $listCheckProduct)->forceDelete();
                return redirect()->route("product.list")->with("status", "Xoá vĩnh viễn sản phẩm thành công");
            }
        } else {
            return redirect()->route("product.list")->with("status", "Bạn cần chọn bản ghi để thao tác");

        }

    }
    function restore($id)
    {

        $product = Product::withTrashed()->find($id)->restore();
        return redirect()->route("product.list")->with("status", "Khôi phục sản phẩm thành công");
    }

    function forceDelete($id)
    {
        $product = Product::withTrashed()->find($id)->forceDelete();
        return redirect()->route("product.list")->with("status", "Xoá sản phẩm vĩnh viễn");

    }


}
