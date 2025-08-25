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
        Schema::create('customer_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->string('type'); // payment_received, payment_confirmed, invoice_created, etc.
            $table->string('title'); // عنوان الإشعار
            $table->text('message'); // رسالة الإشعار
            $table->decimal('amount', 10, 2)->nullable(); // المبلغ إذا كان متعلق بدفع
            $table->string('status')->default('unread'); // unread, read
            $table->timestamp('read_at')->nullable(); // وقت قراءة الإشعار
            $table->timestamps();

            // فهارس
            $table->index('customer_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_notifications');
    }
};
