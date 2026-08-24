<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isFinance(): bool
    {
        return in_array($this->role, ['superadmin', 'finance']);
    }

    public function isEditor(): bool
    {
        return in_array($this->role, ['superadmin', 'editor']);
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function verifiedDonations()
    {
        return $this->hasMany(Donation::class, 'verified_by_user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}
