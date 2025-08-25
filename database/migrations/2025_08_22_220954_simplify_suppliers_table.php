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
        Schema::table('suppliers', function (Blueprint $table) {
            // حذف Foreign Key أولاً
            $table->dropForeign(['branch_id']);

            // حذف الحقول غير المطلوبة
            $table->dropColumn([
                'name', // إزالة الاسم بالإنجليزية
                'city', // إزالة المدينة
                'tax_number', // إزالة الرقم الضريبي
                'commercial_record', // إزالة السجل التجاري
                'credit_limit', // إزالة حد الائتمان
                'current_balance', // إزالة الرصيد الحالي
                'payment_terms', // إزالة شروط الدفع
                'branch_id', // إزالة الفرع
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // إعادة إضافة الحقول المحذوفة
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('commercial_record')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->enum('payment_terms', ['immediate', '7_days', '15_days', '30_days', '60_days', '90_days'])->default('30_days');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
        });
    }
};
