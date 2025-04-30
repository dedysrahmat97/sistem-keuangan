<div wire:ignore>
    <h3 class="text-xl font-bold mb-2">📊 Ringkasan Laba Rugi Keuangan</h3>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <!-- Total Aset -->
        <div class="bg-green-100 text-green-800 rounded p-4 shadow">
            <div class="text-sm font-semibold">Total Laba Rugi</div>
            <div class="text-lg font-bold">Rp
                {{ number_format($dataRingkasanLabaRugi['labaRugi'], 2, ',', '.') }}
            </div>
        </div>

        <!-- Total Aktiva -->
        <div class="bg-red-100 text-red-800 rounded p-4 shadow">
            <div class="text-sm font-semibold">Total Pendapatan</div>
            <div class="text-lg font-bold">Rp {{ number_format($dataRingkasanLabaRugi['totalPendapatan'], 2, ',', '.') }}
            </div>
        </div>

        <!-- Total Kewajiban / Ekuitas -->
        <div class="bg-blue-100 text-blue-800 rounded p-4 shadow">
            <div class="text-sm font-semibold">Total Beban</div>
            <div class="text-lg font-bold">Rp
                {{ number_format($dataRingkasanLabaRugi['totalBeban'], 2, ',', '.') }}</div>
        </div>
    </div>


</div>
