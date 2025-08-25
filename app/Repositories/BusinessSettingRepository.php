<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\BusinessSetting;
use App\Repositories\Contracts\BusinessSettingRepositoryInterface;

class BusinessSettingRepository extends BaseRepository implements BusinessSettingRepositoryInterface
{
    public function __construct(BusinessSetting $model)
    {
        parent::__construct($model);
    }

    /**
     * Get business settings instance
     */
    #[\Override]
    public function getInstance(): BusinessSetting
    {
        $result = $this->model->query()->firstOrCreate();

        return $result instanceof BusinessSetting ? $result : new BusinessSetting;
    }

    /**
     * Get available currencies
     */
    #[\Override]
    public function getCurrencies(): array
    {
        return [
            ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => 'ج.م'],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'ر.س'],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ'],
        ];
    }

    /**
     * Get available timezones
     */
    #[\Override]
    public function getTimezones(): array
    {
        return [
            'Africa/Cairo' => 'Cairo (GMT+2)',
            'UTC' => 'UTC (GMT+0)',
            'Europe/London' => 'London (GMT+0/+1)',
            'America/New_York' => 'New York (GMT-5/-4)',
        ];
    }

    /**
     * Get available date formats
     */
    #[\Override]
    public function getDateFormats(): array
    {
        return [
            'Y-m-d' => '2024-01-01',
            'd/m/Y' => '01/01/2024',
            'm/d/Y' => '01/01/2024',
            'd-m-Y' => '01-01-2024',
        ];
    }

    /**
     * Get available time formats
     */
    #[\Override]
    public function getTimeFormats(): array
    {
        return [
            'H:i' => '24-hour (14:30)',
            'h:i A' => '12-hour (2:30 PM)',
            'H:i:s' => '24-hour with seconds (14:30:45)',
            'h:i:s A' => '12-hour with seconds (2:30:45 PM)',
        ];
    }

    /**
     * Update business settings
     */
    #[\Override]
    public function updateSettings(array $data): bool
    {
        $settings = $this->getInstance();

        return $settings->update($data);
    }

    /**
     * Update business logo
     */
    #[\Override]
    public function updateLogo(string $logoPath): bool
    {
        $settings = $this->getInstance();

        return $settings->update(['logo' => $logoPath]);
    }

    /**
     * Remove business logo
     */
    #[\Override]
    public function removeLogo(): bool
    {
        $settings = $this->getInstance();

        return $settings->update(['logo' => null]);
    }
}
