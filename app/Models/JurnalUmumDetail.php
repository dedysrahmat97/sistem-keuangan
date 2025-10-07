<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalUmumDetail extends Model
{
    protected $table = 'jurnal_umum_detail';
    protected $with = ['akun']; // Default eager loading

    protected $fillable = [
        'jurnal_umum_id',
        'akun_id',
        'tipe',
        'nominal',
    ];

    public function jurnalUmum()
    {
        return $this->belongsTo(JurnalUmum::class, 'jurnal_umum_id');
    }

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }
}