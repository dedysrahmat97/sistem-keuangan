<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    protected $table = 'jurnal_umum';
    protected $with = ['jurnalUmumDetail.akun']; // Default eager loading

    protected $fillable = [
        'tanggal',
        'keterangan',
        'bukti_transfer',
    ];

    public function jurnalUmumDetail()
    {
        return $this->hasMany(JurnalUmumDetail::class, 'jurnal_umum_id');
    }

    // Scope untuk filter performa
    public function scopeWithAkunDetails($query)
    {
        return $query->with(['jurnalUmumDetail' => function ($query) {
            $query->with(['akun' => function ($query) {
                $query->select('id', 'nama_akun'); // Hanya ambil kolom yang diperlukan
            }]);
        }]);
    }

    public function scopeFilterByDateRange($query, $start, $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    public function scopeFilterByAkun($query, $akunId)
    {
        return $query->whereHas('jurnalUmumDetail', function ($query) use ($akunId) {
            $query->where('akun_id', $akunId);
        });
    }
}