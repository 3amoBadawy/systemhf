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
        // Share business settings with all views
        View::composer('*', function ($view) {
            try {
                $businessSettingRepository = app(BusinessSettingRepositoryInterface::class);
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
            } catch (\Exception $e) {
                // Graceful fallback for database errors
                $view->with([
                    'businessName' => 'نظام إدارة معرض الأثاث',
                    'businessNameEn' => 'Furniture Gallery Management System',
                    'logoUrl' => null,
                ]);
            }
        });

        // Share current version with footer
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
}
