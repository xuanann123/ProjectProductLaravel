<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //Lấy toàn bộ vai trò của một user ra
    function roles()
    {
        return $this->belongsToMany(Role::class, "user_role");
    }
    function hasPermission($permission)
    {
        //Duyệt qua vòng lập tính rằng thẳng user đó thì có những vai trò gì và tìm tất cả quyền của những vai trò đó qua slug
        //đi so sánh xem nó có giống tham số truyền vào hay không
        foreach ($this->roles as $role) {
            if ($role->permissions->where("slug", $permission)->count() > 0) {
                return true;
            }
            return false;
        }
    }
    //Một thằng user thì có bao nhiêu lịch sử đơn hàng
    function orders() {
        return $this->hasMany(Order::class);
    }
}
