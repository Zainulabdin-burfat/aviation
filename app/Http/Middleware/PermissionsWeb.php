<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionsWeb
{
    public function handle(Request $request, Closure $next)
    {
        $temp = Str::of($request->route()->getActionName())->afterLast('\\')->split('[@]');
        $permission = (string)Str::of($temp[0])->lower()->remove('controller')->append(' ' . $temp[1])->slug('.');

        if (!$request->user()->hasPermissionTo($permission)) {
            if (!$request->user()->hasDirectPermissionTo($permission)) {
                abort(403);
            }
        }

        return $next($request);
    }
}
