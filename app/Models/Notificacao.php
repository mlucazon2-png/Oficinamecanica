<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table = 'notificacoes';

    protected $fillable = ['user_id', 'os_id', 'tipo', 'status', 'lida', 'mensagem'];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function usuario()   { return $this->belongsTo(User::class, 'user_id'); }
    public function os()        { return $this->belongsTo(OrdemServico::class, 'os_id'); }
}
