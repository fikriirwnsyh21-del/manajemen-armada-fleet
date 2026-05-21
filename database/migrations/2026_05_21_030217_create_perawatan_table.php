<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jenis');
            $table->integer('biaya');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawatan');
    }
};
