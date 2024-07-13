<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    function __construct()
    {
        session(['module_active' => "page"]);
    }
    function index(Request $request)
    {
        $keyword = "";
        if ($request->input("keyword")) {
            $keyword = $request->input("keyword");
        }
        $list_act = [
            'licensed' => "Công khai",
            'pending' => "Chờ xét duyệt",
            'delete' => "Xoá tất cả"
        ];

        $status = $request->input("status");

        if ($status == "trashed") {
            $list_act = [
                'restore' => "Khôi phục tất cả",
                'forceDelete' => "Xoá vĩnh viễn",
            ];
            $pages = Page::onlyTrashed()->where("page_title", "like", "%$keyword%")->simplePaginate();
        } else if ($status == "pending") {
            $list_act = [

                'licensed' => "Công khai",
                'delete' => "Xoá tất cả",
            ];
            $pages = Page::where("page_status", "Chờ duyệt")->where("page_title", "like", "%$keyword%")->simplePaginate(10);

        } else if ($status == "licensed") {
            $list_act = [

                'pending' => "Chờ xét duyệt",
                'delete' => "Xoá tất cả",

            ];
            $pages = Page::where("page_status", "Công khai")->where("page_title", "like", "%$keyword%")->simplePaginate(10);

        } else {

            $pages = Page::where("page_title", "like", "%$keyword%")->simplePaginate(10);
        }
        $count_active = Page::all()->count();
        $count_pending = Page::where("page_status", "Chờ duyệt")->get()->count();
        $count_licensed = Page::where("page_status", "Công khai")->get()->count();
        $count_trashed = Page::onlyTrashed()->get()->count();
        $count = [$count_active, $count_pending, $count_licensed, $count_trashed];
        return view("admin.page.list", compact("pages", "count", "list_act"));
    }
    function add()
    {

        return view("admin.page.add");
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'page_title' => ['required', 'string', 'max:255'],
                'page_description' => ['required'],
                'page_content' => ['required'],
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
            ],
            [
                "page_title" => "Tiêu đề trang",
                "page_description" => "Mô tả trang",
                "page_content" => "Nội dung trang",
            ]
        );
        Page::create([
            "page_title" => $request->input("page_title"),
            "slug" => Str::slug($request->input("page_title")),
            "page_description" => $request->input("page_description"),
            "page_content" => $request->input("page_content"),
        ]);

        return redirect()->route("page.list")->with("status", "Thêm trang thành công");
    }
    function edit(Request $request, Page $page)
    {
        return view("admin.page.edit", compact("page"));
    }
    function update(Request $request, Page $page)
    {
        $request->validate(
            [
                'page_title' => ['required', 'string', 'max:255'],
                'page_description' => ['required'],
                'page_content' => ['required'],
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
            ],
            [
                "page_title" => "Tiêu đề trang",
                "page_description" => "Mô tả trang",
                "page_content" => "Nội dung trang",
            ]
        );
        $page->update([
            "page_title" => $request->input("page_title"),
            "slug" => Str::slug($request->input("page_title")),
            "page_description" => $request->input("page_description"),
            "page_content" => $request->input("page_content"),
            "page_status" => $request->input("page_status")
        ]);
        return redirect()->route("page.list")->with("status", "Sửa trang thành công");
    }
    function delete(Request $request, Page $page)
    {
        $page->update([
            "page_status" => "Chờ duyệt"
        ]);
        $page->delete();
        return redirect()->route("page.list")->with("status", "Xoá trang thành thành công");

    }
    function restore($id)
    {
        Page::onlyTrashed()->find($id)->restore();
        return redirect()->route("page.list")->with("status", "Khôi phục trang thành công");
    }
    function forceDelete($id)
    {
        Page::onlyTrashed()->find($id)->forceDelete();
        return redirect()->route("page.list")->with("status", "Xoá trang vĩnh viễn trang");
    }
    function action(Request $request)
    {
        //Kiểm tra người dùng xem có chọn hành động nào hay chưa
        $act = $request->input("act");
        $list_check = $request->input("list_check");
        if ($request->input("act")) {
            if ($list_check) {
                if ($act == "delete") {
                    Page::whereIn("id", $list_check)->update([
                        'page_status' => "Chờ duyệt"
                    ]);
                    Page::destroy($list_check);
                    return redirect()->route("page.list")->with("status", "Xoá thành công toàn những bản ghi đã chọn");
                }
                if ($act == "pending") {
                    Page::whereIn("id", $list_check)->update([
                        'page_status' => "Chờ duyệt"
                    ]);
                    return redirect()->route("page.list")->with("status", "Tắt Công khai thành công những bản ghi đã chọn");
                }
                if ($act == "licensed") {
                    Page::whereIn("id", $list_check)->update([
                        'page_status' => "Công khai"
                    ]);
                    return redirect()->route("page.list")->with("status", "Kích hoạt page thành công đã chọn");
                }
                if ($act == "forceDelete") {
                    $pages = Page::whereIn("id", $list_check);
                    $pages->forceDelete();
                    return redirect()->route("page.list")->with("status", "Xoá vĩnh viễn thành công");
                }
                if ($act == "restore") {
                    $pages = Page::whereIn("id", $list_check);
                    $pages->restore();
                    return redirect()->route("page.list")->with("status", "Khôi phục thành công");
                }
            } else {
                return redirect()->route("page.list")->with("status", "Vui lòng chọn những bản ghi để làm");
            }
        } else {
            return redirect()->route("page.list")->with("status", "Vui lòng chọn thao tác để làm việc");
        }

    }

}
