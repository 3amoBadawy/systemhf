<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Find model by ID
     */
    public function findById(int $id): ?Model;

    /**
     * Find model by ID or fail
     */
    public function findByIdOrFail(int $id): Model;

    /**
     * Get all models
     */
    public function all(): Collection;

    /**
     * Get paginated models
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Create new model
     */
    public function create(array $data): Model;

    /**
     * Update model by ID
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete model by ID
     */
    public function delete(int $id): ?bool;

    /**
     * Find models by criteria
     */
    public function findBy(array $criteria): Collection;

    /**
     * Find one model by criteria
     */
    public function findOneBy(array $criteria): ?Model;

    /**
     * Count models by criteria
     */
    public function count(array $criteria = []): int;

    /**
     * Check if model exists by criteria
     */
    public function exists(array $criteria = []): bool;
}
