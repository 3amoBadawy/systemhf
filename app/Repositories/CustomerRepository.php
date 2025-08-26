<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated customers with search and sorting
     */
    #[\Override]
    public function getPaginatedCustomers(
        string $search = '',
        string $searchType = 'all',
        string $sortBy = 'created_at',
        string $sortOrder = 'desc',
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = $this->model->query()->with('branch');

        if (! empty($search)) {
            $this->applySearch($query, $search, $searchType);
        }

        return $query->orderBy($searchType === 'name' ? 'name' : $sortBy, $sortOrder)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Search customers by term
     */
    #[\Override]
    public function searchByTerm(string $term, int $limit = 20): Collection
    {
        if (strlen($term) < 2) {
            return new Collection;
        }

        $result = $this->model->query()->where(function ($query) use ($term) {
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('phone2', 'like', "%{$term}%")
                ->orWhere('governorate', 'like', "%{$term}%")
                ->orWhere('address', 'like', "%{$term}%")
                ->orWhere('country', 'like', "%{$term}%");
        })
            ->limit($limit)
            ->get(['id', 'name', 'phone', 'phone2', 'governorate', 'address', 'country']);

        return $result instanceof Collection ? $result : new Collection;
    }

    /**
     * Get customer statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_customers' => $this->model->query()->count(),
            'active_customers' => $this->model->query()->where('is_active', true)->count(),
            'customers_with_balance' => $this->model->query()->whereHas('invoices', function ($query) {
                $query->whereRaw('total > (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE customer_id = customers.id)');
            })->count(),
            'new_customers_this_month' => $this->model->query()->whereMonth('created_at', now()->month)->count(),
        ];
    }

    /**
     * Check if customer can be deleted
     */
    #[\Override]
    public function canDelete(Customer $customer): bool
    {
        return ! $customer->invoices()->exists() && ! $customer->payments()->exists();
    }

    /**
     * تطبيق البحث على الاستعلام
     *
     * @phpstan-ignore-next-line
     */
    private function applySearch(\Illuminate\Database\Eloquent\Builder $query, string $searchTerm, string $searchType = 'all'): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(function ($q) use ($searchTerm, $searchType) {
            if ($searchType === 'name' || $searchType === 'all') {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('name_ar', 'like', "%{$searchTerm}%");
            }
            if ($searchType === 'phone' || $searchType === 'all') {
                $q->orWhere('phone', 'like', "%{$searchTerm}%");
            }
            if ($searchType === 'email' || $searchType === 'all') {
                $q->orWhere('email', 'like', "%{$searchTerm}%");
            }
            if ($searchType === 'national_id' || $searchType === 'all') {
                $q->orWhere('national_id', 'like', "%{$searchTerm}%");
            }
        });
    }
}
