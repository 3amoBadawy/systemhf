<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
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

        $accounts = [
            [
                'name' => 'Main Cash Account',
                'name_ar' => 'الحساب النقدي الرئيسي',
                'type' => 'asset',
                'balance' => 50000.00,
                'description' => 'الحساب النقدي الرئيسي للفرع',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Bank Account - Al Rajhi',
                'name_ar' => 'الحساب البنكي - الراجحي',
                'type' => 'asset',
                'balance' => 150000.00,
                'description' => 'الحساب البنكي في بنك الراجحي',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Bank Account - SABB',
                'name_ar' => 'الحساب البنكي - ساب',
                'type' => 'asset',
                'balance' => 75000.00,
                'description' => 'الحساب البنكي في بنك ساب',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Accounts Receivable',
                'name_ar' => 'الذمم المدينة',
                'type' => 'asset',
                'balance' => 25000.00,
                'description' => 'المبالغ المستحقة من العملاء',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Inventory Account',
                'name_ar' => 'حساب المخزون',
                'type' => 'asset',
                'balance' => 300000.00,
                'description' => 'قيمة المخزون الحالي',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Accounts Payable',
                'name_ar' => 'الذمم الدائنة',
                'type' => 'liability',
                'balance' => 45000.00,
                'description' => 'المبالغ المستحقة للموردين',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Sales Revenue',
                'name_ar' => 'إيرادات المبيعات',
                'type' => 'income',
                'balance' => 0.00,
                'description' => 'إيرادات المبيعات',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Operating Expenses',
                'name_ar' => 'المصروفات التشغيلية',
                'type' => 'expense',
                'balance' => 0.00,
                'description' => 'المصروفات التشغيلية',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
        ];

        foreach ($accounts as $accountData) {
            Account::create($accountData);
        }

        $this->command->info('Accounts seeded successfully!');
    }
}
