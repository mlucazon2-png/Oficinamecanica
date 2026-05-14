<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $fillable = ['uf', 'nome'];

    public function cidades()
    {
        return $this->hasMany(Cidade::class)->orderBy('nome');
    }
}
