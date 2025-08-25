<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find user by email
     */
    #[\Override]
    public function findByEmail(string $email): ?User
    {
        $result = $this->model->query()->where('email', $email)->first();

        return $result instanceof User ? $result : null;
    }

    /**
     * Find users by role
     */
    #[\Override]
    public function findByRole(string $role): Collection
    {
        return $this->model->query()->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();
    }

    /**
     * Find users by branch
     */
    #[\Override]
    public function findByBranch(int $branchId): Collection
    {
        return $this->model->query()->where('branch_id', $branchId)->get();
    }

    /**
     * Find active users
     */
    #[\Override]
    public function findActive(): Collection
    {
        return $this->model->query()->where('is_active', true)->get();
    }

    /**
     * Find users by permission
     */
    #[\Override]
    public function findByPermission(string $permission): Collection
    {
        return $this->model->query()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->get();
    }
}
