<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendeurs', function (Blueprint $table) {
            $table->id('id_vendeur');
            $table->foreignId('id_user')->unique()->constrained('users', 'id_user')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vendeurs'); }
};