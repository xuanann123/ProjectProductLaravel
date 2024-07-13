<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    function __construct()
    {
        session(['module_active' => "user"]);
    }
    function index(Request $request)
    {

        $status = $request->input("status");
        $list_act = ['delete' => "Xoá tạm thời"];
        $keyword = "";
        if ($request->input('search')) {
            $keyword = $request->input('search');
        }
        //Đặt cho nó hai cái status và nếu như là trash thì -> lấy onlyTrashed 
        //Còn ngược lại show toàn bộ ra 
        if ($status == "trash") {
            $list_act = [
                'restore' => "Khôi phục",
                "forceDelete" => "Xoá vĩnh viễn"
            ];
            $users = User::onlyTrashed()->where('name', 'like', "%$keyword%")->simplePaginate(10);
        } else {
        
            $users = User::where('name', 'like', "%$keyword%")->simplePaginate(5);
        }
        //Lấy ra giá trị
        $count_user_active = User::count();
        $count_user_trash = User::onlyTrashed()->count();
        $count = [$count_user_active, $count_user_trash];
        return view('admin.user.list', compact('users', 'count', 'list_act'));
    }
    function add()
    {
        //Lấy danh sách vai trò và đổ xuống đây
        $roles = Role::all();
        return view('admin.user.add', compact("roles"));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'name.required' => 'Vui lòng nhập :attribute.',
                'name.string' => ':Attribute phải là một chuỗi.',
                'name.max' => ':Attribute không được vượt quá 255 ký tự.',
                'email.required' => 'Vui lòng nhập :attribute.',
                'email.string' => ':Attribute phải là một chuỗi.',
                'email.lowercase' => ':Attribute phải viết thường.',
                'email.email' => ':Attribute không hợp lệ.',
                'email.max' => ':Attribute không được vượt quá 255 ký tự.',
                'email.unique' => ':Attribute đã được sử dụng.',
                'password.required' => 'Vui lòng nhập :attribute.',
                'password.confirmed' => 'Xác nhận :attribute không khớp.',
                'password.min' => ':Attribute phải chứa ít nhất :min ký tự.',
                'password.regex' => ':Attribute phải chứa ít nhất một chữ hoa, một chữ thường và một ký tự số.',
            ],
            [
                'name' => 'Tên',
                'email' => 'Địa chỉ email',
                'password' => 'Mật khẩu',
            ]

        );
        $list_role = $request->input("list_roles");
        
        //Sau khi validate xong thì thêm dữ liệu người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->roles()->attach($list_role);
        return redirect()->route("user.list")->with('status', "Thêm user thành công");
    }
    function delete($id)
    {
        //Kiểm tra thằng đấy có phải mình hay không nếu khách thì cho xoá được và ngược lại
        if (Auth::id() != $id) {
            User::where("id", $id)->delete();
            return redirect()->route("user.list")->with('status', "Xoá user thành công");
        } else {
            return redirect()->route("user.list")->with('status', "Bạn không thể xoá chính mình ra khỏi hệ thống");
        }

    }
    function action(Request $request)
    {
        //Lấy ra danh sách rồi đẩy danh sáhc đó vào trong 1 mảng
        $listCheck = $request->input("listCheck");

        //Kiểm tra tồn tại hay không
        if ($listCheck) {
            //Duyệt qua vòng lập kiểm tra xem mình có chọn chính mình không
            foreach ($listCheck as $key => $id) {
                if (Auth::id() == $id) {
                    //Nếu trùng lập tức xoá phần tử đó ra khỏi mảng
                    unset($listCheck[$key]);
                }
            }
            if (!empty($listCheck)) {
                //Lấy ra hành động của người dùng
                $act = $request->input("act");
                if ($act == "delete") {
                    User::destroy($listCheck);
                    return redirect()->route("user.list")->with('status', "Bạn đã xoá thành công");
                }
                if ($act == "restore") {
                    //Xét toàn bộ những thằng rồi kiểm tra những thằng id khôi phục nó
                    User::withTrashed()->whereIn("id", $listCheck)->restore();
                    return redirect()->route("user.list")->with('status', "Bạn đã khôi phục thành công");
                }
                if ($act == "forceDelete") {
                    User::withTrashed()->whereIn("id", $listCheck)->forceDelete();
                    return redirect()->route("user.list")->with('status', "Bạn đã xoá vĩnh viễn thành công");
                }
            } else {
                return redirect()->route("user.list")->with('status', "Bạn không thể xoá bạn ra khỏi hệ thông");
            }
        } else {
            return redirect()->route("user.list")->with('status', "Bạn cần chọn thao tác để thực thi");
        }
    }
    function edit($id)
    {
        $user = User::find($id);

        //Cái này là lấy toàn bộ danh sách ra rồi đi so sánh
        $roles = Role::all();
        //Lấy được danh sách của role ra đây -> lấy ra role_id của user đó rồi đi so sánh với thằng chính kia
        $roles_user = $user->roles()->pluck("role_id")->toArray();

        return view('admin.user.edit', compact('user', 'roles', 'roles_user'));
    }
    function update(Request $request, $id)
    {
        $user = User::find($id);
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => 'required|unique:users,email,' . $user->id,
            ],
            [
                'name.required' => 'Vui lòng nhập :attribute.',
                'name.string' => ':Attribute phải là một chuỗi.',
                'email.required' => 'Vui lòng nhập :attribute.',
                'email.string' => ':Attribute phải là một chuỗi.',
                'email.lowercase' => ':Attribute phải viết thường.',
                'email.email' => ':Attribute không hợp lệ.',
                'email.max' => ':Attribute không được vượt quá 255 ký tự.',
                'email.unique' => ':Attribute đã được sử dụng.',
                'name.max' => ':Attribute không được vượt quá 255 ký tự.',
            ],
            [
                'name' => 'Tên',
                'email' => 'Địa chỉ email',
                'password' => 'Mật khẩu',
            ]
        );

        User::where("id", $id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        //Đi cập nhật dữ liệu ở đây nè
        User::find($id)->roles()->sync($request->input("roles", []));
        return redirect()->route("user.list")->with('status', "Cập nhật user thành công");
    }
    function restore($id) {
        User::onlyTrashed()->find($id)->restore();
        return redirect()->route("user.list")->with('status', "Khôi phục thành công user");
    }
    function forceDelete($id)
    {
        User::onlyTrashed()->find($id)->forceDelete();
        return redirect()->route("user.list")->with('status', "Xoá vĩnh viễn user thành công");
    }
}
