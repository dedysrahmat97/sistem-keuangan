<div wire:ignore class="bg-white shadow-md p-5 rounded-md">
    <h2 class="text-center text-lg font-semibold mb-4">Laporan Neraca</h2>
    <p class="text-center text-sm text-gray-500 mb-4">Data ini menunjukkan distribusi laporan neraca
        berdasarkan tipe akun.</p>
    <canvas id="myChart"class="w-full">
    </canvas>
</div>
@script
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');

        const myChart = new Chart(ctx, {
            type: 'pie', // Menggunakan pie chart
            data: {
                labels: @json($dataChart['chartLabels']), // Mengambil label dari Livewire
                datasets: [{
                    label: 'Saldo ',
                    data: @json($dataChart['chartData']), // Mengambil data dari Livewire
                    backgroundColor: [
                        '#4CAF50', // Hijau
                        '#FF9800', // Oranye
                        '#2196F3', // Biru
                        '#9C27B0', // Ungu
                        '#F44336', // Merah
                        '#00BCD4', // Biru Muda
                        '#FFC107', // Kuning
                    ],
                    borderColor: '#FFFFFF',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                }
            }
        });
    </script>
@endscript
