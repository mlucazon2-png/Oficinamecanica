<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // assistente/gerente
            $table->foreignId('os_id')->constrained('ordens_servico')->cascadeOnDelete();
            $table->enum('tipo', ['solicitacao_os', 'atualizacao'])->default('solicitacao_os');
            $table->enum('status', ['pendente', 'aceita', 'recusada'])->default('pendente');
            $table->boolean('lida')->default(false);
            $table->text('mensagem')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status', 'lida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
