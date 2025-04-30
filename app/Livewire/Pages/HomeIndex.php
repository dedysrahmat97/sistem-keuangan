<?php

namespace App\Livewire\Pages;

use App\Models\Akun;
use Livewire\Component;
use App\Models\JurnalUmumDetail;

class HomeIndex extends Component
{
    public $akunList;
    public $tanggal_awal;
    public $tanggal_akhir;
    public $nama_akun;
    public $tipe_akun_id;
    public $listTipeAkun; // untuk dropdown pilihannya

    public function mount()
    {
        $this->tanggal_awal = date('Y-m-01'); // default awal bulan
        $this->tanggal_akhir = date('Y-m-d'); // default hari ini
        $this->data();
    }

    public function updated($propertyName)
    {
        // Setiap filter berubah, refresh data
        if (in_array($propertyName, ['tanggal_awal', 'tanggal_akhir', 'nama_akun'])) {
            $this->data();
        }
    }

    public function data()
    {
        $query = Akun::with(['jurnalUmumDetail.jurnalUmum'])
            ->orderBy('kode_akun');

        if (!empty($this->nama_akun)) {
            $query->where('nama_akun', 'like', '%' . $this->nama_akun . '%');
        }

        $this->akunList = $query->get();

        foreach ($this->akunList as $akun) {
            $transaksi = JurnalUmumDetail::where('akun_id', $akun->id)
                ->join('jurnal_umum', 'jurnal_umum.id', '=', 'jurnal_umum_detail.jurnal_umum_id')
                ->when($this->tanggal_awal, function ($q) {
                    $q->where('jurnal_umum.tanggal', '>=', $this->tanggal_awal);
                })
                ->when($this->tanggal_akhir, function ($q) {
                    $q->where('jurnal_umum.tanggal', '<=', $this->tanggal_akhir);
                })
                ->orderBy('jurnal_umum.tanggal')
                ->select('jurnal_umum.tanggal', 'jurnal_umum.keterangan', 'jurnal_umum_detail.tipe', 'jurnal_umum_detail.nominal')
                ->get();

            $saldo = $akun->saldo_awal;
            foreach ($transaksi as $trx) {
                if ($trx->tipe === 'debet') {
                    $saldo += $trx->nominal;
                } else {
                    $saldo -= $trx->nominal;
                }
                $trx->saldo = $saldo;
            }

            $akun->transaksi = $transaksi;
        }
    }

    public function render()
    {
        return view('livewire.pages.home-index')->layout('layouts.app');
    }
}