<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * PendudukPolicy — Akses data kependudukan.
 *
 * Administrator & Operator: CRUD penuh
 * Sekdes & Kades: View only (untuk keperluan pengisian surat)
 */
class PendudukPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // semua role bisa melihat daftar
    }

    public function view(User $user, Penduduk $penduduk): bool
    {
        return true; // semua role bisa melihat detail
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::Operator);
    }

    public function update(User $user, Penduduk $penduduk): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::Operator);
    }

    public function delete(User $user, Penduduk $penduduk): bool
    {
        return $user->isAdmin();
    }
}
