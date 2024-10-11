<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employee_books', function (Blueprint $table) {
            $table->bigIncrements('employeebook_id');
            $table->unsignedBigInteger('employee_id'); // Foreign key ke tabel employees
            $table->date('incident_date'); // Tanggal kejadian
            $table->text('incident_details'); // Detail kejadian
            $table->text('remarks'); // Keterangan
            $table->enum('category', ['violation', 'warning', 'reprimand']); // Kategori
            $table->timestamps();

            // Definisikan foreign key
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_books');
    }
};
