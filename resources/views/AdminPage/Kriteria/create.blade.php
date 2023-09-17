<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kriteria') }}
        </h2>
        @section('cta')
            <a href="{{ route('kriteria.show') }}">
                <x-primary-button class="bg-red-500"> <i class="fa-solid fa-rotate-left mr-3"></i> Kembali</x-primary-button>
            </a>
        @endsection
    </x-slot>

    <main>
        <div class="bg-white rounded-md shadow-xl p-5">
            <div class="border-b pb-3 mb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Tambah Kriteria</span>
                </div>
            </div>
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <x-input-label>Nama Kriteria</x-input-label>
                        <x-text-input placeholder="Masukan nama kriteria..." class="w-full" name="nama" />
                        <x-input-error :messages="$errors->get('nama')" />
                    </div>
                    {{-- <div>
                        <x-input-label>Bobot Kriteria</x-input-label>
                        <x-text-input type="number" placeholder="Masukkan bobot kriteria..." class="w-full"
                            name="bobot" step="0.1" min="0" max="9999.99" />
                        <x-input-error :messages="$errors->get('bobot')" />
                    </div> --}}
                    <div>
                        <x-input-label>Jenis Kriteria</x-input-label>
                        <div x-data="{ selected: 'Benefit' }" class="flex">
                            <input type="hidden" name="jenis" x-bind:value="selected">
                            <button type="button" class="w-full">
                                <h6 type="button" class="px-3 py-2 rounded-l-md border" x-on:click="selected = 'Cost'"
                                    x-bind:class="{ 'bg-blue-500 text-white': selected === 'Cost' }">
                                    <i class="fa-solid fa-check mr-3"
                                        x-bind:class="{ 'visible': selected === 'Cost', 'hidden': selected !== 'Cost' }"></i>
                                    Cost
                                </h6>
                            </button>
                            <button type="button" class="w-full">
                                <h6 type="button" class="px-3 py-2 rounded-r-md border"
                                    x-on:click="selected = 'Benefit'"
                                    x-bind:class="{ 'bg-blue-500 text-white': selected === 'Benefit' }"><i
                                        class="fa-solid fa-check mr-3"
                                        x-bind:class="{ 'visible': selected === 'Benefit', 'hidden': selected !== 'Benefit' }"></i>
                                    Benefit
                                </h6>
                            </button>
                            <x-input-error :messages="$errors->get('jenis')" />
                        </div>
                    </div>
                </div>
                <x-primary-button class="bg-green-500"><i
                        class="fa-solid fa-floppy-disk mr-3"></i>Simpan</x-primary-button>
            </form>
        </div>
    </main>
</x-app-layout>
