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
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Role Checks ──────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // ─── Badge Classes ────────────────────────────────────────

    public function getRoleBadgeClass(): string
    {
        return match($this->role) {
            'admin'        => 'bg-light-danger',
            'manager'      => 'bg-light-primary',
            'receptionist' => 'bg-light-success',
            'accountant'   => 'bg-light-warning',
            'staff'        => 'bg-light-secondary',
            default        => 'bg-light-secondary',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return $this->status === 'active'
            ? 'bg-light-success'
            : 'bg-light-danger';
    }

    // ─── Role Label ───────────────────────────────────────────

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin'        => '👑 Admin',
            'manager'      => '🏢 Manager',
            'receptionist' => '🛎️ Receptionist',
            'accountant'   => '💼 Accountant',
            'staff'        => '👤 Staff',
            default        => ucfirst($this->role),
        };
    }

    // ─── Scope: Only non-admins ───────────────────────────────

    public function scopeNotAdmin($query)
    {
        return $query->where('role', '!=', 'admin');
    }
}
