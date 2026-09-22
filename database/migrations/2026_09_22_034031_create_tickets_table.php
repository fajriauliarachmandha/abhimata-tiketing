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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); //nomer tiket
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('departement_id')->constrained('departements');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('location'); //lokasi perbaikan
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium'); //tingkat urgensi
            $table->text('description'); //deskripsi perbaikan
            $table->string('attachment')->nullable(); //foto
            $table->enum('status', ['open','in_progress','done'])->default('open'); //status pengajuan
            $table->foreignId('assigned_to')->nullable()->constrained('users'); //ID IT Support
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
