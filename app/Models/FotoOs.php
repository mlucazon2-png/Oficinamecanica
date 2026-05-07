<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoOs extends Model
{
    protected $table    = 'fotos_os';
    protected $fillable = ['os_id','path','tipo','lado'];

    public function ordemServico() { return $this->belongsTo(OrdemServico::class, 'os_id'); }
    public function url(): string  { return asset('storage/' . $this->path); }
}
