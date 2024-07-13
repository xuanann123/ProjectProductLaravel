<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class AdminSlideController extends Controller
{
    function index(Request $request)
    {
        //Làm nút search
        $keyword = "";
        $status = $request->input("status");
        if ($request->input("keyword")) {
            $keyword = $request->input("keyword");
        }
        $list_act = [
            "licensed" => "Đăng bài",
            "pending" => "Chờ xét duyệt",
            'delete' => "Xoá toàn bộ"
        ];
        if ($status == "trashed") {
            $list_act = [
                'restore' => "Khôi phục",
                "forceDelete" => "Xoá vĩnh viễn"
            ];
            $slides = Slide::onlyTrashed()->where("title", "like", "%$keyword%")->simplePaginate(10);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                'delete' => "Xoá toàn bộ"
            ];
            $slides = Slide::where("status", "Hoạt động")->where("title", "like", "%$keyword%")->simplePaginate(10);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đăng bài",
                'delete' => "Xoá toàn bộ"
            ];
            $slides = Slide::where("status", "Chờ duyệt")->where("title", "like", "%$keyword%")->simplePaginate(10);
        } else {
            $slides = Slide::where("title", "like", "%$keyword%")->get();
        }
        $count_active = Slide::all()->count();
        $count_trashed = Slide::onlyTrashed()->count();
        $count_pending = Slide::where("status", "Chờ duyệt")->count();
        $count_licensed = Slide::where("status", "Hoạt động")->count();

        $count = [$count_active, $count_licensed, $count_pending, $count_trashed];
        return view("admin.slide.list", compact("slides", "count", "list_act"));
    }
    function add()
    {
        return view("admin.slide.add");
    }
    function store(Request $request)
    {
        //Đầu tiên là đi validate nè
        $request->validate(
            [
                'title' => 'nullable|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
                'status' => 'required|in:Chờ duyệt,Hoạt động',
            ],
            [
                "required" => ":attribute không được để trống",
            ],
            [
                "title" => "Tiêu đề",
                "image" => "Hình ảnh",
                "description" => "Mô tả",
                "status" => "Trạng thái",
            ]
        );
        //Kiểm tra xem có file ảnh không
        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $file_name = $file->getClientOriginalName();
            //upload file vào thư mục upload
            $path = $file->move("public/uploads", $file_name);
            //Tạo ra biến để lưu trữ trên database
            $thumb_slide = "public/uploads/" . $file_name;
        }

        Slide::create([
            "title" => $request->input("title"),
            "image" => $thumb_slide,
            "description" => $request->input("description"),
            "status" => $request->input("status"),
        ]);
        return redirect()->route("slide.list")->with("status", "Thêm slide thành công");
    }
    function edit(Slide $slide, Request $request)
    {
        return view("admin.slide.edit", compact("slide"));
    }
    function update(Slide $slide, Request $request)
    {
        $request->validate(
            [
                'title' => 'nullable|string|max:255',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
                'status' => 'required|in:Chờ duyệt,Hoạt động',
            ],
            [
                "required" => ":attribute không được để trống",
            ],
            [
                "title" => "Tiêu đề",
                "image" => "Hình ảnh",
                "description" => "Mô tả",
                "status" => "Trạng thái",
            ]
        );
        $slide_name = $request->input("title");
        //Kiểm tra vẫn là ảnh cũ hay là update ảnh mới
        $input = [
            'title' => $request->input("title"),
            'description' => $request->input("description"),
            'status' => $request->input("status"),
        ];
        //Có đi upload ảnh mới hay không thì như sau
        if ($file = $request->file("image")) {
            $file_name = $file->getClientOriginalName();
            //Chuyển nó vào folder upload
            $path = $file->move("public/uploads", $file_name);
            $thumb_slide = "public/uploads/" . $file_name;
            $input['image'] = $thumb_slide;
        } else {
            unset($input['image']);
        }
        //Từ đó đi cập nhật
        $slide->update($input);
        return redirect()->route("slide.list")->with("status", "Cập nhật slide {{ $slide_name }} thành công");
    }
    //Tiếp đền phần xoá slide
    function delete(Slide $slide)
    {
        $slide->update(["status" => "Chờ duyệt"]);
        $slide->delete();
        return redirect()->route("slide.list")->with("status", "Xoá mền slide thành công");

    }
    function action(Request $request)
    {
        $list_check = $request->input("list_check");
        $act = $request->input("act");
        if ($list_check) {
            if ($act == "") {
                return redirect()->route("slide.list")->with("status", "Hãy chọn tác vụ để thao tác");
            }
            if ($act == "pending") {
                Slide::whereIn("id", $list_check)->update([
                    'status' => "Chờ duyệt"
                ]);
                return redirect()->route("slide.list")->with("status", "Chưa phê duyệt toàn bộ bản ghi thành công");
            }
            if ($act == "licensed") {
                Slide::whereIn("id", $list_check)->update([
                    'status' => "Hoạt động"
                ]);
                return redirect()->route("slide.list")->with("status", "Phê duyệt toàn bộ bản ghi thành công");
            }
            if ($act == "delete") {
                Slide::whereIn("id", $list_check)->update([
                    'status' => "Chờ duyệt"
                ]);
                //Xoá toàn bộ danh sách bằng cách
                Slide::destroy($list_check);
                return redirect()->route("slide.list")->with("status", "Xoá slide thành công");
            }
            if ($act == "restore") {
                Slide::whereIn("id", $list_check)->restore();
                return redirect()->route("slide.list")->with("status", "Khôi phục slide thành công");
            }
            if ($act == "forceDelete") {
                Slide::whereIn("id", $list_check)->forceDelete();
                return redirect()->route("slide.list")->with("status", "Xoá vĩnh viễn slide thành công");
            }
        } else {
            return redirect()->route("slide.list")->with("status", "Bạn cần chọn bản    ghi để thao tác");

        }
    }
    function restore($id)
    {

        $slide = Slide::withTrashed()->find($id)->restore();
        return redirect()->route("slide.list")->with("status", "Khôi phục slide thành công");
    }

    function forceDelete($id)
    {
        $slide = Slide::withTrashed()->find($id)->forceDelete();
        return redirect()->route("slide.list")->with("status", "Xoá slide vĩnh viễn");

    }
}
