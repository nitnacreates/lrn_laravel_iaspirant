<?php

namespace App\Model\Super;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name','password'];

    protected $hidden = [
        'password', 'remember_token'
    ];
}
