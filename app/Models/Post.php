<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['post_title', 'post_content', 'post_description', "slug", "post_status", "post_image"];
    //Một bài viết nằm vào những danh mục nào
    function cats() {
        return $this->belongsToMany(Cat::class, "post_cat");
    }
}
