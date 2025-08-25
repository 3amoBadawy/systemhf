<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SystemVersion;
use App\Repositories\Contracts\SystemVersionRepositoryInterface;

class SystemVersionRepository extends BaseRepository implements SystemVersionRepositoryInterface
{
    public function __construct(SystemVersion $model)
    {
        parent::__construct($model);
    }

    /**
     * Get current version
     */
    #[\Override]
    public function getCurrentVersion(): ?SystemVersion
    {
        $result = $this->model->query()->where('is_current', true)->first();

        return $result instanceof SystemVersion ? $result : null;
    }

    /**
     * Set version as current
     */
    public function setAsCurrent(int $versionId): bool
    {
        // Remove current flag from all versions
        $this->model->query()->update(['is_current' => false]);

        // Set the specified version as current
        return (bool) $this->model->query()->where('id', $versionId)->update(['is_current' => true]);
    }

    /**
     * Unset all current versions
     */
    #[\Override]
    public function unsetAllCurrent(): bool
    {
        return (bool) $this->model->query()->where('is_current', true)->update(['is_current' => false]);
    }
}
