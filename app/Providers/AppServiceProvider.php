<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReportPolicy;
use App\Policies\SettingPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Setting::class, SettingPolicy::class);
        Gate::define('view-reports',[ReportPolicy::class,'view']);
        Gate::define('delete-product',[ProductPolicy::class,'delete']);
        Gate::define('delete-category',[CategoryPolicy::class,'delete']);
        Gate::define('manage-users',function(User $user){
            return $user->isAdmin();
        }
        );
    }
}
