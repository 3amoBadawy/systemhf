<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['category' => 'general', 'key' => 'app_name', 'value' => 'SystemHF', 'type' => 'string', 'name_ar' => 'اسم التطبيق', 'is_editable' => true, 'sort_order' => 1],
            ['category' => 'general', 'key' => 'default_language', 'value' => 'ar', 'type' => 'string', 'name_ar' => 'اللغة الافتراضية', 'is_editable' => true, 'sort_order' => 2],
            ['category' => 'display', 'key' => 'pagination_per_page', 'value' => '20', 'type' => 'integer', 'name_ar' => 'عدد العناصر في الصفحة', 'is_editable' => true, 'sort_order' => 1],
            ['category' => 'reports', 'key' => 'report_pagination_per_page', 'value' => '50', 'type' => 'integer', 'name_ar' => 'عدد عناصر التقارير في الصفحة', 'is_editable' => true, 'sort_order' => 1],
        ];

        foreach ($defaults as $s) {
            SystemSetting::updateOrCreate(
                ['key' => $s['key']],
                $s
            );
        }
    }
}
