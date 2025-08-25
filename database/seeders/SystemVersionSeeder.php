<?php

namespace Database\Seeders;

use App\Models\SystemVersion;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SystemVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $versions = [
            [
                'version' => '1.0.0',
                'version_name' => 'الإصدار الأول - معرض الأثاث المصري',
                'description' => 'الإصدار الأول من نظام إدارة معرض الأثاث المصري مع جميع الميزات الأساسية',
                'release_date' => Carbon::now(),
                'type' => 'major',
                'features' => [
                    'نظام إدارة العملاء',
                    'نظام إدارة المنتجات',
                    'نظام إدارة الفواتير',
                    'نظام إدارة المدفوعات',
                    'نظام إدارة الفروع',
                    'نظام إدارة الحسابات',
                    'نظام إدارة المصروفات',
                    'نظام إدارة الموظفين',
                    'نظام إدارة الفئات',

                    'نظام إدارة طرق الدفع',
                    'نظام إعدادات الأعمال',
                    'لوحة تحكم شاملة',
                    'واجهة حديثة ومتجاوبة',
                    'دعم اللغة العربية',
                    'نظام مصادقة آمن',
                    'تقارير شاملة',
                    'إحصائيات متقدمة',
                    'نظام بحث متطور',
                ],
                'bug_fixes' => [],
                'is_current' => true,
                'is_required' => false,
            ],
        ];

        foreach ($versions as $version) {
            SystemVersion::create($version);
        }
    }
}
