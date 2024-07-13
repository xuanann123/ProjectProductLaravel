<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryPostController extends Controller
{
    function __construct()
    {
        session(['module_active' => "post"]);
    }
    function add()
    {
        $categories = Cat::all();
        //Lấy giao diện rồi đổ ra đây luôn nhek
        function data_tree($data, $parent_id = 0, $level = 0)
        {
            $result = [];
            foreach ($data as $item) {
                if ($item->cat_parent_id == $parent_id) {
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
        //Hiển thị ra view và danh sách post
        return view("admin.post.cat.add", compact("list_category"));
    }
    function store(Request $request)
    {
        //Thêm thành công rồi nè
        $request->validate(
            [
                'post_cat_name' => 'required|string|max:255',
                'post_cat_status' => 'required|in:Chưa phê duyệt,Hoạt động',
            ],
            [
                'post_cat_name.required' => 'Tên danh mục sản phẩm không được để trống',
                'post_cat_name.max' => 'Tên danh mục sản phẩm không được vượt quá 255 ký tự',
                'post_cat_status.required' => 'Trạng thái không được để trống',
                'post_cat_status.in' => 'Trạng thái không hợp lệ',
            ]
        );
        Cat::create([
            'post_cat_name' => $request->input("post_cat_name"),
            'slug' => Str::slug($request->input("post_cat_name")),
            'cat_parent_id' => $request->input("cat_parent_id"),
            'post_cat_status' => $request->input("post_cat_status"),
        ]);
        return redirect()->route("post.cat.add")->with('status', "Thêm danh mục bài viết thành công");
    }
    //Tiếp đến là phần edit
    function edit(Request $request, Cat $cat)
    {
        $categories = Cat::all();
        //Lấy giao diện rồi đổ ra đây luôn nhek
        function data_tree_edit($data, $parent_id = 0, $level = 0)
        {
            $result = [];
            foreach ($data as $item) {
                if ($item->cat_parent_id == $parent_id) {
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
        //Hiển thị ra view và danh sách post
        return view("admin.post.cat.edit", compact("list_category", "cat"));
    }
    function update(Request $request, Cat $cat)
    {
        $request->validate(
            [
                'post_cat_name' => 'required|string|max:255',
                'post_cat_status' => 'required|in:Chưa phê duyệt,Hoạt động',
            ],
            [
                'post_cat_name.required' => 'Tên danh mục sản phẩm không được để trống',
                'post_cat_name.max' => 'Tên danh mục sản phẩm không được vượt quá 255 ký tự',
                'post_cat_status.required' => 'Trạng thái không được để trống',
                'post_cat_status.in' => 'Trạng thái không hợp lệ',
            ]
        );
        $cat->update([
            'post_cat_name' => $request->input("post_cat_name"),
            'slug' => Str::slug($request->input("post_cat_name")),
            'cat_parent_id' => $request->input("cat_parent_id"),
            'post_cat_status' => $request->input("post_cat_status"),
        ]);
        return redirect()->route("post.cat.add")->with('status', "Sửa bài viết thành công");
    }
    function delete(Request $request, Cat $cat)
    {
        $cat->delete();
        $cat->update([
            'post_cat_status' => "Chưa phê duyệt",
        ]);
        return redirect()->route("post.cat.add")->with('status', "Xoá bài viết thành công");
    }
    function detail(Cat $cat) {
        //Một danh mục chứa những bài viết nào
        $list_posts_in_category = $cat->posts()->where("post_status","Hoạt động")->get();
        $post_cat_number = count($list_posts_in_category);
        //Từ đó trả về giá bên view duyệt qua vòng lập
        return view("admin.post.cat.detail", compact("list_posts_in_category","cat","post_cat_number"));
    }
}
