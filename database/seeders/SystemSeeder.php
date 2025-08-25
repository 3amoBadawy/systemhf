<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 بدء إنشاء البيانات الأساسية للنظام...');

        // إنشاء الشِفتات
        $this->createShifts();

        // إنشاء الفرع الرئيسي
        $this->createMainBranch();

        // إنشاء الأدوار الافتراضية
        $this->createDefaultRoles();

        // إنشاء الموظفين الافتراضين
        $this->createDefaultEmployees();

        // إنشاء المستخدمين الافتراضين
        $this->createDefaultUsers();

        $this->command->info('✅ تم إنشاء جميع البيانات الأساسية بنجاح!');
    }

    /**
     * إنشاء الشِفتات
     */
    private function createShifts(): void
    {
        $this->command->info('⏰ إنشاء الشِفتات...');

        $shifts = [
            [
                'name' => 'صباحي',
                'name_ar' => 'صباحي',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'is_active' => true,
                'branch_id' => 1,
            ],
            [
                'name' => 'مسائي',
                'name_ar' => 'مسائي',
                'start_time' => '16:00:00',
                'end_time' => '00:00:00',
                'is_active' => true,
                'branch_id' => 1,
            ],
        ];

        foreach ($shifts as $shiftData) {
            Shift::firstOrCreate(
                ['name' => $shiftData['name']],
                $shiftData
            );
        }

        $this->command->info('✅ تم إنشاء '.count($shifts).' شِفت');
    }

    /**
     * إنشاء الفرع الرئيسي
     */
    private function createMainBranch(): void
    {
        $this->command->info('🏢 إنشاء الفرع الرئيسي...');

        $mainBranch = Branch::firstOrCreate(
            ['code' => 'MAIN'],
            [
                'name' => 'الفرع الرئيسي',
                'name_ar' => 'الفرع الرئيسي',
                'code' => 'MAIN',
                'address' => 'شارع التحرير، وسط البلد، القاهرة',
                'phone' => '+20 2 2574 1234',
                'email' => 'main@egyptfurniture.com',
                'is_active' => true,
            ]
        );

        $this->command->info('✅ تم إنشاء الفرع الرئيسي');
    }

    /**
     * إنشاء الأدوار الافتراضية
     */
    private function createDefaultRoles(): void
    {
        $this->command->info('👥 إنشاء الأدوار الافتراضية...');

        $roles = [
            [
                'name' => 'admin',
                'name_ar' => 'مدير النظام',
                'description' => 'مدير النظام مع جميع الصلاحيات',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'manager',
                'name_ar' => 'مدير',
                'description' => 'مدير الفرع مع صلاحيات إدارية',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'sales',
                'name_ar' => 'مندوب مبيعات',
                'description' => 'مندوب مبيعات مع صلاحيات المبيعات',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'accountant',
                'name_ar' => 'محاسب',
                'description' => 'محاسب مع صلاحيات مالية',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'employee',
                'name_ar' => 'موظف',
                'description' => 'موظف عادي مع صلاحيات محدودة',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('✅ تم إنشاء '.count($roles).' دور');
    }

    /**
     * إنشاء الموظفين الافتراضين
     */
    private function createDefaultEmployees(): void
    {
        $this->command->info('👨‍💼 إنشاء الموظفين الافتراضين...');

        $adminRole = Role::firstWhere('name', 'admin');
        $mainBranch = Branch::firstWhere('code', 'MAIN');

        if (! $adminRole || ! $mainBranch) {
            $this->command->error('Admin role or main branch not found!');

            return;
        }

        $employees = [
            [
                'name' => 'مدير النظام',
                'name_ar' => 'مدير النظام',
                'email' => 'admin@egyptfurniture.com',
                'phone' => '+20 10 1234 5678',
                'national_id' => '12345678901234',
                'position' => 'مدير النظام',
                'department' => 'الإدارة',
                'hire_date' => now()->subYear(),
                'base_salary' => 15000,
                'commission_rate' => 0,
                'is_active' => true,
                'role_id' => $adminRole->id,
                'branch_id' => $mainBranch->id,
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::firstOrCreate(
                ['email' => $employeeData['email']],
                $employeeData
            );
        }

        $this->command->info('✅ تم إنشاء '.count($employees).' موظف');
    }

    /**
     * إنشاء المستخدمين الافتراضين
     */
    private function createDefaultUsers(): void
    {
        $this->command->info('👤 إنشاء المستخدمين الافتراضين...');

        $adminRole = Role::firstWhere('name', 'admin');
        $mainBranch = Branch::firstWhere('code', 'MAIN');

        if (! $adminRole || ! $mainBranch) {
            $this->command->error('Admin role or main branch not found!');

            return;
        }

        $users = [
            [
                'name' => 'مدير النظام',
                'name_ar' => 'مدير النظام',
                'email' => 'admin@egyptfurniture.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'role_id' => $adminRole->id,
                'branch_id' => $mainBranch->id,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ تم إنشاء '.count($users).' مستخدم');
    }
}
