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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('name');
            $table->string('origin_school');
            $table->decimal('final_score', 6, 2);
            $table->string('choice_1'); // Akan diisi nama jurusan
            $table->string('choice_2')->nullable(); // Bisa kosong
            $table->enum('status', ['DITERIMA', 'TIDAK DITERIMA', 'BELUM DIPROSES'])->default('BELUM DIPROSES');
            $table->string('accepted_major')->nullable(); // Jurusan yang diterima
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
