<?php

namespace App\Services;

use App\Repositories\Contracts\SystemVersionRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SystemVersionService
{
    protected SystemVersionRepositoryInterface $systemVersionRepository;

    public function __construct(SystemVersionRepositoryInterface $systemVersionRepository)
    {
        $this->systemVersionRepository = $systemVersionRepository;
    }

    /**
     * Get current system version
     */
    public function getCurrentVersion(): ?object
    {
        try {
            return Cache::remember('current_system_version', 3600, function () {
                $version = $this->systemVersionRepository->getCurrentVersion();

                return $version ?: $this->getDefaultVersion();
            });
        } catch (\Exception $e) {
            Log::error('Error getting current system version', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->getDefaultVersion();
        }
    }

    /**
     * الحصول على جميع الإصدارات
     */
    public function getAllVersions(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->systemVersionRepository->getAll();
    }

    /**
     * Create new system version
     */
    public function createVersion(array $data): bool
    {
        try {
            $this->systemVersionRepository->create($data);
            $this->clearCache();

            return true;
        } catch (\Exception $e) {
            Log::error('Error creating system version', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Update system version
     */
    public function updateVersion(int $id, array $data): bool
    {
        try {
            $updated = $this->systemVersionRepository->update($id, $data);

            if ($updated) {
                $this->clearCache();

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error updating system version', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Delete system version
     */
    public function deleteVersion(int $id): bool
    {
        try {
            $deleted = $this->systemVersionRepository->delete($id);

            if ($deleted) {
                $this->clearCache();

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting system version', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Set version as current
     */
    public function setAsCurrent(int $id): bool
    {
        try {
            // First, unset all current versions
            $this->systemVersionRepository->unsetAllCurrent();

            // Then set the specified version as current
            $updated = $this->systemVersionRepository->update($id, ['is_current' => true]);

            if ($updated) {
                $this->clearCache();

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error setting version as current', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Clear version cache
     */
    public function clearCache(): void
    {
        try {
            Cache::forget('current_system_version');
            Cache::forget('all_system_versions');
        } catch (\Exception $e) {
            Log::error('Error clearing version cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Get default version object for fallback
     */
    private function getDefaultVersion(): object
    {
        return (object) [
            'version' => '1.0.0',
            'type_badge_class' => 'bg-blue-100 text-blue-800',
            'type_name_ar' => 'إصدار أولي',
            'formatted_release_date' => date('Y-m-d'),
            'description' => 'إصدار أولي للنظام',
            'features' => [],
            'bug_fixes' => [],
        ];
    }
}
