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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // مبلغ الدفع
            $table->string('payment_method'); // طريقة الدفع (نقداً، شيك، تحويل بنكي، إلخ)
            $table->string('payment_status')->default('pending'); // حالة الدفع (pending, completed, failed, refunded)
            $table->date('payment_date'); // تاريخ الدفع
            $table->string('reference_number')->nullable(); // رقم المرجع (رقم الشيك، رقم التحويل، إلخ)
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->string('receipt_image')->nullable(); // صورة الإيصال
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المستخدم الذي أضاف الدفع
            $table->timestamps();

            // فهارس للبحث السريع
            $table->index('invoice_id');
            $table->index('customer_id');
            $table->index('payment_date');
            $table->index('payment_status');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
