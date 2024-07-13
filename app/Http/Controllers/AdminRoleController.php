<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminRoleController extends Controller
{
    function __construct()
    {
        session(['module_active' => "permission"]);
    }
    function index(Request $request)
    {
        //Làm nút search
        $keyword = "";
        $list_act = [
            "delete" => "Xoá tạm thời"
        ];
        $status = $request->input("status");
        if ($request->input("keyword")) {
            $keyword = $request->input("keyword");
        }
        if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "forceDelete" => "Xoá vĩnh viễn",
            ];
            $roles = Role::onlyTrashed()->where("name", "like", "%$keyword%")->simplePaginate(10);
        } else {
            // return Auth::user()->hasPermission("post.add");
            //Lấy danh sách vai trò
            $roles = Role::where("name", "like", "%$keyword%")->simplePaginate(10);
        }
     
        $count_active = Role::all()->count();
        $count_trashed = Role::onlyTrashed()->count();
        $count = [$count_active, $count_trashed];
        //Lấy danh sách vai trò của chính thằng user đó
        return view("admin.role.list", compact('roles', "list_act", "count"));
    }
    function add()
    {
        //Lấy danh sách quyền như trước
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(".", $permission->slug)[0];
        });
        // return $permissions;
        return view("admin.role.add", compact("permissions"));
    }
    function store(Request $request)
    {
        $validate = $request->validate(
            //Khi thêm dữ liệu cũng cần validate
            [
                'name' => "required|unique:roles,name",
                'permissions_id' => "nullable|array",
                //Kiểm tra bên trong bảng thì phải tồn tại giá trị id
                'permissions_id.*' => "exits:permission,id",
                'description' => "required",
            ],
            [
                'required' => "Trường  :attribute không được để trống",
                'max' => "Trường  :attribute không được quá 255 kí tự",
                'unique' => "Trường  :attribute không được trùng với các vai trò trước",

            ]
        );
        //Sẽ lấy được 1 danh sách quyền -> với các giá trị id của nó là một mảng permission_id -> thêm vào bên trong role permission Chú ý tới thằng này
        $role = Role::create([
            'name' => $request->input("name"),
            'description' => $request->input("description"),
        ]);
        //Sẽ dùng hàm attach để đính thằng $request -> input("permission_id");
        //Phương thức thêm vào trong bản trung gian qua id
        $role->permissions()->attach($request->input('permission_id'));
        return redirect()->route("role.list")->with("status", "Thêm quyền của một vai trò thành công");
    }
    function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(".", $permission->slug)[0];
        });
        //Lấy ra danh sách quyền của một vai trò
        $list_permissions_id = $role->permissions()->pluck("permission_id")->toArray();
        //Kiểm tra xem ok hay không
        //Sẽ lấy được danh sách permission của thằng quyền này
        //Lấy danh sách quyền này ra rồi đi so sánh xem nó có thuộc check box đó hay không
        return view("admin.role.edit", compact('role', 'permissions', 'list_permissions_id'));
    }
    function update(Role $role, Request $request)
    {
        $request->validate([
            //không được trùng tên ngoại trừ cái bản id đang được dùng đó
            'name' => "required|unique:roles,name," . $role->id,
            'permissions_id' => "nullable|array",
            'permissions_id.*' => "exits:permission,id",
        ]);
        $role->update([
            'name' => $request->input("name"),
            'description' => $request->input("description"),
        ]);
        //Phương thức cập nhật cho bảng trung gian qua id của role
        $role->permissions()->sync($request->input('permission_id', []));
        return redirect()->route("role.list")->with("status", "Cập nhật quyền của một vai trò thành công");
    }
    function delete(Role $role)
    {
        $role->delete();
        return redirect()->route("role.list")->with("status", "Đã xoá vai trò thành công");

    }
    function action(Request $request) {
        $list_check = $request->input("list_check");
        if ($list_check) {
            $act = $request->input("act");
            if ($act == "") {
                return redirect()->route("role.list")->with("status", "Hãy chọn tác vụ để thao tác");
            }
            if ($act == "delete") {
                //Xoá toàn bộ danh sách bằng cách
                Role::destroy($list_check);
                return redirect()->route("role.list")->with("status", "Xoá tạm thời những bản ghi được chọn");
            }
            if ($act == "restore") {
                Role::whereIn("id", $list_check)->restore();
                return redirect()->route("role.list")->with("status", "Khôi phục user thành công");
            }
            if ($act == "forceDelete") {
                Role::whereIn("id", $list_check)->forceDelete();
                return redirect()->route("role.list")->with("status", "Xoá vĩnh viễn user thành công");
            }
        } else {
            return redirect()->route("role.list")->with("status", "Bạn cần chọn bản ghi để thao tác");
        }
    }
    function restore($id)
    {

        $role = Role::withTrashed()->find($id)->restore();
        return redirect()->route("role.list")->with("status", "Khôi phục sản phẩm thành công");
    }

    function forceDelete($id)
    {
        $role = Role::withTrashed()->find($id)->forceDelete();
        return redirect()->route("role.list")->with("status", "Xoá sản phẩm vĩnh viễn");

    }
}
