<?php
// app/Http/Middleware/RedirectIfNotAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}