<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SystemSetting;
use App\Repositories\Contracts\SystemSettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ConfigurationService
{
    protected SystemSettingRepositoryInterface $systemSettingRepository;

    public function __construct(SystemSettingRepositoryInterface $systemSettingRepository)
    {
        $this->systemSettingRepository = $systemSettingRepository;
    }

    /**
     * الحصول على قيمة إعداد
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = SystemSetting::where('key', $key)->first();

        if ($setting) {
            return $this->castValue($setting->value, $setting->type);
        }

        return $default;
    }

    /**
     * تعيين قيمة إعداد
     */
    public function set(string $key, mixed $value, string $type = 'string'): bool
    {
        $setting = SystemSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
            ]
        );

        return true;
    }

    /**
     * الحصول على الإعدادات القابلة للتعديل
     */
    public function getEditable(): array
    {
        $settings = SystemSetting::where('is_editable', true)->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = [
                'value' => $this->castValue($setting->value, $setting->type),
                'type' => $setting->type,
                'description' => $setting->description,
                'is_editable' => $setting->is_editable,
            ];
        }

        return $result;
    }

    /**
     * Get all system settings
     */
    public function getAll(): array
    {
        try {
            $cacheKey = 'system_settings_all';

            return Cache::remember($cacheKey, 3600, function () {
                $settings = $this->systemSettingRepository->all();
                $result = [];

                foreach ($settings as $setting) {
                    $result[$setting->key] = $setting->value;
                }

                return $result;
            });
        } catch (\Exception $e) {
            Log::error('Error getting all system settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [];
        }
    }

    /**
     * Delete a system setting by key
     */
    public function delete(string $key): bool
    {
        try {
            $setting = $this->systemSettingRepository->findByKey($key);
            if ($setting) {
                $deleted = $this->systemSettingRepository->delete($setting->id);
                if ($deleted) {
                    $this->clearCache($key);

                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Error deleting system setting: {$key}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Clear cache for system settings
     */
    public function clearCache(?string $key = null): void
    {
        try {
            if ($key) {
                Cache::forget("system_setting_{$key}");
            }

            Cache::forget('system_settings_editable');
            Cache::forget('system_settings_all');
        } catch (\Exception $e) {
            Log::error('Error clearing system settings cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Cast value based on type.
     */
    protected function castValue(mixed $value, string $type): mixed
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                return (bool) $value;
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}
