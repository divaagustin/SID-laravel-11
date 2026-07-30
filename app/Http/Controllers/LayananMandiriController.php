<?php

namespace App\Http\Controllers;

use App\Models\PermohonanSurat;
use App\Models\PendudukMandiri;
use App\Models\SuratFormat;
use App\Models\UmkmWarga;
use App\Models\JasaWarga;
use App\Models\LogTransaksiJasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LayananMandiriController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('warga')->check()) {
            return redirect()->route('mandiri.dashboard');
        }

        $config = DB::table('config')->first();
        return view('mandiri.login', compact('config'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'pin' => 'required|string',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'pin.required' => 'PIN / Password wajib diisi.',
        ]);

        $penduduk = DB::table('tweb_penduduk')->where('nik', $request->nik)->first();

        if (! $penduduk) {
            return back()->withInput()->withErrors(['nik' => 'NIK tidak terdaftar di database kependudukan desa.']);
        }

        $mandiri = PendudukMandiri::where('id_pend', $penduduk->id)->first();

        if (! $mandiri) {
            return back()->withInput()->withErrors(['nik' => 'NIK Anda belum diaktifkan sebagai akun Layanan Mandiri. Silakan klik "Aktivasi PIN / Buat Akun Baru" di bawah.']);
        }

        if ((int) $mandiri->aktif !== 1 || (int) $penduduk->status_dasar !== 1) {
            return back()->withInput()->withErrors(['nik' => 'Akun Layanan Mandiri Anda tidak aktif / status kependudukan tidak memenuhi syarat. Silakan hubungi Kantor Desa.']);
        }

        // Validasi PIN: Dukungan Hash Bcrypt & Re-hash otomatis jika PIN lama (Legacy MD5 / Plaintext)
        $isPinValid = false;
        if (Hash::check($request->pin, $mandiri->pin)) {
            $isPinValid = true;
        } elseif ($mandiri->pin === $request->pin || $mandiri->pin === md5($request->pin)) {
            $mandiri->update(['pin' => Hash::make($request->pin)]);
            $isPinValid = true;
        }

        if (! $isPinValid) {
            return back()->withInput()->withErrors(['pin' => 'PIN / Password yang Anda masukkan salah.']);
        }

        Auth::guard('warga')->login($mandiri);

        if ($mandiri->ganti_pin == 1) {
            return redirect()->route('mandiri.ganti-pin')->with('warning', 'Demi keamanan, silakan buat PIN baru Anda.');
        }

        return redirect()->route('mandiri.dashboard')->with('success', 'Selamat datang di Portal Layanan Mandiri Desa!');
    }

    public function showRegisterForm()
    {
        $config = DB::table('config')->first();
        return view('mandiri.register', compact('config'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'pin' => 'required|string|min:6|confirmed',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size'     => 'NIK harus 16 digit.',
            'pin.required' => 'PIN wajib diisi.',
            'pin.min'      => 'PIN minimal 6 digit.',
            'pin.confirmed'=> 'Konfirmasi PIN tidak cocok.',
        ]);

        $penduduk = DB::table('tweb_penduduk')->where('nik', $request->nik)->first();

        if (! $penduduk) {
            return back()->withInput()->withErrors(['nik' => 'NIK Anda belum terdata di Master Database Kependudukan Desa. Silakan hubungi Kantor Desa.']);
        }

        $exists = PendudukMandiri::where('id_pend', $penduduk->id)->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['nik' => 'NIK Anda sudah pernah mengaktifkan akun Layanan Mandiri. Silakan Login.']);
        }

        PendudukMandiri::create([
            'config_id'    => $penduduk->config_id ?? 1,
            'id_pend'      => $penduduk->id,
            'pin'          => Hash::make($request->pin),
            'ganti_pin'    => 0,
            'tanggal_buat' => now(),
            'aktif'        => 1,
        ]);

        return redirect()->route('mandiri.login')->with('success', 'Aktivasi Akun Berhasil! Silakan masuk menggunakan NIK dan PIN baru Anda.');
    }

    public function dashboard()
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $permohonans = PermohonanSurat::with('suratFormat')
            ->where('id_pemohon', $penduduk->id)
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $umkmSayaCount = UmkmWarga::where('penduduk_id', $penduduk->id)->count();
        $jasaSayaCount = JasaWarga::where('pembuat_id', $penduduk->id)->orWhere('pekerja_id', $penduduk->id)->count();

        return view('mandiri.dashboard', compact('mandiri', 'penduduk', 'permohonans', 'umkmSayaCount', 'jasaSayaCount'));
    }

    public function permohonanKatalog()
    {
        $mandiri      = Auth::guard('warga')->user();
        $penduduk     = $mandiri->penduduk;
        $suratFormats = SuratFormat::where('mandiri', 1)->get();

        return view('mandiri.surat_katalog', compact('suratFormats', 'penduduk'));
    }

    public function permohonanForm(SuratFormat $suratFormat)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        return view('mandiri.surat_form', compact('suratFormat', 'penduduk'));
    }

    public function permohonanStore(Request $request, SuratFormat $suratFormat)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $request->validate([
            'no_hp_aktif' => 'required|string|max:20',
            'keterangan'  => 'nullable|string|max:500',
            'syarat_file.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5072',
        ], [
            'no_hp_aktif.required' => 'Nomor HP WhatsApp aktif wajib diisi untuk notifikasi.',
        ]);

        $uploadedSyarat = [];
        if ($request->hasFile('syarat_file')) {
            foreach ($request->file('syarat_file') as $syaratId => $file) {
                $path = $file->store('permohonan-syarat', 'public');
                $uploadedSyarat[$syaratId] = $path;
            }
        }

        $noAntrian = 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        PermohonanSurat::create([
            'config_id'   => 1,
            'id_pemohon'  => $penduduk->id,
            'id_surat'    => $suratFormat->id,
            'isian_form'  => $request->except(['_token', 'no_hp_aktif', 'keterangan', 'syarat_file']),
            'status'      => 0,
            'keterangan'  => $request->keterangan,
            'no_hp_aktif' => $request->no_hp_aktif,
            'syarat'      => $uploadedSyarat,
            'no_antrian'  => $noAntrian,
        ]);

        return redirect()->route('mandiri.dashboard')->with('success', "Permohonan {$suratFormat->nama} berhasil diajukan dengan Nomor Antrean: {$noAntrian}");
    }

    public function permohonanDetail(PermohonanSurat $permohonanSurat)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        if ($permohonanSurat->id_pemohon !== $penduduk->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('mandiri.surat_detail', compact('permohonanSurat', 'penduduk'));
    }

    // ===== MODUL UMKM WARGA DI LAYANAN MANDIRI =====
    public function umkmIndex()
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $umkms = UmkmWarga::where('penduduk_id', $penduduk->id)
            ->orWhere('nik_pemilik', $penduduk->nik)
            ->orderByDesc('id')
            ->get();

        return view('mandiri.umkm', compact('penduduk', 'umkms'));
    }

    public function umkmStore(Request $request)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $request->validate([
            'nama_usaha'       => 'required|string|max:150',
            'kategori_usaha'   => 'required|string',
            'no_whatsapp'      => 'required|string|max:25',
            'jam_operasional'  => 'nullable|string|max:100',
            'deskripsi_produk' => 'nullable|string|max:2000',
            'alamat_usaha'     => 'nullable|string|max:500',
            'foto_usaha'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5072',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_usaha')) {
            $fotoPath = $request->file('foto_usaha')->store('galeri', 'public');
        }

        UmkmWarga::create([
            'config_id'          => 1,
            'penduduk_id'        => $penduduk->id,
            'nik_pemilik'        => $penduduk->nik,
            'nama_usaha'         => $request->nama_usaha,
            'kategori_usaha'     => $request->kategori_usaha,
            'deskripsi_produk'   => $request->deskripsi_produk,
            'foto_usaha'         => $fotoPath,
            'jam_operasional'    => $request->jam_operasional ?? '08.00 - 17.00 WIB',
            'no_whatsapp'        => $request->no_whatsapp,
            'alamat_usaha'       => $request->alamat_usaha,
            'status_operasional' => 'buka',
            'status_moderasi'   => 'pending',
        ]);

        return back()->with('success', 'Pengajuan UMKM berhasil dikirim! Menunggu verifikasi dari Admin Desa.');
    }

    public function umkmToggle($id)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $umkm = UmkmWarga::where('id', $id)
            ->where(function ($q) use ($penduduk) {
                $q->where('penduduk_id', $penduduk->id)->orWhere('nik_pemilik', $penduduk->nik);
            })
            ->firstOrFail();

        $newStatus = $umkm->status_operasional === 'buka' ? 'tutup' : 'buka';
        $umkm->update(['status_operasional' => $newStatus]);

        return back()->with('success', "Status operasional UMKM {$umkm->nama_usaha} diubah menjadi: " . strtoupper($newStatus));
    }

    // ===== MODUL JASA WARGA (MICRO-TASKING) DI LAYANAN MANDIRI =====
    public function jasaIndex()
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        // 1. Lowongan job warga lain yang siap diambil (Approved & Open & Bukan milik sendiri)
        $openJobs = JasaWarga::with('pembuat')
            ->where('status_moderasi', 'approved')
            ->where('status_job', 'open')
            ->where(function ($q) use ($penduduk) {
                $q->where('pembuat_id', '!=', $penduduk->id)
                  ->where('nik_pembuat', '!=', $penduduk->nik);
            })
            ->orderByDesc('id')
            ->get();

        // 2. Job yang dibuat sendiri
        $myRequests = JasaWarga::where('pembuat_id', $penduduk->id)
            ->orWhere('nik_pembuat', $penduduk->nik)
            ->orderByDesc('id')
            ->get();

        // 3. Job orang lain yang diambil/dikerjakan
        $myTakenJobs = JasaWarga::with(['pembuat'])
            ->where('pekerja_id', $penduduk->id)
            ->orWhere('nik_pekerja', $penduduk->nik)
            ->orderByDesc('id')
            ->get();

        return view('mandiri.jasa', compact('penduduk', 'openJobs', 'myRequests', 'myTakenJobs'));
    }

    public function jasaStore(Request $request)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $request->validate([
            'judul_pekerjaan' => 'required|string|max:150',
            'kategori'        => 'required|string',
            'fee_insentif'    => 'required|numeric|min:0',
            'lokasi_dusun_rt' => 'required|string|max:100',
            'deskripsi_tugas' => 'required|string|max:2000',
            'alamat_detail'   => 'required|string|max:1000',
            'tenggat_waktu'   => 'nullable|date',
        ]);

        $jasa = JasaWarga::create([
            'config_id'       => 1,
            'pembuat_id'      => $penduduk->id,
            'nik_pembuat'     => $penduduk->nik,
            'judul_pekerjaan' => $request->judul_pekerjaan,
            'kategori'        => $request->kategori,
            'deskripsi_tugas' => $request->deskripsi_tugas,
            'fee_insentif'    => $request->fee_insentif,
            'tenggat_waktu'   => $request->tenggat_waktu,
            'lokasi_dusun_rt' => $request->lokasi_dusun_rt,
            'alamat_detail'   => $request->alamat_detail,
            'status_job'      => 'open',
            'status_moderasi' => 'pending',
        ]);

        LogTransaksiJasa::create([
            'jasa_id'     => $jasa->id,
            'penduduk_id' => $penduduk->id,
            'aksi'        => 'post_created',
            'keterangan'  => 'Posting lowongan jasa dibuat oleh warga.',
        ]);

        return back()->with('success', 'Request pekerjaan berhasil diposting! Menunggu verifikasi moderasi Admin Desa.');
    }

    public function jasaTake($id)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        // Atomic Transaction dengan pessimistic locking
        return DB::transaction(function () use ($id, $mandiri, $penduduk) {
            $jasa = JasaWarga::where('id', $id)->lockForUpdate()->first();

            if (! $jasa) {
                return back()->with('error', 'Lowongan pekerjaan tidak ditemukan.');
            }

            if ($jasa->status_moderasi !== 'approved') {
                return back()->with('error', 'Lowongan ini belum disetujui Admin.');
            }

            if ($jasa->status_job !== 'open') {
                return back()->with('error', 'Maaf, lowongan pekerjaan ini sudah diambil oleh warga lain.');
            }

            if ($jasa->pembuat_id === $penduduk->id || $jasa->nik_pembuat === $penduduk->nik) {
                return back()->with('error', 'Anda tidak dapat mengambil pekerjaan yang Anda buat sendiri.');
            }

            $jasa->update([
                'pekerja_id'  => $penduduk->id,
                'nik_pekerja' => $penduduk->nik,
                'status_job'  => 'in_progress',
            ]);

            LogTransaksiJasa::create([
                'jasa_id'     => $jasa->id,
                'penduduk_id' => $penduduk->id,
                'aksi'        => 'job_taken',
                'keterangan'  => "Pekerjaan diambil oleh {$penduduk->nama}.",
            ]);

            return back()->with('success', 'Selamat! Anda berhasil mengambil pekerjaan ini. Alamat detail & kontak pembuat job kini dapat Anda lihat.');
        });
    }

    public function jasaComplete($id)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $jasa = JasaWarga::where('id', $id)
            ->where(function ($q) use ($penduduk) {
                $q->where('pembuat_id', $penduduk->id)->orWhere('nik_pembuat', $penduduk->nik);
            })
            ->firstOrFail();

        $jasa->update(['status_job' => 'completed']);

        LogTransaksiJasa::create([
            'jasa_id'     => $jasa->id,
            'penduduk_id' => $penduduk->id,
            'aksi'        => 'completed',
            'keterangan'  => 'Pekerjaan telah dikonfirmasi SELESAI oleh pembuat job.',
        ]);

        return back()->with('success', 'Pekerjaan berhasil ditandai SELESAI. Terima kasih!');
    }

    public function jasaCancel($id)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $jasa = JasaWarga::where('id', $id)
            ->where(function ($q) use ($penduduk) {
                $q->where('pembuat_id', $penduduk->id)->orWhere('nik_pembuat', $penduduk->nik);
            })
            ->firstOrFail();

        $jasa->update(['status_job' => 'cancelled']);

        LogTransaksiJasa::create([
            'jasa_id'     => $jasa->id,
            'penduduk_id' => $penduduk->id,
            'aksi'        => 'cancelled',
            'keterangan'  => 'Pekerjaan dibatalkan oleh pembuat job.',
        ]);

        return back()->with('success', 'Pekerjaan berhasil dibatalkan.');
    }

    public function showGantiPinForm()
    {
        return view('mandiri.ganti_pin');
    }

    public function updatePin(Request $request)
    {
        $mandiri = Auth::guard('warga')->user();

        if ($mandiri->ganti_pin == 0) {
            $request->validate([
                'pin_lama' => 'required|string',
                'pin'      => 'required|string|min:6|confirmed',
            ], [
                'pin_lama.required' => 'PIN lama wajib diisi.',
                'pin.required'      => 'PIN baru wajib diisi.',
                'pin.min'           => 'PIN baru minimal 6 digit.',
                'pin.confirmed'     => 'Konfirmasi PIN baru tidak cocok.',
            ]);

            if (! Hash::check($request->pin_lama, $mandiri->pin)) {
                return back()->withErrors(['pin_lama' => 'PIN lama yang Anda masukkan salah.']);
            }
        } else {
            $request->validate([
                'pin' => 'required|string|min:6|confirmed',
            ], [
                'pin.required'  => 'PIN baru wajib diisi.',
                'pin.min'       => 'PIN baru minimal 6 digit.',
                'pin.confirmed' => 'Konfirmasi PIN baru tidak cocok.',
            ]);
        }

        $mandiri->update([
            'pin'       => Hash::make($request->pin),
            'ganti_pin' => 0,
        ]);

        return redirect()->route('mandiri.dashboard')->with('success', 'PIN Anda berhasil diperbarui!');
    }

    public function pengaduanIndex()
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $pengaduanList = \App\Models\PesanMandiri::where('penduduk_id', $penduduk->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mandiri.pengaduan', compact('penduduk', 'pengaduanList'));
    }

    public function pengaduanStore(Request $request)
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $request->validate([
            'subjek'   => 'required|string|max:255',
            'komentar' => 'required|string|max:2000',
        ]);

        \App\Models\PesanMandiri::create([
            'config_id'   => 1,
            'owner'       => '1',
            'penduduk_id' => $penduduk->id,
            'subjek'      => $request->subjek,
            'komentar'    => $request->komentar,
            'status'      => 1,
            'tipe'        => 1,
        ]);

        return back()->with('success', 'Pengaduan / aspirasi Anda berhasil dikirim ke Pemerintah Desa!');
    }

    public function bantuan()
    {
        $mandiri  = Auth::guard('warga')->user();
        $penduduk = $mandiri->penduduk;

        $bantuanList = \App\Models\PesertaBantuan::with('program')
            ->where('kartu_id_pend', $penduduk->id)
            ->orWhere('kartu_nik', $penduduk->nik)
            ->get();

        return view('mandiri.bantuan', compact('mandiri', 'penduduk', 'bantuanList'));
    }

    public function logout()
    {
        Auth::guard('warga')->logout();

        return redirect()->route('mandiri.login')->with('success', 'Anda telah keluar dari Layanan Mandiri.');
    }
}
