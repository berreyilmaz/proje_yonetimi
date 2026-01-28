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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // Hangi şirkete ait?
            $table->string('title'); // Proje Adı
            $table->text('description')->nullable(); // Proje Açıklaması
            $table->enum('status', ['devam_ediyor', 'tamamlandi'])->default('devam_ediyor'); // Durum
            $table->date('start_date'); // Başlangıç Tarihi
            $table->date('end_date')->nullable(); // Bitiş Tarihi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
