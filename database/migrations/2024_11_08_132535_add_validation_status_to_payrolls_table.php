<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->boolean('validation_status')->default(0)->after('total_salary');
        });
    }
    
    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('validation_status');
        });
    }
    
};
