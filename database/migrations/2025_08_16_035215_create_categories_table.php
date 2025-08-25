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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الفئة: غرف نوم، غرف معيشة، مطابخ
            $table->string('slug')->unique(); // slug للرابط
            $table->text('description')->nullable(); // وصف الفئة
            $table->string('image')->nullable(); // صورة الفئة
            $table->boolean('is_active')->default(true); // هل الفئة نشطة
            $table->integer('sort_order')->default(0); // ترتيب الفئة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
