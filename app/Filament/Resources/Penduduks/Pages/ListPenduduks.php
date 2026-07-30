<?php

namespace App\Filament\Resources\Penduduks\Pages;

use App\Filament\Resources\Penduduks\PendudukResource;
use App\Models\Keluarga;
use App\Models\Penduduk;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListPenduduks extends ListRecords
{
    protected static string $resource = PendudukResource::class;
    protected static ?string $title = 'Daftar Data Penduduk Desa';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Penduduk Baru'),

            Action::make('download_template')
                ->label('📄 Unduh Template Excel (Termasuk No. KK)')
                ->color('info')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('admin.penduduk.download-template'))
                ->openUrlInNewTab(),

            Action::make('import_csv')
                ->label('📥 Impor Excel / CSV')
                ->color('success')
                ->icon('heroicon-o-arrow-up-on-square')
                ->form([
                    FileUpload::make('file')
                        ->label('Unggah File CSV / Excel Kependudukan')
                        ->required()
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->helperText('Pastikan format kolom sesuai dengan Template Excel Kependudukan Resmi (Termasuk No. KK).'),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('local')->path($data['file']);

                    if (! file_exists($filePath)) {
                        Notification::make()
                            ->title('File tidak ditemukan.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $handle = fopen($filePath, 'r');
                    if (! $handle) {
                        Notification::make()
                            ->title('Gagal membaca file CSV.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Remove UTF-8 BOM if present
                    $bom = fread($handle, 3);
                    if ($bom !== "\xEF\xBB\xBF") {
                        rewind($handle);
                    }

                    $header = fgetcsv($handle, 2000, ',');
                    $importedCount = 0;
                    $skippedCount = 0;

                    while (($row = fgetcsv($handle, 2000, ',')) !== false) {
                        if (count($row) < 2) {
                            continue;
                        }

                        $nik            = preg_replace('/[^0-9]/', '', trim($row[0] ?? ''));
                        $noKk           = preg_replace('/[^0-9]/', '', trim($row[1] ?? ''));
                        $nama           = trim($row[2] ?? '');
                        $sexInput       = trim($row[3] ?? '1');
                        $sex            = (in_array(strtoupper($sexInput), ['L', 'LAKI-LAKI', '1'])) ? 1 : 2;
                        $tempatlahir    = trim($row[4] ?? 'Serdang');
                        $tanggallahir   = trim($row[5] ?? '1990-01-01');
                        $suku           = trim($row[6] ?? '');
                        $kkLevel        = intval(trim($row[7] ?? '4')) ?: 4;
                        $agamaId        = intval(trim($row[8] ?? '1')) ?: 1;
                        $pendidikanKkId = intval(trim($row[9] ?? '1')) ?: 1;
                        $pekerjaanId    = intval(trim($row[10] ?? '1')) ?: 1;
                        $statusKawin    = intval(trim($row[11] ?? '1')) ?: 1;
                        $golDarahId     = intval(trim($row[12] ?? '13')) ?: 13;
                        $idCluster      = !empty(trim($row[13] ?? '')) ? intval(trim($row[13])) : null;
                        $statusDasar    = intval(trim($row[14] ?? '1')) ?: 1;
                        $alamatSekarang = trim($row[15] ?? '');
                        $telepon        = trim($row[16] ?? '');
                        $namaAyah       = trim($row[17] ?? '-');
                        $namaIbu        = trim($row[18] ?? '-');
                        $foto           = trim($row[19] ?? '');

                        // Relasi otomatis Kartu Keluarga berdasarkan No. KK
                        $idKk = null;
                        if (!empty($noKk) && strlen($noKk) >= 15) {
                            $keluarga = Keluarga::firstOrCreate(
                                ['no_kk' => $noKk],
                                [
                                    'config_id'  => 1,
                                    'nik_kepala' => $nik,
                                    'tgl_daftar' => now(),
                                    'alamat'     => $alamatSekarang,
                                    'id_cluster' => $idCluster,
                                ]
                            );
                            $idKk = $keluarga->id;
                        }

                        if (! empty($nik) && strlen($nik) >= 15) {
                            Penduduk::withoutGlobalScopes()->updateOrCreate(
                                ['nik' => $nik],
                                [
                                    'config_id'         => 1,
                                    'id_kk'             => $idKk,
                                    'nama'              => $nama,
                                    'sex'               => $sex,
                                    'tempatlahir'       => $tempatlahir,
                                    'tanggallahir'      => $tanggallahir,
                                    'suku'              => $suku,
                                    'kk_level'          => $kkLevel,
                                    'agama_id'          => $agamaId,
                                    'pendidikan_kk_id'  => $pendidikanKkId,
                                    'pekerjaan_id'      => $pekerjaanId,
                                    'status_kawin'      => $statusKawin,
                                    'golongan_darah_id' => $golDarahId,
                                    'id_cluster'        => $idCluster,
                                    'status_dasar'      => $statusDasar,
                                    'alamat_sekarang'   => $alamatSekarang,
                                    'telepon'           => $telepon,
                                    'nama_ayah'         => $namaAyah,
                                    'nama_ibu'          => $namaIbu,
                                    'foto'              => $foto,
                                    'warganegara_id'    => 1,
                                    'dokumen_pasport'   => '-',
                                    'dokumen_kitas'     => '-',
                                ]
                            );
                            $importedCount++;
                        } else {
                            $skippedCount++;
                        }
                    }

                    fclose($handle);

                    Notification::make()
                        ->title('Impor Data Penduduk Berhasil!')
                        ->body("Berhasil mengimpor {$importedCount} data penduduk dan menghubungkannya ke Kartu Keluarga masing-masing." . ($skippedCount > 0 ? " ({$skippedCount} baris diabaikan karena NIK invalid)" : ''))
                        ->persistent()
                        ->success()
                        ->send();
                }),

            Action::make('export_csv')
                ->label('📤 Ekspor Data')
                ->color('gray')
                ->icon('heroicon-o-document-arrow-up')
                ->url(fn () => route('admin.penduduk.export'))
                ->openUrlInNewTab(),
        ];
    }
}
