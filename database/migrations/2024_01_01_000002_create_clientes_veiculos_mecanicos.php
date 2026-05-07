<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── clientes ────────────────────────────────────────────
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nome', 150);
            $table->string('cpf', 14)->unique();
            $table->string('telefone', 20);
            $table->string('email', 150)->nullable();
            $table->string('endereco', 255)->nullable();
            $table->string('cidade', 100)->nullable();
            $table->char('estado', 2)->nullable();
            $table->timestamps();
        });

        // ── veiculos ────────────────────────────────────────────
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->string('placa', 10)->unique();
            $table->string('marca', 80);
            $table->string('modelo', 80);
            $table->smallInteger('ano');
            $table->string('cor', 50)->nullable();
            $table->string('chassi', 50)->nullable();
            $table->unsignedInteger('km_atual')->default(0);
            $table->timestamps();
        });

        // ── mecanicos ───────────────────────────────────────────
        Schema::create('mecanicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nome', 150);
            $table->string('cpf', 14)->nullable()->unique();
            $table->string('telefone', 20)->nullable();
            $table->string('especialidade', 100)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mecanicos');
        Schema::dropIfExists('veiculos');
        Schema::dropIfExists('clientes');
    }
};
