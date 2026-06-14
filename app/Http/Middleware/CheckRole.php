<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle($request, Closure $next, $roleName)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->role->role_name !== $roleName) {
            abort(403);
        }

        return $next($request);
    }}