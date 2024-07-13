<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    function __construct()
    {
        session(['module_active' => "permission"]);
    }
    function add()
    {
        //Lấy ra danh sách module.action chia chuỗi này ra bằng cách dùng explode
        //Dùng function group này để lấy ra từng phần tử chi tiết qua mỗi vòng lập -> slug
        $permissions = Permission::all()->groupBy(function ($permission) {
            //chia thành một mảng có hai kí tự nhưng chỉ lấy phần tử thứ nhất -> post product page
            return explode(".", $permission->slug)[0];
        });
        // return $permissions;
        return view("admin.permission.add", compact('permissions'));
    }
    function store(Request $request)
    {
        $validate = $request->validate(
            [
                'name' => "required|max:255",
                'slug' => "required",
            ],
            [
                'required' => "Trường  :attribute không được để trống",
                'max' => "Trường  :attribute không được quá 255 kí tự",
            ]
        );
        //Thêm quyền vào bảng quyền
        Permission::create([
            'name' => $request->input("name"),
            'slug' => $request->input("slug"),
            'description' => $request->input("description"),
        ]);
        //Sau khi validate xong thì thêm dữ liệu vào thôi
        return redirect()->route("permission.add")->with('status', "Thêm quyền thành công");
    }
    function edit($id)
    {
        $permission = Permission::find($id);
        $permissions = Permission::all()->groupBy(function ($permission) {
            //chia thành một mảng có hai kí tự nhưng chỉ lấy phần tử thứ nhất -> post product page
            return explode(".", $permission->slug)[0];
        });
        return view("admin.permission.edit", compact('permissions', 'permission'));
    }
    function update(Request $request, $id)
    {
        $validate = $request->validate(
            [
                'name' => "required|max:255",
                'slug' => "required",
            ],
            [
                'required' => "Trường  :attribute không được để trống",
                'max' => "Trường  :attribute không được quá 255 kí tự",
            ]
        );
        Permission::where("id", $id)->update([
            'name' => $request->input("name"),
            'slug' => $request->input("slug"),
            'description' => $request->input("description"),
        ]);
        return redirect()->route("permission.add")->with('status', "Cập nhật quyền thành công");
    }
    function delete($id)
    {
        Permission::find($id)->delete();
        return redirect()->route("permission.add")->with('status', "Xoá quyền thành công");
    }
}
