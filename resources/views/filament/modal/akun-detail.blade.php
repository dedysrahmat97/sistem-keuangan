<div class="p-6">
    <h2 class="text-lg font-bold mb-4">Detail Akun</h2>

    <!-- Informasi dasar tentang akun -->
    <div class="mb-4">
        <p><strong>Kode Akun:</strong> {{ $record->kode_akun }}</p>
        <p><strong>Nama Akun:</strong> {{ $record->nama_akun }}</p>
        <p><strong>Posisi Saldo:</strong> {{ ucfirst($record->pos_saldo) }}</p>
        <p><strong>Saldo Awal:</strong> Rp {{ number_format($record->saldo_awal, 0, ',', '.') }}</p>
    </div>

    <!-- Daftar jurnal umum detail terkait akun -->
    <h3 class="text-md font-semibold mb-2">Jurnal Umum Detail</h3>
    <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2 text-left">Tanggal</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Keterangan</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Tipe</th>
                <th class="border border-gray-300 px-4 py-2 text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDebet = 0;
                $totalKredit = 0;
            @endphp
            @forelse ($record->jurnalUmumDetail as $jurnalDetail)
                @php
                    if ($jurnalDetail->tipe === 'debet') {
                        $totalDebet += $jurnalDetail->nominal;
                    } elseif ($jurnalDetail->tipe === 'kredit') {
                        $totalKredit += $jurnalDetail->nominal;
                    }
                @endphp
                <tr>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $jurnalDetail->jurnalUmum->tanggal }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $jurnalDetail->jurnalUmum->keterangan }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($jurnalDetail->tipe) }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-right">
                        Rp {{ number_format($jurnalDetail->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">
                        Tidak ada data jurnal umum detail untuk akun ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Rangkuman Buat berada di sebelah kanan -->
    <div class="mt-4 w-1/4  float-right bg-gray-50 p-4 border border-gray-200 rounded-lg shadow-sm">
        <p><strong>Total Debet:</strong> Rp {{ number_format($totalDebet, 0, ',', '.') }}</p>
        <p><strong>Total Kredit:</strong> Rp {{ number_format($totalKredit, 0, ',', '.') }}</p>
        @php
            // Logika untuk menghitung saldo akhir berdasarkan pos_saldo
            $saldoAkhir = $record->saldo_awal;
            if ($record->pos_saldo === 'debet') {
                $saldoAkhir += $totalDebet - $totalKredit;
            } elseif ($record->pos_saldo === 'kredit') {
                $saldoAkhir += $totalKredit - $totalDebet;
            }
        @endphp
        <p><strong>Saldo Akhir:</strong> Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
    </div>
</div>
