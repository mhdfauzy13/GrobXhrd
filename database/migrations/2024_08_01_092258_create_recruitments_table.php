<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->bigIncrements('recruitment_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->date('date_of_birth');
            $table->string('last_education');
            $table->string('last_position');
            $table->string('apply_position'); // Field baru
            $table->string('cv_file');
            $table->text('comment')->nullable();

            // Status enum yang sudah ada
            $table->enum('status', [
                'Initial Interview',
                'User Interview 1',
                'User Interview 2',
                'Background Check',
                'Offering letter',
                'Accept',
                'Decline'
            ])->default('Initial Interview');

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('recruitments');
    }
}
