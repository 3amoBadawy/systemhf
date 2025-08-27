<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SystemSetting;
use App\Repositories\Contracts\SystemSettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ConfigurationService
{
    protected SystemSettingRepositoryInterface $settingsRepo;

    public function __construct(SystemSettingRepositoryInterface $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
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
        SystemSetting::updateOrCreate(
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
    public function getEditable(): \Illuminate\Support\Collection
    {
        $settings = SystemSetting::where('is_editable', true)->get();

        $result = collect();
        foreach ($settings as $setting) {
            $category = $setting->category ?? 'general';
            if (! $result->has($category)) {
                $result->put($category, collect());
            }

            $result->get($category)->push([
                'key' => $setting->key,
                'value' => $this->castValue($setting->value, $setting->type),
                'type' => $setting->type,
                'description' => $setting->description_ar ?? $setting->description_en ?? $setting->key,
                'is_editable' => $setting->is_editable,
                'requires_restart' => $setting->requires_restart ?? false,
            ]);
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
                $settings = $this->settingsRepo->all();
                $result = [];

                foreach ($settings as $setting) {
                    /** @var \App\Models\SystemSetting $setting */
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
            $setting = $this->settingsRepo->findByKey($key);
            if ($setting) {
                $deleted = $this->settingsRepo->delete($setting->id);
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

    /**
     * Reset system settings to defaults
     */
    public function resetToDefaults(): bool
    {
        try {
            $defaultSettings = [
                'app_name' => 'SystemHF',
                'app_locale' => 'ar',
                'app_timezone' => 'Africa/Cairo',
                'app_debug' => false,
                'app_maintenance' => false,
                'mail_driver' => 'smtp',
                'cache_driver' => 'file',
                'session_driver' => 'file',
                'queue_driver' => 'sync',
            ];

            foreach ($defaultSettings as $key => $value) {
                $this->set($key, $value);
            }

            $this->clearCache();

            return true;
        } catch (\Exception $e) {
            Log::error('Error resetting system settings to defaults', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Get settings by category
     */
    public function getByCategory(string $category): array
    {
        try {
            $settings = $this->settingsRepo->getByCategory($category);
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = [
                    'value' => $this->castValue($setting->value, $setting->type),
                    'type' => $setting->type,
                    'description' => $setting->description_ar ?? $setting->description_en,
                    'is_editable' => $setting->is_editable,
                    'requires_restart' => $setting->requires_restart,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("Error getting settings by category: {$category}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [];
        }
    }

    /**
     * Search settings by query
     */
    public function search(string $query): array
    {
        try {
            $settings = SystemSetting::where('key', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhere('name_en', 'like', "%{$query}%")
                ->orWhere('description_ar', 'like', "%{$query}%")
                ->orWhere('description_en', 'like', "%{$query}%")
                ->get();

            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = [
                    'value' => $this->castValue($setting->value, $setting->type),
                    'type' => $setting->type,
                    'category' => $setting->category,
                    'description' => $setting->description_ar ?? $setting->description_en,
                    'is_editable' => $setting->is_editable,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("Error searching settings: {$query}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [];
        }
    }
}
