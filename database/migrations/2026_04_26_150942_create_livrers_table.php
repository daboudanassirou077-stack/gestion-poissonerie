<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('livrer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_livs')->constrained('livraisons', 'id_livs')->onDelete('cascade');
            $table->foreignId('id_prod')->constrained('produits', 'id_prod')->onDelete('restrict');
            $table->decimal('quantite', 10, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('livrer'); }
};