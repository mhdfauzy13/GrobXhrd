<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offrequests', function (Blueprint $table) {
            $table->bigIncrements('offrequest_id'); // Primary key kolom 'offrequest_id'
            $table->unsignedBigInteger('user_id'); // Kolom foreign key
            // $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('manager_id')->nullable(); // Kolom foreign key opsional
            $table->string('name');
            $table->string('email')->unique();
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_event');
            $table->dateTime('end_event');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Foreign key constraints
            $table
                ->foreign('user_id')
                ->references('user_id') // Mengacu ke kolom 'user_id' di tabel 'users'
                ->on('users')
                ->onDelete('cascade');
            $table
                ->foreign('manager_id')
                ->references('user_id') // Mengacu ke kolom 'user_id' di tabel 'users'
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offrequests');
    }
};
