<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions with user context if available
            $context = [];

            if (Auth::check()) {
                $user = Auth::user();
                if ($user) {
                    /** @var \App\Models\User $user */
                    $context['user_id'] = Auth::id();
                    $context['user_email'] = $user->email;
                }
            }

            $context['url'] = request()->fullUrl();
            $context['method'] = request()->method();
            $context['ip'] = request()->ip();
            $context['user_agent'] = request()->userAgent();

            Log::error($e->getMessage(), array_merge($context, [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]));
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error' => 'authentication_required',
                ], 401);
            }
        });
    }
}
