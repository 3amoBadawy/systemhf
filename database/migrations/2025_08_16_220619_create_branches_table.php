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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم بالإنجليزية
            $table->string('name_ar'); // الاسم بالعربية
            $table->string('code')->unique(); // الكود الفريد
            $table->text('address')->nullable(); // العنوان
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->string('manager_name')->nullable(); // اسم المدير
            $table->boolean('is_active')->default(true); // نشط أم لا
            $table->integer('sort_order')->default(0); // ترتيب العرض
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
