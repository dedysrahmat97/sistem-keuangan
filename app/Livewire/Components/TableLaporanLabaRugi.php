<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class TableLaporanLabaRugi extends Component
{
    public $laba_rugi; // Property untuk menangkap data
    public $bulan; // Property untuk menangkap data

    public function mount($laba_rugi, $bulan)
    {
        // Data otomatis akan diassign ke property $laba_rugi
        // Anda juga bisa memproses data tambahan di sini jika perlu
        $this->laba_rugi = $laba_rugi;
        $this->bulan = $bulan;
    }

    #[On('refresh')]
    public function render()
    {

        return view('livewire.components.table-laporan-laba-rugi');
    }
}