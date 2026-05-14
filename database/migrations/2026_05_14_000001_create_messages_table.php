<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Pengirim: 'admin' atau 'mitra'
            $table->string('sender_role', 20); // 'admin' | 'mitra'
            $table->unsignedBigInteger('mitra_id'); // selalu merujuk ke faskes mitra terkait

            $table->text('body');
            $table->boolean('read_by_mitra')->default(false);
            $table->boolean('read_by_admin')->default(false);

            $table->timestamps();

            $table->foreign('mitra_id')->references('id')->on('mitras')->onDelete('cascade');
            $table->index(['mitra_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
