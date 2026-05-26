<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $otp
 * @property \Illuminate\Support\Carbon $otp_expires_at
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property bool $is_active
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'otp_expires_at',
        'email_verified_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at'    => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function isAdmin(): bool        { return $this->role === 'admin'; }
    public function isUniversity(): bool   { return $this->role === 'university'; }
    public function isGovernment(): bool   { return $this->role === 'government'; }
    public function isStudent(): bool      { return $this->role === 'student'; }
    public function isVerified(): bool     { return !is_null($this->email_verified_at); }

    public function university()      { return $this->hasOne(University::class); }
    public function governmentUser()  { return $this->hasOne(GovernmentUser::class); }
}
