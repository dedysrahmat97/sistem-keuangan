<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class ChartLaporanNeraca extends Component
{
    public $dataChart; // Property untuk menangkap data

    public function mount($dataChart)
    {
        // Data otomatis akan diassign ke property $dataChart
        $this->dataChart = $dataChart;
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.components.chart-laporan-neraca');
    }
}