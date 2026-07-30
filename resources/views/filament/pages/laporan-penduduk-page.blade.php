<x-filament-panels::page>
    <div class="space-y-8" x-data="{ tab: 'umur' }">
        
        {{-- Ringkasan Top Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Penduduk Jiwa</div>
                <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($this->stats['total']) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-mono">Laki: {{ number_format($this->stats['laki']) }} | Pr: {{ number_format($this->stats['perempuan']) }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Kepala Keluarga (KK)</div>
                <div class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ number_format($this->stats['kk']) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-mono">Data KK Terdaftar</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Usia Produktif (18-59 Thn)</div>
                <div class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ number_format($this->stats['produktif']) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-mono">Tenaga Kerja Utama Desa</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Lansia (&ge;60 Thn)</div>
                <div class="text-3xl font-black text-purple-600 dark:text-purple-400 mt-1">{{ number_format($this->stats['lansia']) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-mono">Warga Senior</div>
            </div>
        </div>

        {{-- Tab Navigation Dimensi Demografi --}}
        <div class="flex overflow-x-auto gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
            <button @click="tab = 'umur'" :class="tab === 'umur' ? 'bg-amber-500 text-white font-extrabold' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 text-xs rounded-xl transition font-semibold whitespace-nowrap">
                📊 Kelompok Usia
            </button>
            <button @click="tab = 'agama'" :class="tab === 'agama' ? 'bg-amber-500 text-white font-extrabold' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 text-xs rounded-xl transition font-semibold whitespace-nowrap">
                ☪️ Agama &amp; Kepercayaan
            </button>
            <button @click="tab = 'pendidikan'" :class="tab === 'pendidikan' ? 'bg-amber-500 text-white font-extrabold' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 text-xs rounded-xl transition font-semibold whitespace-nowrap">
                🎓 Pendidikan Terakhir
            </button>
            <button @click="tab = 'pekerjaan'" :class="tab === 'pekerjaan' ? 'bg-amber-500 text-white font-extrabold' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 text-xs rounded-xl transition font-semibold whitespace-nowrap">
                💼 Pekerjaan Utama
            </button>
            <button @click="tab = 'kawin'" :class="tab === 'kawin' ? 'bg-amber-500 text-white font-extrabold' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 text-xs rounded-xl transition font-semibold whitespace-nowrap">
                💍 Status Perkawinan
            </button>
            <button @click="tab = 'wilayah'" :class="tab === 'wilayah' ? 'bg-amber-500 text-white font-extrabold' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 text-xs rounded-xl transition font-semibold whitespace-nowrap">
                🏡 Dusun / Wilayah
            </button>
            <button @click="tab = 'suku'" :class="tab === 'suku' ? 'bg-amber-500 text-white font-extrabold' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 text-xs rounded-xl transition font-semibold whitespace-nowrap">
                🗿 Suku &amp; Etnis
            </button>
        </div>

        {{-- TAB 1: KELOMPOK USIA --}}
        <div x-show="tab === 'umur'" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">📊 Matriks Demografi Menurut Kelompok Usia</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Kelompok Usia</th>
                            <th class="px-4 py-3">Rentang Umur</th>
                            <th class="px-4 py-3 text-center">Jumlah Penduduk</th>
                            <th class="px-4 py-3 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr>
                            <td class="px-4 py-3 font-semibold">Balita &amp; Batita</td>
                            <td class="px-4 py-3">0 - 5 Tahun</td>
                            <td class="px-4 py-3 text-center font-bold text-emerald-600">{{ number_format($this->stats['balita']) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($this->stats['balita'] / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold">Anak-Anak &amp; Remaja</td>
                            <td class="px-4 py-3">6 - 17 Tahun</td>
                            <td class="px-4 py-3 text-center font-bold text-blue-600">{{ number_format($this->stats['anak']) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($this->stats['anak'] / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold">Usia Produktif</td>
                            <td class="px-4 py-3">18 - 59 Tahun</td>
                            <td class="px-4 py-3 text-center font-bold text-amber-600">{{ number_format($this->stats['produktif']) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($this->stats['produktif'] / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold">Lansia</td>
                            <td class="px-4 py-3">&ge; 60 Tahun</td>
                            <td class="px-4 py-3 text-center font-bold text-purple-600">{{ number_format($this->stats['lansia']) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($this->stats['lansia'] / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @if($this->stats['unknown_umur'] > 0)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-400">Tidak Diketahui / Belum Isi Tgl Lahir</td>
                            <td class="px-4 py-3 text-gray-400">-</td>
                            <td class="px-4 py-3 text-center font-bold text-gray-400">{{ number_format($this->stats['unknown_umur']) }}</td>
                            <td class="px-4 py-3 text-center font-mono text-gray-400">{{ $this->stats['total'] > 0 ? round(($this->stats['unknown_umur'] / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: AGAMA --}}
        <div x-show="tab === 'agama'" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">☪️ Demografi Menurut Agama &amp; Kepercayaan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Agama / Kepercayaan</th>
                            <th class="px-4 py-3 text-center">Jumlah Penduduk</th>
                            <th class="px-4 py-3 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->stats['agama'] as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-center font-bold text-emerald-600">{{ number_format($item->total) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($item->total / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 3: PENDIDIKAN --}}
        <div x-show="tab === 'pendidikan'" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🎓 Demografi Menurut Pendidikan Terakhir</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Tingkat Pendidikan Dalam KK</th>
                            <th class="px-4 py-3 text-center">Jumlah Penduduk</th>
                            <th class="px-4 py-3 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->stats['pendidikan'] as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-center font-bold text-blue-600">{{ number_format($item->total) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($item->total / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 4: PEKERJAAN --}}
        <div x-show="tab === 'pekerjaan'" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">💼 Demografi Menurut Pekerjaan Utama</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Jenis Pekerjaan</th>
                            <th class="px-4 py-3 text-center">Jumlah Penduduk</th>
                            <th class="px-4 py-3 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->stats['pekerjaan'] as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-center font-bold text-amber-600">{{ number_format($item->total) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($item->total / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 5: STATUS PERKAWINAN --}}
        <div x-show="tab === 'kawin'" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">💍 Demografi Menurut Status Perkawinan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Status Perkawinan</th>
                            <th class="px-4 py-3 text-center">Jumlah Penduduk</th>
                            <th class="px-4 py-3 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->stats['status_kawin'] as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-center font-bold text-purple-600">{{ number_format($item->total) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($item->total / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 6: WILAYAH / DUSUN --}}
        <div x-show="tab === 'wilayah'" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🏡 Demografi Menurut Dusun / Wilayah</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Nama Dusun / Wilayah</th>
                            <th class="px-4 py-3 text-center">Jumlah Penduduk</th>
                            <th class="px-4 py-3 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->stats['wilayah'] as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $item->dusun ?? 'Dusun Tanpa Nama' }}</td>
                            <td class="px-4 py-3 text-center font-bold text-emerald-600">{{ number_format($item->total) }}</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($item->total / $this->stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 7: SUKU / ETNIS --}}
        <div x-show="tab === 'suku'" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🗿 Komposisi Demografi Menurut Suku / Etnis Penduduk</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Suku / Etnis</th>
                            <th class="px-4 py-3 text-center">Jumlah Penduduk</th>
                            <th class="px-4 py-3 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @if(count($this->stats['suku']) > 0)
                            @foreach($this->stats['suku'] as $item)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $item->nama }}</td>
                                <td class="px-4 py-3 text-center font-bold text-amber-600">{{ number_format($item->total) }}</td>
                                <td class="px-4 py-3 text-center font-mono">{{ $this->stats['total'] > 0 ? round(($item->total / $this->stats['total']) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada data suku/etnis yang diisi pada master data penduduk.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
