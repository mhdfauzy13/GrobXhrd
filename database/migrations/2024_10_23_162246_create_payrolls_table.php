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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id('payroll_id');
            $table->unsignedBigInteger('employee_id');
            $table->integer('total_days_worked');
            $table->integer('total_days_off');
            $table->integer('effective_work_days');
            $table->integer('current_salary');
            $table->boolean('is_validated')->default(false); // HR validation
            $table->timestamps();
        
            // Relasi dengan tabel employees
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
