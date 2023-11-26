<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait UserPermissionTrait
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasPermissionTo($permission)
    {
        foreach ($this->roles as $role) {
            $permissions = $role->permissions->pluck('name')->toArray();
            if (in_array($permission, $permissions)) {
                return true;
            }
        }

        return false;
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    public function hasDirectPermissionTo($permission)
    {
        foreach ($this->permissions->toArray() as $permissionName) {
            if ($permission == $permissionName['name']) {
                return true;
            }
        }

        return false;
    }

    public function hasRole($role)
    {
        foreach ($this->roles->toArray() as $roleName) {
            if (strtolower($roleName['name']) == strtolower($role)) {
                return true;
            }
        }
        return false;
    }

    public function getRoleNames()
    {
        return $this->roles->pluck('name')->toArray();
    }

    public function getDirectPermissions()
    {
        return $this->permissions->pluck('name')->toArray();
    }
}
