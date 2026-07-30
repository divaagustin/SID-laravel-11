<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\LogSurat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * LogSuratPolicy — Kebijakan akses untuk alur pembuatan & verifikasi surat.
 *
 * Alur verifikasi berjenjang OpenSID:
 *   Operator → buat draf
 *   Sekretaris Desa → periksa & paraf (verifikasi_sekdes)
 *   Kepala Desa → setujui & TTE (verifikasi_kades)
 *
 * Kolom status di log_surat:
 *   verifikasi_operator  = 1 (sudah diserahkan)
 *   verifikasi_sekdes    = 1 (disetujui Sekdes) / 2 (ditolak)
 *   verifikasi_kades     = 1 (disetujui Kades)  / 2 (ditolak)
 *   tte                  = 1 (sudah ditanda tangani elektronik)
 */
class LogSuratPolicy
{
    use HandlesAuthorization;

    /** Siapa saja yang bisa melihat daftar surat */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(
            UserRole::Administrator,
            UserRole::Operator,
            UserRole::SekretarisDesa,
            UserRole::KepalaDesa,
        );
    }

    /** Siapa saja yang bisa melihat detail satu surat */
    public function view(User $user, LogSurat $logSurat): bool
    {
        return $user->hasAnyRole(
            UserRole::Administrator,
            UserRole::Operator,
            UserRole::SekretarisDesa,
            UserRole::KepalaDesa,
        );
    }

    /** Hanya Operator & Administrator yang bisa membuat surat baru */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::Operator);
    }

    /** Surat hanya bisa diedit jika belum disetujui Sekdes, atau oleh Administrator */
    public function update(User $user, LogSurat $logSurat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Operator hanya bisa edit surat yg belum diserahkan ke Sekdes
        if ($user->isOperator()) {
            return $logSurat->verifikasi_sekdes === null || $logSurat->verifikasi_sekdes == 0;
        }

        return false;
    }

    /** Hanya Administrator yang bisa menghapus surat */
    public function delete(User $user, LogSurat $logSurat): bool
    {
        return $user->isAdmin();
    }

    /** Sekretaris Desa & Administrator bisa memverifikasi surat */
    public function verifySekdes(User $user, LogSurat $logSurat): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::SekretarisDesa)
            && $logSurat->verifikasi_sekdes !== 1;
    }

    /** Kepala Desa & Administrator bisa menyetujui surat */
    public function verifyKades(User $user, LogSurat $logSurat): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::KepalaDesa)
            && $logSurat->verifikasi_sekdes == 1  // harus sudah disetujui Sekdes
            && $logSurat->verifikasi_kades !== 1;
    }

    /** Hanya Kepala Desa & Administrator yang bisa kirim ke BSrE untuk TTE */
    public function kirimTte(User $user, LogSurat $logSurat): bool
    {
        return $user->hasAnyRole(UserRole::Administrator, UserRole::KepalaDesa)
            && $logSurat->verifikasi_kades == 1
            && ! $logSurat->tte;
    }
}
