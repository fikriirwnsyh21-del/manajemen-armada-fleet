<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nopol')->unique();
            $table->string('merk');
            $table->string('tipe');
            $table->year('tahun');
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Dalam Perbaikan']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};