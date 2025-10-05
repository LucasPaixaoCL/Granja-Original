<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticable
{
    use HasFactory;
    use Notifiable;
    //use SoftDeletes; // descomentar para exclusão lógica
    protected $fillable = [
        'department_id','name', 'email', 'email_verified_at', 'password', 'role', 'permissions',
    ];

    protected $hidden = [
        'password'
    ];
    public function detail()
    {
        return $this->hasOne(UserDetail::class); // cada usuário possui apenas um detalhe
    }

    public function department()
    {
        return $this->belongsTo(Department::class); // este utilizador pertence a um departamento
    }

    public function nucleos()
    {
        return $this->hasMany(Nucleo::class);
    }
}
