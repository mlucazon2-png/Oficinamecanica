<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = ['nome','descricao','categoria','valor_mao_obra','tempo_estimado','ativo'];
    protected $casts    = ['ativo' => 'boolean', 'valor_mao_obra' => 'decimal:2'];

    public function itens() { return $this->hasMany(ItemOs::class, 'servico_id'); }
}
