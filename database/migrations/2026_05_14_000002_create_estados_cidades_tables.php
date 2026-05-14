<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->char('uf', 2)->unique();
            $table->string('nome', 80);
            $table->timestamps();
        });

        Schema::create('cidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estado_id')->constrained('estados')->cascadeOnDelete();
            $table->string('nome', 120);
            $table->timestamps();
            $table->unique(['estado_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cidades');
        Schema::dropIfExists('estados');
    }
};
