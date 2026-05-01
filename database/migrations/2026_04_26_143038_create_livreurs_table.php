<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('livreurs_cl', function (Blueprint $table) {
            $table->id('id_livreur');
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('email')->nullable()->unique();
            $table->text('adresse')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('livreurs_cl'); }
};