<div>
    <!-- Rincian Akun -->
    <h4 class="text-lg font-semibold mb-2 mt-4">📁 Rincian Akun Neraca Bulan : {{ $bulan }}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Aktiva --}}
        <div>
            <h3 class="text-md font-bold">Aktiva</h3>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Akun</th>
                            <th class="px-4 py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalAktiva = 0; @endphp
                        @foreach ($neraca->whereIn('tipe_akun', ['AKTIVA LANCAR', 'AKTIVA TETAP']) as $row)
                            {{-- anggap tipe_akun_id = 1 adalah aktiva --}}
                            <tr>
                                <td class="px-4 py-2">{{ $row['akun'] }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            </tr>
                            @php $totalAktiva += $row['saldo']; @endphp
                        @endforeach
                        <tr class="font-bold bg-gray-100">
                            <td class="px-4 py-2">Total Aktiva</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($totalAktiva, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kewajiban & Ekuitas --}}
        <div>
            <h3 class="text-md font-bold">Kewajiban & Ekuitas</h3>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Akun</th>
                            <th class="px-4 py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPasiva = 0; @endphp
                        @foreach ($neraca->whereIn('tipe_akun', ['KEWAJIBAN', 'EKUITAS']) as $row)
                            {{-- tipe_akun_id = 2 kewajiban, 3 ekuitas --}}
                            <tr>
                                <td class="px-4 py-2">{{ $row['akun'] }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            </tr>
                            @php $totalPasiva += $row['saldo']; @endphp
                        @endforeach
                        <tr class="font-bold bg-gray-100">
                            <td class="px-4 py-2">Total Kewajiban + Ekuitas</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($totalPasiva, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
