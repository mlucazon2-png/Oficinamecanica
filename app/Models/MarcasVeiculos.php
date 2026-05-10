<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcasVeiculos extends Model
{
    protected $table = 'marcas_veiculos';

    protected $fillable = ['nome'];

    public $timestamps = true;

    public function modelos()
    {
        return $this->hasMany(ModelosVeiculos::class, 'marca_id');
    }
}

