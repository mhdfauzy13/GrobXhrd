<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('company_id'); // Nama kolom primary key
            $table->string('name_company');
            $table->string('address');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status dengan default 'active'
            $table->string('google_client_id')->nullable();
            $table->string('google_client_secret')->nullable();
            $table->string('google_oauth_scope')->nullable();
            $table->string('google_json_file')->nullable();
            $table->string('google_oauth_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
