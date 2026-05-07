<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function cliente()  { return $this->hasOne(Cliente::class); }
    public function mecanico() { return $this->hasOne(Mecanico::class); }

    public function isGerente():   bool { return $this->role === 'gerente'; }
    public function isAtendente(): bool { return $this->role === 'atendente'; }
    public function isMecanico():  bool { return $this->role === 'mecanico'; }
    public function isCliente():   bool { return $this->role === 'cliente'; }
}
