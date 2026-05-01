<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('livraisons_cl', function (Blueprint $table) {
            $table->id('id_livcl');
            $table->foreignId('id_cmd')->constrained('commandes', 'id_cmd')->onDelete('restrict');
            $table->foreignId('id_livreur')->constrained('livreurs_cl', 'id_livreur')->onDelete('restrict');
            $table->date('date_livcl');
            $table->text('adresse_livcl');
            $table->enum('statut', ['en_attente','en_cours','livree'])->default('en_attente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('livraisoncls'); }
};