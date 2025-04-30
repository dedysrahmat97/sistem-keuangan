<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';

    protected $fillable = [
        'tipe_akun_id',
        'kode_akun',
        'nama_akun',
        'pos_saldo',
        'pos_laporan',
        'saldo_awal',
        'saldo_akhir',
    ];

    public function tipeAkun()
    {
        return $this->belongsTo(TipeAkun::class);
    }

    public function jurnalUmumDetail()
    {
        return $this->hasMany(JurnalUmumDetail::class);
    }
}
