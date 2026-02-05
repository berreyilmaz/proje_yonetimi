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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained();
            
            // Hata almamak için burayı 'reference' yapıyoruz:
            $table->nullableMorphs('reference'); 
            
            $table->string('title');
            $table->decimal('amount', 15, 2); 
            $table->enum('type', ['income', 'expense']);
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Tablo adını düzelttik
        Schema::dropIfExists('financial_transactions');
    }
};
