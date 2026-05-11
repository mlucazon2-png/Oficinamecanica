<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── servicos ────────────────────────────────────────────
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->string('categoria', 80)->nullable();
            $table->decimal('valor_mao_obra', 10, 2)->default(0);
            $table->integer('tempo_estimado')->nullable()->comment('minutos');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // ── pecas ───────────────────────────────────────────────
        Schema::create('pecas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('codigo', 80)->nullable()->unique();
            $table->string('fabricante', 100)->nullable();
            $table->decimal('preco_custo', 10, 2)->default(0);
            $table->decimal('preco_venda', 10, 2)->default(0);
            $table->integer('estoque')->default(0);
            $table->integer('estoque_minimo')->default(5);
            $table->string('unidade', 20)->default('un');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->index(['estoque', 'estoque_minimo']);
        });

        // ── ordens_servico ──────────────────────────────────────
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique()->comment('OS-YYYYMMDD-XXXX');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('veiculo_id')->constrained('veiculos');
            $table->foreignId('mecanico_id')->nullable()->constrained('mecanicos')->nullOnDelete();
            $table->enum('status', [
                'aguardando_aceitacao','aberta','em_diagnostico','aguardando_aprovacao',
                'aprovada','em_execucao','aguardando_pecas','finalizada','cancelada'
            ])->default('aguardando_aceitacao');
            $table->text('sintomas')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('observacoes')->nullable();
            $table->unsignedInteger('km_entrada')->nullable();
            $table->decimal('valor_servicos', 10, 2)->default(0);
            $table->decimal('valor_pecas', 10, 2)->default(0);
            $table->decimal('valor_desconto', 10, 2)->default(0);
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->boolean('aprovado_cliente')->default(false);
            $table->timestamp('data_aprovacao')->nullable();
            $table->date('data_previsao')->nullable();
            $table->timestamp('data_conclusao')->nullable();
            $table->timestamps();
            $table->index('status');
        });

        // ── itens_os ────────────────────────────────────────────
        Schema::create('itens_os', function (Blueprint $table) {
            $table->id();
            $table->foreignId('os_id')->constrained('ordens_servico')->cascadeOnDelete();
            $table->enum('tipo', ['servico','peca']);
            $table->foreignId('servico_id')->nullable()->constrained('servicos')->nullOnDelete();
            $table->foreignId('peca_id')->nullable()->constrained('pecas')->nullOnDelete();
            $table->string('descricao', 255);
            $table->decimal('quantidade', 10, 3)->default(1);
            $table->decimal('valor_unitario', 10, 2)->default(0);
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->timestamps();
        });

        // ── fotos_os ────────────────────────────────────────────
        Schema::create('fotos_os', function (Blueprint $table) {
            $table->id();
            $table->foreignId('os_id')->constrained('ordens_servico')->cascadeOnDelete();
            $table->string('path', 255);
            $table->enum('tipo', ['entrada','saida','processo'])->default('entrada');
            $table->enum('lado', ['frontal','traseira','lateral_dir','lateral_esq','interior','outro'])->nullable();
            $table->timestamps();
        });

        // ── garantias ───────────────────────────────────────────
        Schema::create('garantias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('os_id')->constrained('ordens_servico')->cascadeOnDelete();
            $table->text('descricao');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->boolean('acionada')->default(false);
            $table->timestamp('data_acionamento')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garantias');
        Schema::dropIfExists('fotos_os');
        Schema::dropIfExists('itens_os');
        Schema::dropIfExists('ordens_servico');
        Schema::dropIfExists('pecas');
        Schema::dropIfExists('servicos');
    }
};
