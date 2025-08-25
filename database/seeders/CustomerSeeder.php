<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
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

        $customers = [
            [
                'name' => 'Ahmed Al-Rashid',
                'phone' => '+966501234567',
                'governorate' => 'الرياض',
                'address' => 'شارع الملك فهد، الرياض',
                'notes' => 'عميل VIP - أحمد الراشد',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Fatima Al-Zahra',
                'phone' => '+966502345678',
                'governorate' => 'جدة',
                'address' => 'شارع التحلية، جدة',
                'notes' => 'عميلة VIP - فاطمة الزهراء',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Mohammed Al-Sayed',
                'phone' => '+966503456789',
                'governorate' => 'الدمام',
                'address' => 'شارع الملك خالد، الدمام',
                'notes' => 'عميل VIP - محمد السيد',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Aisha Al-Mansouri',
                'phone' => '+966504567890',
                'governorate' => 'الرياض',
                'address' => 'شارع العليا، الرياض',
                'notes' => 'عميلة VIP - عائشة المنصوري',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Omar Al-Hamdan',
                'phone' => '+966505678901',
                'governorate' => 'جدة',
                'address' => 'شارع التحلية، جدة',
                'notes' => 'عميل VIP - عمر الحمدان',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Layla Al-Qahtani',
                'phone' => '+966506789012',
                'governorate' => 'الدمام',
                'address' => 'شارع الملك فهد، الدمام',
                'notes' => 'عميلة VIP - ليلى القحطاني',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Khalid Al-Otaibi',
                'phone' => '+966507890123',
                'governorate' => 'الرياض',
                'address' => 'شارع العليا، الرياض',
                'notes' => 'عميل VIP - خالد العتيبي',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Noor Al-Shehri',
                'phone' => '+966508901234',
                'governorate' => 'جدة',
                'address' => 'شارع التحلية، جدة',
                'notes' => 'عميلة VIP - نور الشهري',
                'is_active' => true,
                'branch_id' => $branch->id,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('Customers seeded successfully!');
    }
}
