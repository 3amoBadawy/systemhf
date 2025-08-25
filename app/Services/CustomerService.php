<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    protected CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get paginated customers with search and sorting
     */
    public function getPaginatedCustomers(
        string $search = '',
        string $searchType = 'all',
        string $sortBy = 'created_at',
        string $sortOrder = 'desc',
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->customerRepository->getPaginatedCustomers(
            search: $search,
            searchType: $searchType,
            sortBy: $sortBy,
            sortOrder: $sortOrder,
            perPage: $perPage
        );
    }

    /**
     * إنشاء عميل جديد
     */
    public function createCustomer(array $data): Customer
    {
        $customer = $this->customerRepository->create($data);

        // Log the creation
        Log::info('Customer created', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'created_by' => auth()->id(),
        ]);

        return $customer;
    }

    /**
     * تحديث بيانات العميل
     */
    public function updateCustomer(int $id, array $data): ?Customer
    {
        $customer = $this->customerRepository->findById($id);

        if (! $customer) {
            return null;
        }

        $customer = $this->customerRepository->update($id, $data);

        // Log the update
        Log::info('Customer updated', [
            'customer_id' => $id,
            'updated_by' => auth()->id(),
        ]);

        return $customer;
    }

    /**
     * حذف العميل
     */
    public function deleteCustomer(int $id): bool
    {
        $customer = $this->customerRepository->findById($id);

        if (! $customer) {
            return false;
        }

        $result = $this->customerRepository->delete($id);

        if ($result) {
            // Log the deletion
            Log::info('Customer deleted', [
                'customer_id' => $id,
                'deleted_by' => auth()->id(),
            ]);
        }

        return $result;
    }

    /**
     * Search customers by term
     */
    public function searchCustomers(string $term, int $limit = 20): Collection
    {
        return $this->customerRepository->searchByTerm($term, $limit);
    }

    /**
     * Get customer statistics
     */
    public function getCustomerStatistics(): array
    {
        return $this->customerRepository->getStatistics();
    }

    /**
     * Check if customer can be deleted
     */
    public function canDeleteCustomer(Customer $customer): bool
    {
        return $this->customerRepository->canDelete($customer);
    }
}
