<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $fillable = ['cliente_id','placa','marca','modelo','ano','cor','km_atual'];


    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function ordens()  { return $this->hasMany(OrdemServico::class); }
}
