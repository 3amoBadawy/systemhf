<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find users by role
     */
    public function findByRole(string $role): Collection;

    /**
     * Find users by branch
     */
    public function findByBranch(int $branchId): Collection;

    /**
     * Find active users
     */
    public function findActive(): Collection;

    /**
     * Find users with specific permission
     */
    public function findByPermission(string $permission): Collection;
}
