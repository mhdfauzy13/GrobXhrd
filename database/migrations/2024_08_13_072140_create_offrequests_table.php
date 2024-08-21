<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offrequests', function (Blueprint $table) {
            $table->bigIncrements('offrequest_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mtitle');
            $table->text('description');
            $table->dateTime('start_event');
            $table->dateTime('end_event');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offrequests');
    }
};
