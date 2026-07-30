<?php

namespace App\Enums;

/**
 * UserRole — Peran pengguna sistem OpenSID.
 *
 * Dipetakan dari tabel user_grup OpenSID:
 *   - administrator   (id: 1) — Akses penuh ke seluruh modul
 *   - operator        (id: 2) — Entry data & pembuatan surat
 *   - sekretaris_desa (id: 6) — Review & verifikasi surat (paraf Sekdes)
 *   - kepala_desa     (custom) — Persetujuan final & TTE BSrE
 */
enum UserRole: string
{
    case Administrator    = 'administrator';
    case Operator         = 'operator';
    case SekretarisDesa   = 'sekretaris_desa';
    case KepalaDesa       = 'kepala_desa';

    public function label(): string
    {
        return match ($this) {
            self::Administrator  => 'Administrator',
            self::Operator       => 'Operator',
            self::SekretarisDesa => 'Sekretaris Desa',
            self::KepalaDesa     => 'Kepala Desa',
        };
    }

    /**
     * Warna badge Filament untuk setiap role.
     */
    public function color(): string
    {
        return match ($this) {
            self::Administrator  => 'danger',
            self::KepalaDesa     => 'warning',
            self::SekretarisDesa => 'info',
            self::Operator       => 'success',
        };
    }

    /**
     * Apakah role ini bisa menyetujui surat (verifikasi_sekdes)?
     */
    public function canVerifySurat(): bool
    {
        return in_array($this, [self::SekretarisDesa, self::KepalaDesa, self::Administrator]);
    }

    /**
     * Apakah role ini bisa melakukan TTE via BSrE?
     */
    public function canTte(): bool
    {
        return in_array($this, [self::KepalaDesa, self::Administrator]);
    }

    /**
     * Apakah role ini bisa mengelola data kependudukan?
     */
    public function canManageKependudukan(): bool
    {
        return in_array($this, [self::Administrator, self::Operator]);
    }

    /**
     * Apakah role ini bisa mengakses seluruh modul?
     */
    public function isAdmin(): bool
    {
        return $this === self::Administrator;
    }

    /**
     * Ambil semua role dalam format [value => label] untuk Filament Select.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($role) => [$role->value => $role->label()])
            ->toArray();
    }
}
