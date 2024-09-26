<?php

// app/Http/Middleware/LogRouteMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRouteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Log the requested route
        Log::info('Requested Route:', ['uri' => $request->getRequestUri()]);

        return $next($request);
    }
}
