<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * تشغيل البيانات التجريبية
     */
    public function run(): void
    {
        $this->command->info('🚀 بدء إنشاء البيانات التجريبية...');

        // إنشاء الفرع الرئيسي
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

        // إنشاء المستخدم الافتراضي
        $defaultUser = User::firstOrCreate(
            ['email' => 'admin@egyptfurniture.com'],
            [
                'name' => 'مدير النظام',
                'name_ar' => 'مدير النظام',
                'email' => 'admin@egyptfurniture.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'role_id' => 1,
                'branch_id' => $mainBranch->id,
            ]
        );

        // إنشاء العملاء
        $customers = [
            [
                'name' => 'أحمد محمد',
                'name_ar' => 'أحمد محمد',
                'phone' => '+20 10 1234 5678',
                'email' => 'ahmed@example.com',
                'national_id' => '12345678901234',
                'address' => 'شارع النيل، المعادي، القاهرة',
                'is_active' => true,
                'branch_id' => $mainBranch->id,
            ],
            [
                'name' => 'فاطمة علي',
                'name_ar' => 'فاطمة علي',
                'phone' => '+20 10 8765 4321',
                'email' => 'fatima@example.com',
                'national_id' => '98765432109876',
                'address' => 'شارع الهرم، الجيزة',
                'is_active' => true,
                'branch_id' => $mainBranch->id,
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::firstOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );
        }

        // إنشاء الفئات
        $categories = [
            [
                'name' => 'غرف النوم',
                'name_ar' => 'غرف النوم',
                'description' => 'أثاث غرف النوم الفاخر',
                'is_active' => true,
                'branch_id' => $mainBranch->id,
            ],
            [
                'name' => 'غرف المعيشة',
                'name_ar' => 'غرف المعيشة',
                'description' => 'أثاث غرف المعيشة الأنيق',
                'is_active' => true,
                'branch_id' => $mainBranch->id,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }

        // إنشاء المنتجات
        $products = [
            [
                'name' => 'طقم غرفة نوم فاخر',
                'name_ar' => 'طقم غرفة نوم فاخر',
                'description' => 'طقم غرفة نوم من الخشب الطبيعي',
                'category_id' => 1,
                'price' => 15000,
                'cost_price' => 10000,
                'stock_quantity' => 5,
                'is_active' => true,
                'branch_id' => $mainBranch->id,
            ],
            [
                'name' => 'كنبة معيشة أنيقة',
                'name_ar' => 'كنبة معيشة أنيقة',
                'description' => 'كنبة معيشة من الجلد الطبيعي',
                'category_id' => 2,
                'price' => 8000,
                'cost_price' => 5000,
                'stock_quantity' => 3,
                'is_active' => true,
                'branch_id' => $mainBranch->id,
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }

        // إنشاء طرق الدفع
        $paymentMethods = [
            [
                'name' => 'نقداً',
                'name_ar' => 'نقداً',
                'code' => 'CASH',
                'description' => 'الدفع النقدي',
                'is_active' => true,
                'sort_order' => 1,
                'type' => 'cash',
                'branch_id' => $mainBranch->id,
            ],
        ];

        foreach ($paymentMethods as $methodData) {
            PaymentMethod::firstOrCreate(
                ['code' => $methodData['code']],
                $methodData
            );
        }

        // إنشاء الحسابات المالية
        $accounts = [
            [
                'name' => 'الصندوق النقدي',
                'name_ar' => 'الصندوق النقدي',
                'type' => 'cash',
                'balance' => 100000,
                'description' => 'الصندوق النقدي الرئيسي',
                'is_active' => true,
                'branch_id' => $mainBranch->id,
            ],
        ];

        foreach ($accounts as $accountData) {
            Account::create($accountData);
        }

        $this->command->info('✅ تم إنشاء جميع البيانات التجريبية بنجاح!');
    }
}
