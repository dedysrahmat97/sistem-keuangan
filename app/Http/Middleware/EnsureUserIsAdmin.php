<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || (! $request->user()->hasRole('admin') && ! $request->user()->hasRole('it'))) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}