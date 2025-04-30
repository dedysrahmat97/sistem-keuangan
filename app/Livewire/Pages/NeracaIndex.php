<?php

namespace App\Livewire\Pages;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class NeracaIndex extends Component
{
    public $startDate;
    public $endDate;
    public $bulan;
    public $akunNeraca;
    public $neraca;
    public $dataRingkasanNeraca = [];
    public $dataChart = [];

    public function mount()
    {
        Carbon::setLocale('id'); // Set locale globally to Indonesian
        // Set default date range
        $this->startDate = date('Y-m-01'); // default awal bulan
        $this->endDate = date('Y-m-d'); // default hari ini
        

        $this->akunNeraca = $this->getAkunNeraca();
        $this->data();
    }

    protected function getAkunNeraca()
    {
        return DB::table('akun')
            ->join('tipe_akun', 'akun.tipe_akun_id', '=', 'tipe_akun.id')
            ->where('akun.pos_laporan', 'neraca')
            ->select('akun.*', 'tipe_akun.nama_tipe')
            ->get();
    }

    #[On('refresh')]
    public function data()
    {
        $this->bulan = Carbon::parse($this->startDate)->translatedFormat('F Y');
        
        $this->neraca = $this->akunNeraca->map(function ($akun) {
            $jurnal = $this->getJurnalAkun($akun->id);

            $saldoBerjalan = ($akun->pos_saldo === 'debet')
                ? ($akun->saldo_awal + ($jurnal->total_debet ?? 0) - ($jurnal->total_kredit ?? 0))
                : ($akun->saldo_awal + ($jurnal->total_kredit ?? 0) - ($jurnal->total_debet ?? 0));

            return [
                'akun' => $akun->nama_akun,
                'tipe_akun' => $akun->nama_tipe,
                'pos_saldo' => $akun->pos_saldo,
                'saldo' => $saldoBerjalan,
            ];
        });

        $this->hitungRingkasan();
        $this->generateChart();
    }

    protected function getJurnalAkun($akunId)
    {
        return DB::table('jurnal_umum_detail')
            ->join('jurnal_umum', 'jurnal_umum.id', '=', 'jurnal_umum_detail.jurnal_umum_id')
            ->where('akun_id', $akunId)
            ->whereBetween('jurnal_umum.tanggal', [
                Carbon::parse($this->startDate)->startOfDay()->format('Y-m-d H:i:s'),
                Carbon::parse($this->endDate)->endOfDay()->format('Y-m-d H:i:s')
            ])
            ->selectRaw("
                SUM(CASE WHEN tipe = 'debet' THEN nominal ELSE 0 END) as total_debet,
                SUM(CASE WHEN tipe = 'kredit' THEN nominal ELSE 0 END) as total_kredit
            ")
            ->first();
    }

    protected function hitungRingkasan()
    {
        $totalAktiva = $this->neraca->whereIn('tipe_akun', ['AKTIVA LANCAR', 'AKTIVA TETAP'])->sum('saldo');
        $totalKewajibanEkuitas = $this->neraca->whereIn('tipe_akun', ['KEWAJIBAN', 'EKUITAS'])->sum('saldo');
        $totalSaldoNeraca = $totalAktiva + $totalKewajibanEkuitas;

        $this->dataRingkasanNeraca = [
            'totalAktiva' => $totalAktiva,
            'totalKewajibanEkuitas' => $totalKewajibanEkuitas,
            'totalSaldoNeraca' => $totalSaldoNeraca,
            'periode' => [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ],
        ];
    }

    protected function generateChart()
    {
        $dataMap = $this->akunNeraca->map(function ($akun) {
            $jurnal = $this->getJurnalAkun($akun->id);

            $saldo = ($akun->pos_saldo === 'debet')
                ? ($akun->saldo_awal + ($jurnal->total_debet ?? 0) - ($jurnal->total_kredit ?? 0))
                : ($akun->saldo_awal + ($jurnal->total_kredit ?? 0) - ($jurnal->total_debet ?? 0));

            $kategori = in_array($akun->nama_tipe, ['AKTIVA LANCAR', 'AKTIVA TETAP']) ? 'Aktiva'
                        : (in_array($akun->nama_tipe, ['KEWAJIBAN', 'EKUITAS']) ? 'Kewajiban & Ekuitas' : 'Lainnya');

            return compact('kategori', 'saldo');
        })
        ->groupBy('kategori')
        ->map(fn ($group) => $group->sum('saldo'));

        $this->dataChart = [
            'chartLabels' => $dataMap->keys()->toArray(),
            'chartData' => $dataMap->values()->toArray(),
        ];
    }

    public function filterByMonth($monthOffset = 0)
    {
        $this->startDate = Carbon::now()->addMonths($monthOffset)->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
        $this->endDate = Carbon::now()->addMonths($monthOffset)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
        $this->data(); // Panggil langsung untuk update data
    }

    public function filterByRange()
    {
         $this->data(); // Panggil langsung untuk update data

    }

    public function render()
    {
        return view('livewire.pages.neraca-index')->layout('layouts.app');
    }
}