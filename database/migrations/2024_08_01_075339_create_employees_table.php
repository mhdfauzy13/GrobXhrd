<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('employee_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('place_birth')->nullable();
            $table->date('date_birth')->nullable();
            $table->string('personal_no')->nullable();
            $table->string('address')->nullable();
            $table->string('current_address')->nullable();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
            $table->string('blood_rhesus')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('hp_number')->nullable();
            $table->enum('marital_status', ['single', 'married', 'widow', 'widower'])->nullable();
            $table->enum('last_education', ['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'])->nullable();
            $table->string('degree')->nullable();
            $table->date('starting_date')->nullable();
            $table->string('interview_by')->nullable();
            $table->integer('current_salary')->nullable();
            $table->boolean('insurance')->nullable();
            $table->string('serious_illness')->nullable();
            $table->string('hereditary_disease')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('relations')->nullable();
            $table->string('emergency_number')->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
