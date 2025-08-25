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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المورد
            $table->string('name_ar'); // اسم المورد بالعربية
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('phone2')->nullable(); // رقم هاتف ثاني
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            $table->string('governorate')->nullable(); // المحافظة
            $table->string('city')->nullable(); // المدينة
            $table->string('tax_number')->nullable(); // الرقم الضريبي
            $table->string('commercial_record')->nullable(); // السجل التجاري
            $table->text('notes')->nullable(); // ملاحظات
            $table->decimal('credit_limit', 15, 2)->default(0); // حد الائتمان
            $table->decimal('current_balance', 15, 2)->default(0); // الرصيد الحالي
            $table->enum('payment_terms', ['immediate', '7_days', '15_days', '30_days', '60_days', '90_days'])->default('30_days'); // شروط الدفع
            $table->boolean('is_active')->default(true); // نشط
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null'); // الفرع
            $table->timestamps();

            // فهارس
            $table->index(['name', 'name_ar']);
            $table->index('phone');
            $table->index('is_active');
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
