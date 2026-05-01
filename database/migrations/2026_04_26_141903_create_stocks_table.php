<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('id_stock');
            $table->foreignId('id_prod')->unique()->constrained('produits', 'id_prod')->onDelete('cascade');
            $table->decimal('quantite_stock', 10, 2)->default(0);
            $table->decimal('seuil_alerte', 10, 2)->default(5);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stocks'); }
};