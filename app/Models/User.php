<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // These fields can be mass-assigned (e.g. User::create([...]))
    protected $fillable = [
        'name',
        'email',
        'password',
        'solde',
        'role',
    ];

    // These fields are NEVER included in JSON responses
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // These fields are automatically cast to the correct PHP type
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',  // automatically bcrypt hashes the password
        'solde' => 'integer',
    ];

    // REQUIRED by JWTSubject interface
    // Tells JWT what to use as the unique identifier inside the token
    // We use the user's ID (e.g. token will contain { "sub": 1 })
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // REQUIRED by JWTSubject interface
    // Lets you add extra custom data inside the token payload
    // We don't need extra data, so we return an empty array
    public function getJWTCustomClaims()
    {
        return [];
    }
}