<div>
    <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div>
            <label for="tanggal_awal" class="block text-sm font-medium">Tanggal Awal</label>
            <input type="date" id="tanggal_awal" wire:model.live="tanggal_awal"
                class="border p-2 rounded w-full bg-white shadow-md">
        </div>
        <div>
            <label for="tanggal_akhir" class="block text-sm font-medium">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" wire:model.live="tanggal_akhir"
                class="border p-2 rounded w-full bg-white shadow-md">
        </div>
        <div>
            <label for="nama_akun" class="block text-sm font-medium">Nama Akun</label>
            <input type="text" id="nama_akun" wire:model.live="nama_akun" placeholder="Cari nama akun..."
                class="border p-2 rounded w-full bg-white shadow-md">
        </div>
    </div>

    <div>
        <livewire:components.table-buku-besar :akunList="$akunList" :key="'akunList-' . now()->timestamp" />
    </div>
</div>
