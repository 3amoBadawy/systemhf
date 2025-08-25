<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
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

        // الحصول على طريقة الدفع
        $paymentMethod = PaymentMethod::where('code', 'CASH')->first();
        if (! $paymentMethod) {
            $paymentMethod = PaymentMethod::first();
        }

        // الحصول على الحساب
        $account = Account::where('type', 'cash')->first();
        if (! $account) {
            $account = Account::first();
        }

        // الحصول على الفاتورة
        $invoice = Invoice::where('status', 'completed')->first();
        if (! $invoice) {
            $invoice = Invoice::first();
        }

        if (! $paymentMethod) {
            $this->command->error('No payment methods found! Please run PaymentMethodSeeder first.');

            return;
        }

        // الحصول على الحسابات
        $accounts = Account::where('branch_id', $branch->id)->get();

        if ($accounts->isEmpty()) {
            $this->command->error('No accounts found! Please run AccountSeeder first.');

            return;
        }

        // الحصول على الفواتير
        $invoices = Invoice::where('branch_id', $branch->id)->get();

        if ($invoices->isEmpty()) {
            $this->command->error('No invoices found! Please run InvoiceSeeder first.');

            return;
        }

        $payments = [
            [
                'customer_id' => $invoices->random()->customer_id,
                'user_id' => $user->id,
                'payment_method' => 'cash',
                'payment_method_id' => $paymentMethod->id,
                'account_id' => $account->id,
                'amount' => 5000.00,
                'payment_date' => now()->subDays(25),
                'payment_status' => 'completed',
                'reference_number' => 'REF-001',
                'notes' => 'دفعة أولى',
            ],
            [
                'customer_id' => $invoices->random()->customer_id,
                'user_id' => $user->id,
                'payment_method' => 'bank_transfer',
                'payment_method_id' => $paymentMethod->where('code', 'bank_transfer')->first()->id,
                'account_id' => $accounts->where('name', 'Bank Account - Al Rajhi')->first()->id,
                'amount' => 8000.00,
                'payment_date' => now()->subDays(20),
                'payment_status' => 'completed',
                'reference_number' => 'REF-002',
                'notes' => 'تحويل بنكي',
            ],
            [
                'customer_id' => $invoices->random()->customer_id,
                'user_id' => $user->id,
                'payment_method' => 'credit_card',
                'payment_method_id' => $paymentMethod->where('code', 'credit_card')->first()->id,
                'account_id' => $accounts->where('name', 'Bank Account - SABB')->first()->id,
                'amount' => 12000.00,
                'payment_date' => now()->subDays(15),
                'payment_status' => 'completed',
                'reference_number' => 'REF-003',
                'notes' => 'دفع ببطاقة ائتمان',
            ],
            [
                'customer_id' => $invoices->random()->customer_id,
                'user_id' => $user->id,
                'payment_method' => 'other',
                'payment_method_id' => $paymentMethod->where('code', 'other')->first()->id,
                'account_id' => $accounts->where('name', 'Bank Account - Al Rajhi')->first()->id,
                'amount' => 6000.00,
                'payment_date' => now()->subDays(10),
                'payment_status' => 'completed',
                'reference_number' => 'REF-004',
                'notes' => 'دفع بشيك',
            ],
            [
                'customer_id' => $invoices->random()->customer_id,
                'user_id' => $user->id,
                'payment_method' => 'other',
                'payment_method_id' => $paymentMethod->where('code', 'other')->first()->id,
                'account_id' => $accounts->where('name', 'Accounts Receivable')->first()->id,
                'amount' => 3000.00,
                'payment_date' => now()->subDays(5),
                'payment_status' => 'completed',
                'reference_number' => 'REF-005',
                'notes' => 'قسط شهري',
            ],
            [
                'customer_id' => $invoices->random()->customer_id,
                'user_id' => $user->id,
                'payment_method' => 'cash',
                'payment_method_id' => $paymentMethod->where('code', 'cash')->first()->id,
                'account_id' => $accounts->where('name', 'Main Cash Account')->first()->id,
                'amount' => 7000.00,
                'payment_date' => now()->subDays(2),
                'payment_status' => 'completed',
                'reference_number' => 'REF-006',
                'notes' => 'دفعة نقدية',
            ],
            [
                'customer_id' => $invoices->random()->customer_id,
                'user_id' => $user->id,
                'payment_method' => 'bank_transfer',
                'payment_method_id' => $paymentMethod->where('code', 'bank_transfer')->first()->id,
                'account_id' => $accounts->where('name', 'Bank Account - SABB')->first()->id,
                'amount' => 4500.00,
                'payment_date' => now(),
                'payment_status' => 'pending',
                'reference_number' => 'REF-007',
                'notes' => 'تحويل معلق',
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }

        $this->command->info('Payments seeded successfully!');
    }
}
