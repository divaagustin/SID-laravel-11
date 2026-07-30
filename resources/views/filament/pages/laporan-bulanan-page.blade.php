<x-filament-panels::page>
    @php
        $data = $this->reportData;
        $rows = $data['rows'];
        $total = $data['total'];
        $ageRanges = $data['age_ranges'];
        $agamaData = $data['agama_data'];
        $sukuData = $data['suku_data'];
        $pendidikanData = $data['pendidikan_data'];
        $pekerjaanData = $data['pekerjaan_data'];
        $statusKawinData = $data['status_kawin_data'];
    @endphp

    <style>
        .report-card {
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .dark .report-card {
            border-color: #374151;
        }
        .report-card-title {
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 0.875rem !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem !important;
            line-height: 1.4 !important;
            text-align: center;
        }
        .report-table th, .report-table td {
            border: 1px solid #cbd5e1 !important;
            padding: 8px 10px !important;
            font-size: 0.8125rem !important;
        }
        .dark .report-table th, .dark .report-table td {
            border: 1px solid #374151 !important;
        }
    </style>

    {{-- Action Bar Header --}}
    <div class="report-card bg-white dark:bg-gray-900">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Bulan:</span>
                    <select wire:model.live="bulan" class="text-sm border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-800 dark:text-white px-3 py-1.5">
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tahun:</span>
                    <select wire:model.live="tahun" class="text-sm border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-800 dark:text-white px-3 py-1.5">
                        @for($y = date('Y'); $y >= 2019; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan-bulanan.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-lg shadow transition duration-150">
                    <x-heroicon-o-document-text style="width: 16px; height: 16px;" />
                    Ekspor Excel / CSV
                </a>

                <a href="{{ route('admin.laporan-bulanan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition duration-150">
                    <x-heroicon-o-printer style="width: 16px; height: 16px;" />
                    Cetak PDF Lampiran IV
                </a>
            </div>
        </div>
    </div>

    {{-- TABEL 1: DATA PENDUDUK BERDASARKAN KEWARGANEGARAAN PER DUSUN --}}
    <div class="report-card bg-white dark:bg-gray-900">
        <h3 class="report-card-title text-gray-900 dark:text-white">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
            1. Data Penduduk Berdasarkan Kewarganegaraan per Dusun (Lampiran IV)
        </h3>

        <div class="overflow-x-auto">
            <table class="report-table">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold">
                        <th rowspan="2" style="width: 40px;">NO</th>
                        <th rowspan="2" style="text-align: left; min-width: 160px;">NAMA DUSUN</th>
                        <th colspan="3">WARGA DESA (WNI TETAP)</th>
                        <th colspan="3">WARGA LUAR DESA (TIDAK TETAP)</th>
                        <th colspan="3">WARGA NEGARA ASING (WNA)</th>
                        <th colspan="3">JUMLAH TOTAL PENDUDUK</th>
                    </tr>
                    <tr class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-semibold">
                        <th style="width: 45px;">L</th>
                        <th style="width: 45px;">P</th>
                        <th style="width: 65px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">JUMLAH</th>
                        <th style="width: 45px;">L</th>
                        <th style="width: 45px;">P</th>
                        <th style="width: 65px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">JUMLAH</th>
                        <th style="width: 45px;">L</th>
                        <th style="width: 45px;">P</th>
                        <th style="width: 65px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">JUMLAH</th>
                        <th style="width: 45px;">L</th>
                        <th style="width: 45px;">P</th>
                        <th style="width: 75px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @forelse($rows as $index => $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left;" class="font-bold text-gray-900 dark:text-white uppercase">{{ $row['dusun'] }}</td>
                            <td>{{ number_format($row['wni_l'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['wni_p'], 0, ',', '.') }}</td>
                            <td class="font-bold bg-gray-50 dark:bg-gray-800/40">{{ number_format($row['wni_total'], 0, ',', '.') }}</td>

                            <td>{{ $row['luar_l'] > 0 ? number_format($row['luar_l'], 0, ',', '.') : '-' }}</td>
                            <td>{{ $row['luar_p'] > 0 ? number_format($row['luar_p'], 0, ',', '.') : '-' }}</td>
                            <td class="font-bold bg-amber-50/50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300">{{ $row['luar_total'] > 0 ? number_format($row['luar_total'], 0, ',', '.') : '-' }}</td>

                            <td>{{ $row['wna_l'] > 0 ? number_format($row['wna_l'], 0, ',', '.') : '-' }}</td>
                            <td>{{ $row['wna_p'] > 0 ? number_format($row['wna_p'], 0, ',', '.') : '-' }}</td>
                            <td class="font-bold bg-purple-50/50 dark:bg-purple-950/20 text-purple-800 dark:text-purple-300">{{ $row['wna_total'] > 0 ? number_format($row['wna_total'], 0, ',', '.') : '-' }}</td>

                            <td>{{ number_format($row['all_l'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['all_p'], 0, ',', '.') }}</td>
                            <td class="font-extrabold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400">{{ number_format($row['all_total'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-gray-500 dark:text-gray-400 italic p-4">Belum ada data dusun tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 dark:bg-gray-800 font-extrabold text-gray-900 dark:text-white">
                        <td colspan="2" class="uppercase">Jumlah Total</td>
                        <td>{{ number_format($total['wni_l'], 0, ',', '.') }}</td>
                        <td>{{ number_format($total['wni_p'], 0, ',', '.') }}</td>
                        <td class="bg-gray-200/60 dark:bg-gray-700/60">{{ number_format($total['wni_total'], 0, ',', '.') }}</td>

                        <td>{{ $total['luar_l'] > 0 ? number_format($total['luar_l'], 0, ',', '.') : '-' }}</td>
                        <td>{{ $total['luar_p'] > 0 ? number_format($total['luar_p'], 0, ',', '.') : '-' }}</td>
                        <td class="bg-amber-200/60 dark:bg-amber-800/60 text-amber-900 dark:text-amber-100">{{ $total['luar_total'] > 0 ? number_format($total['luar_total'], 0, ',', '.') : '-' }}</td>

                        <td>{{ $total['wna_l'] > 0 ? number_format($total['wna_l'], 0, ',', '.') : '-' }}</td>
                        <td>{{ $total['wna_p'] > 0 ? number_format($total['wna_p'], 0, ',', '.') : '-' }}</td>
                        <td class="bg-purple-200/60 dark:bg-purple-800/60 text-purple-900 dark:text-purple-100">{{ $total['wna_total'] > 0 ? number_format($total['wna_total'], 0, ',', '.') : '-' }}</td>

                        <td>{{ number_format($total['all_l'], 0, ',', '.') }}</td>
                        <td>{{ number_format($total['all_p'], 0, ',', '.') }}</td>
                        <td class="bg-emerald-600 text-white font-extrabold">{{ number_format($total['all_total'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- TABEL 2: DATA PENDUDUK BERDASARKAN RENTANG USIA --}}
    <div class="report-card bg-white dark:bg-gray-900">
        <h3 class="report-card-title text-gray-900 dark:text-white">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
            2. Data Penduduk Berdasarkan Rentang Usia
        </h3>

        <div class="overflow-x-auto">
            <table class="report-table">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold">
                        <th style="width: 45px;">NO</th>
                        <th style="text-align: left;">RENTANG USIA</th>
                        <th style="width: 140px;">LAKI-LAKI (L)</th>
                        <th style="width: 140px;">PEREMPUAN (P)</th>
                        <th style="width: 160px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">JUMLAH TOTAL</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @php $sumAgeL = 0; $sumAgeP = 0; @endphp
                    @foreach($ageRanges as $idx => $ageRow)
                        @php 
                            $rowTotal = $ageRow['l'] + $ageRow['p'];
                            $sumAgeL += $ageRow['l'];
                            $sumAgeP += $ageRow['p'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="text-gray-600 dark:text-gray-400">{{ $idx + 1 }}</td>
                            <td style="text-align: left;" class="font-bold text-gray-900 dark:text-white">{{ $ageRow['label'] }}</td>
                            <td>{{ number_format($ageRow['l'], 0, ',', '.') }}</td>
                            <td>{{ number_format($ageRow['p'], 0, ',', '.') }}</td>
                            <td class="font-bold bg-blue-50/50 dark:bg-blue-950/20 text-blue-800 dark:text-blue-300">{{ number_format($rowTotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 dark:bg-gray-800 font-extrabold text-gray-900 dark:text-white">
                        <td colspan="2" class="uppercase">Jumlah Total</td>
                        <td>{{ number_format($sumAgeL, 0, ',', '.') }}</td>
                        <td>{{ number_format($sumAgeP, 0, ',', '.') }}</td>
                        <td class="bg-blue-600 text-white font-extrabold">{{ number_format($sumAgeL + $sumAgeP, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- GRID DUA KOLOM: AGAMA & SUKU --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        {{-- TABEL 3: AGAMA --}}
        <div class="report-card bg-white dark:bg-gray-900 !mb-0">
            <h3 class="report-card-title text-gray-900 dark:text-white">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                3. Data Penduduk Berdasarkan Agama
            </h3>

            <div class="overflow-x-auto">
                <table class="report-table">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold">
                            <th style="text-align: left;">AGAMA</th>
                            <th style="width: 100px;">JUMLAH</th>
                            <th style="width: 110px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">PERSENTASE</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900">
                        @php $totAgama = 0; @endphp
                        @forelse($agamaData as $ag)
                            @php $totAgama += $ag['total']; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td style="text-align: left;" class="font-bold text-gray-900 dark:text-white">{{ $ag['nama'] }}</td>
                                <td class="font-medium">{{ number_format($ag['total'], 0, ',', '.') }}</td>
                                <td class="font-bold bg-amber-50/50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300">{{ $ag['persen'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 italic p-4">Belum ada data agama.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 dark:bg-gray-800 font-extrabold text-gray-900 dark:text-white">
                            <td style="text-align: left;" class="uppercase">Jumlah Total</td>
                            <td>{{ number_format($totAgama, 0, ',', '.') }}</td>
                            <td class="bg-amber-600 text-white font-extrabold">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- TABEL 4: SUKU / ETNIS --}}
        <div class="report-card bg-white dark:bg-gray-900 !mb-0">
            <h3 class="report-card-title text-gray-900 dark:text-white">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500 inline-block"></span>
                4. Data Penduduk Berdasarkan Suku / Etnis
            </h3>

            <div class="overflow-x-auto">
                <table class="report-table">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold">
                            <th style="text-align: left;">SUKU / ETNIS</th>
                            <th style="width: 100px;">JUMLAH</th>
                            <th style="width: 110px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">PERSENTASE</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900">
                        @php $totSuku = 0; @endphp
                        @forelse($sukuData as $sk)
                            @php $totSuku += $sk['total']; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td style="text-align: left;" class="font-bold text-gray-900 dark:text-white uppercase">{{ $sk['nama'] }}</td>
                                <td class="font-medium">{{ number_format($sk['total'], 0, ',', '.') }}</td>
                                <td class="font-bold bg-purple-50/50 dark:bg-purple-950/20 text-purple-800 dark:text-purple-300">{{ $sk['persen'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 italic p-4">Belum ada data suku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 dark:bg-gray-800 font-extrabold text-gray-900 dark:text-white">
                            <td style="text-align: left;" class="uppercase">Jumlah Total</td>
                            <td>{{ number_format($totSuku, 0, ',', '.') }}</td>
                            <td class="bg-purple-600 text-white font-extrabold">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    {{-- GRID TIGA KOLOM: PENDIDIKAN KK, PEKERJAAN, & STATUS KAWIN --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- TABEL 5: PENDIDIKAN KK --}}
        <div class="report-card bg-white dark:bg-gray-900 !mb-0">
            <h3 class="report-card-title text-gray-900 dark:text-white">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 inline-block"></span>
                5. Pendidikan Dalam KK
            </h3>

            <div class="overflow-x-auto">
                <table class="report-table">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold">
                            <th style="text-align: left;">TINGKAT PENDIDIKAN</th>
                            <th style="width: 70px;">JUMLAH</th>
                            <th style="width: 80px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900">
                        @php $totPend = 0; @endphp
                        @forelse($pendidikanData as $pd)
                            @php $totPend += $pd['total']; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td style="text-align: left;" class="font-semibold text-gray-900 dark:text-white">{{ $pd['nama'] }}</td>
                                <td class="font-medium">{{ number_format($pd['total'], 0, ',', '.') }}</td>
                                <td class="font-bold bg-cyan-50/50 dark:bg-cyan-950/20 text-cyan-800 dark:text-cyan-300">{{ $pd['persen'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 italic p-4">Belum ada data pendidikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 dark:bg-gray-800 font-extrabold text-gray-900 dark:text-white">
                            <td style="text-align: left;" class="uppercase">Jumlah</td>
                            <td>{{ number_format($totPend, 0, ',', '.') }}</td>
                            <td class="bg-cyan-600 text-white font-extrabold">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- TABEL 6: PEKERJAAN UTAMA --}}
        <div class="report-card bg-white dark:bg-gray-900 !mb-0">
            <h3 class="report-card-title text-gray-900 dark:text-white">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500 inline-block"></span>
                6. Pekerjaan Utama
            </h3>

            <div class="overflow-x-auto max-h-[420px] overflow-y-auto">
                <table class="report-table">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold sticky top-0">
                            <th style="text-align: left;">NAMA PEKERJAAN</th>
                            <th style="width: 70px;">JUMLAH</th>
                            <th style="width: 80px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900">
                        @php $totPek = 0; @endphp
                        @forelse($pekerjaanData as $pk)
                            @php $totPek += $pk['total']; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td style="text-align: left;" class="font-semibold text-gray-900 dark:text-white">{{ $pk['nama'] }}</td>
                                <td class="font-medium">{{ number_format($pk['total'], 0, ',', '.') }}</td>
                                <td class="font-bold bg-teal-50/50 dark:bg-teal-950/20 text-teal-800 dark:text-teal-300">{{ $pk['persen'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 italic p-4">Belum ada data pekerjaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 dark:bg-gray-800 font-extrabold text-gray-900 dark:text-white">
                            <td style="text-align: left;" class="uppercase">Jumlah</td>
                            <td>{{ number_format($totPek, 0, ',', '.') }}</td>
                            <td class="bg-teal-600 text-white font-extrabold">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- TABEL 7: STATUS PERKAWINAN --}}
        <div class="report-card bg-white dark:bg-gray-900 !mb-0">
            <h3 class="report-card-title text-gray-900 dark:text-white">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                7. Status Perkawinan
            </h3>

            <div class="overflow-x-auto">
                <table class="report-table">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold">
                            <th style="text-align: left;">STATUS PERKAWINAN</th>
                            <th style="width: 70px;">JUMLAH</th>
                            <th style="width: 80px;" class="bg-gray-200/70 dark:bg-gray-700/70 font-bold">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900">
                        @php $totKawin = 0; @endphp
                        @forelse($statusKawinData as $skw)
                            @php $totKawin += $skw['total']; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td style="text-align: left;" class="font-semibold text-gray-900 dark:text-white">{{ $skw['nama'] }}</td>
                                <td class="font-medium">{{ number_format($skw['total'], 0, ',', '.') }}</td>
                                <td class="font-bold bg-rose-50/50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-300">{{ $skw['persen'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 italic p-4">Belum ada data status kawin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 dark:bg-gray-800 font-extrabold text-gray-900 dark:text-white">
                            <td style="text-align: left;" class="uppercase">Jumlah</td>
                            <td>{{ number_format($totKawin, 0, ',', '.') }}</td>
                            <td class="bg-rose-600 text-white font-extrabold">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</x-filament-panels::page>
