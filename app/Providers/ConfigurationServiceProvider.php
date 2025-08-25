<?php

namespace App\Providers;

use App\Repositories\Contracts\BusinessSettingRepositoryInterface;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تحميل إعدادات النظام
        $this->loadSystemSettings();

        // مشاركة الإعدادات مع جميع الواجهات
        $this->shareSettingsWithViews();
    }

    /**
     * تحميل إعدادات النظام
     */
    protected function loadSystemSettings(): void
    {
        try {
            // تحديث إعدادات Laravel من قاعدة البيانات
            $this->updateConfigFromDatabase();
        } catch (\Exception $e) {
            // في حالة عدم وجود قاعدة البيانات أو الجداول بعد
            // سيتم تجاهل الخطأ واستخدام الإعدادات الافتراضية
        }
    }

    /**
     * تحديث إعدادات Laravel من قاعدة البيانات
     */
    protected function updateConfigFromDatabase(): void
    {
        try {
            $configService = app(ConfigurationService::class);

            // إعدادات الجلسة
            if ($sessionLifetime = $configService->get('session_lifetime')) {
                Config::set('session.lifetime', $sessionLifetime);
            }

            // إعدادات الأمان
            if ($maxAttempts = $configService->get('max_login_attempts')) {
                Config::set('auth.throttle.max_attempts', $maxAttempts);
            }

            // إعدادات الملفات
            if ($maxFileSize = $configService->get('max_file_size')) {
                Config::set('filesystems.max_file_size', $maxFileSize * 1024); // تحويل إلى بايت
            }

            // إعدادات التطبيق
            if ($appName = $configService->get('app_name')) {
                Config::set('app.name', $appName);
            }

            if ($defaultLang = $configService->get('default_language')) {
                Config::set('app.locale', $defaultLang);
            }

            // إعدادات قاعدة البيانات للنسخ الاحتياطي
            if ($backupEnabled = $configService->get('auto_backup_enabled')) {
                Config::set('backup.enabled', $backupEnabled);
            }

            if ($backupFrequency = $configService->get('backup_frequency')) {
                Config::set('backup.frequency', $backupFrequency);
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود قاعدة البيانات، استخدم القيم الافتراضية
        }
    }

    /**
     * مشاركة الإعدادات مع الواجهات
     */
    protected function shareSettingsWithViews(): void
    {
        View::composer('*', function ($view) {
            try {
                $configService = app(ConfigurationService::class);
                $businessSettingRepository = app(BusinessSettingRepositoryInterface::class);

                // إعدادات الأعمال - فقط إذا لم تكن موجودة بالفعل
                if (! $view->offsetExists('businessSettings')) {
                    $businessSettings = $businessSettingRepository->getInstance();

                    $view->with([
                        'businessName' => $businessSettings->business_name_ar ?: $businessSettings->business_name ?: 'نظام إدارة الأعمال',
                        'businessEmail' => $businessSettings->business_email ?: 'info@example.com',
                        'businessPhone' => $businessSettings->business_phone ?: '+966 50 000 0000',
                        'businessAddress' => $businessSettings->business_address_ar ?: $businessSettings->business_address ?: 'العنوان',
                        'businessLogo' => $businessSettings->business_logo ?: '/images/default-logo.png',
                        'businessFavicon' => $businessSettings->business_favicon ?: '/images/default-favicon.ico',
                        'currency' => $businessSettings->currency ?: 'SAR',
                        'currencySymbol' => $businessSettings->currency_symbol ?: 'ر.س',
                        'timezone' => $businessSettings->timezone ?: 'Asia/Riyadh',
                        'dateFormat' => $businessSettings->date_format ?: 'Y-m-d',
                        'timeFormat' => $businessSettings->time_format ?: 'H:i',
                    ]);
                } else {
                    // إذا كانت businessSettings موجودة بالفعل، شارك فقط الإعدادات العامة
                    $view->with([
                        'dateFormat' => $configService->get('date_format', 'd-m-Y'),
                        'timeFormat' => $configService->get('time_format', 'H:i'),
                        'recordsPerPage' => $configService->get('records_per_page', 25),
                        'systemVersion' => $configService->get('app_version', '1.0.0'),
                        'maintenanceMode' => $configService->get('maintenance_mode', false),
                    ]);
                }
            } catch (\Exception $e) {
                // في حالة عدم وجود قاعدة البيانات، استخدم القيم الافتراضية
                if (! $view->offsetExists('businessSettings')) {
                    $view->with([
                        'businessName' => 'نظام إدارة معرض الأثاث',
                        'businessNameEn' => 'Furniture Gallery Management System',
                        'currencySymbol' => 'ج.م',
                        'defaultProfitPercent' => 30,
                        'logoUrl' => null,
                        'timezone' => 'Africa/Cairo',
                        'dateFormat' => 'd-m-Y',
                        'timeFormat' => 'H:i',
                        'recordsPerPage' => 25,
                        'systemVersion' => '1.0.0',
                        'maintenanceMode' => false,
                    ]);
                } else {
                    $view->with([
                        'dateFormat' => 'd-m-Y',
                        'timeFormat' => 'H:i',
                        'recordsPerPage' => 25,
                        'systemVersion' => '1.0.0',
                        'maintenanceMode' => false,
                    ]);
                }
            }
        });
    }
}
