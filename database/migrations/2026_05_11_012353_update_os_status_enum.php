<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Corrige o ENUM do campo status para incluir 'aguardando_aceitacao'
        // (o banco atual não aceita esse valor, gerando truncation e falha no INSERT).
        DB::statement("ALTER TABLE ordens_servico MODIFY status ENUM('aberta','em_diagnostico','aguardando_aprovacao','aprovada','em_execucao','aguardando_pecas','finalizada','cancelada','aguardando_aceitacao') NOT NULL DEFAULT 'aberta'");
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte o ENUM para o estado original (sem aguardando_aceitacao)
        DB::statement("ALTER TABLE ordens_servico MODIFY status ENUM('aberta','em_diagnostico','aguardando_aprovacao','aprovada','em_execucao','aguardando_pecas','finalizada','cancelada') NOT NULL DEFAULT 'aberta'");
    }

};
