<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'name_ar' => 'مدير عام',
                'description' => 'مدير النظام مع جميع الصلاحيات',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Branch Manager',
                'name_ar' => 'مدير فرع',
                'description' => 'مدير فرع مع صلاحيات إدارية',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Sales Manager',
                'name_ar' => 'مدير مبيعات',
                'description' => 'مدير قسم المبيعات',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Sales Representative',
                'name_ar' => 'مندوب مبيعات',
                'description' => 'مندوب مبيعات مع عمولات',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Accountant',
                'name_ar' => 'محاسب',
                'description' => 'محاسب مع صلاحيات مالية',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Cashier',
                'name_ar' => 'كاشير',
                'description' => 'كاشير لاستقبال المدفوعات',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'HR Manager',
                'name_ar' => 'مدير موارد بشرية',
                'description' => 'مدير الموارد البشرية',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Warehouse Manager',
                'name_ar' => 'مدير مستودع',
                'description' => 'مدير المستودع والمخزون',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('Roles seeded successfully!');
    }
}
