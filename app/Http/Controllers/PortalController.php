<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\DokumenPublik;
use App\Models\UmkmWarga;
use App\Models\JasaWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortalController extends Controller
{
    public function index()
    {
        $config = DB::table('config')->first();

        $stats = [
            'penduduk'  => DB::table('tweb_penduduk')->where('status_dasar', 1)->count(),
            'keluarga'  => DB::table('tweb_keluarga')->count(),
            'laki_laki' => DB::table('tweb_penduduk')->where('status_dasar', 1)->where('sex', 1)->count(),
            'perempuan' => DB::table('tweb_penduduk')->where('status_dasar', 1)->where('sex', 2)->count(),
            'produk'    => \App\Models\ProdukDesa::where('status', 1)->count(),
            'pembangunan' => \App\Models\Pembangunan::count(),
        ];

        // Slide Berita Utama (Slider = 1)
        $sliderArtikels = Artikel::with('kategori')
            ->where('enabled', 1)
            ->where('slider', 1)
            ->orderByDesc('tgl_upload')
            ->limit(5)
            ->get();

        // 3 Berita Terbaru
        $artikel = Artikel::with('kategori')
            ->where('enabled', 1)
            ->orderByDesc('tgl_upload')
            ->limit(3)
            ->get();

        // Produk Lapak BUMDes
        $produkDesa = \App\Models\ProdukDesa::with('pelapak')
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        // APBDes Terbaru
        $apbdes = \App\Models\KeuanganApbdes::orderByDesc('tahun')->first();

        // Proyek Pembangunan
        $pembangunan = \App\Models\Pembangunan::orderByDesc('tahun_anggaran')
            ->limit(3)
            ->get();

        // Aparatur Desa (Tampilkan di Beranda)
        $pamong = \App\Models\AparaturDesa::with('jabatan')
            ->where('pamong_status', 1)
            ->where('tampilkan_beranda', 1)
            ->orderBy('urut')
            ->get();

        return view('portal.beranda', compact(
            'config',
            'stats',
            'artikel',
            'sliderArtikels',
            'produkDesa',
            'apbdes',
            'pembangunan',
            'pamong'
        ));
    }

    public function tentang()
    {
        $config = DB::table('config')->first();

        // Aparatur Desa — hierarki (Tampilkan di Struktur Organisasi)
        $pamong = \App\Models\AparaturDesa::with('jabatan')
            ->where('pamong_status', 1)
            ->where('tampilkan_struktur', 1)
            ->orderBy('bagan_tingkat')
            ->orderBy('urut')
            ->get();

        $pamongTop    = $pamong->where('atasan', null)->where('bagan_tingkat', '<=', 1);
        $pamongMid    = $pamong->where('bagan_tingkat', 2);
        $pamongBottom = $pamong->where('bagan_tingkat', '>=', 3);

        $kelompokPkk = \App\Models\Kelompok::with(['anggota.penduduk'])
            ->where(function ($q) {
                $q->where('nama', 'like', '%PKK%')
                  ->orWhere('tipe', 'pkk');
            })
            ->first();

        $kelompokBpd = \App\Models\Kelompok::with(['anggota.penduduk'])
            ->where(function ($q) {
                $q->where('nama', 'like', '%BPD%')
                  ->orWhere('nama', 'like', '%Badan Permusyawaratan%')
                  ->orWhere('tipe', 'bpd');
            })
            ->first();

        $totalPenduduk = DB::table('tweb_penduduk')->where('status_dasar', 1)->count();
        $totalKk       = DB::table('tweb_keluarga')->count();
        $totalDusun    = DB::table('tweb_wil_clusterdesa')
            ->where('config_id', 1)
            ->distinct('dusun')
            ->count('dusun');

        return view('portal.tentang', compact(
            'config',
            'pamong',
            'pamongTop',
            'pamongMid',
            'pamongBottom',
            'kelompokPkk',
            'kelompokBpd',
            'totalPenduduk',
            'totalKk',
            'totalDusun'
        ));
    }

    public function berita(Request $request)
    {
        $config    = DB::table('config')->first();
        $kategories = Kategori::where('enabled', 1)->orderBy('kategori')->get();

        $query = Artikel::with('kategori')
            ->where('enabled', 1);

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->q . '%')
                    ->orWhere('isi', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        $artikels = $query->orderByDesc('tgl_upload')->paginate(9)->withQueryString();

        return view('portal.berita', compact('config', 'kategories', 'artikels'));
    }

    public function beritaDetail($slug)
    {
        $config  = DB::table('config')->first();
        $artikel = Artikel::with(['kategori', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        $artikel->increment('hit');

        $beritaTerkait = Artikel::where('enabled', 1)
            ->where('id', '!=', $artikel->id)
            ->where('id_kategori', $artikel->id_kategori)
            ->orderByDesc('tgl_upload')
            ->limit(3)
            ->get();

        return view('portal.berita_detail', compact('config', 'artikel', 'beritaTerkait'));
    }

    public function petaDesa()
    {
        $config = DB::table('config')->first();

        $defaultLat = $config->lat ?? '-3.023456';
        $defaultLng = $config->lng ?? '99.612345';

        $lokasis = \App\Models\Lokasi::with('kategoriPoint')
            ->where('enabled', 1)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();

        $areas = \App\Models\Area::where('enabled', 1)
            ->whereNotNull('path')
            ->get();

        $kategoriPoints = \App\Models\GisPoint::where('enabled', 1)->get();

        return view('portal.peta', compact('config', 'defaultLat', 'defaultLng', 'lokasis', 'areas', 'kategoriPoints'));
    }

    public function galeri()
    {
        $config  = DB::table('config')->first();
        $galeris = \App\Models\Galeri::where('enabled', 1)
            ->orderByDesc('tgl_upload')
            ->paginate(12);

        return view('portal.galeri', compact('config', 'galeris'));
    }

    public function dokumenPublik(Request $request)
    {
        $config = DB::table('config')->first();

        $query = DokumenPublik::where('enabled', 1);

        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_info_publik', $request->kategori);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $dokumens = $query->orderByDesc('tgl_upload')->paginate(10)->withQueryString();

        return view('portal.dokumen', compact('config', 'dokumens'));
    }

    public function unduhDokumen($id)
    {
        $dokumen = DokumenPublik::findOrFail($id);
        $cleanPath = ltrim($dokumen->satuan ?? '', '/');
        if (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        $fileName = Str::slug($dokumen->nama) . '.pdf';

        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->download($cleanPath, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        $publicPath = storage_path('app/public/' . $cleanPath);
        if (file_exists($publicPath) && ! is_dir($publicPath)) {
            return response()->download($publicPath, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        $privatePath = storage_path('app/' . $cleanPath);
        if (file_exists($privatePath) && ! is_dir($privatePath)) {
            return response()->download($privatePath, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        abort(404, 'Berkas dokumen publik belum diunggah atau tidak ditemukan.');
    }

    public function bacaDokumen($id)
    {
        $dokumen = DokumenPublik::findOrFail($id);
        $cleanPath = ltrim($dokumen->satuan ?? '', '/');
        if (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        $fileName = Str::slug($dokumen->nama) . '.pdf';

        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->response($cleanPath, $fileName, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        $publicPath = storage_path('app/public/' . $cleanPath);
        if (file_exists($publicPath) && ! is_dir($publicPath)) {
            return response()->file($publicPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        $privatePath = storage_path('app/' . $cleanPath);
        if (file_exists($privatePath) && ! is_dir($privatePath)) {
            return response()->file($privatePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        abort(404, 'Berkas dokumen publik belum diunggah atau tidak ditemukan.');
    }

    /**
     * Halaman Direktori Katalog UMKM Warga Desa
     */
    public function umkmWarga(Request $request)
    {
        $config = DB::table('config')->first();

        $query = UmkmWarga::where('status_moderasi', 'approved')
            ->whereHas('pemilik', function ($q) {
                $q->where('status_dasar', 1);
            });

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_usaha', 'like', '%' . $request->q . '%')
                  ->orWhere('deskripsi_produk', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_usaha', $request->kategori);
        }

        if ($request->filled('operasional')) {
            $query->where('status_operasional', $request->operasional);
        }

        $umkms = $query->orderByDesc('id')->paginate(12)->withQueryString();

        return view('portal.umkm', compact('config', 'umkms'));
    }

    /**
     * Halaman Papan Pekerjaan Jasa Warga (Micro-Tasking)
     */
    public function jasaWarga(Request $request)
    {
        $config = DB::table('config')->first();

        $query = JasaWarga::where('status_moderasi', 'approved')
            ->whereHas('pembuat', function ($q) {
                $q->where('status_dasar', 1);
            });

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul_pekerjaan', 'like', '%' . $request->q . '%')
                  ->orWhere('deskripsi_tugas', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status_job', $request->status);
        }

        $jasas = $query->orderByDesc('id')->paginate(9)->withQueryString();

        return view('portal.jasa', compact('config', 'jasas'));
    }
}
