<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasCategoryAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isSuperadmin || $request->user()?->categoryAccess) {
            return $next($request);
        }

        abort(404);
    }
}
