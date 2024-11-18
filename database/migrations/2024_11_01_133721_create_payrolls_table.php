<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->bigIncrements('payroll_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('month'); // Bulan perhitungan payroll
            $table->integer('total_worked_days');
            $table->integer('total_days_off');
            $table->integer('total_late');
            $table->integer('total_early');
            $table->integer('effective_work_days');
            $table->integer('current_salary');
            $table->integer('overtime_pay')->default(0); // Gaji lembur
            $table->integer('total_salary'); // Gaji total setelah lembur
            // $table->enum('validation_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Foreign Key relation dengan tabel employees

            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });
        
        
        
        
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
