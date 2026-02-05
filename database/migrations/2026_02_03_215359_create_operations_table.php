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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Talep eden kişi
            
            // Operasyon Türü (7.2 maddesine göre)
            $table->enum('type', ['time', 'budget', 'resource', 'process']);
            
            // Etki Değeri (Örn: +15 gün, +5000 TL)
            $table->decimal('impact_value', 15, 2)->default(0);
            
            // Kayıt Altına Alma (7.1 maddesi: Zorunlu açıklama)
            $table->text('description'); 
            
            // Onay Mekanizması (7.3 maddesi: Onaylanmadan uygulanamaz)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Onaylayan yönetici
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
