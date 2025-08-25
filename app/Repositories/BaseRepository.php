<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Find model by ID
     */
    #[\Override]
    public function findById(int $id): ?Model
    {
        return $this->model->query()->find($id);
    }

    /**
     * Find model by ID or fail
     */
    #[\Override]
    public function findByIdOrFail(int $id): Model
    {
        return $this->model->query()->findOrFail($id);
    }

    /**
     * Get all models
     */
    #[\Override]
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->query()->get();
    }

    /**
     * Get paginated models
     */
    #[\Override]
    public function paginate(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->model->query()->paginate($perPage);
    }

    /**
     * Create new model
     */
    #[\Override]
    public function create(array $data): Model
    {
        return $this->model->query()->create($data);
    }

    /**
     * Update model by ID
     */
    public function update(int $id, array $data): ?Model
    {
        $model = $this->findById($id);
        if ($model) {
            $model->update($data);

            return $model;
        }

        return null;
    }

    /**
     * Delete model by ID
     */
    public function delete(int $id): ?bool
    {
        $model = $this->findById($id);
        if ($model) {
            return $model->delete();
        }

        return null;
    }

    /**
     * Find models by criteria
     */
    public function findBy(array $criteria): Collection
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->get();
    }

    /**
     * Find one model by criteria
     */
    public function findOneBy(array $criteria): ?Model
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->first();
    }

    /**
     * Count models by criteria
     */
    public function count(array $criteria = []): int
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->count();
    }

    /**
     * Check if model exists by criteria
     */
    public function exists(array $criteria = []): bool
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->exists();
    }
}
