<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('mdp');
            $table->string('telephone')->nullable();
            $table->enum('role', ['admin', 'vendeur', 'gerant', 'client'])->default('client');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};