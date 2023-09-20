<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Subkriteria') }}
        </h2>
        @section('cta')
            <a href="{{ route('subkriteria.show') }}">
                <x-primary-button class="bg-red-500"> <i class="fa-solid fa-rotate-left mr-3"></i> Kembali</x-primary-button>
            </a>
        @endsection
    </x-slot>
    <main x-data="{ islokasi: {{ $subkriteria->isLokasi ? true : false }} }">
        <div class="bg-white rounded-md shadow-xl p-5">
            <div class="border-b mb-3 pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-blue-500 text-white text-center uppercase">
                        Kriteria {{ $kriteria->nama }}</span>
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Edit Subkriteria</span>
                </div>
            </div>
            <form action="{{ route('subkriteria.update', ['idSubkriteria' => request('idSubkriteria')]) }}"
                method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <x-input-label>Nama Subkriteria</x-input-label>
                        <x-text-input placeholder="Masukan nama subkriteria..." class="w-full" name="nama"
                            value="{{ $subkriteria->nama }}" />
                        <x-input-error :messages="$errors->get('nama')" />
                    </div>
                    <div>
                        <x-input-label>Nilai</x-input-label>
                        <x-text-input type="number" placeholder="Masukkan nilai subkriteria..." class="w-full"
                            name="nilai" min="0" max="9999.99" value="{{ $subkriteria->nilai }}" />
                        <x-input-error :messages="$errors->get('nilai')" />
                    </div>
                    <div x-show="islokasi">
                        <x-input-label>Latitude</x-input-label>
                        <x-text-input type="text" placeholder="Masukkan latitude..." class="w-full" name="lat"
                            value="{{ $subkriteria->lat }}" />
                        <x-input-error :messages="$errors->get('lat')" />
                    </div>
                    <div x-show="islokasi">
                        <x-input-label>Longitude</x-input-label>
                        <x-text-input type="text" placeholder="Masukkan longitude..." class="w-full" name="lon"
                            value="{{ $subkriteria->lon }}" />
                        <x-input-error :messages="$errors->get('lon')" />
                    </div>

                    <input type="hidden" name="islokasi" x-bind:value="islokasi">

                    @if (strtolower($kriteria->nama) == 'lokasi')
                        <x-secondary-button type="button" @click="islokasi = !islokasi"
                            x-text="islokasi ? 'Sembunyikan Input Lokasi (lat, long) ?' : 'Input Lokasi (lat, long) ?'"></x-secondary-button>
                    @endif

                </div>
                <x-primary-button class="bg-green-500"><i
                        class="fa-solid fa-floppy-disk mr-3"></i>Simpan</x-primary-button>
            </form>
        </div>
    </main>
</x-app-layout>
