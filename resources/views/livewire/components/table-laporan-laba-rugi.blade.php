<div>
    <!-- Rincian Akun -->
    <h4 class="text-lg font-semibold mb-2 mt-4">📁 Rincian Akun Laba Rugi Bulan : {{ $bulan }}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
        {{-- Aktiva --}}
        <div>
            <div>
                <h3 class="text-md font-bold">PENDAPATAN</h3>
                <table class="table-auto w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-2 md:px-4 py-2 text-left">Akun</th>
                            <th class="px-2 md:px-4 py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalAktiva = 0; @endphp
                        @foreach ($laba_rugi->where('tipe_akun', 'PENDAPATAN') as $row)
                            <tr>
                                <td class="px-2 md:px-4 py-2">{{ $row['akun'] }}</td>
                                <td class="px-2 md:px-4 py-2 text-right">Rp
                                    {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            </tr>
                            @php $totalAktiva += $row['saldo']; @endphp
                        @endforeach
                        <tr class="font-bold bg-gray-100">
                            <td class="px-2 md:px-4 py-2">Total Pendapatan</td>
                            <td class="px-2 md:px-4 py-2 text-right">Rp {{ number_format($totalAktiva, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <h3 class="text-md font-bold">HPP</h3>
                <table class="table-auto w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-2 md:px-4 py-2 text-left">Akun</th>
                            <th class="px-2 md:px-4 py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPasiva = 0; @endphp
                        @foreach ($laba_rugi->whereIn('tipe_akun', 'HPP') as $row)
                            <tr>
                                <td class="px-2 md:px-4 py-2">{{ $row['akun'] }}</td>
                                <td class="px-2 md:px-4 py-2 text-right">Rp
                                    {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            </tr>
                            @php $totalPasiva += $row['saldo']; @endphp
                        @endforeach
                        <tr class="font-bold bg-gray-100">
                            <td class="px-2 md:px-4 py-2">Total HPP</td>
                            <td class="px-2 md:px-4 py-2 text-right">Rp {{ number_format($totalPasiva, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kewajiban & Ekuitas --}}
        <div>
            <h3 class="text-md font-bold">BIAYA OPERASIONAL</h3>
            <table class="table-auto w-full text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-2 md:px-4 py-2 text-left">Akun</th>
                        <th class="px-2 md:px-4 py-2 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPasiva = 0; @endphp
                    @foreach ($laba_rugi->whereIn('tipe_akun', 'BIAYA OPERASIONAL') as $row)
                        <tr>
                            <td class="px-2 md:px-4 py-2">{{ $row['akun'] }}</td>
                            <td class="px-2 md:px-4 py-2 text-right">Rp {{ number_format($row['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @php $totalPasiva += $row['saldo']; @endphp
                    @endforeach
                    <tr class="font-bold bg-gray-100">
                        <td class="px-2 md:px-4 py-2">Total Biaya Operasional</td>
                        <td class="px-2 md:px-4 py-2 text-right">Rp {{ number_format($totalPasiva, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
