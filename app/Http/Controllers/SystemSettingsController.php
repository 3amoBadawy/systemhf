<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessSettingsRequest;
use App\Repositories\Contracts\BusinessSettingRepositoryInterface;
use App\Services\ConfigurationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SystemSettingsController extends Controller
{
    protected BusinessSettingRepositoryInterface $settingRepository;

    protected ConfigurationService $configService;

    public function __construct(
        BusinessSettingRepositoryInterface $settingRepository,
        ConfigurationService $configService
    ) {
        $this->settingRepository = $settingRepository;
        $this->configService = $configService;
    }

    public function index(): View|RedirectResponse
    {
        try {
            $businessSettings = $this->settingRepository->getInstance();
            $timezones = $this->settingRepository->getTimezones();
            $dateFormats = $this->settingRepository->getDateFormats();
            $timeFormats = $this->settingRepository->getTimeFormats();
            $currencies = $this->settingRepository->getCurrencies();
            $settingsByCategory = $this->configService->getEditable();

            return view('system-settings.index', compact(
                'businessSettings',
                'timezones',
                'dateFormats',
                'timeFormats',
                'currencies',
                'settingsByCategory'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحميل الإعدادات: '.$e->getMessage());
        }
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'settings' => 'required|array',
                'settings.*.key' => 'required|string',
                'settings.*.value' => 'nullable',
            ]);

            foreach ($request->input('settings') as $setting) {
                $this->configService->set($setting['key'], $setting['value']);
            }

            return back()->with('success', 'تم تحديث الإعدادات بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات: '.$e->getMessage());
        }
    }

    public function updateBusiness(BusinessSettingsRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                if ($logoFile && $logoFile instanceof \Illuminate\Http\UploadedFile) {
                    $logoPath = $logoFile->store('business/logos', 'public');
                    if ($logoPath !== false) {
                        $this->settingRepository->updateLogo($logoPath);
                    }
                }
            }

            // Update other settings
            unset($data['logo']); // Remove logo from data array
            $this->settingRepository->updateSettings($data);

            return back()->with('success', 'تم تحديث إعدادات الأعمال بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات: '.$e->getMessage());
        }
    }

    public function removeLogo(): RedirectResponse
    {
        try {
            $this->settingRepository->removeLogo();

            return back()->with('success', 'تم إزالة الشعار بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إزالة الشعار: '.$e->getMessage());
        }
    }

    public function reset(): RedirectResponse
    {
        try {
            // Reset system settings to defaults
            $this->configService->resetToDefaults();

            return back()->with('success', 'تم إعادة تعيين الإعدادات إلى القيم الافتراضية');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إعادة تعيين الإعدادات: '.$e->getMessage());
        }
    }

    public function export(): StreamedResponse
    {
        try {
            $settings = $this->configService->getAll();

            return response()->stream(function () use ($settings) {
                $handle = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fwrite($handle, "\xEF\xBB\xBF");

                // Headers
                fputcsv($handle, ['Key', 'Value', 'Type', 'Category']);

                foreach ($settings as $key => $value) {
                    fputcsv($handle, [$key, $value, 'string', 'system']);
                }

                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="system-settings.csv"',
            ]);
        } catch (\Exception $e) {
            abort(500, 'حدث خطأ أثناء تصدير الإعدادات');
        }
    }

    public function import(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'settings_file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            $file = $request->file('settings_file');
            $handle = fopen($file->getPathname(), 'r');

            // Skip header row
            fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) >= 2) {
                    $this->configService->set($data[0], $data[1]);
                }
            }

            fclose($handle);

            return back()->with('success', 'تم استيراد الإعدادات بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء استيراد الإعدادات: '.$e->getMessage());
        }
    }

    public function advanced(): View|RedirectResponse
    {
        try {
            $systemInfo = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'database_connection' => DB::connection()->getDatabaseName(),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
            ];

            return view('system-settings.advanced', compact('systemInfo'));
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحميل الصفحة المتقدمة: '.$e->getMessage());
        }
    }

    public function clearCache(): RedirectResponse
    {
        try {
            $this->configService->clearCache();

            return back()->with('success', 'تم مسح الذاكرة المؤقتة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء مسح الذاكرة المؤقتة: '.$e->getMessage());
        }
    }

    public function getCategory(string $category): View|RedirectResponse
    {
        try {
            $settings = $this->configService->getByCategory($category);

            return view('system-settings.category', compact('settings', 'category'));
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحميل الفئة: '.$e->getMessage());
        }
    }

    public function updateSingle(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'key' => 'required|string',
                'value' => 'nullable',
            ]);

            $this->configService->set($request->input('key'), $request->input('value'));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الإعداد بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الإعداد: '.$e->getMessage(),
            ], 400);
        }
    }

    public function search(Request $request): View|RedirectResponse
    {
        try {
            $query = $request->get('q', '');
            $settings = $this->configService->search($query);

            return view('system-settings.search', compact('settings', 'query'));
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء البحث: '.$e->getMessage());
        }
    }

    // Advanced system management methods
    public function restartQueue(): JsonResponse
    {
        try {
            Artisan::call('queue:restart');

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة تشغيل قائمة الانتظار بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة تشغيل قائمة الانتظار: '.$e->getMessage(),
            ], 400);
        }
    }

    public function restartCache(): JsonResponse
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة تشغيل الكاش بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة تشغيل الكاش: '.$e->getMessage(),
            ], 400);
        }
    }

    public function restartConfig(): JsonResponse
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('config:cache');

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة تشغيل التكوين بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة تشغيل التكوين: '.$e->getMessage(),
            ], 400);
        }
    }

    public function toggleMaintenance(): JsonResponse
    {
        try {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
                $message = 'تم إيقاف وضع الصيانة';
            } else {
                Artisan::call('down', ['--secret' => 'maintenance-secret']);
                $message = 'تم تفعيل وضع الصيانة';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير وضع الصيانة: '.$e->getMessage(),
            ], 400);
        }
    }

    public function optimizeDatabase(): JsonResponse
    {
        try {
            Artisan::call('migrate:status');

            return response()->json([
                'success' => true,
                'message' => 'تم فحص قاعدة البيانات بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء فحص قاعدة البيانات: '.$e->getMessage(),
            ], 400);
        }
    }

    public function clearLogs(): JsonResponse
    {
        try {
            $logFiles = Storage::disk('logs')->files();
            foreach ($logFiles as $file) {
                if (str_ends_with($file, '.log')) {
                    Storage::disk('logs')->delete($file);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم مسح السجلات بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء مسح السجلات: '.$e->getMessage(),
            ], 400);
        }
    }

    public function getMetrics(): JsonResponse
    {
        try {
            $metrics = [
                'memory_usage' => memory_get_usage(true),
                'peak_memory' => memory_get_peak_usage(true),
                'disk_free_space' => disk_free_space(storage_path()),
                'database_size' => $this->getDatabaseSize(),
                'cache_hits' => Cache::get('cache_hits', 0),
                'cache_misses' => Cache::get('cache_misses', 0),
            ];

            return response()->json([
                'success' => true,
                'data' => $metrics,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحصول على المقاييس: '.$e->getMessage(),
            ], 400);
        }
    }

    public function getActivityLogs(): View|RedirectResponse
    {
        try {
            $logs = $this->getRecentActivityLogs();

            return view('system-settings.activity-logs', compact('logs'));
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحميل سجلات النشاط: '.$e->getMessage());
        }
    }

    public function exportLogs(): StreamedResponse
    {
        try {
            $logs = $this->getRecentActivityLogs();

            return response()->stream(function () use ($logs) {
                $handle = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fwrite($handle, "\xEF\xBB\xBF");

                // Headers
                fputcsv($handle, ['Timestamp', 'Level', 'Message', 'Context']);

                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log['timestamp'] ?? '',
                        $log['level'] ?? '',
                        $log['message'] ?? '',
                        $log['context'] ?? '',
                    ]);
                }

                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="activity-logs.csv"',
            ]);
        } catch (\Exception $e) {
            abort(500, 'حدث خطأ أثناء تصدير السجلات');
        }
    }

    private function getDatabaseSize(): string
    {
        try {
            $result = DB::select('SELECT pg_size_pretty(pg_database_size(current_database())) as size');

            return $result[0]->size ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getRecentActivityLogs(): array
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (! file_exists($logFile)) {
                return [];
            }

            $logs = [];
            $lines = file($logFile);
            $recentLines = array_slice($lines, -100); // Last 100 lines

            foreach ($recentLines as $line) {
                if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)/', $line, $matches)) {
                    $logs[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[2],
                        'context' => $matches[3],
                        'message' => trim($matches[4]),
                    ];
                }
            }

            return array_reverse($logs);
        } catch (\Exception $e) {
            return [];
        }
    }
}
