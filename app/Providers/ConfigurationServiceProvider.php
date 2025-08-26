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
            $sessionLifetime = $configService->get('session_lifetime');
            if ($sessionLifetime) {
                Config::set('session.lifetime', $sessionLifetime);
            }

            // إعدادات الأمان
            $maxAttempts = $configService->get('max_login_attempts');
            if ($maxAttempts) {
                Config::set('auth.throttle.max_attempts', $maxAttempts);
            }

            // إعدادات الملفات
            $maxFileSize = $configService->get('max_file_size');
            if ($maxFileSize) {
                Config::set('filesystems.max_file_size', $maxFileSize * 1024); // تحويل إلى بايت
            }

            // إعدادات التطبيق
            $appName = $configService->get('app_name');
            if ($appName) {
                Config::set('app.name', $appName);
            }

            $defaultLang = $configService->get('default_language');
            if ($defaultLang) {
                Config::set('app.locale', $defaultLang);
            }

            // إعدادات قاعدة البيانات للنسخ الاحتياطي
            $backupEnabled = $configService->get('auto_backup_enabled');
            if ($backupEnabled) {
                Config::set('backup.enabled', $backupEnabled);
            }

            $backupFrequency = $configService->get('backup_frequency');
            if ($backupFrequency) {
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
                $this->shareBusinessSettings($view);
            } catch (\Exception $e) {
                $this->shareDefaultSettings($view);
            }
        });
    }

    /**
     * مشاركة إعدادات الأعمال
     */
    private function shareBusinessSettings($view): void
    {
        $configService = app(ConfigurationService::class);
        $businessRepo = app(BusinessSettingRepositoryInterface::class);

        if (! $view->offsetExists('businessSettings')) {
            $businessSettings = $businessRepo->getInstance();
            $view->with($this->getBusinessSettingsData($businessSettings));

            return;
        }

        $view->with($this->getGeneralSettingsData($configService));
    }

    /**
     * مشاركة الإعدادات الافتراضية
     */
    private function shareDefaultSettings($view): void
    {
        if (! $view->offsetExists('businessSettings')) {
            $view->with($this->getDefaultBusinessSettings());

            return;
        }

        $view->with($this->getDefaultGeneralSettings());
    }

    /**
     * الحصول على بيانات إعدادات الأعمال
     */
    private function getBusinessSettingsData($businessSettings): array
    {
        return [
            'businessName' => $this->getBusinessName($businessSettings),
            'businessEmail' => $this->getBusinessEmail($businessSettings),
            'businessPhone' => $this->getBusinessPhone($businessSettings),
            'businessAddress' => $this->getBusinessAddress($businessSettings),
            'businessLogo' => $this->getBusinessLogo($businessSettings),
            'businessFavicon' => $this->getBusinessFavicon($businessSettings),
            'currency' => $this->getBusinessCurrency($businessSettings),
            'currencySymbol' => $this->getBusinessCurrencySymbol($businessSettings),
            'timezone' => $this->getBusinessTimezone($businessSettings),
            'dateFormat' => $this->getBusinessDateFormat($businessSettings),
            'timeFormat' => $this->getBusinessTimeFormat($businessSettings),
        ];
    }

    /**
     * الحصول على بريد الأعمال
     */
    private function getBusinessEmail($businessSettings): string
    {
        return $businessSettings->business_email ?: 'info@example.com';
    }

    /**
     * الحصول على هاتف الأعمال
     */
    private function getBusinessPhone($businessSettings): string
    {
        return $businessSettings->business_phone ?: '+966 50 000 0000';
    }

    /**
     * الحصول على شعار الأعمال
     */
    private function getBusinessLogo($businessSettings): string
    {
        return $businessSettings->business_logo ?: '/images/default-logo.png';
    }

    /**
     * الحصول على أيقونة الأعمال
     */
    private function getBusinessFavicon($businessSettings): string
    {
        return $businessSettings->business_favicon ?: '/images/default-favicon.ico';
    }

    /**
     * الحصول على عملة الأعمال
     */
    private function getBusinessCurrency($businessSettings): string
    {
        return $businessSettings->currency ?: 'SAR';
    }

    /**
     * الحصول على رمز عملة الأعمال
     */
    private function getBusinessCurrencySymbol($businessSettings): string
    {
        return $businessSettings->currency_symbol ?: 'ر.س';
    }

    /**
     * الحصول على المنطقة الزمنية للأعمال
     */
    private function getBusinessTimezone($businessSettings): string
    {
        return $businessSettings->timezone ?: 'Asia/Riyadh';
    }

    /**
     * الحصول على تنسيق التاريخ للأعمال
     */
    private function getBusinessDateFormat($businessSettings): string
    {
        return $businessSettings->date_format ?: 'Y-m-d';
    }

    /**
     * الحصول على تنسيق الوقت للأعمال
     */
    private function getBusinessTimeFormat($businessSettings): string
    {
        return $businessSettings->time_format ?: 'H:i';
    }

    /**
     * الحصول على اسم الأعمال
     */
    private function getBusinessName($businessSettings): string
    {
        return $businessSettings->business_name_ar ?: $businessSettings->business_name ?: 'نظام إدارة الأعمال';
    }

    /**
     * الحصول على عنوان الأعمال
     */
    private function getBusinessAddress($businessSettings): string
    {
        return $businessSettings->business_address_ar ?: $businessSettings->business_address ?: 'العنوان';
    }

    /**
     * الحصول على بيانات الإعدادات العامة
     */
    private function getGeneralSettingsData($configService): array
    {
        return [
            'dateFormat' => $configService->get('date_format', 'd-m-Y'),
            'timeFormat' => $configService->get('time_format', 'H:i'),
            'recordsPerPage' => $configService->get('records_per_page', 25),
            'systemVersion' => $configService->get('app_version', '1.0.0'),
            'maintenanceMode' => $configService->get('maintenance_mode', false),
        ];
    }

    /**
     * الحصول على الإعدادات الافتراضية للأعمال
     */
    private function getDefaultBusinessSettings(): array
    {
        return [
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
        ];
    }

    /**
     * الحصول على الإعدادات العامة الافتراضية
     */
    private function getDefaultGeneralSettings(): array
    {
        return [
            'dateFormat' => 'd-m-Y',
            'timeFormat' => 'H:i',
            'recordsPerPage' => 25,
            'systemVersion' => '1.0.0',
            'maintenanceMode' => false,
        ];
    }
}
