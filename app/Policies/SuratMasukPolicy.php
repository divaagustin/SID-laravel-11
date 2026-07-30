<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * SuratMasukPolicy — Akses buku register surat masuk.
 * Administrator & Operator: akses penuh
 * Sekdes & Kades: view only
 */
class SuratMasukPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SuratMasuk $suratMasuk): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::Operator);
    }

    public function update(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::Operator);
    }

    public function delete(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->isAdmin();
    }
}
