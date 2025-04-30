<x-filament::page>
    <div class="space-y-4">
        <div class="flex gap-4">
            <a href="{{ route('filament.dashboard.pages.buku-besar') }}"
                class="bg-white shadow-md rounded-lg p-4 text-center hover:bg-gray-100">
                <h2 class="text-xl font-semibold">Buku Besar</h2>
            </a>
        </div>
        {{-- <h2 class="text-xl font-bold text-gray-800">Laporan Laba Rugi</h2> --}}
        <div>
            {{ $this->table }}
        </div>
    </div>
</x-filament::page>
