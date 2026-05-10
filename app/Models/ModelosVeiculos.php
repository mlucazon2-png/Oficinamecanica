<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelosVeiculos extends Model
{
    protected $table = 'modelos_veiculos';

    protected $fillable = ['marca_id', 'nome'];

    public $timestamps = true;

    public function marca()
    {
        return $this->belongsTo(MarcasVeiculos::class, 'marca_id');
    }
}

