<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //Active menu
    function __construct()
    {
        session(['module_active' => "dashboard"]);
    }
    //Show info dashboard
    function index()
    {
        $orders = Order::simplePaginate(10);
        $list_order = Order::all();
        $list = [];
        //Lấy được số lượng sản phẩm tồn khoản
        //Gọi đến Prodcut để làm việc
        $total_quantity_remain = 0;
        $total_quantity_sold = 0;
        $total_product_sales = 0;
        $status_order_success = 0;
        $status_order_shipping = 0;
        $status_order_pending = 0;


        $products = Product::all();
        foreach ($products as $product) {
            $total_quantity_remain += $product->qty_remain;
            $total_quantity_sold += $product->qty_sold;

        }
        //Tính doanh số của toàn bộ đơn hàng khi 
        foreach ($list_order as $order) {
            foreach ($order->products()->get() as $product) {
                $total_product_sales += ($product->pivot->number_order * $product->price_new);
            }
        }
        //Tính cái thằng số lượng đơn hàng được giao hàng thành công
        foreach ($list_order as $order) {
            $list[] = $order;
            if ($order->order_status == "Giao hàng thành công") {
                $status_order_success++;
            }
            if ($order->order_status == "Đang vận chuyển") {
                $status_order_shipping++;
            }
            if ($order->order_status == "Chưa xác nhận") {
                $status_order_pending++;
            }
        }
        $status_order = [$status_order_success, $status_order_shipping, $status_order_pending];
        //Lấy được sản status_order_shipping bán được
        //Lấy được tổng số tiền
        //Lấy được doanh số của các đơn hàng
        //ĐƠN HÀNG THÀNH CÔNG số lượng sản phẩm thành công
        // $qty_remain = get_total_quantity_remain();
        // $qty_sold = get_total_quantity_sold();
        // $order_success = get_order_success();
        // $order_pending = get_order_pending();
        // $order_shipping = get_order_shipping();
        // $total_product_sales = get_total_product_sales();

        //Làm công việc gửi dữ liệu sang bên kia thoaii
        return view('admin.dashboard', compact("orders", "total_quantity_remain", "total_quantity_sold", "total_product_sales", "status_order"));
    }
}
