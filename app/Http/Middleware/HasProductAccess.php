<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasProductAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isSuperadmin || $request->user()?->productAccess) {
            return $next($request);
        }

        abort(404);
    }
}
