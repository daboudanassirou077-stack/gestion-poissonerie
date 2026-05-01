<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calibres', function (Blueprint $table) {
            $table->id('id_calibre');
            $table->string('type_produit');
            $table->string('unite_vente');
            $table->decimal('poids_min', 8, 2)->nullable();
            $table->decimal('poids_max', 8, 2)->nullable();
            $table->string('taille')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('calibres'); }
};