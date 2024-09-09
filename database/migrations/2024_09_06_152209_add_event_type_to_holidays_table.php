<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventTypeToHolidaysTable extends Migration
{
    public function up()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->string('event_type')->default('regular'); // 'regular' for regular holidays and 'special' for special events
        });
    }

    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('event_type');
        });
    }
}
