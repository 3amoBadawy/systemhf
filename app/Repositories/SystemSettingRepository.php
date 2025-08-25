<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SystemSetting;
use App\Repositories\Contracts\SystemSettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SystemSettingRepository extends BaseRepository implements SystemSettingRepositoryInterface
{
    public function __construct(SystemSetting $model)
    {
        parent::__construct($model);
    }

    /**
     * Find setting by key
     */
    #[\Override]
    public function findByKey(string $key): ?SystemSetting
    {
        $result = $this->model->query()->where('key', $key)->first();

        return $result instanceof SystemSetting ? $result : null;
    }

    /**
     * Get setting value
     */
    #[\Override]
    public function getValue(string $key, mixed $default = null): mixed
    {
        $setting = $this->findByKey($key);

        return $setting ? $setting->value : $default;
    }

    /**
     * Get multiple settings by keys
     */
    #[\Override]
    public function getMultiple(array $keys): \Illuminate\Database\Eloquent\Collection
    {
        $result = $this->model->query()->whereIn('key', $keys)->get();

        return $result instanceof \Illuminate\Database\Eloquent\Collection ? $result : new \Illuminate\Database\Eloquent\Collection;
    }

    /**
     * Get settings by category
     */
    #[\Override]
    public function getByCategory(string $category): Collection
    {
        return $this->model->query()->where('category', $category)->get();
    }

    /**
     * Update or create setting
     */
    #[\Override]
    public function updateOrCreate(array $attributes, array $values): SystemSetting
    {
        $result = $this->model->query()->updateOrCreate($attributes, $values);

        return $result instanceof SystemSetting ? $result : new SystemSetting;
    }
}
