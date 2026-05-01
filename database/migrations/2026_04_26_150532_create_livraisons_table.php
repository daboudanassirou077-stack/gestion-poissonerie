<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id('id_livs');
            $table->foreignId('id_bon')->constrained('bons_frs', 'id_bon')->onDelete('restrict');
            $table->date('date_livs');
            $table->text('adresse_liv');
            $table->enum('statut', ['en_attente','en_cours','receptionne'])->default('en_attente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('livraisons'); }
};