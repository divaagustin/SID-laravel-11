<?php

use App\Http\Controllers\LayananMandiriController;
use App\Http\Controllers\PendudukExportImportController;
use App\Http\Controllers\PortalController;
use App\Http\Middleware\WargaAuthMiddleware;
use App\Models\LogSurat;
use App\Models\Penduduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Portal Publik Desa (Tanpa Auth / Guest)
Route::get('/', [PortalController::class, 'index'])->name('beranda');
Route::get('/tentang-desa', [PortalController::class, 'tentang'])->name('tentang');
Route::get('/berita', [PortalController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [PortalController::class, 'beritaDetail'])->name('berita.detail');
Route::get('/peta-desa', [PortalController::class, 'petaDesa'])->name('peta');
Route::get('/galeri', [PortalController::class, 'galeri'])->name('galeri');
Route::get('/dokumen-publik', [PortalController::class, 'dokumenPublik'])->name('dokumen');
Route::get('/dokumen-publik/unduh/{id}', [PortalController::class, 'unduhDokumen'])->name('dokumen.unduh');
Route::get('/dokumen-publik/baca/{id}', [PortalController::class, 'bacaDokumen'])->name('dokumen.baca');

// Modul UMKM Warga & Jasa Warga (Publik)
Route::get('/umkm-warga', [PortalController::class, 'umkmWarga'])->name('umkm.publik');
Route::get('/jasa-warga', [PortalController::class, 'jasaWarga'])->name('jasa.publik');

// Layanan Mandiri Warga (Online Request & Auth)
Route::prefix('layanan-mandiri')->group(function () {
    Route::get('/login', [LayananMandiriController::class, 'showLoginForm'])->name('mandiri.login');
    Route::post('/login', [LayananMandiriController::class, 'login'])->middleware('throttle:6,1')->name('mandiri.login.post');
    Route::get('/pendaftaran', [LayananMandiriController::class, 'showRegisterForm'])->name('mandiri.register');
    Route::post('/pendaftaran', [LayananMandiriController::class, 'register'])->middleware('throttle:6,1')->name('mandiri.register.post');

    Route::middleware([WargaAuthMiddleware::class])->group(function () {
        Route::get('/dashboard', [LayananMandiriController::class, 'dashboard'])->name('mandiri.dashboard');
        Route::get('/permohonan-surat', [LayananMandiriController::class, 'permohonanKatalog'])->name('mandiri.surat.katalog');
        Route::get('/permohonan-surat/{suratFormat}', [LayananMandiriController::class, 'permohonanForm'])->name('mandiri.surat.form');
        Route::post('/permohonan-surat/{suratFormat}', [LayananMandiriController::class, 'permohonanStore'])->name('mandiri.surat.store');
        Route::get('/permohonan-surat/detail/{permohonanSurat}', [LayananMandiriController::class, 'permohonanDetail'])->name('mandiri.surat.detail');
        Route::get('/pengaduan', [LayananMandiriController::class, 'pengaduanIndex'])->name('mandiri.pengaduan');
        Route::post('/pengaduan', [LayananMandiriController::class, 'pengaduanStore'])->name('mandiri.pengaduan.store');
        
        // Modul UMKM Warga di Layanan Mandiri
        Route::get('/umkm-saya', [LayananMandiriController::class, 'umkmIndex'])->name('mandiri.umkm');
        Route::post('/umkm-saya', [LayananMandiriController::class, 'umkmStore'])->name('mandiri.umkm.store');
        Route::post('/umkm-saya/toggle/{id}', [LayananMandiriController::class, 'umkmToggle'])->name('mandiri.umkm.toggle');

        // Modul Jasa Warga di Layanan Mandiri
        Route::get('/jasa-saya', [LayananMandiriController::class, 'jasaIndex'])->name('mandiri.jasa');
        Route::post('/jasa-saya', [LayananMandiriController::class, 'jasaStore'])->name('mandiri.jasa.store');
        Route::post('/jasa-saya/ambil/{id}', [LayananMandiriController::class, 'jasaTake'])->name('mandiri.jasa.take');
        Route::post('/jasa-saya/selesai/{id}', [LayananMandiriController::class, 'jasaComplete'])->name('mandiri.jasa.complete');
        Route::post('/jasa-saya/batal/{id}', [LayananMandiriController::class, 'jasaCancel'])->name('mandiri.jasa.cancel');

        Route::get('/bantuan', [LayananMandiriController::class, 'bantuan'])->name('mandiri.bantuan');
        Route::get('/ganti-pin', [LayananMandiriController::class, 'showGantiPinForm'])->name('mandiri.ganti-pin');
        Route::post('/ganti-pin', [LayananMandiriController::class, 'updatePin'])->name('mandiri.ganti-pin.post');
        Route::post('/logout', [LayananMandiriController::class, 'logout'])->name('mandiri.logout');
    });
});

// Jalur Admin Khusus (Memerlukan Autentikasi Staff/Admin Desa)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/surat/cetak/{log}', function (LogSurat $log) {
        $config = DB::table('config')->first();
        $penduduk = Penduduk::withoutGlobalScopes()->find($log->id_pend);

        if (! $penduduk) {
            abort(404, 'Data warga pemohon tidak ditemukan.');
        }

        $pdf = Pdf::loadView('pdf.surat', compact('log', 'config', 'penduduk'));
        return $pdf->stream('Surat_' . str_replace('/', '_', $log->no_surat ?? 'Resmi') . '.pdf');
    })->name('admin.surat.cetak');

    Route::get('/admin/penduduk/unduh-template', [PendudukExportImportController::class, 'downloadTemplate'])->name('admin.penduduk.download-template');
    Route::get('/admin/penduduk/ekspor', [PendudukExportImportController::class, 'exportData'])->name('admin.penduduk.export');

    Route::get('/admin/laporan-bulanan/pdf', function (\Illuminate\Http\Request $request) {
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $page = new \App\Filament\Pages\LaporanBulananPage();
        $page->bulan = $bulan;
        $page->tahun = $tahun;

        $data = $page->getReportDataProperty();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan_bulanan', compact('data'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_Bulanan_Demografi_{$data['bulan_nama']}_{$tahun}.pdf");
    })->name('admin.laporan-bulanan.pdf');

    Route::get('/admin/laporan-mutasi-dusun/pdf', function (\Illuminate\Http\Request $request) {
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $page = new \App\Filament\Pages\LaporanBulananPage();
        $page->bulan = $bulan;
        $page->tahun = $tahun;

        $data = $page->getReportDataProperty();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan_mutasi_dusun', compact('data'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_Mutasi_Dusun_{$data['bulan_nama']}_{$tahun}.pdf");
    })->name('admin.laporan-mutasi-dusun.pdf');

    Route::get('/admin/laporan-bulanan/excel', function (\Illuminate\Http\Request $request) {
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $page = new \App\Filament\Pages\LaporanBulananPage();
        $page->bulan = $bulan;
        $page->tahun = $tahun;

        $data = $page->getReportDataProperty();

        $filename = "Laporan_Demografi_{$data['bulan_nama']}_{$tahun}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // 0. Lampiran IV: Data Penduduk Berdasarkan Kewarganegaraan & Domisili per Dusun
            fputcsv($file, ['LAPORAN PENDUDUK BERDASARKAN KEWARGANEGARAAN & DOMISILI PER DUSUN (LAMPIRAN IV)']);
            fputcsv($file, ['Periode', $data['bulan_nama'] . ' ' . $data['tahun']]);
            fputcsv($file, ['NO', 'NAMA DUSUN', 'WNI TETAP (L)', 'WNI TETAP (P)', 'WNI TETAP (TOTAL)', 'WARGA LUAR DESA (L)', 'WARGA LUAR DESA (P)', 'WARGA LUAR DESA (TOTAL)', 'WNA (L)', 'WNA (P)', 'WNA (TOTAL)', 'TOTAL L', 'TOTAL P', 'TOTAL KESELURUHAN']);

            $no = 1;
            foreach ($data['rows'] as $r) {
                fputcsv($file, [
                    $no++,
                    $r['dusun'],
                    $r['wni_l'],
                    $r['wni_p'],
                    $r['wni_total'],
                    $r['luar_l'],
                    $r['luar_p'],
                    $r['luar_total'],
                    $r['wna_l'],
                    $r['wna_p'],
                    $r['wna_total'],
                    $r['all_l'],
                    $r['all_p'],
                    $r['all_total'],
                ]);
            }
            fputcsv($file, [
                '',
                'JUMLAH TOTAL',
                $data['total']['wni_l'],
                $data['total']['wni_p'],
                $data['total']['wni_total'],
                $data['total']['luar_l'],
                $data['total']['luar_p'],
                $data['total']['luar_total'],
                $data['total']['wna_l'],
                $data['total']['wna_p'],
                $data['total']['wna_total'],
                $data['total']['all_l'],
                $data['total']['all_p'],
                $data['total']['all_total'],
            ]);
            fputcsv($file, []);

            // 1. Data Penduduk Berdasarkan Rentang Usia
            fputcsv($file, ['LAPORAN PENDUDUK BERDASARKAN RENTANG USIA']);
            fputcsv($file, ['Periode', $data['bulan_nama'] . ' ' . $data['tahun']]);
            fputcsv($file, ['NO', 'RENTANG USIA', 'LAKI-LAKI (L)', 'PEREMPUAN (P)', 'JUMLAH TOTAL']);

            $no = 1;
            $totAgeL = 0;
            $totAgeP = 0;
            foreach ($data['age_ranges'] as $a) {
                $sum = $a['l'] + $a['p'];
                $totAgeL += $a['l'];
                $totAgeP += $a['p'];
                fputcsv($file, [$no++, $a['label'], $a['l'], $a['p'], $sum]);
            }
            fputcsv($file, ['', 'JUMLAH TOTAL', $totAgeL, $totAgeP, $totAgeL + $totAgeP]);
            fputcsv($file, []);

            // 2. Data Penduduk Berdasarkan Agama
            fputcsv($file, ['LAPORAN PENDUDUK BERDASARKAN AGAMA']);
            fputcsv($file, ['NO', 'AGAMA', 'JUMLAH PENDUDUK', 'PERSENTASE (%)']);
            $no = 1;
            $totAgama = 0;
            foreach ($data['agama_data'] as $ag) {
                $totAgama += $ag['total'];
                fputcsv($file, [$no++, $ag['nama'], $ag['total'], $ag['persen'] . '%']);
            }
            fputcsv($file, ['', 'JUMLAH TOTAL', $totAgama, '100%']);
            fputcsv($file, []);

            // 3. Data Penduduk Berdasarkan Suku
            fputcsv($file, ['LAPORAN PENDUDUK BERDASARKAN SUKU / ETNIS']);
            fputcsv($file, ['NO', 'SUKU / ETNIS', 'JUMLAH PENDUDUK', 'PERSENTASE (%)']);
            $no = 1;
            $totSuku = 0;
            foreach ($data['suku_data'] as $sk) {
                $totSuku += $sk['total'];
                fputcsv($file, [$no++, $sk['nama'], $sk['total'], $sk['persen'] . '%']);
            }
            fputcsv($file, ['', 'JUMLAH TOTAL', $totSuku, '100%']);

            // 4. Data Penduduk Berdasarkan Pendidikan KK
            fputcsv($file, []);
            fputcsv($file, ['LAPORAN PENDUDUK BERDASARKAN PENDIDIKAN DALAM KK']);
            fputcsv($file, ['NO', 'TINGKAT PENDIDIKAN', 'JUMLAH PENDUDUK', 'PERSENTASE (%)']);
            $no = 1;
            $totPend = 0;
            foreach ($data['pendidikan_data'] as $pd) {
                $totPend += $pd['total'];
                fputcsv($file, [$no++, $pd['nama'], $pd['total'], $pd['persen'] . '%']);
            }
            fputcsv($file, ['', 'JUMLAH TOTAL', $totPend, '100%']);

            // 5. Data Penduduk Berdasarkan Pekerjaan Utama
            fputcsv($file, []);
            fputcsv($file, ['LAPORAN PENDUDUK BERDASARKAN PEKERJAAN UTAMA']);
            fputcsv($file, ['NO', 'NAMA PEKERJAAN', 'JUMLAH PENDUDUK', 'PERSENTASE (%)']);
            $no = 1;
            $totPek = 0;
            foreach ($data['pekerjaan_data'] as $pk) {
                $totPek += $pk['total'];
                fputcsv($file, [$no++, $pk['nama'], $pk['total'], $pk['persen'] . '%']);
            }
            fputcsv($file, ['', 'JUMLAH TOTAL', $totPek, '100%']);

            // 6. Data Penduduk Berdasarkan Status Perkawinan
            fputcsv($file, []);
            fputcsv($file, ['LAPORAN PENDUDUK BERDASARKAN STATUS PERKAWINAN']);
            fputcsv($file, ['NO', 'STATUS PERKAWINAN', 'JUMLAH PENDUDUK', 'PERSENTASE (%)']);
            $no = 1;
            $totKw = 0;
            foreach ($data['status_kawin_data'] as $skw) {
                $totKw += $skw['total'];
                fputcsv($file, [$no++, $skw['nama'], $skw['total'], $skw['persen'] . '%']);
            }
            fputcsv($file, ['', 'JUMLAH TOTAL', $totKw, '100%']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    })->name('admin.laporan-bulanan.excel');
});
