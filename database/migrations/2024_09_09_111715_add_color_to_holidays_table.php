<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorToHolidaysTable extends Migration
{
    public function up()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->string('color')->nullable(); // Kolom untuk menyimpan warna event
        });
    }

    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
