<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BusinessSetting;
use App\Repositories\Contracts\BusinessSettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class BusinessSettingsService
{
    protected BusinessSettingRepositoryInterface $settingsRepo;

    public function __construct(BusinessSettingRepositoryInterface $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
    }

    /**
     * الحصول على جميع الإعدادات
     */
    public function getSettings(): array
    {
        $settings = $this->getInstance();

        return [
            'business_name' => $settings->business_name ?? '',
            'business_name_ar' => $settings->business_name_ar ?? '',
            'business_email' => $settings->business_email ?? '',
            'business_phone' => $settings->business_phone ?? '',
            'business_address' => $settings->business_address ?? '',
            'business_address_ar' => $settings->business_address_ar ?? '',
            'business_website' => $settings->business_website ?? '',
            'business_logo' => $settings->business_logo ?? '',
            'business_favicon' => $settings->business_favicon ?? '',
            'currency' => $settings->currency ?? 'EGP',
            'currency_symbol' => $settings->currency_symbol ?? 'ج.م',
            'timezone' => $settings->timezone ?? 'Africa/Cairo',
            'date_format' => $settings->date_format ?? 'Y-m-d',
            'time_format' => $settings->time_format ?? 'H:i:s',
            'tax_rate' => $settings->tax_rate ?? 14.0,
            'tax_number' => $settings->tax_number ?? '',
            'commercial_record' => $settings->commercial_record ?? '',
            'bank_name' => $settings->bank_name ?? '',
            'bank_account' => $settings->bank_account ?? '',
            'iban' => $settings->iban ?? '',
        ];
    }

    /**
     * Get singleton instance
     */
    public function getInstance(): BusinessSetting
    {
        return BusinessSetting::firstOrCreate([], [
            'business_name' => 'SystemHF',
            'business_name_ar' => 'نظام إدارة الأعمال',
            'business_email' => 'info@systemhf.com',
            'business_phone' => '+20 100 000 0000',
            'business_address' => 'Cairo, Egypt',
            'business_address_ar' => 'القاهرة، مصر',
            'business_website' => 'https://systemhf.com',
            'business_logo' => '',
            'business_favicon' => '',
            'currency' => 'EGP',
            'currency_symbol' => 'ج.م',
            'timezone' => 'Africa/Cairo',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'tax_rate' => 14.0,
            'tax_number' => '',
            'commercial_record' => '',
            'bank_name' => '',
            'bank_account' => '',
            'iban' => '',
            'default_profit_percent' => 30.0,
        ]);
    }

    /**
     * Get business name
     */
    public function getBusinessName(string $locale = 'ar'): string
    {
        $settings = $this->getSettings();

        return $locale === 'ar' ? $settings['business_name_ar'] : $settings['business_name'];
    }

    /**
     * Get formatted currency
     */
    public function formatCurrency(float $amount): string
    {
        $settings = $this->getSettings();
        $symbol = $settings['currency_symbol'] ?? 'ج.م';

        return $symbol.' '.number_format($amount, 2);
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbol(): string
    {
        $settings = $this->getSettings();

        return $settings['currency_symbol'] ?? 'ج.م';
    }

    /**
     * Get default profit percent
     */
    public function getDefaultProfitPercent(): float
    {
        $settings = $this->getSettings();

        return $settings['default_profit_percent'] ?? 30.0;
    }

    /**
     * Get logo URL
     */
    public function getLogoUrl(): ?string
    {
        $settings = $this->getSettings();

        return $settings['business_logo'] ?? null;
    }

    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        Cache::forget('business_settings');
    }

    /**
     * Get all settings as array
     */
    public function getAllSettings(): array
    {
        $settings = $this->getSettings();

        return [
            'business_name' => $settings['business_name'],
            'business_name_ar' => $settings['business_name_ar'],
            'default_profit_percent' => $settings['default_profit_percent'],
            'currency_symbol' => $settings['currency_symbol'],
            'timezone' => $settings['timezone'],
            'logo_url' => $settings['business_logo'],
            'date_format' => $settings['date_format'],
            'time_format' => $settings['time_format'],
        ];
    }
}
