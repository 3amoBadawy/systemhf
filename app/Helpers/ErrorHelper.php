<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ErrorHelper
{
    /**
     * Log an error with context
     *
     * @param  array<string, mixed>  $context
     */
    public static function logError(string $message, array $context = []): void
    {
        $context['timestamp'] = now();
        $context['url'] = request()->fullUrl();
        $context['method'] = request()->method();

        if (Auth::check()) {
            $user = Auth::user();
            if ($user) {
                $context['user_id'] = Auth::id();
                $context['user_email'] = $user->email;
            }
        }

        Log::error($message, $context);
    }

    /**
     * Get error details by ID
     *
     * @return array<string, mixed>|null
     */
    public static function getError(string $errorId): ?array
    {
        // This would typically query a database or cache
        // For now, return null as placeholder
        return null;
    }

    /**
     * Display error message to user
     */
    public static function displayError(string $error, bool $showDetails = false): string
    {
        if ($showDetails) {
            return $error;
        }

        // Return generic error message for production
        return 'An error occurred. Please try again later.';
    }

    /**
     * Validate data against rules
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, string>  $rules
     * @return array<string, mixed>
     */
    public static function validateData(array $data, array $rules): array
    {
        $validator = validator($data, $rules);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => $validator->errors()->toArray(),
            ];
        }

        return [
            'valid' => true,
            'data' => $data,
        ];
    }

    /**
     * Format error message for display
     */
    public static function formatErrorMessage(string $message): string
    {
        return ucfirst(trim($message));
    }

    /**
     * Check if error is critical
     */
    public static function isCriticalError(string $error): bool
    {
        $criticalKeywords = [
            'database',
            'connection',
            'fatal',
            'critical',
            'emergency',
        ];

        foreach ($criticalKeywords as $keyword) {
            if (stripos($error, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}
