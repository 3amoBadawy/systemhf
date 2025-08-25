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
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // معلومات الملف الأساسية
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('path');
            $table->string('disk')->default('public');

            // معلومات إضافية
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();

            // إعدادات
            $table->boolean('is_public')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('media_type')->default('image'); // image, video, document

            // معلومات الصورة/الفيديو
            $table->json('dimensions')->nullable(); // width, height
            $table->integer('duration')->nullable(); // للفيديوهات بالثواني
            $table->string('thumbnail_path')->nullable();
            $table->json('optimized_versions')->nullable(); // thumbnail, medium, large, webp

            // العلاقات
            $table->string('mediaable_type');
            $table->unsignedBigInteger('mediaable_id');

            // الترتيب
            $table->integer('order')->default(0);

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index(['mediaable_type', 'mediaable_id']);
            $table->index('media_type');
            $table->index('is_public');
            $table->index('is_featured');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
