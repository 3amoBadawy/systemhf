<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash',
                'name_ar' => 'نقداً',
                'code' => 'CASH',
                'description' => 'دفع نقدي في الفرع',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Bank Transfer',
                'name_ar' => 'تحويل بنكي',
                'code' => 'BANK',
                'description' => 'تحويل من حساب بنكي',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Credit Card',
                'name_ar' => 'بطاقة ائتمان',
                'code' => 'CARD',
                'description' => 'دفع ببطاقة ائتمان',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Check',
                'name_ar' => 'شيك',
                'code' => 'CHECK',
                'description' => 'دفع بشيك بنكي',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Installment',
                'name_ar' => 'تقسيط',
                'code' => 'INSTALL',
                'description' => 'دفع على أقساط',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info('Payment Methods seeded successfully!');
    }
}
