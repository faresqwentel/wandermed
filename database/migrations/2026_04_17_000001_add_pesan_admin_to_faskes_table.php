<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->text('pesan_admin')->nullable()->after('pengumuman')
                  ->comment('Pesan atau catatan dari Admin untuk Mitra Faskes');
        });
    }

    public function down(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->dropColumn('pesan_admin');
        });
    }
};
