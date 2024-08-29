<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('attandances', function (Blueprint $table) {
            $table->bigIncrements('attandance_id');

            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');

            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['IN', 'OUT']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attandances');
    }
};
