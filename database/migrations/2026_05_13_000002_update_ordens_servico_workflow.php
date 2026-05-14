<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE ordens_servico MODIFY status ENUM('aguardando_aceitacao','solicitacao_aceita','solicitacao_recusada','em_diagnostico','orcamento_enviado_atendente','aguardando_aprovacao','aprovada','em_execucao','aguardando_pecas','finalizada','cancelada','aberta') NOT NULL DEFAULT 'aguardando_aceitacao'");

        Schema::table('ordens_servico', function (Blueprint $table) {
            $table->string('motivo_recusa', 120)->nullable()->after('observacoes');
            $table->text('detalhes_recusa')->nullable()->after('motivo_recusa');
        });
    }

    public function down(): void
    {
        Schema::table('ordens_servico', function (Blueprint $table) {
            $table->dropColumn(['motivo_recusa', 'detalhes_recusa']);
        });

        DB::statement("ALTER TABLE ordens_servico MODIFY status ENUM('aberta','em_diagnostico','aguardando_aprovacao','aprovada','em_execucao','aguardando_pecas','finalizada','cancelada','aguardando_aceitacao') NOT NULL DEFAULT 'aberta'");
    }
};
