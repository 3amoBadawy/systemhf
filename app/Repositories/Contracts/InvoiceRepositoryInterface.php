<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

interface InvoiceRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find invoices by customer
     */
    public function findByCustomer(int $customerId): Collection;

    /**
     * Find invoices by branch
     */
    public function findByBranch(int $branchId): Collection;

    /**
     * Find invoices by status
     */
    public function findByStatus(string $status): Collection;

    /**
     * Find invoices by date range
     */
    public function findByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Find unpaid invoices
     */
    public function findUnpaid(): Collection;

    /**
     * Find overdue invoices
     */
    public function findOverdue(): Collection;

    /**
     * Get invoice statistics
     */
    public function getStatistics(): array;
}
