<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    //liên kết mối quan hệ nè nè hahhaa
    protected $fillable = ['product_id', 'image_path'];
    function product()
    {
        
        return $this->belongsTo(Product::class);
    }
}
