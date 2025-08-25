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
        Schema::table('payment_methods', function (Blueprint $table) {
            // إضافة الحقول الجديدة
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['income', 'expense', 'asset', 'liability'])->default('income');
            $table->decimal('initial_balance', 15, 2)->default(0);

            // إضافة فهارس
            $table->index(['branch_id', 'is_active']);
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id', 'is_active']);
            $table->dropIndex(['type', 'is_active']);

            $table->dropColumn(['account_id', 'branch_id', 'type', 'initial_balance']);
        });
    }
};
