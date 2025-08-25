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
        // إضافة branch_id إلى customers
        if (! Schema::hasColumn('customers', 'branch_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            });
        }

        // إضافة branch_id إلى invoices
        if (! Schema::hasColumn('invoices', 'branch_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            });
        }

        // إضافة account_id إلى payments
        if (! Schema::hasColumn('payments', 'account_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            });
        }

        // إضافة account_id إلى expenses
        if (! Schema::hasColumn('expenses', 'account_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            });
        }

        // تغيير payment_method إلى payment_method_id في payments
        if (Schema::hasColumn('payments', 'payment_method') && ! Schema::hasColumn('payments', 'payment_method_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('payment_method_id')->nullable()->constrained()->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إزالة branch_id من customers
        if (Schema::hasColumn('customers', 'branch_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }

        // إزالة branch_id من invoices
        if (Schema::hasColumn('invoices', 'branch_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }

        // إزالة account_id من payments
        if (Schema::hasColumn('payments', 'account_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['account_id']);
                $table->dropColumn('account_id');
            });
        }

        // إزالة account_id من expenses
        if (Schema::hasColumn('expenses', 'account_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropForeign(['account_id']);
                $table->dropColumn('account_id');
            });
        }

        // إزالة payment_method_id من payments
        if (Schema::hasColumn('payments', 'payment_method_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn('payment_method_id');
            });
        }
    }
};
