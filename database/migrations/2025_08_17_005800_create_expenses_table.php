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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // العنوان بالإنجليزية
            $table->string('title_ar'); // العنوان بالعربية
            $table->decimal('amount', 15, 2); // المبلغ
            $table->string('category'); // الفئة
            $table->text('description')->nullable(); // الوصف
            $table->date('date'); // تاريخ المصروف
            $table->foreignId('branch_id')->constrained()->onDelete('cascade'); // الفرع
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade'); // طريقة الدفع
            $table->string('receipt_image')->nullable(); // صورة الإيصال
            $table->boolean('is_approved')->default(false); // معتمد أم لا
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // المعتمد بواسطة
            $table->timestamp('approved_at')->nullable(); // تاريخ الاعتماد
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
