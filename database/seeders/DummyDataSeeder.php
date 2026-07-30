<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wilayah;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Pamong;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $w = Wilayah::firstOrCreate(
            ['dusun' => 'Dusun I', 'rt' => '01', 'rw' => '01'],
            ['config_id' => 1]
        );

        $k = Keluarga::firstOrCreate(
            ['no_kk' => '1209081234567890'],
            [
                'config_id' => 1,
                'id_cluster' => $w->id,
                'alamat' => 'Jl. Merpati No. 10'
            ]
        );

        $p = Penduduk::firstOrCreate(
            ['nik' => '1209082012000001'],
            [
                'config_id' => 1,
                'nama' => 'Adinda Olivia',
                'id_kk' => $k->id,
                'kk_level' => 1,
                'sex' => 2,
                'tempatlahir' => 'Asahan',
                'tanggallahir' => '2000-05-15',
                'status_dasar' => 1,
                'status_kawin' => 1,
                'nama_ayah' => 'Ayah Olivia',
                'nama_ibu' => 'Ibu Olivia',
                'alamat_sekarang' => 'Dusun I RT 01 RW 01 Desa Serdang',
                'agama_id' => 1,
                'pekerjaan_id' => 1,
                'pendidikan_kk_id' => 1,
                'warganegara_id' => 1,
                'golongan_darah_id' => '1', // OpenSID biasanya menggunakan ID angka untuk gol darah
                'id_cluster' => $w->id
            ]
        );

        $k->update(['nik_kepala' => $p->id]);

        Pamong::firstOrCreate(
            ['pamong_nik' => '1209081234560002'],
            [
                'config_id' => 1,
                'pamong_nama' => 'Budi Santoso',
                'pamong_nip' => '198008122005011002',
                'pamong_status' => 1,
                'pamong_ttd' => 1,
                'jabatan_id' => 1
            ]
        );
    }
}
