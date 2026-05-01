<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id('id_frs');
            $table->string('nom_frs');
            $table->string('prenom_frs')->nullable();
            $table->string('telephone');
            $table->string('email')->nullable()->unique();
            $table->text('adresse')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('fournisseurs'); }
};