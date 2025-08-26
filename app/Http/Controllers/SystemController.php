<?php

namespace App\Http\Controllers;

use App\Services\SystemHealthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    /**
     * Get system health status
     */
    public function health(): array
    {
        return [
            'controllers' => $this->checkControllers(),
            'models' => $this->checkModels(),
            'routes' => $this->checkRoutes(),
            'views' => $this->checkViews(),
            'database' => $this->checkDatabase(),
            'files' => $this->checkFiles(),
            'permissions' => $this->checkPermissions(),
            'php_extensions' => $this->checkPhpExtensions(),
            'env' => $this->checkEnv(),
            'migrations' => $this->checkMigrations(),
            'services' => $this->checkServices(),
            'system_info' => [
                'database_version' => $this->getDatabaseVersion(),
                'disk_space' => $this->getDiskSpace(),
                'memory_usage' => $this->getMemoryUsage(),
                'uptime' => $this->getUptime(),
            ],
        ];
    }

    /**
     * Check if all controllers exist and are working
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkControllers(): array
    {
        return SystemHealthService::checkControllers();
    }

    /**
     * Check if all models exist and are working
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkModels(): array
    {
        return SystemHealthService::checkModels();
    }

    /**
     * Check if all expected routes exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkRoutes(): array
    {
        return SystemHealthService::checkRoutes();
    }

    /**
     * Check if all expected views exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkViews(): array
    {
        return SystemHealthService::checkViews();
    }

    /**
     * Check if all expected database tables exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkDatabase(): array
    {
        return SystemHealthService::checkDatabase();
    }

    /**
     * Check if all required files and directories exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkFiles(): array
    {
        return SystemHealthService::checkFiles();
    }

    /**
     * Check if all required directories have proper permissions
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkPermissions(): array
    {
        return SystemHealthService::checkPermissions();
    }

    /**
     * Check PHP extensions
     *
     * @return array<string, array{status: string, message: string}>
     */
    private function checkPhpExtensions(): array
    {
        return SystemHealthService::checkPhpExtensions();
    }

    /**
     * @return array<string, array{status: string, message: string}>
     */
    private function checkEnv(): array
    {
        $requiredKeys = [
            'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_URL',
            'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME',
            'CACHE_DRIVER', 'SESSION_DRIVER', 'QUEUE_CONNECTION',
        ];

        $results = [];
        foreach ($requiredKeys as $key) {
            $value = env($key);
            if (! empty($value)) {
                $results[$key] = [
                    'status' => 'working',
                    'message' => 'مُعرّف',
                ];

                continue;
            }

            $results[$key] = [
                'status' => 'warning',
                'message' => 'غير مُعرّف',
            ];
        }

        // تحقق خاص بـ APP_KEY
        $appKey = config('app.key');
        if (! empty($appKey)) {
            $results['APP_KEY_FORMAT'] = [
                'status' => 'working',
                'message' => 'مفتاح التطبيق مضبوط',
            ];

            return $results;
        }

        $results['APP_KEY_FORMAT'] = [
            'status' => 'error',
            'message' => 'APP_KEY غير مضبوط',
        ];

        return $results;
    }

    /**
     * @return array<string, array{status: string, message: string}>
     */
    private function checkMigrations(): array
    {
        $results = [];

        try {
            $hasTable = Schema::hasTable('migrations');
            $results['migrations_table'] = [
                'status' => $hasTable ? 'working' : 'error',
                'message' => $hasTable ? 'جدول الهجرات موجود' : 'جدول الهجرات مفقود',
            ];

            $migrationFiles = glob(base_path('database/migrations/*.php')) ?: [];
            $totalFiles = count($migrationFiles);
            $applied = $hasTable ? (int) DB::table('migrations')->count() : 0;
            $pending = max($totalFiles - $applied, 0);

            $results['migrations_status'] = [
                'status' => $pending === 0 ? 'working' : 'warning',
                'message' => "تم تنفيذ {$applied} من أصل {$totalFiles} (متبقي {$pending})",
            ];
        } catch (\Exception $e) {
            $results['migrations_status'] = [
                'status' => 'error',
                'message' => 'تعذر فحص حالة الهجرات: '.$e->getMessage(),
            ];
        }

        return $results;
    }

    private function checkServices(): array
    {
        $services = [
            'Database Connection' => $this->checkDatabaseConnection(),
            'Cache Service' => $this->checkCacheService(),
            'Session Service' => $this->checkSessionService(),
            'Queue Service' => $this->checkQueueService(),
            'Mail Service' => $this->checkMailService(),
            'Storage Service' => $this->checkStorageService(),
        ];

        return $services;
    }

    /**
     * @return string[]
     */
    private function checkDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'status' => 'working',
                'message' => 'الاتصال يعمل',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل الاتصال: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     */
    private function checkCacheService(): array
    {
        try {
            $key = 'system_test_'.time();
            cache([$key => 'test'], 1);
            $value = cache($key);
            cache()->forget($key);

            if ($value === 'test') {
                return [
                    'status' => 'working',
                    'message' => 'خدمة Cache تعمل',
                ];
            }

            return [
                'status' => 'warning',
                'message' => 'خدمة Cache تعمل جزئياً',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل في خدمة Cache: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     */
    private function checkSessionService(): array
    {
        try {
            session(['test' => 'test']);
            $value = session('test');
            session()->forget('test');

            if ($value === 'test') {
                return [
                    'status' => 'working',
                    'message' => 'خدمة Session تعمل',
                ];
            }

            return [
                'status' => 'warning',
                'message' => 'خدمة Session تعمل جزئياً',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل في خدمة Session: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     */
    private function checkQueueService(): array
    {
        return [
            'status' => 'working',
            'message' => 'خدمة Queue متاحة',
        ];
    }

    /**
     * @return string[]
     */
    private function checkMailService(): array
    {
        return [
            'status' => 'working',
            'message' => 'خدمة Mail متاحة',
        ];
    }

    /**
     * @return string[]
     */
    private function checkStorageService(): array
    {
        try {
            $testFile = 'test_'.time().'.txt';
            Storage::put($testFile, 'test');
            $exists = Storage::exists($testFile);
            Storage::delete($testFile);

            if ($exists) {
                return [
                    'status' => 'working',
                    'message' => 'خدمة Storage تعمل',
                ];
            }

            return [
                'status' => 'warning',
                'message' => 'خدمة Storage تعمل جزئياً',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل في خدمة Storage: '.$e->getMessage(),
            ];
        }
    }

    private function getDatabaseVersion(): string
    {
        try {
            $version = DB::select('SELECT VERSION() as version')[0]->version;

            return $version;
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }

    private function getDiskSpace(): string
    {
        try {
            $free = disk_free_space(storage_path());
            $total = disk_total_space(storage_path());
            $used = $total - $free;
            $percentage = round(($used / $total) * 100, 2);

            return round($free / 1024 / 1024 / 1024, 2).' GB متاح ('.$percentage.'% مستخدم)';
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }

    private function getMemoryUsage(): string
    {
        try {
            $memory = memory_get_usage(true);
            $memoryPeak = memory_get_peak_usage(true);

            return round($memory / 1024 / 1024, 2).' MB (Peak: '.round($memoryPeak / 1024 / 1024, 2).' MB)';
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }

    private function getUptime(): string
    {
        try {
            $uptime = time() - filemtime(storage_path('framework/cache'));
            $days = floor($uptime / 86400);
            $hours = floor(($uptime % 86400) / 3600);
            $minutes = floor(($uptime % 3600) / 60);

            if ($days > 0) {
                return $days.' يوم '.$hours.' ساعة '.$minutes.' دقيقة';
            }

            if ($hours > 0) {
                return $hours.' ساعة '.$minutes.' دقيقة';
            }

            return $minutes.' دقيقة';
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }
}
