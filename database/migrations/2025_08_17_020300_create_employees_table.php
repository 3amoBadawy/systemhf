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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique(); // رقم الموظف
            $table->string('name'); // الاسم بالإنجليزية
            $table->string('name_ar'); // الاسم بالعربية
            $table->string('national_id')->unique(); // رقم الهوية
            $table->string('phone');
            $table->string('email')->unique();
            $table->date('birth_date');
            $table->date('hire_date'); // تاريخ التعيين
            $table->string('position'); // المنصب
            $table->string('position_ar'); // المنصب بالعربية
            $table->string('department'); // القسم
            $table->string('department_ar'); // القسم بالعربية
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // ربط بحساب المستخدم
            $table->decimal('base_salary', 10, 2); // الراتب الأساسي
            $table->decimal('commission_rate', 5, 2)->default(0); // نسبة العمولة
            $table->string('bank_name')->nullable(); // اسم البنك
            $table->string('bank_account')->nullable(); // رقم الحساب البنكي
            $table->string('iban')->nullable(); // رقم الآيبان
            $table->text('address');
            $table->text('emergency_contact')->nullable(); // جهة اتصال للطوارئ
            $table->enum('status', ['active', 'inactive', 'suspended', 'terminated'])->default('active');
            $table->date('termination_date')->nullable(); // تاريخ إنهاء الخدمة
            $table->text('termination_reason')->nullable(); // سبب إنهاء الخدمة
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
