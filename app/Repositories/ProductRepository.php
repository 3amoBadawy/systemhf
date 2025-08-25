<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Find products by category
     */
    #[\Override]
    public function findByCategory(string $category): Collection
    {
        return $this->model->query()->where('category', $category)->get();
    }

    /**
     * Find products by supplier
     */
    #[\Override]
    public function findBySupplier(int $supplierId): Collection
    {
        return $this->model->query()->where('supplier_id', $supplierId)->get();
    }

    /**
     * Find products by branch
     */
    #[\Override]
    public function findByBranch(int $branchId): Collection
    {
        return $this->model->query()->where('branch_id', $branchId)->get();
    }

    /**
     * Search products by name or description
     */
    #[\Override]
    public function search(string $query): Collection
    {
        $result = $this->model->query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->get();

        return $result instanceof Collection ? $result : new \Illuminate\Database\Eloquent\Collection;
    }

    /**
     * Find active products
     */
    #[\Override]
    public function findActive(): Collection
    {
        return $this->model->query()->where('is_active', true)->get();
    }

    /**
     * Find products with low stock
     */
    #[\Override]
    public function findLowStock(int $threshold = 10): Collection
    {
        return $this->model->query()->where('stock_quantity', '<=', $threshold)->get();
    }
}
