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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade'); // الفاتورة
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // المنتج
            $table->string('product_name'); // اسم المنتج (للحفظ)
            $table->integer('quantity'); // الكمية
            $table->decimal('unit_price', 10, 2); // سعر الوحدة
            $table->decimal('discount', 10, 2)->default(0); // خصم على المنتج
            $table->decimal('total_price', 10, 2); // السعر الإجمالي للمنتج
            $table->text('notes')->nullable(); // ملاحظات على المنتج
            $table->timestamps(); // تاريخ الإنشاء والتحديث

            // فهارس للبحث السريع
            $table->index('invoice_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
