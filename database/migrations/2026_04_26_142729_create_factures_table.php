<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id('id_fact');
            $table->foreignId('id_cmd')->unique()->constrained('commandes', 'id_cmd')->onDelete('restrict');
            $table->date('date_fact');
            $table->decimal('montant_fact', 10, 2);
            $table->enum('mode_paie', ['mtn','moov','especes','carte'])->default('especes');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('factures'); }
};