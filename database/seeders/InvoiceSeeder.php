<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على الفرع
        $branch = Branch::where('name', 'الفرع الرئيسي')->first();
        if (! $branch) {
            $branch = Branch::first();
        }

        if (! $branch) {
            $this->command->error('Main branch not found! Please run BranchSeeder first.');

            return;
        }

        // الحصول على المستخدم
        $user = User::first();
        if (! $user) {
            $this->command->error('No users found! Please run UserSeeder first.');

            return;
        }

        // الحصول على العميل
        $customer = Customer::where('name', 'أحمد محمد')->first();
        if (! $customer) {
            $customer = Customer::first();
        }

        if (! $customer) {
            $this->command->error('No customers found! Please run CustomerSeeder first.');

            return;
        }

        $invoices = [
            [
                'invoice_number' => 'INV-2025-001',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now()->subDays(30),
                'contract_number' => 'CNT-2025-001',
                'subtotal' => 15000.00,
                'discount' => 1000.00,
                'total' => 14000.00,
                'notes' => 'أثاث غرفة نوم كاملة',
                'branch_id' => $branch->id,
            ],
            [
                'invoice_number' => 'INV-2025-002',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now()->subDays(25),
                'contract_number' => 'CNT-2025-002',
                'subtotal' => 22000.00,
                'discount' => 1500.00,
                'total' => 20500.00,
                'notes' => 'أثاث صالة معيشة',
                'branch_id' => $branch->id,
            ],
            [
                'invoice_number' => 'INV-2025-003',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now()->subDays(20),
                'contract_number' => 'CNT-2025-003',
                'subtotal' => 18000.00,
                'discount' => 1200.00,
                'total' => 16800.00,
                'notes' => 'أثاث مكتب',
                'branch_id' => $branch->id,
            ],
            [
                'invoice_number' => 'INV-2025-004',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now()->subDays(15),
                'contract_number' => 'CNT-2025-004',
                'subtotal' => 12000.00,
                'discount' => 800.00,
                'total' => 11200.00,
                'notes' => 'أثاث غرفة طعام',
                'branch_id' => $branch->id,
            ],
            [
                'invoice_number' => 'INV-2025-005',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now()->subDays(10),
                'contract_number' => 'CNT-2025-005',
                'subtotal' => 25000.00,
                'discount' => 2000.00,
                'total' => 23000.00,
                'notes' => 'أثاث غرفة نوم رئيسية',
                'branch_id' => $branch->id,
            ],
            [
                'invoice_number' => 'INV-2025-006',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now()->subDays(5),
                'contract_number' => 'CNT-2025-006',
                'subtotal' => 16000.00,
                'discount' => 1000.00,
                'total' => 15000.00,
                'notes' => 'أثاث غرفة ضيوف',
                'branch_id' => $branch->id,
            ],
            [
                'invoice_number' => 'INV-2025-007',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now()->subDays(2),
                'contract_number' => 'CNT-2025-007',
                'subtotal' => 19000.00,
                'discount' => 1300.00,
                'total' => 17700.00,
                'notes' => 'أثاث غرفة دراسة',
                'branch_id' => $branch->id,
            ],
            [
                'invoice_number' => 'INV-2025-008',
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'sale_date' => now(),
                'contract_number' => 'CNT-2025-008',
                'subtotal' => 14000.00,
                'discount' => 900.00,
                'total' => 13100.00,
                'notes' => 'أثاث غرفة نوم أطفال',
                'branch_id' => $branch->id,
            ],
        ];

        foreach ($invoices as $invoice) {
            Invoice::create($invoice);
        }

        $this->command->info('Invoices seeded successfully!');
    }
}
