<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find products by category
     */
    public function findByCategory(string $category): Collection;

    /**
     * Find products by supplier
     */
    public function findBySupplier(int $supplierId): Collection;

    /**
     * Find products by branch
     */
    public function findByBranch(int $branchId): Collection;

    /**
     * Search products by name or description
     */
    public function search(string $query): Collection;

    /**
     * Find active products
     */
    public function findActive(): Collection;

    /**
     * Find products with low stock
     */
    public function findLowStock(int $threshold = 10): Collection;
}
