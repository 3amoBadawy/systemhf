<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Main Branch',
                'name_ar' => 'الفرع الرئيسي',
                'code' => 'MAIN',
                'address' => 'شارع الملك فهد، الرياض، المملكة العربية السعودية',
                'phone' => '+966 11 123 4567',
                'email' => 'main@high-furniture.com',
                'manager_name' => 'أحمد محمد',
                'is_active' => true,
                'sort_order' => 1,
                'notes' => 'الفرع الرئيسي للمعرض - مقر الإدارة',
            ],
            [
                'name' => 'Jeddah Branch',
                'name_ar' => 'فرع جدة',
                'code' => 'JEDDAH',
                'address' => 'شارع التحلية، جدة، المملكة العربية السعودية',
                'phone' => '+966 12 234 5678',
                'email' => 'jeddah@high-furniture.com',
                'manager_name' => 'محمد علي',
                'is_active' => true,
                'sort_order' => 2,
                'notes' => 'فرع جدة - يخدم المنطقة الغربية',
            ],
            [
                'name' => 'Dammam Branch',
                'name_ar' => 'فرع الدمام',
                'code' => 'DAMMAM',
                'address' => 'شارع الملك خالد، الدمام، المملكة العربية السعودية',
                'phone' => '+966 13 345 6789',
                'email' => 'dammam@high-furniture.com',
                'manager_name' => 'علي أحمد',
                'is_active' => true,
                'sort_order' => 3,
                'notes' => 'فرع الدمام - يخدم المنطقة الشرقية',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
