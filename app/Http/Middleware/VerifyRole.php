<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyRole
{
    public function handle (Request $request, Closure $next, String $role=null) {
        $user = Auth::user();

        if (!$role || $user->hasRole($role)) {
            return $next($request);
        }

        App:
        abort(405, 'Access Denied');
    }
}
