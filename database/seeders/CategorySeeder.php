<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'غرف نوم',
                'description' => 'غرف النوم بأحدث التصاميم والألوان',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'غرف معيشة',
                'description' => 'غرف المعيشة المريحة والأنيقة',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'مطابخ',
                'description' => 'مطابخ عصرية وعملية',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'حمامات',
                'description' => 'حمامات فاخرة ومريحة',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'مكاتب',
                'description' => 'مكاتب أنيقة للعمل والدراسة',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'حدائق',
                'description' => 'أثاث حدائق خارجي',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
