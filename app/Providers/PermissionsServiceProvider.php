<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Console\Commands\CreateControllerPermission;

class PermissionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateControllerPermission::class,
            ]);
        }
    }

    public function boot()
    {
        $this->registerBladeDirectives();
    }

    public function registerBladeDirectives()
    {
        //Blade directives

        // @permission('user.index') @endpermission
        Blade::directive('permission', function ($permission) {
            return "<?php if(Auth::check() && (Auth::user()->hasPermissionTo($permission) || Auth::user()->hasDirectPermissionTo($permission)) ){ ?>";
        });
        Blade::directive('endpermission', function () {
            return "<?php } ?>";
        });
    }
}
