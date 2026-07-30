<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User model untuk sistem OpenSID v2.
 *
 * Tabel `users` (Laravel) dipisah dari `user` (OpenSID legacy).
 * Kolom `role` memetakan ke user_grup OpenSID:
 *   - administrator → Administrator (id: 1)
 *   - operator       → Operator (id: 2)
 *   - sekretaris_desa → Sekretaris Desa (id: 6)
 *   - kepala_desa    → Kepala Desa (custom)
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
            'is_active'         => 'boolean',
        ];
    }

    // ─────────────────────────────────────────────
    // Filament Access Control
    // ─────────────────────────────────────────────

    /**
     * Izinkan akses panel hanya untuk user yang aktif.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    // ─────────────────────────────────────────────
    // Role Helpers
    // ─────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Administrator;
    }

    public function isKepalaDesa(): bool
    {
        return $this->role === UserRole::KepalaDesa;
    }

    public function isSekretarisDesa(): bool
    {
        return $this->role === UserRole::SekretarisDesa;
    }

    public function isOperator(): bool
    {
        return $this->role === UserRole::Operator;
    }

    public function canVerifySurat(): bool
    {
        return $this->role?->canVerifySurat() ?? false;
    }

    public function canTte(): bool
    {
        return $this->role?->canTte() ?? false;
    }

    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(UserRole ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Ambil label role untuk ditampilkan di UI.
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->role?->label() ?? 'Tidak Diketahui';
    }
}
