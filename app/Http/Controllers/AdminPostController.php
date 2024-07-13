<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AdminPostController extends Controller
{
    function __construct()
    {
        session(['module_active' => "post"]);
    }
    function index(Request $request)
    {

        $keyword = "";
        if ($request->input("keyword")) {
            $keyword = $request->input("keyword");
        }
        $list_act = [
            "licensed" => "Đăng bài",
            "pending" => "Chờ xét duyệt",
            "delete" => "Xoá tạm thời",
        ];
        
        $status = $request->status;
        if ($status == "pending") { 
            $list_act = [
                "licensed" => "Đăng bài",
                "delete" => "Xoá tạm thời",
            ];
            $posts = Post::where("post_status", "Chưa phê duyệt")->where("post_title", "like", "%$keyword%")->simplePaginate(10);
           
            ;
        } elseif ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $posts = Post::where("post_status", "Hoạt động")->where("post_title", "like", "%$keyword%")->simplePaginate(10);
        } elseif ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "forceDelete" => "Xoá vĩnh viễn",
            ];
            $posts = Post::onlyTrashed()->where("post_title", "like", "%$keyword%")->simplePaginate(10);
        } else {
           
            $posts = Post::where("post_title", "like", "%$keyword%")->simplePaginate(10);
        }
        $count_actitve = Post::all()->count();
        $count_licensed = Post::where("post_status", "Hoạt động")->get()->count();
        $count_pending = Post::where("post_status", "Chưa phê duyệt")->get()->count();
        $count_trashed = Post::onlyTrashed()->count();

        $count = [$count_actitve, $count_licensed, $count_pending, $count_trashed];


        //Đặt keyword là mảng không chứa kí tự nào

        return view("admin.post.list", compact("posts", "count", "list_act"));
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
        return view("admin.post.add", compact("list_category"));
    }
    function store(Request $request)
    {
        //Lấy title ra để tí đính kèm vào đường status session để thông báo
        $post_title = $request->input('post_title');

        $request->validate(
            [
                'post_title' => ['required', 'string', 'max:255'],
                'post_description' => ['required'],
                'post_content' => ['required'],
                "post_image" => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                'post_cat' => ['required'],
                'post_status' => 'required|in:Chưa phê duyệt,Hoạt động',
            ],
            [
                'required' => ":attribute không được để trống",
                "max" => [
                    "numeric" => ":attribute không được lớn hơn :max.",
                    "file" => ":attribute không được nhiều hơn :max KB.",
                    "string" => ":attribute không được nhiều hơn :max kí tự.",
                    "array" => ":attribute không được nhiều hơn :max mục.",
                ],
                "min" => [
                    "numeric" => ":attribute không được bé hơn :min.",
                    "file" => ":attribute không được ít hơn :min KB.",
                    "string" => ":attribute không được ít hơn :min kí tự.",
                    "array" => ":attribute phải có ít nhất :min mục.",
                ],
                'in' => '::attribute không hợp lệ',
            ],
            [
                "post_title" => "Tiêu đề bài viết",
                "post_description" => "Mô tả bài viết",
                "post_content" => "Nội dung bài viết",
                "post_image" => "Đường dẫn ảnh",
                "post_cat" => "Danh mục bài viết",
                "post_status" => "Trạng thái bài viết"
            ]
        );
        // Tạo một mảng chứa các thông tin create mảnng input
        $input = [
            'post_title' => $request->input("post_title"),
            'post_description' => $request->input("post_description"),
            'post_content' => $request->input("post_content"),
            'slug' => Str::slug($request->input("post_title")),
            'post_status' => $request->input("post_status"),
        ];
        //Lấy đường dẫn ra bằng cách sau
        if ($request->hasFile("post_image")) {
            $file = $request->file("post_image");
            $file_name = $file->getClientOriginalName();
            //Lấy ra cái tên rồi đi upload nó vào folder chính
            $path = $file->move("public/uploads", $file_name);
            $post_image = "public/uploads/" . $file->getClientOriginalName();
            $input['post_image'] = $post_image;
        }
        // return $input;
        $post = Post::create($input);
        $post->cats()->attach($request->input("post_cat"));
        return redirect()->route("post.list")->with("status", "Đã thêm bài viết có tiêu đề {$post_title} thành công");


    }
    function edit(Post $post, Request $request)
    {
        //Đây là toàn bộ danh mục
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
        //Lấy ra danh sách id_cat của danh mục đó
        $list_category_this_post = $post->cats()->pluck("cat_id")->toArray();
        return view("admin.post.edit", compact("post", "list_category", 'list_category_this_post'));
    }
    function update(Request $request, Post $post) {
        $post_title = $request->input('post_title');

        $request->validate(
            [
                'post_title' => ['required', 'string', 'max:255'],
                'post_description' => ['required'],
                'post_content' => ['required'],
                'post_cat' => ['required'],
                'post_status' => 'required|in:Chưa phê duyệt,Hoạt động',
            ],
            [
                'required' => ":attribute không được để trống",
                "max" => [
                    "numeric" => ":attribute không được lớn hơn :max.",
                    "file" => ":attribute không được nhiều hơn :max KB.",
                    "string" => ":attribute không được nhiều hơn :max kí tự.",
                    "array" => ":attribute không được nhiều hơn :max mục.",
                ],
                "min" => [
                    "numeric" => ":attribute không được bé hơn :min.",
                    "file" => ":attribute không được ít hơn :min KB.",
                    "string" => ":attribute không được ít hơn :min kí tự.",
                    "array" => ":attribute phải có ít nhất :min mục.",
                ],
                'in' => '::attribute không hợp lệ',
            ],
            [
                "post_title" => "Tiêu đề bài viết",
                "post_description" => "Mô tả bài viết",
                "post_content" => "Nội dung bài viết",
                "post_cat" => "Danh mục bài viết",
                "post_status" => "Trạng thái bài viết"
            ]
        );
        // Tạo một mảng chứa các thông tin create mảnng input
        $input = [
            'post_title' => $request->input("post_title"),
            'post_description' => $request->input("post_description"),
            'post_content' => $request->input("post_content"),
            'slug' => Str::slug($request->input("post_title")),
            'post_status' => $request->input("post_status"),
        ];
        if($file = $request->file("post_image")) {
            //Lấy đường dẫn ảnh rồi chỉnh move vào uploads
            $file_name = $file->getClientOriginalName();
            //Lấy ra cái tên rồi đi upload nó vào folder chính
            $path = $file->move("public/uploads", $file_name);
            $post_image = "public/uploads/" . $file->getClientOriginalName();
            $input['post_image'] = $post_image;
        } else {
            unset($input['post_image']);
        }
        $post->update($input);
        $post->cats()->sync($request->input("post_cat"));
        return redirect()->route("post.list")->with("status", "Cập nhật bài viết có title $post_title thành công");


    }
    function delete(Request $request, Post $post)
    {
        $post_title = $request->input('post_title');
        $post->update([
            'post_status' => "Chưa phê duyệt",
        ]);
        $post->delete();
        return redirect()->route("post.list")->with("status", "Xoá bài viết thành công");
    }
    function action(Request $request)
    {
        // Thực hiện công việc xoá toàn bộ
        $listCheckPost = $request->input("listCheckPost");
        $act = $request->input("act");
        if ($listCheckPost) {
            if ($act != "") {
                if ($act == "delete") {
                    //Xoá toàn bộ danh sách bằng cách
                    Post::destroy($listCheckPost);
                    return redirect()->route("post.list")->with("status", "Xoá danh sách bài viết thành công");
                }
                if ($act == "restore") {
                    Post::whereIn("id", $listCheckPost)->restore();
                    return redirect()->route("post.list")->with("status", "Khôi phục danh sách bài viết thành công");
                }
                if ($act == "restore") {
                    Post::whereIn("id", $listCheckPost)->restore();
                    return redirect()->route("post.list")->with("status", "Khôi phục danh sách bài viết thành công");
                }
                if ($act == "pending") {
                    Post::whereIn("id", $listCheckPost)->update([
                        'post_status' => "Chưa phê duyệt"
                    ]);
                    return redirect()->route("post.list")->with("status", "Tắt hoạt động thành công bài viết đã chọn");
                }
                if ($act == "licensed") {
                    Post::whereIn("id", $listCheckPost)->update([
                        'post_status' => "Hoạt động"
                    ]);
                    return redirect()->route("post.list")->with("status", "Kích hoạt bài viết thành công đã chọn");
                }
              

                if ($act == "forceDelete") {
                    Post::whereIn("id", $listCheckPost)->forceDelete();
                    return redirect()->route("post.list")->with("status", "Xoá vĩnh viễn danh sách bài viết thành công");
                }
               
            } else {
                return redirect()->route("post.list")->with("status", "Hãy chọn tác vụ để thao tác");
            }
            
        } else {
            return redirect()->route("post.list")->with("status", "Bạn cần chọn bản ghi để thao tác");

        }

    }
    function restore($id)
    {
        $post = Post::withTrashed()->find($id)->restore();
        return redirect()->route("post.list")->with("status", "Khôi phục bài viết thành công thành công");
    }

    function forceDelete($id)
    {
        $post = Post::withTrashed()->find($id)->forceDelete();
        return redirect()->route("post.list")->with("status", "Xoá bài viết vĩnh viên vĩnh viễn");

    }
}
