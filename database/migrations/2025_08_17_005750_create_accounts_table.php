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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم بالإنجليزية
            $table->string('name_ar'); // الاسم بالعربية
            $table->enum('type', ['income', 'expense', 'asset', 'liability']); // نوع الحساب
            $table->decimal('balance', 15, 2)->default(0); // الرصيد الحالي
            $table->text('description')->nullable(); // الوصف
            $table->boolean('is_active')->default(true); // نشط أم لا
            $table->foreignId('branch_id')->constrained()->onDelete('cascade'); // الفرع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
