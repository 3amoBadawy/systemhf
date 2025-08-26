<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ErrorLogger
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // تسجيل الأخطاء
        if ($response->getStatusCode() >= 400) {
            Log::warning('HTTP Error', [
                'status' => $response->getStatusCode(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'headers' => $request->headers->all(),
                'input' => $request->all(),
            ]);
        }

        return $response;
    }
}
