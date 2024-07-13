<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use Illuminate\Support\Str;

class AdminCategoryProductController extends Controller
{
    public function add()
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
        return view("admin.product.categoryProduct.add", compact("categories", "list_category"));

    }
    function store(Request $request)
    {
    //   return  $request->all();
        $request->validate(
            [
                'category_name' => 'required|string|max:255',
                'category_description' => 'nullable|string',
                'category_status' => 'required|in:pending,licensed',
            ],
            [
                'category_name.required' => 'Tên danh mục sản phẩm không được để trống',
                'category_name.max' => 'Tên danh mục sản phẩm không được vượt quá 255 ký tự',
                'category_description.string' => 'Mô tả phải là chuỗi',
                'category_status.required' => 'Trạng thái không được để trống',
                'category_status.in' => 'Trạng thái không hợp lệ',
            ]
        );
        Category::create([
            'category_name' => $request->input("category_name"),
            'slug' => Str::slug($request->input("category_name")),
            'parent_id' => $request->input("parent_id"),
            'category_description' => $request->input("category_description"),
            'category_status' => $request->input("category_status"),
        ]);
        return redirect()->route("product.cat.add")->with('status', "Thêm danh mục sản phẩm thành công");
    }
    function edit(Request $request, Category $category)
    {
    
        //Đỏ toàn bộ danh mục ra 
        

        $categories = Category::all();
        function data_tree_edit($data, $parent_id = 0, $level = 0)
        {
            $result = [];
            foreach ($data as $item) {
                if ($item->parent_id == $parent_id) {
                    //Lấy level tuân theo cấp bậc paren_id
                    $item['level'] = $level;
                    //Lấy được những danh mục cấp cha
                    $result[] = $item;
                    //Đệ quy menu truy vấn vào thằng con để lấy dữ liệu tiếp cho đến hết -> tương đương cấp bậc của thằng con sẽ được +1
                    $child = data_tree_edit($data, $item->id, $level + 1);
                    //Đẩy những thằng con này vào trong mảng đó luôn
                    $result = array_merge($result, $child);
                }
            }
            return $result;
        }
        $list_category = data_tree_edit($categories);
        return view("admin.product.categoryProduct.edit", compact("categories", "list_category", "category"));

    }
    function update(Request $request, Category $category)
    {
        // return $request->input();
        $request->validate(
            [
                'category_name' => 'required|string|max:255',
                'category_description' => 'nullable|string',
                'category_status' => 'required|in:pending,licensed',
            ],
            [
                'category_name.required' => 'Tên danh mục sản phẩm không được để trống',
                'category_name.max' => 'Tên danh mục sản phẩm không được vượt quá 255 ký tự',
                'category_description.string' => 'Mô tả phải là chuỗi',
                'category_status.required' => 'Trạng thái không được để trống',
                'category_status.in' => 'Trạng thái không hợp lệ',
            ]
        );
        $category->update([
            'category_name' => $request->input("category_name"),
            'slug' => Str::slug($request->input("category_name")),
            'parent_id' => $request->input("parent_id"),
            'category_description' => $request->input("category_description"),
            'category_status' => $request->input("category_status")
        ]);
        
        return redirect()->route("product.cat.add")->with('status', "Cập nhật sản phẩm theo danh mục thành công");
    }
    function delete(Category $category, Request $request) {
        $category->delete();
        return redirect()->route("product.cat.add")->with('status', "xoá sản phẩm theo danh mục thành công");

    }
    function detail(Category $category) {
        //Danh mục sản phẩm này chứa những sản phẩm nào
        $products = $category->products()->get();
        return view("admin.product.categoryProduct.detail", compact("products"));
    }
}
