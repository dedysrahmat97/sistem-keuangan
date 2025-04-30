<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class RingkasanLaporanLabaRugi extends Component
{
    public $dataRingkasanLabaRugi; // Property untuk menangkap data

    public function mount($dataRingkasanLabaRugi)
    {
        // Data otomatis akan diassign ke property $dataRingkasanLabaRugi
        $this->dataRingkasanLabaRugi = $dataRingkasanLabaRugi;
    }

    #[On('refresh')]
    public function render()
    {

        return view('livewire.components.ringkasan-laporan-laba-rugi');
    }
}