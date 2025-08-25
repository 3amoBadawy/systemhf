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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade'); // الحساب
            $table->enum('type', ['debit', 'credit']); // نوع المعاملة
            $table->decimal('amount', 15, 2); // المبلغ
            $table->text('description'); // الوصف
            $table->string('reference_type')->nullable(); // نوع المرجع
            $table->unsignedBigInteger('reference_id')->nullable(); // معرف المرجع
            $table->date('date'); // تاريخ المعاملة
            $table->foreignId('branch_id')->constrained()->onDelete('cascade'); // الفرع
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
