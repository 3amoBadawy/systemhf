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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('base_salary', 10, 2);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->decimal('overtime_rate', 8, 2)->default(0);
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->json('allowances')->nullable(); // transport, meals, housing
            $table->json('deductions')->nullable(); // late, absence, penalties
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->enum('status', ['generated', 'reviewed', 'approved', 'paid'])->default('generated');
            $table->foreignId('generated_by')->constrained('employees')->onDelete('cascade');
            $table->datetime('generated_at');
            $table->foreignId('reviewed_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->datetime('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->datetime('approved_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->datetime('paid_at')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'card'])->nullable();
            $table->string('bank_transfer_ref')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_exported_to_bank')->default(false);
            $table->timestamps();

            // فهارس
            $table->index(['employee_id', 'year', 'month']);
            $table->index(['year', 'month']);
            $table->index(['status']);
            $table->unique(['employee_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
