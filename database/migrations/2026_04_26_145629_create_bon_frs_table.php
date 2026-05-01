<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bons_frs', function (Blueprint $table) {
            $table->id('id_bon');
            $table->foreignId('id_frs')->constrained('fournisseurs', 'id_frs')->onDelete('restrict');
            $table->date('date_bon');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('statut', ['brouillon','envoye','recu','annule'])->default('brouillon');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bons_frs'); }
};