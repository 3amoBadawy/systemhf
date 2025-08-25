<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\BusinessSettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class BusinessSettingsService
{
    protected BusinessSettingRepositoryInterface $businessSettingRepository;

    public function __construct(BusinessSettingRepositoryInterface $businessSettingRepository)
    {
        $this->businessSettingRepository = $businessSettingRepository;
    }

    /**
     * الحصول على جميع الإعدادات
     */
    public function getSettings(): array
    {
        $settings = $this->getInstance();

        return [
            'business_name' => $settings->business_name,
            'business_name_ar' => $settings->business_name_ar,
            'business_email' => $settings->business_email,
            'business_phone' => $settings->business_phone,
            'business_address' => $settings->business_address,
            'business_address_ar' => $settings->business_address_ar,
            'business_website' => $settings->business_website,
            'business_logo' => $settings->business_logo,
            'business_favicon' => $settings->business_favicon,
            'currency' => $settings->currency,
            'currency_symbol' => $settings->currency_symbol,
            'timezone' => $settings->timezone,
            'date_format' => $settings->date_format,
            'time_format' => $settings->time_format,
            'tax_rate' => $settings->tax_rate,
            'tax_number' => $settings->tax_number,
            'commercial_record' => $settings->commercial_record,
            'bank_name' => $settings->bank_name,
            'bank_account' => $settings->bank_account,
            'iban' => $settings->iban,
        ];
    }

    /**
     * Get business name
     */
    public function getBusinessName(string $locale = 'ar'): string
    {
        $settings = $this->getSettings();

        return $locale === 'ar' ? $settings->business_name_ar : $settings->business_name;
    }

    /**
     * Get formatted currency
     */
    public function formatCurrency(float $amount): string
    {
        $settings = $this->getSettings();
        $symbol = $settings->currency_symbol ?? 'ج.م';

        return $symbol.' '.number_format($amount, 2);
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbol(): string
    {
        $settings = $this->getSettings();

        return $settings->currency_symbol ?? 'ج.م';
    }

    /**
     * Get default profit percent
     */
    public function getDefaultProfitPercent(): float
    {
        $settings = $this->getSettings();

        return $settings->default_profit_percent ?? 30.0;
    }

    /**
     * Get logo URL
     */
    public function getLogoUrl(): ?string
    {
        $settings = $this->getSettings();

        return $settings->logo_url;
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
            'business_name' => $settings->business_name,
            'business_name_ar' => $settings->business_name_ar,
            'default_profit_percent' => $settings->default_profit_percent,
            'currency_symbol' => $settings->currency_symbol,
            'timezone' => $settings->timezone,
            'logo_url' => $settings->logo_url,
            'date_format' => $settings->date_format,
            'time_format' => $settings->time_format,
        ];
    }
}
