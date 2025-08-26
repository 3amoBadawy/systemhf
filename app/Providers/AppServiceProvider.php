<?php

namespace App\Providers;

use App\Repositories\Contracts\BusinessSettingRepositoryInterface;
use App\Services\SystemVersionService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->shareBusinessSettings();
        $this->shareSystemVersion();
    }

    /**
     * مشاركة إعدادات الأعمال مع جميع الواجهات
     */
    private function shareBusinessSettings(): void
    {
        View::composer('*', function ($view) {
            try {
                $businessSettingRepository = app(BusinessSettingRepositoryInterface::class);
                $businessSettings = $businessSettingRepository->getInstance();

                $view->with($this->getBusinessSettingsData($businessSettings));
            } catch (\Exception $e) {
                // Graceful fallback for database errors
                $view->with($this->getDefaultBusinessSettings());
            }
        });
    }

    /**
     * مشاركة إصدار النظام مع تذييل الصفحة
     */
    private function shareSystemVersion(): void
    {
        View::composer('components.footer', function ($view) {
            try {
                $systemVersionService = app(SystemVersionService::class);
                $currentVersion = $systemVersionService->getCurrentVersion();

                $view->with('currentVersion', $currentVersion);
            } catch (\Exception $e) {
                // Graceful fallback for database errors
                $view->with('currentVersion', '1.0.0');
            }
        });
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
     * الحصول على إعدادات الأعمال الافتراضية
     */
    private function getDefaultBusinessSettings(): array
    {
        return [
            'businessName' => 'نظام إدارة معرض الأثاث',
            'businessNameEn' => 'Furniture Gallery Management System',
            'logoUrl' => null,
        ];
    }
}
