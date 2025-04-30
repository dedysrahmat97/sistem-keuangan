<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeAkun extends Model
{
    protected $table = 'tipe_akun';

    protected $fillable = [
        'kode_tipe',
        'nama_tipe',
    ];

    public function akun()
    {
        return $this->hasMany(Akun::class);
    }
}
