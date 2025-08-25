<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\SystemVersion;

interface SystemVersionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get current system version
     */
    public function getCurrentVersion(): ?SystemVersion;

    /**
     * Unset all current versions
     */
    public function unsetAllCurrent(): bool;
}
