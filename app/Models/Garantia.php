<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    protected $fillable = ['os_id','descricao','data_inicio','data_fim','acionada','data_acionamento','observacao'];
    protected $casts    = [
        'acionada'         => 'boolean',
        'data_inicio'      => 'date',
        'data_fim'         => 'date',
        'data_acionamento' => 'datetime',
    ];

    public function ordemServico() { return $this->belongsTo(OrdemServico::class, 'os_id'); }
    public function ativa():    bool { return !$this->acionada && today()->lte($this->data_fim); }
    public function expirada(): bool { return today()->gt($this->data_fim); }
}
