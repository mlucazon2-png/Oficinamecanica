<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    protected $table = 'ordens_servico';

    protected $fillable = [
        'numero','cliente_id','veiculo_id','mecanico_id',
        'status','sintomas','diagnostico','observacoes','km_entrada',
        'valor_servicos','valor_pecas','valor_desconto','valor_total',
        'aprovado_cliente','data_aprovacao','data_previsao','data_conclusao',
    ];

    protected $casts = [
        'aprovado_cliente' => 'boolean',
        'data_aprovacao'   => 'datetime',
        'data_conclusao'   => 'datetime',
        'data_previsao'    => 'date',
        'valor_total'      => 'decimal:2',
    ];

    // ── Relacionamentos ──────────────────────────────────────────────────────
    public function cliente()  { return $this->belongsTo(Cliente::class); }
    public function veiculo()  { return $this->belongsTo(Veiculo::class); }
    public function mecanico() { return $this->belongsTo(Mecanico::class); }
    public function itens()    { return $this->hasMany(ItemOs::class, 'os_id'); }
    public function fotos()    { return $this->hasMany(FotoOs::class, 'os_id'); }
    public function garantias(){ return $this->hasMany(Garantia::class, 'os_id'); }

    // ── Helpers ──────────────────────────────────────────────────────────────
    public function recalcularTotais(): void
    {
        $servicos = $this->itens()->where('tipo','servico')->sum('valor_total');
        $pecas    = $this->itens()->where('tipo','peca')->sum('valor_total');
        $this->update([
            'valor_servicos' => $servicos,
            'valor_pecas'    => $pecas,
            'valor_total'    => ($servicos + $pecas) - $this->valor_desconto,
        ]);
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'aberta'               => 'Aberta',
            'em_diagnostico'       => 'Em Diagnóstico',
            'aguardando_aprovacao' => 'Aguardando Aprovação',
            'aprovada'             => 'Aprovada',
            'em_execucao'          => 'Em Execução',
            'aguardando_pecas'     => 'Aguardando Peças',
            'finalizada'           => 'Finalizada',
            'cancelada'            => 'Cancelada',
            default                => $this->status ?? 'Indefinida',
        };
    }

    public function statusCor(): string
    {
        return match($this->status) {
            'aberta'               => 'secondary',
            'em_diagnostico'       => 'info',
            'aguardando_aprovacao' => 'warning',
            'aprovada'             => 'primary',
            'em_execucao'          => 'primary',
            'aguardando_pecas'     => 'warning',
            'finalizada'           => 'success',
            'cancelada'            => 'danger',
            default                => 'secondary',
        };
    }

    // Gera número automático: OS-YYYYMMDD-0001
    public static function gerarNumero(): string
    {
        $prefixo = 'OS-' . date('Ymd') . '-';
        $ultimo  = static::where('numero','like',$prefixo.'%')
                         ->orderByDesc('numero')->value('numero');
        $seq = $ultimo ? ((int) substr($ultimo, -4)) + 1 : 1;
        return $prefixo . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
