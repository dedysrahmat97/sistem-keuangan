<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NeracaChart extends ChartWidget
{
    protected static ?string $heading = 'Laporan Neraca - Pie Chart';

    public ?string $filter = 'bulan_ini'; // Set default filter

    protected function getData(): array
    {
        $dateRange = $this->getDateRange($this->filter);

        $records = DB::table('akun')
            ->join('tipe_akun', 'akun.tipe_akun_id', '=', 'tipe_akun.id')
            ->where('akun.pos_laporan', 'neraca')
            ->select('akun.*', 'tipe_akun.nama_tipe')
            ->get()
            ->map(function ($akun) use ($dateRange) {
                $query = DB::table('jurnal_umum_detail')
                    ->join('jurnal_umum', 'jurnal_umum.id', '=', 'jurnal_umum_detail.jurnal_umum_id')
                    ->where('akun_id', $akun->id);
                
                if ($dateRange && $this->filter !== 'semua') {
                    $query->whereBetween('jurnal_umum.tanggal', [
                        $dateRange['start']->format('Y-m-d'), 
                        $dateRange['end']->format('Y-m-d')
                    ]);
                }
                
                $jurnal = $query->selectRaw("
                        SUM(CASE WHEN tipe = 'debet' THEN nominal ELSE 0 END) as total_debet,
                        SUM(CASE WHEN tipe = 'kredit' THEN nominal ELSE 0 END) as total_kredit
                    ")
                    ->first();

                $saldo = ($akun->pos_saldo === 'debet')
                    ? ($akun->saldo_awal + ($jurnal->total_debet ?? 0) - ($jurnal->total_kredit ?? 0))
                    : ($akun->saldo_awal + ($jurnal->total_kredit ?? 0) - ($jurnal->total_debet ?? 0));

                // Kategorisasi
                $kategori = in_array($akun->nama_tipe, ['AKTIVA LANCAR', 'AKTIVA TETAP']) ? 'Aktiva'
                            : (in_array($akun->nama_tipe, ['KEWAJIBAN', 'EKUITAS']) ? 'Kewajiban & Ekuitas' : 'Lainnya');

                return [
                    'kategori' => $kategori,
                    'saldo' => abs($saldo),
                    'akun' => $akun,
                    'jurnal' => $jurnal
                ];
            })
            ->groupBy('kategori')
            ->map(function ($group) {
                return $group->sum('saldo');
            });

        $chartLabels = $records->keys()->toArray();
        $chartData = $records->values()->toArray();

        // Hitung total aktiva dan kewajiban+ekuitas
        $totalAktiva = $records->get('Aktiva', 0);
        $totalKewajibanEkuitas = $records->get('Kewajiban & Ekuitas', 0);

        return [
            'labels' => $chartLabels,
            'datasets' => [
                [
                    'label' => 'Distribusi Laporan Neraca',
                    'data' => $chartData,
                    'backgroundColor' => [
                        '#4CAF50', // Aset - Hijau
                        '#FF9800', // Liabilitas - Oranye
                    ],
                ],
            ],
            'summary' => [
                'totalAktiva' => $totalAktiva,
                'totalKewajibanEkuitas' => $totalKewajibanEkuitas,
                'selisih' => $totalAktiva - $totalKewajibanEkuitas,
                'periode' => $this->getFilterDescription($this->filter),
            ]
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'hari_ini' => 'Hari Ini',
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'tahun_ini' => 'Tahun Ini',
            'semua' => 'Semua Data',
        ];
    }

    protected function getDateRange(?string $filter): ?array
    {
        $now = Carbon::now();
        
        return match ($filter) {
            'hari_ini' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'minggu_ini' => [
                'start' => $now->copy()->startOfWeek(Carbon::SUNDAY),
                'end' => $now->copy()->endOfWeek(Carbon::SATURDAY),
            ],
            'bulan_ini' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
            ],
            'tahun_ini' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear(),
            ],
            default => null,
        };
    }

    protected function getFilterDescription(string $filter): string
    {
        return match ($filter) {
            'hari_ini' => 'Hari Ini ('.Carbon::now()->format('d M Y').')',
            'minggu_ini' => 'Minggu Ini ('.Carbon::now()->startOfWeek()->format('d M').' - '.Carbon::now()->endOfWeek()->format('d M Y').')',
            'bulan_ini' => 'Bulan Ini ('.Carbon::now()->format('F Y').')',
            'tahun_ini' => 'Tahun Ini ('.Carbon::now()->format('Y').')',
            'semua' => 'Semua Data',
            default => '',
        };
    }

    protected function getType(): string
    {
        return 'pie';
    }
}