<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'description'];
    //Roled dang có perrmission nào
    function permissions() {
        //mối quan hệ nhiều nhiều permission thông qua bảng role_permission
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
