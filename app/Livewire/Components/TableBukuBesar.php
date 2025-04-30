<?php

namespace App\Livewire\Components;

use App\Models\Akun;
use Livewire\Component;
use App\Models\JurnalUmumDetail;

class TableBukuBesar extends Component
{
    public $akunList;

    public function mount($akunList)
    {
        $this->akunList = $akunList;
    }

    public function render()
    {
        return view('livewire.components.table-buku-besar');
    }
}