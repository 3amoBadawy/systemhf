<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\BusinessSetting;

interface BusinessSettingRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get business settings instance
     */
    public function getInstance(): BusinessSetting;

    /**
     * Get available currencies
     */
    public function getCurrencies(): array;

    /**
     * Get available timezones
     */
    public function getTimezones(): array;

    /**
     * Get available date formats
     */
    public function getDateFormats(): array;

    /**
     * Get available time formats
     */
    public function getTimeFormats(): array;

    /**
     * Update business settings
     */
    public function updateSettings(array $data): bool;

    /**
     * Update logo
     */
    public function updateLogo(string $logoPath): bool;

    /**
     * Remove logo
     */
    public function removeLogo(): bool;
}
