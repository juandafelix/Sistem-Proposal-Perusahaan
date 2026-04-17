<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const ROLES = [
        'manager',
        'finance',
        'division_1',
        'division_2',
        'division_3',
        'division_4',
        'division_5',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division',
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

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'submitted_by');
    }

    public function approvedSubmissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'approved_by');
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    public function isDivision(): bool
    {
        return str_starts_with($this->role, 'division_');
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'manager' => 'Manager',
            'finance' => 'Finance',
            'division_1' => 'Divisi 1',
            'division_2' => 'Divisi 2',
            'division_3' => 'Divisi 3',
            'division_4' => 'Divisi 4',
            'division_5' => 'Divisi 5',
            default => ucfirst($this->role),
        };
    }
}
