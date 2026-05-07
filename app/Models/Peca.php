<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peca extends Model
{
    protected $fillable = ['nome','codigo','fabricante','preco_custo','preco_venda','estoque','estoque_minimo','unidade','ativo'];
    protected $casts    = ['ativo' => 'boolean'];

    public function itens() { return $this->hasMany(ItemOs::class, 'peca_id'); }

    public function estoqueAbaixoMinimo(): bool
    {
        return $this->estoque <= $this->estoque_minimo;
    }
}
