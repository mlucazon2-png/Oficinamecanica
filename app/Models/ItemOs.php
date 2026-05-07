<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOs extends Model
{
    protected $table    = 'itens_os';
    protected $fillable = ['os_id','tipo','servico_id','peca_id','descricao','quantidade','valor_unitario','valor_total'];
    protected $casts    = ['valor_unitario' => 'decimal:2', 'valor_total' => 'decimal:2'];

    public function ordemServico() { return $this->belongsTo(OrdemServico::class, 'os_id'); }
    public function servico()      { return $this->belongsTo(Servico::class); }
    public function peca()         { return $this->belongsTo(Peca::class); }

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $item->valor_total = $item->quantidade * $item->valor_unitario;
        });
        static::saved(function (self $item) {
            $item->ordemServico->recalcularTotais();
        });
        static::deleted(function (self $item) {
            $item->ordemServico->recalcularTotais();
            if ($item->tipo === 'peca' && $item->peca_id) {
                Peca::find($item->peca_id)?->increment('estoque', $item->quantidade);
            }
        });
    }
}
