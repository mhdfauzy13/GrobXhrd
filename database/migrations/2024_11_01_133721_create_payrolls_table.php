<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('total_salary', 10, 2); // Gaji total
            $table->integer('days_present')->default(0); // Jumlah hari kehadiran
            $table->integer('total_leave')->default(0); // Jumlah cuti
            $table->integer('total_late')->default(0); // Jumlah keterlambatan
            $table->integer('total_early')->default(0); // Jumlah pulang lebih awal
            $table->integer('effective_work_days')->default(0); // Hari kerja efektif
            $table->decimal('current_salary', 10, 2)->nullable(); // Gaji per bulan karyawan
            $table->decimal('overtime_pay', 10, 2)->nullable(); // Overtime pay (upah lembur)
            $table->timestamps();
            
            // Menambahkan foreign key constraint
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });
        
        
        
        
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
