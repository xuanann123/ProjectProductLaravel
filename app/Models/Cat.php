<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;
    protected $fillable = ["post_cat_name", "post_cat_status", "slug", "cat_parent_id"];
    function posts()
    {
        return $this->belongsToMany(Post::class, "post_cat");
    }
    function categoryChild()
    {
        return $this->hasMany(Cat::class, "cat_parent_id");
    }
}
