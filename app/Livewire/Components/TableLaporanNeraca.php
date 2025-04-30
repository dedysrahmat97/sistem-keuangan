<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class TableLaporanNeraca extends Component
{
    public $neraca; // Property untuk menangkap data
    public $bulan; // Property untuk menangkap data

    public function mount($neraca, $bulan)
    {
        // Data otomatis akan diassign ke property $neraca
        // Anda juga bisa memproses data tambahan di sini jika perlu
        $this->neraca = $neraca;
        $this->bulan = $bulan;
    }

    #[On('refresh')]
    public function render()
    {

        return view('livewire.components.table-laporan-neraca');
    }
}