<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    /**
     * Find invoices by customer
     */
    #[\Override]
    public function findByCustomer(int $customerId): Collection
    {
        return $this->model->query()->where('customer_id', $customerId)->get();
    }

    /**
     * Find invoices by branch
     */
    #[\Override]
    public function findByBranch(int $branchId): Collection
    {
        return $this->model->query()->where('branch_id', $branchId)->get();
    }

    /**
     * Find invoices by status
     */
    #[\Override]
    public function findByStatus(string $status): Collection
    {
        return $this->model->query()->where('status', $status)->get();
    }

    /**
     * Find invoices by date range
     */
    #[\Override]
    public function findByDateRange(string $startDate, string $endDate): Collection
    {
        $result = $this->model->query()->whereBetween('invoice_date', [$startDate, $endDate])->get();

        return $result instanceof Collection ? $result : new \Illuminate\Database\Eloquent\Collection;
    }

    /**
     * Find unpaid invoices
     */
    #[\Override]
    public function findUnpaid(): Collection
    {
        return $this->model->query()->where('status', 'unpaid')->get();
    }

    /**
     * Find overdue invoices
     */
    #[\Override]
    public function findOverdue(): Collection
    {
        return $this->model->query()->where('due_date', '<', now())->where('status', '!=', 'paid')->get();
    }

    /**
     * Get invoice statistics
     */
    #[\Override]
    public function getStatistics(): array
    {
        return [
            'total_invoices' => $this->model->query()->count(),
            'paid_invoices' => $this->model->query()->where('status', 'paid')->count(),
            'unpaid_invoices' => $this->model->query()->where('status', 'unpaid')->count(),
            'overdue_invoices' => $this->model->query()->where('due_date', '<', now())->where('status', '!=', 'paid')->count(),
            'total_amount' => $this->model->query()->sum('total'),
            'paid_amount' => $this->model->query()->where('status', 'paid')->sum('total'),
            'unpaid_amount' => $this->model->query()->where('status', 'unpaid')->sum('total'),
        ];
    }
}
