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
        // إصلاح جدول العملاء - إضافة customer_type المفقود
        if (Schema::hasTable('customers') && ! Schema::hasColumn('customers', 'customer_type')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->enum('customer_type', ['جديد', 'متكرر', 'VIP', 'مميز'])->default('جديد')->after('address');
            });
        }

        // إصلاح جدول المنتجات - إضافة الحقول المفقودة
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'name_ar')) {
                    $table->string('name_ar')->after('name');
                }
                if (! Schema::hasColumn('products', 'description_ar')) {
                    $table->text('description_ar')->nullable()->after('description');
                }
                if (! Schema::hasColumn('products', 'category_id')) {
                    $table->foreignId('category_id')->nullable()->after('description_ar')->constrained()->onDelete('set null');
                }
                if (! Schema::hasColumn('products', 'supplier_id')) {
                    $table->foreignId('supplier_id')->nullable()->after('category_id')->constrained()->onDelete('set null');
                }
                if (! Schema::hasColumn('products', 'branch_id')) {
                    $table->foreignId('branch_id')->after('supplier_id')->constrained()->onDelete('cascade');
                }
                if (! Schema::hasColumn('products', 'profit_percentage')) {
                    $table->decimal('profit_percentage', 5, 2)->default(0)->after('price');
                }
                if (! Schema::hasColumn('products', 'stock_quantity')) {
                    $table->integer('stock_quantity')->default(0)->after('profit_percentage');
                }
                if (! Schema::hasColumn('products', 'min_stock_level')) {
                    $table->integer('min_stock_level')->default(1)->after('stock_quantity');
                }
                if (! Schema::hasColumn('products', 'barcode')) {
                    $table->string('barcode')->nullable()->unique()->after('sku');
                }
                if (! Schema::hasColumn('products', 'unit')) {
                    $table->string('unit')->default('قطعة')->after('barcode');
                }
                if (! Schema::hasColumn('products', 'warranty_months')) {
                    $table->integer('warranty_months')->default(0)->after('unit');
                }

                // إضافة فهارس
                $table->index(['category_id', 'is_active']);
                $table->index(['supplier_id', 'is_active']);
                $table->index(['branch_id', 'is_active']);
            });
        }

        // إصلاح جدول المستخدمين - إضافة العلاقة مع الفرع
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('role')->constrained()->onDelete('set null');
                $table->string('employee_number')->nullable()->unique()->after('branch_id');
                $table->index(['branch_id', 'is_active']);
            });
        }

        // إصلاح جدول الفواتير - إضافة الحقول المفقودة
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (! Schema::hasColumn('invoices', 'employee_id')) {
                    $table->foreignId('employee_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
                }
                if (! Schema::hasColumn('invoices', 'status')) {
                    $table->enum('status', ['draft', 'confirmed', 'delivered', 'cancelled', 'returned'])->default('draft')->after('total');
                }
                if (! Schema::hasColumn('invoices', 'delivery_date')) {
                    $table->date('delivery_date')->nullable()->after('sale_date');
                }
                if (! Schema::hasColumn('invoices', 'payment_status')) {
                    $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid')->after('status');
                }
                if (! Schema::hasColumn('invoices', 'tax_rate')) {
                    $table->decimal('tax_rate', 5, 2)->default(0)->after('discount');
                }
                if (! Schema::hasColumn('invoices', 'tax_amount')) {
                    $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate');
                }

                // إضافة فهارس
                $table->index(['employee_id', 'sale_date']);
                $table->index(['status', 'payment_status']);
                $table->index(['branch_id', 'sale_date']);
            });
        }

        // إصلاح جدول المدفوعات - إضافة الحقول المفقودة
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (! Schema::hasColumn('payments', 'employee_id')) {
                    $table->foreignId('employee_id')->nullable()->after('customer_id')->constrained()->onDelete('set null');
                }
                if (! Schema::hasColumn('payments', 'branch_id')) {
                    $table->foreignId('branch_id')->after('employee_id')->constrained()->onDelete('cascade');
                }
                if (! Schema::hasColumn('payments', 'account_id')) {
                    $table->foreignId('account_id')->nullable()->after('branch_id')->constrained()->onDelete('set null');
                }
                if (! Schema::hasColumn('payments', 'payment_method_id')) {
                    $table->foreignId('payment_method_id')->nullable()->after('account_id')->constrained()->onDelete('set null');
                }
                if (! Schema::hasColumn('payments', 'status')) {
                    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'refunded'])->default('confirmed')->after('amount');
                }
                if (! Schema::hasColumn('payments', 'reference_number')) {
                    $table->string('reference_number')->nullable()->after('status');
                }
                if (! Schema::hasColumn('payments', 'notes')) {
                    $table->text('notes')->nullable()->after('reference_number');
                }

                // إضافة فهارس
                $table->index(['employee_id', 'payment_date']);
                $table->index(['branch_id', 'payment_date']);
                $table->index(['status', 'payment_date']);
            });
        }

        // إنشاء جدول إعدادات النظام المتقدمة
        if (! Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('category')->index(); // business, system, security, etc.
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string'); // string, number, boolean, json
                $table->string('name_ar');
                $table->string('name_en')->nullable();
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->text('validation_rules')->nullable();
                $table->text('default_value')->nullable();
                $table->boolean('is_editable')->default(true);
                $table->boolean('requires_restart')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->index(['category', 'is_editable']);
            });
        }

        // إنشاء جدول سجل الأنشطة
        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
                $table->string('action'); // created, updated, deleted, viewed, etc.
                $table->string('model_type')->nullable(); // Customer, Product, etc.
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();

                $table->index(['model_type', 'model_id']);
                $table->index(['user_id', 'created_at']);
                $table->index(['employee_id', 'created_at']);
                $table->index(['branch_id', 'created_at']);
                $table->index(['action', 'created_at']);
            });
        }

        // إنشاء جدول إعدادات الفروع
        if (! Schema::hasTable('branch_settings')) {
            Schema::create('branch_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->string('setting_key');
                $table->text('setting_value')->nullable();
                $table->string('type')->default('string');
                $table->timestamps();

                $table->unique(['branch_id', 'setting_key']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // العكس - حذف الجداول والحقول المضافة
        Schema::dropIfExists('branch_settings');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('system_settings');

        // حذف الحقول المضافة من الجداول الموجودة
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
                $table->dropForeign(['branch_id']);
                $table->dropForeign(['account_id']);
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn(['employee_id', 'branch_id', 'account_id', 'payment_method_id', 'status', 'reference_number', 'notes']);
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn(['employee_id', 'status', 'delivery_date', 'payment_status', 'tax_rate', 'tax_amount']);
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn(['branch_id', 'employee_number']);
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropForeign(['supplier_id']);
                $table->dropForeign(['branch_id']);
                $table->dropColumn(['name_ar', 'description_ar', 'category_id', 'supplier_id', 'branch_id', 'profit_percentage', 'min_stock_level', 'barcode', 'unit', 'warranty_months']);
            });
        }

        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['customer_type']);
            });
        }
    }
};
