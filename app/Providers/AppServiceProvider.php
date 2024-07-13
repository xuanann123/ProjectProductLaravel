<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tạo Gate bằng vòng lập
        $permissions = Permission::all();
        //Duyệt qua vòng lập và đẩy giá trị slug vào
        foreach ($permissions as $permission) {
            Gate::define($permission->slug , function (User $user) use ($permission){
            //Trả về true thì được thực hiện -> còn false thì abort(403);
            return $user->hasPermission($permission->slug); 
        });
        }
        
    }
}
