<?php

namespace App\Livewire\Pages;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class LabaRugiIndex extends Component
{
    public $startDate;
    public $endDate;
    public $bulan;
    public $akunLabaRugi;
    public $laba_rugi;
    public $dataRingkasanLabaRugi = [];
    public $dataChart = [];

    public function mount()
    {
        Carbon::setLocale('id'); // Set locale globally to Indonesian
        // Set default date range
        $this->startDate = date('Y-m-01'); // default awal bulan
        $this->endDate = date('Y-m-d'); // default hari ini
        

        $this->akunLabaRugi = $this->getAkunNeraca();
        $this->data();
    }

    protected function getAkunNeraca()
    {
        return DB::table('akun')
            ->join('tipe_akun', 'akun.tipe_akun_id', '=', 'tipe_akun.id')
            ->where('akun.pos_laporan', 'laba_rugi')
            ->select('akun.*', 'tipe_akun.nama_tipe')
            ->get();
    }

    #[On('refresh')]
    public function data()
    {
        $this->bulan = Carbon::parse($this->startDate)->translatedFormat('F Y');
        
        $this->laba_rugi = $this->akunLabaRugi->map(function ($akun) {
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
        // Untuk laporan laba rugi, kita perlu memisahkan pendapatan dan beban
        $totalPendapatan = $this->laba_rugi
            ->where('pos_saldo', 'kredit') // Pendapatan biasanya kredit
            ->sum('saldo');
        
        $totalBeban = $this->laba_rugi
            ->where('pos_saldo', 'debet') // Beban biasanya debit
            ->sum('saldo');
        
        $labaRugi = $totalPendapatan - $totalBeban;

        $this->dataRingkasanLabaRugi = [
            'totalPendapatan' => $totalPendapatan,
            'totalBeban' => $totalBeban,
            'labaRugi' => $labaRugi,
            'periode' => [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ],
        ];
    }

    protected function generateChart()
    {
        // Kelompokkan berdasarkan tipe akun (pendapatan/beban) bukan debit/kredit
        $dataMap = $this->akunLabaRugi->map(function ($akun) {
            $jurnal = $this->getJurnalAkun($akun->id);

            $saldo = ($akun->pos_saldo === 'debet')
                ? ($akun->saldo_awal + ($jurnal->total_debet ?? 0) - ($jurnal->total_kredit ?? 0))
                : ($akun->saldo_awal + ($jurnal->total_kredit ?? 0) - ($jurnal->total_debet ?? 0));

            // Gunakan kategori yang lebih bermakna untuk laba rugi
            $kategori = $akun->nama_tipe; // Misalnya: 'Pendapatan', 'Beban Operasional', dll.

            return [
                'kategori' => $kategori,
                'saldo' => abs($saldo), // Gunakan nilai absolut untuk chart
                'tipe' => $akun->pos_saldo
            ];
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
        return view('livewire.pages.laba-rugi-index')->layout('layouts.app');
    }
}