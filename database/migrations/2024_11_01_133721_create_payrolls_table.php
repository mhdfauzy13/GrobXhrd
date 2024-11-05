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
            $table->decimal('total_salary', 10, 2);
            $table->boolean('is_validated')->default(false); // Status validasi
            $table->integer('days_present')->default(0); // Menambahkan kolom
            $table->integer('total_leave')->default(0); // Menambahkan kolom
            $table->integer('total_late')->default(0); // Menambahkan kolom
            $table->integer('total_early')->default(0); // Menambahkan kolom
            $table->integer('effective_work_days')->default(0); // Menambahkan kolom
            $table->decimal('current_salary', 10, 2)->nullable(); // Menambahkan kolom
            $table->timestamps();
        
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });
        
        
        
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
