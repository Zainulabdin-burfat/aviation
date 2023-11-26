<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class CreateControllerPermission extends Command
{
	protected $signature = 'create:permission';
	protected $description = 'Create permissions of the controllers.';

	public function __construct()
	{
		parent::__construct();
	}

	private function getControllerMethods($controllerClass)
	{
		$methods = [];
		$controllerName = Str::of(class_basename($controllerClass))->before('Controller')->snake();
		$controllerName = Str::replace('_', '.', $controllerName);

		if ($controllerName) {
			foreach ((new ReflectionClass($controllerClass))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if ($method->name !== '__construct') {
					$controllerName = Str::replace('.', '', Str::snake($controllerName));
                    $methodName = strtolower($method->name);
					$permissionName = "{$controllerName}.{$methodName}";
					$methods[] = $permissionName;
				}

                if ($method->name == 'destroy') {
					break;
				}
			}
		}

		return $methods;
	}


	public function handle()
	{
		if (!method_exists(User::class, 'hasDirectPermissionTo')) {
			$this->error("Trait 'UserPermissionTrait' not used in the User model.");
			return 1;
		}

		if (!Schema::hasTable('permissions') || !Schema::hasTable('roles')) {
			$this->error("Package tables not found. Run migrations to resolve this issue.");
			return 1;
		}

		$this->info("Creating permissions for controllers...");

		$this->line('');

		$controllers = File::files(app_path('Http/Controllers'));
		$newPermissions = 0;
		$oldPermissions = 0;
		foreach ($controllers as $controller) {
			$controllerPath = realpath($controller->getPathname());
			$controllerClass = 'App\\Http\\Controllers\\' . str_replace(
				[app_path('Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR, '.php'],
				['', '\\', ''],
				$controllerPath
			);

			if (!class_exists($controllerClass)) {
				$this->warn("Controller class not found: $controllerClass");
				continue;
			}

			$methods = $this->getControllerMethods($controllerClass);
			foreach ($methods as $method) {
				$permission = Permission::firstOrCreate(['name' => $method]);

				if ($permission->wasRecentlyCreated) {
					$this->info("Permission created: $method");
					$newPermissions++;
				} else {
					$this->warn("Permission already exists: $method");
					$oldPermissions++;
				}
			}
		}

		$this->line('');
		$this->info("$newPermissions new permissions created, $oldPermissions permissions already exist.");
		$this->info("Command executed successfully.");

		return 0;
	}
}
