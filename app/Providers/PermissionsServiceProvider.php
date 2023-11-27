<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use CreateControllerPermission;
use App\Models\Permission;

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

        // $this->registerPassportServices();
    }

    public function registerPassportServices()
    {
        $permission_all = Permission::all();


        $permissions = ['-'];

        if ($permission_all) {
            unset($permissions);
            foreach ($permission_all as $permission) {
                $permissions[$permission['name']] = $permission['name'];
            }
        }

        // Passport::tokensCan($permissions);

        // Passport::routes();

        // Passport::tokensExpireIn(config('customrbac.tokensExpireIn'));
        // Passport::refreshTokensExpireIn(config('customrbac.refreshTokensExpireIn'));
        // Passport::personalAccessTokensExpireIn(config('customrbac.personalAccessTokensExpireIn'));
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

        // @role('admin') @endrole
        Blade::directive('role', function ($role) {
            return "<?php if(Auth::check() && Auth::user()->hasRole($role) ){ ?>";
        });
        Blade::directive('endrole', function () {
            return "<?php } ?>";
        });
    }
}
