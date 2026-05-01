<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id_client');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nom_client');
            $table->string('prenom_client');
            $table->string('email_client')->unique();
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('clients'); }
};