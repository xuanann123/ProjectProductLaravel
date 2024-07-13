<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['product_code', 'product_name','product_image','slug','price_old','price_new', 'qty_sold', 'qty_remain', 'product_detail','product_desc','product_status'];
    //Sản phẩm này thuộc vào những danh mục nào
    function categories() {
        return $this->belongsToMany(Category::class, "category_product");
    }
    //Một sản phẩm có nhiều hình ảnh chi tiết nhưu là
    function images() {
        return $this->hasMany(Image::class);
    }
}
