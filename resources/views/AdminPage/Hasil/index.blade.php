<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kriteria') }}
        </h2>
    </x-slot>

    <main>
        <div x-data="selected()" class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Tabel Hasil Akhir</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start border-r">Nama Alternatif</th>
                        <th class="py-2 px-4 text-start">Nilai Akhir</th>
                        <th class="py-2 px-4 text-start">Ranking</th>
                        <th class="py-2 px-4 text-start w-20 border-l">Map</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasilAkhir as $item)
                        <tr class="border-b">
                            <td class="py-2 px-4 border-r">{{ $item['nama'] }}</td>
                            <td class="py-2 px-4">{{ $item['nilai'] }}</td>
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border-l cursor-pointer"
                                x-on:click="setSelected({ nama: '{{ $item['nama'] }}', nilai: '{{ $item['nilai'] }}', ranking: '{{ $loop->iteration }}', lat: {{ $item['lat'] }}, lon: {{ $item['lon'] }}, lokasi: '{{ $item['lokasi'] }}' })">
                                <span class="bg-green-500 p-2 text-white rounded-md"><i
                                        class="fa-solid fa-map-location-dot"></i></span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5">
            <div class="border-b pb-3 mb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Maps</span>
                </div>
            </div>
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            function selected() {
                selectedData = null;

                return {
                    setSelected(data) {
                        selectedData = data; // Set selectedData dengan data yang diterima

                        // Validasi apakah data ada atau tidak
                        if (selectedData && selectedData.lat && selectedData.lon) {
                            // Hapus semua marker yang ada di peta
                            mymap.eachLayer(function(layer) {
                                if (layer instanceof L.Marker) {
                                    mymap.removeLayer(layer);
                                }
                            });

                            // Tambahkan marker baru berdasarkan data yang dipilih
                            var lat = selectedData.lat;
                            var lon = selectedData.lon;
                            var nama = selectedData.nama;
                            var lokasi = selectedData.lokasi;

                            var marker = L.marker([lat, lon]).addTo(mymap);
                            marker.bindPopup('Nama: ' + nama + '<br>Lokasi: ' + lokasi).openPopup();

                            // Set peta (mymap) ke koordinat baru berdasarkan selectedData
                            mymap.setView([lat, lon], 13);
                        } else {
                            // Handle jika data tidak lengkap
                            console.error('Data tidak lengkap atau tidak ada.');
                        }
                    }
                }
            }

            var mymap = L.map('map').setView([-8.409518, 115.188919], 13);

            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);

            L.marker([-8.409518, 115.188919]).addTo(mymap)
                .bindPopup('Halo')
                .openPopup();
        </script>

    </main>
</x-app-layout>
