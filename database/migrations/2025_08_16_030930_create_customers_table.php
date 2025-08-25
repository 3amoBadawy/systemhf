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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم العميل
            $table->string('phone'); // رقم التلفون الأساسي
            $table->string('phone2')->nullable(); // رقم التلفون الثاني (اختياري)
            $table->string('country')->default('مصر'); // البلد
            $table->string('governorate'); // المحافظة
            $table->text('address'); // العنوان التفصيلي
            $table->enum('customer_type', ['جديد', 'متكرر', 'VIP'])->default('جديد'); // نوع العميل
            $table->text('notes')->nullable(); // ملاحظات
            $table->boolean('is_active')->default(true); // حالة العميل
            $table->timestamps(); // تاريخ الإنشاء والتحديث

            // فهارس للبحث السريع
            $table->index('phone');
            $table->index('customer_type');
            $table->index('governorate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
