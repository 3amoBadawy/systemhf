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
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->decimal('allocated_amount', 10, 2); // المبلغ المخصص لهذه الفاتورة
            $table->decimal('remaining_balance', 10, 2); // الرصيد المتبقي للفاتورة بعد الدفع
            $table->string('allocation_status')->default('partial'); // partial, full
            $table->text('notes')->nullable(); // ملاحظات حول التوزيع
            $table->timestamps();

            // فهارس
            $table->index('payment_id');
            $table->index('invoice_id');
            $table->index('allocation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
