<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOutController extends Controller
{
    function checkout()
    {
        return view("cilent.payment.index");
    }
    function payment(Request $request)
    {
        //Lấy ra chính thằng user đó
        $user = Auth::user();
        $request->validate([
            'fullname' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'payment-method' => 'required|in:Thanh toán tại cửa hàng,Thanh toán online',
        ], [
            "required" => "Vui lòng chọn :attribute trước khi đặt hàng",
        ], [
            "payment-method" => "Phương thức thanh toán",
            "email" => "Địa chỉ email",
            "address" => "Địa chỉ",
            "phone" => "Số điện thoại",
        ]);
        $user->update([
            "name" => $request->input("fullname"),
            "address" => $request->input("address"),
            "phone" => $request->input("phone"),
        ]);
        if ($request->input("payment-method")) {
            if ($request->input("payment-method") == "Thanh toán online") {
                return view("cilent.vnpay.index");
            } else {
                $order_code = rand(1, 10000);
                //Công việc của một đơn hàng khi nhận được nè
                $dataOrder = [
                    "order_code" => $order_code,
                    "payment_method" => $request->input("payment-method"),
                    "order_status" => "Chưa xác nhận",
                    "user_id" => Auth::user()->id
                ];
                //Tiếp tục lưu chi tiết của một đơn hàng ra sao
                $order = Order::create($dataOrder);
                //Đi lưu thông tin của đơn hàng của khách hàng này vào db
                // return $dataOrder;
                if ($order) {
                    //Bắt đầu đi thêm chi tiết sản phẩm của từng đơn hàng này
                    $shopping = Cart::content();
                    //Ở đaay thì gửi email cho mấy thằng kia đơn hàng của nó gồm những cái này và thanh toán thành công
                    foreach ($shopping as $key => $value) {
                        $order->products()->attach($value->id, ["number_order" => $value->qty]);
                    }
                    //Ok rồi đó
                }
                //Xoá toàn bộ giỏ hàng
                Cart::destroy();
                return redirect()->route("user.order");
            }
        }
    }
    function createPayment(Request $request)
    {
        //Tài khoản số thẻ tên chủ thẻ và ngày phát hành 
        //Số thẻ: 	9704198526191432198
        //Tên chủ thẻ: 	NGUYEN VAN A
        //Ngày phát hành: 	07/15
        //Mã otp: 	123456
        $total_money = Cart::total();
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route("vnpay.return");
        $vnp_TmnCode = "32PAXT4M";//Mã website tại VNPAY 
        $vnp_HashSecret = "118N9T5SHZ51YZ4TSPED85NN0LIQZ33V"; //Chuỗi bí mật
        //Phần này tí nữa lưu vào bảng order lưu order_code
        $vnp_TxnRef = rand(1, 10000); //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $total_money; // Số tiền thanh toán
        $vnp_Locale = $request->input("language"); //Ngôn ngữ chuyển hướng thanh toán
        $vnp_BankCode = $request->input("bankCode"); //Mã phương thức thanh toán
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Noi dung thanh toan don hang " . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        //Cho nó chuỷen sang VNP giao diện thanh toán
        header('Location: ' . $vnp_Url);
        die();
    }
    function vnpayReturn(Request $request)
    {
        if ($request->input("vnp_ResponseCode") == '00') {
            $vnpayData = $request->all();
            //Thêm dữ liệu vào bảng order trong bảng order gồm những trường order_code
            $dataOrder = [
                "order_code" => $request->input("vnp_TxnRef"),
                "payment_method" => "Thanh toán online " . $request->input("vnp_BankCode"),
                "order_status" => "Chưa xác nhận",
                "user_id" => Auth::user()->id
            ];
            //Đi lưu thông tin của đơn hàng của khách hàng này vào db
            // return $dataOrder;
            $order = Order::create($dataOrder);
            if ($order) {
                //Bắt đầu đi thêm chi tiết sản phẩm của từng đơn hàng này
                $shopping = Cart::content();
                //Ở đaay thì gửi email cho mấy thằng kia đơn hàng của nó gồm những cái này và thanh toán thành công
                foreach ($shopping as $key => $value) {
                    $order->products()->attach($value->id, ["number_order" => $value->qty]);
                }
                //Thêm dữ liệu vào bảng chi tiết đơn hàng thành công
                $dataPayment = [
                    "p_order_id" => $order->id,
                    "p_order_code" => $request->input("vnp_TxnRef"),
                    "p_user_id" => Auth::user()->id,
                    'p_money' => Cart::total(),
                    "note" => $request->input("vnp_OrderInfo"),
                    "p_vnp_response_code" => $request->input("vnp_ResponseCode"),
                    "p_code_vnp" => $request->input("vnp_TransactionNo"),
                    "p_code_bank" => $request->input("vnp_BankCode"),
                    "p_time" => date("Y-m-d H:i", strtotime($request->input("vnp_PayDate"))),
                ];
                //Thêm dữ liệu vào customer là khác hàng nào
                //Cuối cùng thì đi lưu dữ liệu của $dataPayment
                Payment::create($dataPayment);
            }
            //Xoá toàn bộ giỏ hàng
            Cart::destroy();
            //Rồi gửi dữ liệu qua phần return
            return view("cilent.vnpay.vnp_return", compact("vnpayData"));
        }
    }
    function order()
    {
        //Show thông tin user này ra
        $user = Auth::user();
        //Danh sách đơn hàng khi thằng user này mua hàng
        return view("cilent.order.list", compact("user"));

    }
    function detail(Order $order)
    {
        return view("cilent.order.detail", compact("order"));
    }
    function delete(Order $order)
    {
        //Huỷ đơn hàng này đi
        $order->update([
            "order_status" => "Bị huỷ"
        ]);
        return redirect()->route("user.order")->with("status", "Huỷ đơn hàng thành công");
    }
}
