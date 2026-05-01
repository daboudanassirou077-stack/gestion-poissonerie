<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id('id_cmd');
            $table->foreignId('id_client')->constrained('clients', 'id_client')->onDelete('restrict');
            $table->string('reference')->unique();
            $table->date('date_cmd');
            $table->enum('statut_cmd', ['en_attente','confirmee','en_preparation','en_livraison','livree','annulee'])->default('en_attente');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->text('adresse_livraison')->nullable();
            $table->string('quartier')->nullable();
            $table->string('instructions_livraison')->nullable();
            $table->enum('momo_operateur', ['mtn','moov','especes'])->nullable();
            $table->string('momo_numero')->nullable();
            $table->enum('statut_paiement', ['en_attente','paye','echoue'])->default('en_attente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('commandes'); }
};