<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * SuratKeluarPolicy — Akses buku register surat keluar.
 */
class SuratKeluarPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SuratKeluar $suratKeluar): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::Operator);
    }

    public function update(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::Operator);
    }

    public function delete(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->isAdmin();
    }
}
