<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ["order_code", "payment_method", "notes", "order_status", "user_id"];
    //Đơn hàng này thuộc về customer nào
    function user()
    {
        return $this->belongsTo(User::class);
    }
    //Đơn hàng này thì bao nhiêu sản phẩm
    function products()
    {
        return $this->belongsToMany(Product::class, "order_product")->withPivot('number_order');
    }
    //Một đơn hàng có một phương thức thanh toán một đơn hàng thì thuọc về 1 phương thức thanh toán
    function payment() {
        //Thông qua bảng nào khoá chính là gì khoá phụ là gì 
        return $this->belongsTo(Payment::class, "id","p_order_id");
    }
}
