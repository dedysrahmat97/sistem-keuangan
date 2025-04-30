<div>
    @foreach ($akunList as $akun)
        <div class="p-4 mb-6 bg-white rounded shadow">
            <h2 class="text-lg font-bold mb-2">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border">
                    <thead>
                        <tr>
                            <th class="border px-2 py-1">Tanggal</th>
                            <th class="border px-2 py-1">Keterangan</th>
                            <th class="border px-2 py-1">Debet</th>
                            <th class="border px-2 py-1">Kredit</th>
                            <th class="border px-2 py-1">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1" colspan="4"><strong>Saldo Awal</strong></td>
                            <td class="border px-2 py-1 font-bold">Rp. {{ number_format($akun->saldo_awal, 2) }}</td>
                        </tr>
                        @foreach ($akun->transaksi as $trx)
                            <tr>
                                <td class="border px-2 py-1">{{ $trx->tanggal }}</td>
                                <td class="border px-2 py-1">{{ $trx->keterangan }}</td>
                                <td class="border px-2 py-1">Rp.
                                    {{ $trx->tipe === 'debet' ? number_format($trx->nominal, 2) : '' }}
                                </td>
                                <td class="border px-2 py-1">Rp.
                                    {{ $trx->tipe === 'kredit' ? number_format($trx->nominal, 2) : '' }}
                                </td>
                                <td class="border px-2 py-1">Rp. {{ number_format($trx->saldo, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
