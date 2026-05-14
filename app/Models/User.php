<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'profile_photo_path', 'password_change_requested_at', 'last_seen_at'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'password_change_requested_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function cliente()  { return $this->hasOne(Cliente::class); }
    public function mecanico() { return $this->hasOne(Mecanico::class); }

    public function isGerente():   bool { return $this->role === 'gerente'; }
    public function isAtendente(): bool { return $this->role === 'atendente'; }
    public function isMecanico():  bool { return $this->role === 'mecanico'; }
    public function isCliente():   bool { return $this->role === 'cliente'; }

    public function isOnline(): bool
    {
        return $this->last_seen_at?->gt(now()->subMinutes(5)) ?? false;
    }

    public function profilePhotoUrl(): ?string
    {
        return $this->profile_photo_path ? asset('storage/' . $this->profile_photo_path) : null;
    }
}
