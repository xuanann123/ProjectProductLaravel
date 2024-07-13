<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ["category_name", "category_status", "slug", "parent_id", "category_description"];
    //một danh mục có bao nhiêu sản phẩm
    function products()
    {
        return $this->belongsToMany(Product::class, "category_product");
    }
    function categoryChild()
    {
        return $this->hasMany(Category::class, "parent_id");
    }
}
