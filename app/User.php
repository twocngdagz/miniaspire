<?php

namespace App;

use App\Loan;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getTokenAttribute()
    {
        return $this->token() ? $this->token() : $this->createToken('myapp')->accessToken;
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
