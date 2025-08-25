<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\SystemSetting;
use Illuminate\Database\Eloquent\Collection;

interface SystemSettingRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find setting by key
     */
    public function findByKey(string $key): ?SystemSetting;

    /**
     * Get setting value by key
     */
    public function getValue(string $key, mixed $default = null): mixed;

    /**
     * Get multiple settings by keys
     */
    public function getMultiple(array $keys): Collection;

    /**
     * Get all settings by category
     */
    public function getByCategory(string $category): Collection;

    /**
     * Update or create setting
     */
    public function updateOrCreate(array $attributes, array $values): SystemSetting;
}
