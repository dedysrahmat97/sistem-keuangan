<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    protected $table = 'jurnal_umum';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'bukti_transfer',
    ];

    public function jurnalUmumDetail()
    {
        return $this->hasMany(JurnalUmumDetail::class, 'jurnal_umum_id');
    }
}
