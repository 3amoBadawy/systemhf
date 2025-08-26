<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class FileSystemHealthService
{
    /**
     * Check file system permissions
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkFiles(): array
    {
        $paths = self::getRequiredPaths();
        $results = [];

        foreach ($paths as $label => $absolutePath) {
            $results[$label] = self::checkPathStatus($absolutePath);
        }

        return $results;
    }

    /**
     * Get required paths list
     */
    private static function getRequiredPaths(): array
    {
        return [
            'storage' => storage_path(),
            'storage/logs' => storage_path('logs'),
            'storage/framework' => storage_path('framework'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];
    }

    /**
     * Check path status
     */
    private static function checkPathStatus(string $absolutePath): array
    {
        $exists = File::exists($absolutePath);
        $isDir = File::isDirectory($absolutePath);
        $writable = self::checkPathWritable($absolutePath, $exists, $isDir);

        if ($exists && $isDir && $writable) {
            return [
                'status' => 'working',
                'message' => 'المجلد موجود وقابل للكتابة',
            ];
        }

        if ($exists && $isDir && ! $writable) {
            return [
                'status' => 'warning',
                'message' => 'المجلد غير قابل للكتابة',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'المجلد مفقود',
        ];
    }

    /**
     * Check if path is writable
     */
    private static function checkPathWritable(string $absolutePath, bool $exists, bool $isDir): bool
    {
        if (! $exists || ! $isDir) {
            return false;
        }

        try {
            return is_writable($absolutePath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if all required directories have proper permissions
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkPermissions(): array
    {
        $paths = [
            'storage' => storage_path(),
            'storage/logs' => storage_path('logs'),
            'storage/framework' => storage_path('framework'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $results = [];
        foreach ($paths as $label => $absolutePath) {
            $results[$label] = self::checkPathPermissions($absolutePath);
        }

        return $results;
    }

    /**
     * Check path permissions
     */
    private static function checkPathPermissions(string $absolutePath): array
    {
        $exists = File::exists($absolutePath);
        $isDir = File::isDirectory($absolutePath);
        $writable = self::checkPathWritable($absolutePath, $exists, $isDir);

        if ($exists && $isDir && $writable) {
            return [
                'status' => 'working',
                'message' => 'المجلد موجود وقابل للكتابة',
            ];
        }

        if ($exists && $isDir && ! $writable) {
            return [
                'status' => 'warning',
                'message' => 'المجلد غير قابل للكتابة',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'المجلد مفقود',
        ];
    }

    /**
     * Check if all expected migrations exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkMigrations(): array
    {
        $migrationsPath = database_path('migrations');
        $migrationFiles = File::glob($migrationsPath.'/*.php');

        if (empty($migrationFiles)) {
            return [
                'migrations' => [
                    'status' => 'error',
                    'message' => 'لا توجد ملفات هجرة',
                ],
            ];
        }

        return [
            'migrations' => [
                'status' => 'working',
                'message' => 'تم العثور على '.count($migrationFiles).' ملف هجرة',
            ],
        ];
    }
}
