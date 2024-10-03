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
            $table->bigIncrements('payroll_id');
            $table->unsignedBigInteger('employee_id'); // Relasi ke employees
            $table->decimal('allowance', 10, 2)->nullable(); // Tunjangan bersifat opsional
            $table->decimal('overtime', 10, 2)->nullable();  // Lembur bersifat opsional
            $table->decimal('deductions', 10, 2)->nullable();  // Potongan bersifat opsional
            $table->decimal('total_salary', 10, 2); // Total gaji setelah perhitungan
            $table->timestamps();

            // Foreign key untuk relasi dengan employees
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
