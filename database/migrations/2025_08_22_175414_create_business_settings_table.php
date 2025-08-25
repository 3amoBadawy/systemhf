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
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->default('Egypt Furniture Gallery');
            $table->string('business_name_ar')->default('معرض الأثاث المصري');
            $table->decimal('default_profit_percent', 5, 2)->default(30.00);
            $table->string('currency', 10)->default('EGP');
            $table->string('currency_symbol', 10)->default('ج.م');
            $table->enum('currency_symbol_placement', ['before', 'after'])->default('after');
            $table->string('timezone')->default('Africa/Cairo');
            $table->string('logo')->nullable();
            $table->string('date_format')->default('d-m-Y');
            $table->string('time_format')->default('H:i');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
