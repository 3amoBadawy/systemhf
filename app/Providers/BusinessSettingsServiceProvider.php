<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\BusinessSettingsService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class BusinessSettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BusinessSettingsService::class, function ($app) {
            return new BusinessSettingsService($app->make('App\Repositories\Contracts\BusinessSettingRepositoryInterface'));
        });
    }

    public function boot(): void
    {
        $this->shareBusinessSettingsWithViews();
    }

    protected function shareBusinessSettingsWithViews(): void
    {
        View::composer('*', function ($view) {
            try {
                $businessSettingsService = app(BusinessSettingsService::class);

                $view->with('businessSettings', $businessSettingsService->getAllSettings());
                $view->with('businessName', $businessSettingsService->getBusinessName('ar'));
                $view->with('businessNameEn', $businessSettingsService->getBusinessName('en'));
                $view->with('logoUrl', $businessSettingsService->getLogoUrl());
                $view->with('currencySymbol', $businessSettingsService->getCurrencySymbol());
                $view->with('defaultProfitPercent', $businessSettingsService->getDefaultProfitPercent());
            } catch (\Exception $e) {
                // Fallback values in case of errors
                $view->with('businessSettings', []);
                $view->with('businessName', 'نظام إدارة الأعمال');
                $view->with('businessNameEn', 'Business Management System');
                $view->with('logoUrl', null);
                $view->with('currencySymbol', 'ج.م');
                $view->with('defaultProfitPercent', 30);
            }
        });
    }
}
