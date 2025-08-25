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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->datetime('check_in_time')->nullable();
            $table->datetime('check_out_time')->nullable();
            $table->enum('check_in_method', ['web_kiosk', 'qr_pin', 'gps', 'manual'])->default('web_kiosk');
            $table->enum('check_out_method', ['web_kiosk', 'qr_pin', 'gps', 'manual'])->nullable();
            $table->string('check_in_location')->nullable();
            $table->string('check_out_location')->nullable();
            $table->string('check_in_ip')->nullable();
            $table->string('check_out_ip')->nullable();
            $table->decimal('check_in_gps_lat', 10, 8)->nullable();
            $table->decimal('check_in_gps_lng', 11, 8)->nullable();
            $table->decimal('check_out_gps_lat', 10, 8)->nullable();
            $table->decimal('check_out_gps_lng', 11, 8)->nullable();
            $table->integer('late_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->datetime('break_start_time')->nullable();
            $table->datetime('break_end_time')->nullable();
            $table->integer('total_break_minutes')->default(0);
            $table->decimal('work_hours', 5, 2)->default(0);
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'leave'])->default('present');
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->datetime('approved_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // فهارس
            $table->index(['employee_id', 'date']);
            $table->index(['date', 'status']);
            $table->index(['employee_id', 'shift_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
