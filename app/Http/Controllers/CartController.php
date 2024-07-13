<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function show()
    {
        return view("cilent.cart.list");
    }
    function add(Product $product, Request $request)
    {
        // return $request->all();
        if($request->input("num-order")) {
            $number_order = $request->input("num-order");
        } else {
            $number_order = 1;
        }
        // return Cart::destroy();
        Cart::add([
            'id' => $product->id,
            'name' => $product->product_name,
            'qty' => $number_order,
            'price' => $product->price_new?$product->price_new : $product->price_old,
            "options" => ["image"=> $product->product_image, "code" => $product->product_code, "qty_max" => $product->qty_remain],
        ]);
        //Xoá sản phẩm thông qua rowId
        // return Cart::content();
        if($request->input("buy_now")) {
            return redirect()->route("cart.checkout");
        }
        return redirect()->route("/");
    }
    function delete($rowId) {
        Cart::remove($rowId);
        return redirect()->route("cart.show");
    }
    function destroy() {
        Cart::destroy();
        return redirect()->route("cart.show");
    }
    function update(Request $request) {
        $data = $request->get("qty");
        foreach ($data as $k => $v) {
            Cart::update($k, $v);
        }
        return redirect()->route("cart.show");

    }
    function buy(Product $product) {
        //Đi thêm sản phẩm này vào giỏ hàng luôn và chuyển nó đến thằng checkout
        Cart::add([
            'id' => $product->id,
            'name' => $product->product_name,
            'qty' => 1,
            'price' => $product->price_new ? $product->price_new : $product->price_old,
            "options" => ["image" => $product->product_image, "code" => $product->product_code, "qty_max" => $product->qty_remain],
        ]);
        //Chuyển thằng này đến thằng checkout luôn
        return redirect()->route("cart.checkout");
    }
    
}

