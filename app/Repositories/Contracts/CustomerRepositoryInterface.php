<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated customers with search and sorting
     */
    public function getPaginatedCustomers(
        string $search = '',
        string $searchType = 'all',
        string $sortBy = 'created_at',
        string $sortOrder = 'desc',
        int $perPage = 20
    ): LengthAwarePaginator;

    /**
     * Search customers by term
     */
    public function searchByTerm(string $term, int $limit = 20): Collection;

    /**
     * Check if customer can be deleted
     */
    public function canDelete(Customer $customer): bool;

    /**
     * Get customer statistics
     */
    public function getStatistics(): array;
}
