<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // صلاحيات العملاء
            ['name' => 'customers.view', 'name_ar' => 'عرض العملاء', 'group' => 'customers'],
            ['name' => 'customers.create', 'name_ar' => 'إنشاء عملاء', 'group' => 'customers'],
            ['name' => 'customers.edit', 'name_ar' => 'تعديل العملاء', 'group' => 'customers'],
            ['name' => 'customers.delete', 'name_ar' => 'حذف العملاء', 'group' => 'customers'],

            // صلاحيات الفواتير
            ['name' => 'invoices.view', 'name_ar' => 'عرض الفواتير', 'group' => 'invoices'],
            ['name' => 'invoices.create', 'name_ar' => 'إنشاء فواتير', 'group' => 'invoices'],
            ['name' => 'invoices.edit', 'name_ar' => 'تعديل الفواتير', 'group' => 'invoices'],
            ['name' => 'invoices.delete', 'name_ar' => 'حذف الفواتير', 'group' => 'invoices'],
            ['name' => 'invoices.print', 'name_ar' => 'طباعة الفواتير', 'group' => 'invoices'],

            // صلاحيات المدفوعات
            ['name' => 'payments.view', 'name_ar' => 'عرض المدفوعات', 'group' => 'payments'],
            ['name' => 'payments.create', 'name_ar' => 'إنشاء مدفوعات', 'group' => 'payments'],
            ['name' => 'payments.edit', 'name_ar' => 'تعديل المدفوعات', 'group' => 'payments'],
            ['name' => 'payments.delete', 'name_ar' => 'حذف المدفوعات', 'group' => 'payments'],

            // صلاحيات طرق الدفع
            ['name' => 'payment_methods.view', 'name_ar' => 'عرض طرق الدفع', 'group' => 'payment_methods'],
            ['name' => 'payment_methods.create', 'name_ar' => 'إنشاء طرق دفع', 'group' => 'payment_methods'],
            ['name' => 'payment_methods.edit', 'name_ar' => 'تعديل طرق الدفع', 'group' => 'payment_methods'],
            ['name' => 'payment_methods.delete', 'name_ar' => 'حذف طرق الدفع', 'group' => 'payment_methods'],

            // صلاحيات الفروع
            ['name' => 'branches.view', 'name_ar' => 'عرض الفروع', 'group' => 'branches'],
            ['name' => 'branches.create', 'name_ar' => 'إنشاء فروع', 'group' => 'branches'],
            ['name' => 'branches.edit', 'name_ar' => 'تعديل الفروع', 'group' => 'branches'],
            ['name' => 'branches.delete', 'name_ar' => 'حذف الفروع', 'group' => 'branches'],

            // صلاحيات الحسابات المالية
            ['name' => 'accounts.view', 'name_ar' => 'عرض الحسابات', 'group' => 'accounts'],
            ['name' => 'accounts.create', 'name_ar' => 'إنشاء حسابات', 'group' => 'accounts'],
            ['name' => 'accounts.edit', 'name_ar' => 'تعديل الحسابات', 'group' => 'accounts'],
            ['name' => 'accounts.delete', 'name_ar' => 'حذف الحسابات', 'group' => 'accounts'],

            // صلاحيات المصروفات
            ['name' => 'expenses.view', 'name_ar' => 'عرض المصروفات', 'group' => 'expenses'],
            ['name' => 'expenses.create', 'name_ar' => 'إنشاء مصروفات', 'group' => 'expenses'],
            ['name' => 'expenses.edit', 'name_ar' => 'تعديل المصروفات', 'group' => 'expenses'],
            ['name' => 'expenses.delete', 'name_ar' => 'حذف المصروفات', 'group' => 'expenses'],
            ['name' => 'expenses.approve', 'name_ar' => 'الموافقة على المصروفات', 'group' => 'expenses'],

            // صلاحيات الموظفين
            ['name' => 'employees.view', 'name_ar' => 'عرض الموظفين', 'group' => 'employees'],
            ['name' => 'employees.create', 'name_ar' => 'إنشاء موظفين', 'group' => 'employees'],
            ['name' => 'employees.edit', 'name_ar' => 'تعديل الموظفين', 'group' => 'employees'],
            ['name' => 'employees.delete', 'name_ar' => 'حذف الموظفين', 'group' => 'employees'],
            ['name' => 'employees.report', 'name_ar' => 'تقارير الموظفين', 'group' => 'employees'],

            // صلاحيات الحضور
            ['name' => 'attendance.view', 'name_ar' => 'عرض الحضور', 'group' => 'attendance'],
            ['name' => 'attendance.create', 'name_ar' => 'تسجيل الحضور', 'group' => 'attendance'],
            ['name' => 'attendance.edit', 'name_ar' => 'تعديل الحضور', 'group' => 'attendance'],
            ['name' => 'attendance.report', 'name_ar' => 'تقارير الحضور', 'group' => 'attendance'],

            // صلاحيات العمولات
            ['name' => 'commissions.view', 'name_ar' => 'عرض العمولات', 'group' => 'commissions'],
            ['name' => 'commissions.create', 'name_ar' => 'إنشاء عمولات', 'group' => 'commissions'],
            ['name' => 'commissions.edit', 'name_ar' => 'تعديل العمولات', 'group' => 'commissions'],
            ['name' => 'commissions.approve', 'name_ar' => 'الموافقة على العمولات', 'group' => 'commissions'],
            ['name' => 'commissions.report', 'name_ar' => 'تقارير العمولات', 'group' => 'commissions'],

            // صلاحيات المرتبات
            ['name' => 'salaries.view', 'name_ar' => 'عرض المرتبات', 'group' => 'salaries'],
            ['name' => 'salaries.create', 'name_ar' => 'إنشاء مرتبات', 'group' => 'salaries'],
            ['name' => 'salaries.edit', 'name_ar' => 'تعديل المرتبات', 'group' => 'salaries'],
            ['name' => 'salaries.approve', 'name_ar' => 'الموافقة على المرتبات', 'group' => 'salaries'],
            ['name' => 'salaries.report', 'name_ar' => 'تقارير المرتبات', 'group' => 'salaries'],

            // صلاحيات النظام
            ['name' => 'system.view', 'name_ar' => 'عرض النظام', 'group' => 'system'],
            ['name' => 'system.settings', 'name_ar' => 'إعدادات النظام', 'group' => 'system'],
            ['name' => 'system.users', 'name_ar' => 'إدارة المستخدمين', 'group' => 'system'],
            ['name' => 'system.roles', 'name_ar' => 'إدارة الأدوار', 'group' => 'system'],
            ['name' => 'system.permissions', 'name_ar' => 'إدارة الصلاحيات', 'group' => 'system'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // ربط الصلاحيات بالأدوار
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminPermissions = Permission::whereIn('name', [
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
                'system_settings.view', 'system_settings.edit',
                'reports.view', 'reports.export',
            ])->get();
            $adminRole->permissions()->sync($adminPermissions->pluck('id'));
        }

        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerPermissions = Permission::whereIn('name', [
                'customers.view', 'customers.create', 'customers.edit',
                'products.view', 'products.create', 'products.edit',
                'invoices.view', 'invoices.create', 'invoices.edit',
                'payments.view', 'payments.create',
                'reports.view',
            ])->get();
            $managerRole->permissions()->sync($managerPermissions->pluck('id'));
        }

        $salesRole = Role::where('name', 'sales')->first();
        if ($salesRole) {
            $salesPermissions = Permission::whereIn('name', [
                'customers.view', 'customers.create',
                'products.view',
                'invoices.view', 'invoices.create',
                'payments.view', 'payments.create',
            ])->get();
            $salesRole->permissions()->sync($salesPermissions->pluck('id'));
        }

        $accountantRole = Role::where('name', 'accountant')->first();
        if ($accountantRole) {
            $accountantPermissions = Permission::whereIn('name', [
                'invoices.view',
                'payments.view', 'payments.create', 'payments.edit',
                'expenses.view', 'expenses.create', 'expenses.edit',
                'reports.view', 'reports.export',
            ])->get();
            $accountantRole->permissions()->sync($accountantPermissions->pluck('id'));
        }

        $employeeRole = Role::where('name', 'employee')->first();
        if ($employeeRole) {
            $employeePermissions = Permission::whereIn('name', [
                'products.view',
                'invoices.view',
            ])->get();
            $employeeRole->permissions()->sync($employeePermissions->pluck('id'));
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
