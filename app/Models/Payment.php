<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['p_order_id', 'p_order_code', 'p_user_id',"p_money",'note','p_vnp_response_code','p_code_vnp','p_code_bank','p_time'];

}
