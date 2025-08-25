<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
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

        if ($paymentMethod->isEmpty()) {
            $this->command->error('No payment methods found! Please run PaymentMethodSeeder first.');

            return;
        }

        // الحصول على الحساب
        $account = Account::where('type', 'cash')->first();
        if (! $account) {
            $account = Account::first();
        }

        if ($account->isEmpty()) {
            $this->command->error('No accounts found! Please run AccountSeeder first.');

            return;
        }

        $expenses = [
            [
                'title' => 'Office Supplies',
                'title_ar' => 'مستلزمات مكتبية',
                'amount' => 500.00,
                'category' => 'office',
                'description' => 'شراء مستلزمات مكتبية للفرع',
                'date' => now()->subDays(30),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'cash')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => true,
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(29),
                'notes' => 'مستلزمات مكتبية ضرورية',
            ],
            [
                'title' => 'Electricity Bill',
                'title_ar' => 'فاتورة الكهرباء',
                'amount' => 800.00,
                'category' => 'utilities',
                'description' => 'فاتورة الكهرباء الشهرية',
                'date' => now()->subDays(25),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'bank_transfer')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => true,
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(24),
                'notes' => 'فاتورة كهرباء شهر يناير',
            ],
            [
                'title' => 'Internet Service',
                'title_ar' => 'خدمة الإنترنت',
                'amount' => 300.00,
                'category' => 'utilities',
                'description' => 'اشتراك الإنترنت الشهري',
                'date' => now()->subDays(20),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'bank_transfer')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => true,
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(19),
                'notes' => 'اشتراك الإنترنت',
            ],
            [
                'title' => 'Cleaning Services',
                'title_ar' => 'خدمات التنظيف',
                'amount' => 600.00,
                'category' => 'services',
                'description' => 'خدمات تنظيف الفرع',
                'date' => now()->subDays(15),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'other')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => true,
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(14),
                'notes' => 'خدمات تنظيف أسبوعية',
            ],
            [
                'title' => 'Maintenance Equipment',
                'title_ar' => 'معدات الصيانة',
                'amount' => 1200.00,
                'category' => 'equipment',
                'description' => 'شراء معدات صيانة',
                'date' => now()->subDays(10),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'credit_card')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => true,
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(9),
                'notes' => 'معدات صيانة ضرورية',
            ],
            [
                'title' => 'Marketing Materials',
                'title_ar' => 'مواد تسويقية',
                'amount' => 400.00,
                'category' => 'marketing',
                'description' => 'طباعة مواد تسويقية',
                'date' => now()->subDays(5),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'cash')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => false,
                'notes' => 'طباعة كتالوجات جديدة',
            ],
            [
                'title' => 'Security Services',
                'title_ar' => 'خدمات الأمن',
                'amount' => 1500.00,
                'category' => 'services',
                'description' => 'خدمات أمنية للفرع',
                'date' => now()->subDays(2),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'bank_transfer')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => false,
                'notes' => 'خدمات أمنية شهرية',
            ],
            [
                'title' => 'Office Furniture',
                'title_ar' => 'أثاث مكتبي',
                'amount' => 2500.00,
                'category' => 'equipment',
                'description' => 'شراء أثاث مكتبي جديد',
                'date' => now(),
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->where('code', 'bank_transfer')->first()->id,
                'account_id' => $account->where('name', 'Operating Expenses')->first()->id,
                'is_approved' => false,
                'notes' => 'أثاث مكتبي للموظفين الجدد',
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }

        $this->command->info('Expenses seeded successfully!');
    }
}
