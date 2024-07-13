<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    function __construct()
    {
        session(['module_active' => "order"]);
    }
    function index(Request $request)
    {
       
     

        //Lấy ra những urlWithFullQuery
        $status = $request->input("status");
        $keyword = "";
        $list_act = [
            "delivery_successful" => "Giao hàng thành công",
            "shipping" => "Đang vận chuyển",
            "pending" => "Chờ xét duyệt",
            "delete" => "Huỷ đơn",
        ];
        if ($request->input("keyword")) {
            $keyword = $request->input("keyword");
        }
        if ($status == "delivery_successful") {
            $list_act = [
                "shipping" => "Đang vận chuyển",
                "pending" => "Chờ xét duyệt",
                "delete" => "Huỷ đơn",
            ];

            $orders = Order::where("order_status", "Giao hàng thành công")->where("order_code", "like", "%$keyword%")->simplePaginate(10);
            //lấy ra số lượng đơn hàng của sản phẩm đó và trừ đi
        } else if ($status == "shipping") {
            $list_act = [
                "delivery_successful" => "Giao hàng thành công",
                "pending" => "Chờ xét duyệt",
                "delete" => "Huỷ đơn",
            ];
            $orders = Order::where("order_status", "Đang vận chuyển")->where("order_code", "like", "%$keyword%")->simplePaginate(10);
        } else if ($status == "pending") {
            $list_act = [
                "delivery_successful" => "Giao hàng thành công",
                "shipping" => "Đang vận chuyển",
                "delete" => "Huỷ đơn",
            ];
            $orders = Order::where("order_status", "Chưa xác nhận")->where("order_code", "like", "%$keyword%")->simplePaginate(10);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "forceDelete" => "Xoá vĩnh viễn",
            ];
            $orders = Order::onlyTrashed()->where("order_code", "like", "%$keyword%")->simplePaginate(10);
        } else {
            $orders = Order::where("order_code", "like", "%$keyword%")->simplePaginate(10);
        }
        $count_active_order = Order::all()->count();
        $count_delivery_successful_order = Order::where("order_status", "Giao hàng thành công")->count();
        $count_shipping_order = Order::where("order_status", "Đang vận chuyển")->count();
        $count_pending_order = Order::where("order_status", "Chưa xác nhận")->count();
        $count_trashed_order = Order::onlyTrashed()->count();
        //Lấy ra $count của mỗi trạng thái
        $count = [$count_active_order, $count_delivery_successful_order, $count_shipping_order, $count_pending_order, $count_trashed_order];
        //Tính doanh số
        $total_all_product = 0;
        $order_products = Order::where("order_status", "Giao hàng thành công")->with("products")->get();
        //Duyệt qua 2 vòng lậy rồi để lấy ra
        foreach ($order_products as $products) {
            //Đi thẳng vào thằng mảng products
            foreach ($products->products as $product) {
                $total_all_product += ($product->price_new * $product->pivot->number_order);
            }
        }
        return view("admin.order.list", compact("orders", "count", "list_act", "total_all_product"));
    }
    function edit(Request $request, Order $order)
    {
        return view("admin.order.edit", compact("order"));
    }
    function update(Request $request, Order $order)
    {
        $order_code = $request->input("order_code");
        $request->validate(
            [
                // 'order_code' => ['required', 'string', 'max:255'],
                'customer_name' => ['required', 'string', 'max:300'],
                'customer_phone' => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10",
                "customer_address" => ['required', 'string', 'max:300'],
                // "payment_method" => ['required'],
                "order_status" => ['required'],
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
                // "order_code" => "Mã đơn hàng",
                "customer_name" => "Tên khách hàng",
                "customer_phone" => "Số điện thoại",
                "customer_email" => "Địa chỉ email",
                "customer_address" => "Địa chỉ nhận hàng",
                // "payment_method" => "Hình thức thanh toán",
                "order_status" => "Trạng thái đơn hàng",
            ]
        );
        //Cập nhật trạng thái của đơn hàng
        $order->update([
            "order_status" => $request->input("order_status"),
        ]);
        //Cập nhật đơn hàng khi mua thành công
        if($order->order_status == "Đang vận chuyển") {
            //Lấy ra số lượng sản phẩm của từng products trong đơn hàng
         //Trong đơn hàng này ví dụ như có 2 sản phẩm đi duyệt qua từng sản phẩm 1 -> lấy số lượng của từng sản phẩm đó ra
         //Tiếp đó là đến cập nhật luôn số lượng cũng như bán được bao nhiêu và tồn kho được bao nhiêu được bao nhiêu
         //Đi update luôn -> qua lần duyệt thứ 2 vẫn còn 1 sản phẩm nữa thì lại đi tiếp tục duyệt -> cũng làm tương tư như trên là lúc này thì reset mấy cái tính toán về 0
            foreach ($order->products()->get() as $product) {
                //Đơn hàng cũng như là số lượng sản phẩm của mỗi đơn hàng sau khi tính toàn sau sẽ reset vể 0 để đảm bảo cho thằng sau
                $count_this_order_product = 0;
                $qty_sold_old = 0;
                //Số lượng sản phẩm tồn kho
                $qty_remain_old = 0;
                //Lấy được số lượng sản phẩm trong một đơn hàng
                $count_this_order_product += $product->pivot->number_order;
                $qty_sold_old = $product->qty_sold;
                $qty_remain_old = $product->qty_remain;
                //Từ lúc đó đi cập nhật cho bảng product
                $product->update([
                    "qty_sold" => $qty_sold_old + $count_this_order_product,
                    "qty_remain" => $qty_remain_old - $count_this_order_product,
                ]);
            }   
        }
        if ($order->order_status == "Bị huỷ") {
            //Lấy ra số lượng sản phẩm của từng products trong đơn hàng
            //Trong đơn hàng này ví dụ như có 2 sản phẩm đi duyệt qua từng sản phẩm 1 -> lấy số lượng của từng sản phẩm đó ra
            //Tiếp đó là đến cập nhật luôn số lượng cũng như bán được bao nhiêu và tồn kho được bao nhiêu được bao nhiêu
            //Đi update luôn -> qua lần duyệt thứ 2 vẫn còn 1 sản phẩm nữa thì lại đi tiếp tục duyệt -> cũng làm tương tư như trên là lúc này thì reset mấy cái tính toán về 0
            foreach ($order->products()->get() as $product) {
                //Đơn hàng cũng như là số lượng sản phẩm của mỗi đơn hàng sau khi tính toàn sau sẽ reset vể 0 để đảm bảo cho thằng sau
                $count_this_order_product = 0;
                $qty_sold_old = 0;
                //Số lượng sản phẩm tồn kho
                $qty_remain_old = 0;
                //Lấy được số lượng sản phẩm trong một đơn hàng
                $count_this_order_product += $product->pivot->number_order;
                $qty_sold_old = $product->qty_sold;
                $qty_remain_old = $product->qty_remain;
                //Từ lúc đó đi cập nhật cho bảng product
                $product->update([
                    "qty_sold" => $qty_sold_old - $count_this_order_product,
                    "qty_remain" => $qty_remain_old + $count_this_order_product,
                ]);
            }
        }
        //Cập nhật đơn hàng bị thất bại
        

        
        //Cập nhật mối quan hệ 1 nhiều
        $order->customer()->update([
            "customer_name" => $request->input("customer_name"),
            "customer_phone" => $request->input("customer_phone"),
            "customer_address" => $request->input("customer_address"),
        ]);


        //Được roài đi update module thoãi

        return redirect()->route("order.list")->with("status", "Cập nhật đơn hàng thành công");

    }
    function delete(Request $request, Order $order)
    {
        $order->update([
            "order_status" => "Bị huỷ",
        ]);
        $order->delete();
        return redirect()->route("order.list")->with("status", "Xoá đơn hàng thành công");

    }
    function restore($id)
    {
        $order = Order::withTrashed()->find($id);
        $order->update([
            "order_status" => "Chưa xác nhận",
        ]);
        $order->restore();
        return redirect()->route("order.list")->with("status", "Khôi phục đơn hàng $order->order_code thàn công");
    }
    function deleteForce($id)
    {
        $order = Order::withTrashed()->find($id);
        $order->forceDelete();
        return redirect()->route("order.list")->with("status", "Xoá vĩnh viên đơn hàng trên thành công");
    }
    function action(Request $request)
    {
        $act = $request->input("act");
        $listCheck = $request->input("listCheck");
        if ($act != "") {
            if ($listCheck) {
                if ($act == "delete") {
                    //Lấy ra được danh sách đơn hàng
                    $list_order = Order::whereIn("id", $listCheck)->get();
                    //Truy xuất vào danh sách đơn hàng để lấy 1 đơn hàng có bao nhiêu sản phẩm
                    //Lấy ra số lượng sản phẩm của từng products trong đơn hàng
                    foreach ($list_order as $order) {
                        //Tiếp tục duyệt tiếp xem một Một đơn hàng có bao nhiêu sản phẩm
                        foreach ($order->products()->get() as $product) {
                            $count_this_order_product = 0;
                            //Số lượng sản phẩm bán được
                            $qty_sold_old = 0;
                            //Số lượng sản phẩm tồn kho
                            $qty_remain_old = 0;
                            //Lấy được số lượng sản phẩm trong một đơn hàng
                            $count_this_order_product += $product->pivot->number_order;
                            $qty_sold_old = $product->qty_sold;
                            $qty_remain_old = $product->qty_remain;
                            //Từ lúc đó đi cập nhật cho bảng product
                            $product->update([
                                "qty_sold" => $qty_sold_old - $count_this_order_product,
                                "qty_remain" => $qty_remain_old + $count_this_order_product,
                            ]);
                        }
                    }
                    Order::destroy($listCheck);
                    return redirect()->route("order.list")->with("status", "Bạn đã chuyển toàn bộ bản ghi vào thùng rác");
                } else if ($act == "delivery_successful") {
                    Order::whereIn("id", $listCheck)->update(
                        [
                            "order_status" => "Giao hàng thành công",
                        ]
                    );
               
                    //Lấy ra được danh sách đơn hàng
                    $list_order = Order::whereIn("id", $listCheck)->get();
                    //Truy xuất vào danh sách đơn hàng để lấy 1 đơn hàng có bao nhiêu sản phẩm
                    //Lấy ra số lượng sản phẩm của từng products trong đơn hàng
                    

                    foreach ($list_order as $order) {
                        //Tiếp tục duyệt tiếp xem một Một đơn hàng có bao nhiêu sản phẩm
                        foreach($order->products()->get() as $product) {
                            $count_this_order_product = 0;
                            //Số lượng sản phẩm bán được
                            $qty_sold_old = 0;
                            //Số lượng sản phẩm tồn kho
                            $qty_remain_old = 0;
                            //Lấy được số lượng sản phẩm trong một đơn hàng
                            $count_this_order_product += $product->pivot->number_order;
                            $qty_sold_old = $product->qty_sold;
                            $qty_remain_old = $product->qty_remain;
                            //Từ lúc đó đi cập nhật cho bảng product
                            $product->update([
                                "qty_sold" => $qty_sold_old + $count_this_order_product,
                                "qty_remain" => $qty_remain_old - $count_this_order_product,
                            ]);
                        }
                    }
                    
                    

                    return redirect()->route("order.list")->with("status", "Bạn đã chuyển toàn bộ bản ghi vào giao hàng thành công");
                } else if ($act == "shipping") {
                    Order::whereIn("id", $listCheck)->update(
                        [
                            "order_status" => "Đang vận chuyển",
                        ]
                    );
                    return redirect()->route("order.list")->with("status", "Bạn đã chuyển toàn bộ bản ghi vào đang vận chuyển");
                } else if ($act == "pending") {
                    Order::whereIn("id", $listCheck)->update(
                        [
                            "order_status" => "Chưa xác nhận",
                        ]
                    );
                    return redirect()->route("order.list")->with("status", "Bạn đã chuyển toàn bộ bản ghi vào giao hàng thành công");
                } else if ($act == "restore") {
                    Order::whereIn("id", $listCheck)->restore();
                    return redirect()->route("order.list")->with("status", "Khôi phục toàn bộ bản ghi thành công");
                } else if ($act == "forceDelete") {
                    Order::whereIn("id", $listCheck)->forceDelete();
                    
                    return redirect()->route("order.list")->with("status", "Xoá vĩnh viễn bản ghi thành công");
                }
            } else {
                return redirect()->route("order.list")->with("status", "Hãy chọn bản ghi để thao tác");

            }
        } else {
            return redirect()->route("order.list")->with("status", "Cần chọn tác vụ để thao tác và thực hiện");

        }
    }
    function detail(Request $request, Order $order) {

        $products = $order->products()->get();
        //Lấy được số lượng sản phẩm của mỗi thằng ra được
        return view("admin.order.detail", compact("products", "order"));
    }
}
