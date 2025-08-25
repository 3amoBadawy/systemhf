<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use Illuminate\Database\Seeder;

class BusinessSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $businessSettings = [
            'business_name' => 'Egypt Furniture Gallery',
            'business_name_ar' => 'معرض الأثاث المصري',
            'default_profit_percent' => 30.00,
            'currency' => 'EGP',
            'currency_symbol' => 'ج.م',
            'currency_symbol_placement' => 'after',
            'timezone' => 'Africa/Cairo',
            'date_format' => 'd-m-Y',
            'time_format' => 'H:i',
            'phone' => '+20 100 123 4567',
            'email' => 'info@egypt-furniture.com',
            'address' => 'شارع التحرير، وسط البلد، القاهرة، جمهورية مصر العربية',
            'description' => 'معرض متخصص في بيع الأثاث الفاخر والتصميمات الحديثة في مصر',
        ];
        BusinessSetting::create($businessSettings);
    }
}
