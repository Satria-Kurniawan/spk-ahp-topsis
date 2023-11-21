<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Alternatif') }}
        </h2>
        @section('cta')
            <a href="{{ route('alternatif.show') }}">
                <x-primary-button class="bg-red-500"> <i class="fa-solid fa-rotate-left mr-3"></i> Kembali</x-primary-button>
            </a>
        @endsection
    </x-slot>

    <main>
        <div class="bg-white rounded-md shadow-xl p-5">
            <div class="border-b pb-3 mb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Tambah Alternatif</span>
                </div>
            </div>
            <form action="{{ route('alternatif.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-3 gap-5 mb-5">
                    <div>
                        <x-input-label>Nama Alternatif</x-input-label>
                        <x-text-input placeholder="Masukan nama alternatif..." class="w-full" name="nama" />
                        <x-input-error :messages="$errors->get('nama')" />
                    </div>
                    @foreach ($listKriteria as $kriteria)
                        <div>
                            <x-input-label>{{ $kriteria->nama }}</x-input-label>
                            <select
                                class="w-full border border-gray-300 focus:border-gray-800 focus:ring-gray-800 rounded-md shadow-sm cursor-pointer"
                                name="{{ $kriteria->nama }}">
                                @foreach ($listSubkriteria->where('kriteria_id', $kriteria->id) as $subkriteria)
                                    <option value="{{ $subkriteria->nilai }}">
                                        {{ $subkriteria->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                    <div>
                        <x-input-label>Latitude</x-input-label>
                        <x-text-input id="latInput" placeholder="Input dari map"
                            class="w-full bg-gray-100 focus:outline-none focus:border-0 focus:ring-0" name="lat"
                            readonly />
                        <x-input-error :messages="$errors->get('lat')" />
                    </div>
                    <div>
                        <x-input-label>Longitude</x-input-label>
                        <x-text-input id="lonInput" placeholder="Input dari map"
                            class="w-full bg-gray-100 focus:outline-none focus:border-0 focus:ring-0" name="lon"
                            readonly />
                        <x-input-error :messages="$errors->get('lon')" />
                    </div>

                    <div class="col-span-3" id="map" style="width: 100%; height: 500px;"></div>
                </div>
                <x-primary-button class="bg-green-500"><i
                        class="fa-solid fa-floppy-disk mr-3"></i>Simpan</x-primary-button>
            </form>
        </div>
    </main>

    <script>
        const myMap = L.map('map').setView([-8.409518, 115.188919], 13);
        let marker

        myMap.on('click', function(event) {
            const lat = event.latlng.lat;
            const lon = event.latlng.lng;
            let latInputElement = document.getElementById('latInput')
            let lonInputElement = document.getElementById('lonInput')

            latInputElement.value = lat;
            lonInputElement.value = lon


            if (marker) myMap.removeLayer(marker);

            const url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lon;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const locationName = data.display_name;

                    marker = L.marker([lat, lon]).addTo(myMap)
                        .bindPopup(locationName)
                        .openPopup();
                })
                .catch(error => console.error('Error:', error));
        });


        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(myMap);

        const searchControl = L.Control.geocoder().addTo(myMap);

        searchControl.on('markgeocode', function(event) {
            const latlng = event.geocode.center;
            myMap.setView(latlng, myMap.getZoom());
        });
    </script>
</x-app-layout>
