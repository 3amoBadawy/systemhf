<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المنتج
            $table->string('category'); // الفئة (غرفة نوم، صالة، مطبخ، إلخ)
            $table->text('description')->nullable(); // وصف المنتج
            $table->decimal('price', 10, 2); // السعر
            $table->decimal('cost_price', 10, 2)->nullable(); // سعر التكلفة
            $table->integer('stock_quantity')->default(0); // الكمية المتوفرة
            $table->string('sku')->nullable(); // الكود التعريفي
            $table->string('image')->nullable(); // صورة المنتج
            $table->boolean('is_active')->default(true); // حالة المنتج
            $table->timestamps(); // تاريخ الإنشاء والتحديث

            // فهارس للبحث السريع
            $table->index('name');
            $table->index('category');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
