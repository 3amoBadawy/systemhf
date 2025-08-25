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
        Schema::create('system_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20)->unique();
            $table->string('version_name')->nullable();
            $table->text('description')->nullable();
            $table->date('release_date');
            $table->enum('type', ['major', 'minor', 'patch'])->default('patch');
            $table->json('features')->nullable();
            $table->json('bug_fixes')->nullable();
            $table->boolean('is_current')->default(false);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_versions');
    }
};
