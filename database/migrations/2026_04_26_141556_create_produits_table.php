<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id('id_prod');
            $table->foreignId('id_categorie')->constrained('categories', 'id_categorie')->onDelete('restrict');
            $table->foreignId('id_calibre')->constrained('calibres', 'id_calibre')->onDelete('restrict');
            $table->string('libelle_prod');
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->string('image')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('produits'); }
};