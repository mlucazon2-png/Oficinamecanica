<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['user_id','nome','cpf','telefone','email','endereco','cidade','estado'];

    public function user()     { return $this->belongsTo(User::class); }
    public function veiculos() { return $this->hasMany(Veiculo::class); }
    public function ordens()   { return $this->hasMany(OrdemServico::class); }
}
