<x-filament::page>
    <div class="space-y-4">
        <!-- Buat dua buah card seperti tab yang akan mengarah ke halaman laporan neraca dan laba rugi -->
        <div class="flex  gap-4">
            <a href="{{ route('filament.dashboard.pages.laporan-neraca') }}"
                class="bg-white shadow-md rounded-lg p-4 text-center hover:bg-gray-100">
                <h2 class="text-xl font-semibold">Laporan Neraca</h2>
            </a>
            <a href="{{ route('filament.dashboard.pages.laporan-laba-rugi') }}"
                class="bg-white shadow-md rounded-lg p-4 text-center hover:bg-gray-100">
                <h2 class="text-xl font-semibold">Laporan Laba Rugi</h2>
            </a>
        </div>

        <!-- Tampilkan tabel buku besar -->
        <div>
            {{ $this->table }}
        </div>
    </div>
</x-filament::page>
