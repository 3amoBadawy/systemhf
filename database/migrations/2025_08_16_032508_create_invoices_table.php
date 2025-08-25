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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // رقم الفاتورة
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // العميل
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // البائع/الموظف
            $table->date('sale_date'); // تاريخ البيع
            $table->string('contract_number'); // رقم العقد في المعرض
            $table->string('contract_image')->nullable(); // صورة العقد
            $table->enum('status', ['مدفوع', 'مؤجل', 'مقسم', 'ملغي'])->default('مدفوع'); // حالة الفاتورة
            $table->decimal('subtotal', 12, 2); // المجموع الفرعي
            $table->decimal('discount', 10, 2)->default(0); // الخصم
            $table->decimal('total', 12, 2); // المجموع النهائي
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps(); // تاريخ الإنشاء والتحديث

            // فهارس للبحث السريع
            $table->index('invoice_number');
            $table->index('contract_number');
            $table->index('sale_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
