<div>
    <div class="my-3">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-semibold">Filter Periode Laporan</h2>

            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <div class="flex gap-2">
                    <button wire:click="filterByMonth(-1)"
                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 text-sm">
                        Bulan Lalu
                    </button>
                    <button wire:click="filterByMonth(0)"
                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 text-sm">
                        Bulan Ini
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="date" id="startDate" wire:model.live="startDate" wire:change="filterByRange"
                        class="border rounded px-3 py-1 text-sm w-full">
                    <span class="self-center hidden sm:block">s/d</span>
                    <input type="date" id="endDate" wire:model.live="endDate" wire:change="filterByRange"
                        class="border rounded px-3 py-1 text-sm w-full">
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <livewire:components.chart-laporan-laba-rugi :dataChart="$dataChart" :key="'dataChart-' . now()->timestamp" />
        </div>
        <div class="col-span-2">
            <livewire:components.ringkasan-laporan-laba-rugi :dataRingkasanLabaRugi="$dataRingkasanLabaRugi" :key="'dataRingkasanLabaRugi-' . now()->timestamp" />
            <livewire:components.table-laporan-laba-rugi :laba_rugi="$laba_rugi" :bulan="$bulan" :key="'laba_rugi-' . now()->timestamp" />
        </div>
    </div>
</div>
