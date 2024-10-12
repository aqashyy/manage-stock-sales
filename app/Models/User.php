<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() || $this->isSuperAdmin();
    }
    const ROLE_SUADMIN  =   'SUADMIN';
    const ROLE_ADMIN  =   'ADMIN';

    const ROLES =   [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_SUADMIN => 'Super Admin',
    ];
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUADMIN;
    }
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
