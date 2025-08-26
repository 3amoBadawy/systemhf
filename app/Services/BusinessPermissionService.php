<?php

namespace App\Services;

class BusinessPermissionService
{
    /**
     * التحقق من صلاحية إدارة المنتجات
     */
    public static function canManageProducts(): bool
    {
        return UserPermissionService::hasAnyPermission([
            'products.create',
            'products.edit',
            'products.delete',
            'products.inventory',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة العملاء
     */
    public static function canManageCustomers(): bool
    {
        return UserPermissionService::hasAnyPermission([
            'customers.create',
            'customers.edit',
            'customers.delete',
            'customers.credit',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة الفواتير
     */
    public static function canManageInvoices(): bool
    {
        return UserPermissionService::hasAnyPermission([
            'invoices.create',
            'invoices.edit',
            'invoices.delete',
            'invoices.approve',
            'invoices.cancel',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة المدفوعات
     */
    public static function canManagePayments(): bool
    {
        return UserPermissionService::hasAnyPermission([
            'payments.create',
            'payments.edit',
            'payments.delete',
            'payments.refund',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة المصروفات
     */
    public static function canManageExpenses(): bool
    {
        return UserPermissionService::hasAnyPermission([
            'expenses.create',
            'expenses.edit',
            'expenses.delete',
            'expenses.approve',
        ]);
    }

    /**
     * التحقق من صلاحية عرض التقارير
     */
    public static function canViewReports(): bool
    {
        return UserPermissionService::hasPermission('reports.view');
    }

    /**
     * التحقق من صلاحية تصدير التقارير
     */
    public static function canExportReports(): bool
    {
        return UserPermissionService::hasPermission('reports.export');
    }

    /**
     * التحقق من صلاحية إدارة الإعدادات
     */
    public static function canManageSettings(): bool
    {
        return UserPermissionService::hasAnyPermission([
            'settings.edit',
            'settings.business',
            'settings.system',
            'settings.branches',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة النظام
     */
    public static function canManageSystem(): bool
    {
        return UserPermissionService::hasAnyPermission([
            'system.admin',
            'system.maintenance',
            'system.backup',
            'system.logs',
        ]);
    }
}
