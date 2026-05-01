<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commander_frs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bon')->constrained('bons_frs', 'id_bon')->onDelete('cascade');
            $table->foreignId('id_prod')->constrained('produits', 'id_prod')->onDelete('restrict');
            $table->decimal('quantite_cmd', 10, 2);
            $table->decimal('prix_comd', 10, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('commander_frs'); }
};