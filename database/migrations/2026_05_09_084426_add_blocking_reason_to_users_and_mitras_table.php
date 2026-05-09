<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('blocking_reason')->nullable()->after('is_active');
        });

        Schema::table('mitras', function (Blueprint $table) {
            $table->string('blocking_reason')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('blocking_reason');
        });

        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn('blocking_reason');
        });
    }
};
