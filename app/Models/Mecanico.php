<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mecanico extends Model
{
    protected $fillable = ['user_id','nome','cpf','telefone','especialidade','ativo'];
    protected $casts    = ['ativo' => 'boolean'];

    public function user()   { return $this->belongsTo(User::class); }
    public function ordens() { return $this->hasMany(OrdemServico::class); }
}
