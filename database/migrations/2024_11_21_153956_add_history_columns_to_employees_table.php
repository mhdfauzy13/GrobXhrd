<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Menambahkan kolom resign_date, reason, remarks, dan document ke tabel employees
            $table->date('resign_date')->nullable()->after('status');
            $table->string('reason')->nullable()->after('resign_date');
            $table->text('remarks')->nullable()->after('reason');
            $table->string('document')->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Menghapus kolom jika rollback dilakukan
            $table->dropColumn(['resign_date', 'reason', 'remarks', 'document']);
        });
    }
};
