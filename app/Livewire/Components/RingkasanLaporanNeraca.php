<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class RingkasanLaporanNeraca extends Component
{
    public $dataRingkasanNeraca; // Property untuk menangkap data

    public function mount($dataRingkasanNeraca)
    {
        // Data otomatis akan diassign ke property $dataRingkasanNeraca
        $this->dataRingkasanNeraca = $dataRingkasanNeraca;
    }

    #[On('refresh')]
    public function render()
    {

        return view('livewire.components.ringkasan-laporan-neraca');
    }
}