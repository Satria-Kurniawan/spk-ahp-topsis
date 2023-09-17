<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Subkriteria') }}
        </h2>
        @section('cta')
            {{-- <a href="{{ route('subkriteria.create', ['idKriteria' => $kriteria->id]) }}">
                <x-primary-button class="bg-green-500">
                    <i class="fa-solid fa-plus mr-3"></i> Tambah
                </x-primary-button>
            </a> --}}
        @endsection
    </x-slot>

    <main>
        @foreach ($listKriteria as $kriteria)
            <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5">
                <div class="flex justify-between border-b pb-3">
                    <a href="{{ route('subkriteria.create', ['idKriteria' => $kriteria->id]) }}">
                        <x-primary-button class="bg-green-500">
                            <i class="fa-solid fa-plus mr-3"></i> Tambah
                        </x-primary-button>
                    </a>
                    <div>
                        <span class="py-1 px-3 rounded-md bg-blue-500 text-white text-center uppercase">
                            Kriteria {{ $kriteria->nama }}</span>
                        <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                            Tabel Subkriteria</span>
                    </div>
                </div>
                <table class="w-full mt-3">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="py-2 px-4 text-start w-20 border-r">No</th>
                            <th class="py-2 px-4 text-start border-r">Nama Subsubkriteria</th>
                            <th class="py-2 px-4 text-start border-r">Nilai</th>
                            <th class="py-2 px-4 text-start w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listSubkriteria as $subkriteria)
                            @if ($subkriteria->kriteria_id == $kriteria->id)
                                <tr class="border-b">
                                    <td class="py-2 px-4 border-r">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4 border-r">{{ $subkriteria->nama }}</td>
                                    <td class="py-2 px-4 border-r">{{ $subkriteria->nilai }}</td>
                                    <td class="py-2 px-4">
                                        <a
                                            href="{{ route('subkriteria.edit', [
                                                'idKriteria' => $kriteria->id,
                                                'idSubkriteria' => $subkriteria->id,
                                            ]) }}">
                                            <x-secondary-button class="mr-1 text-blue-500">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </x-secondary-button>
                                        </a>
                                        <x-secondary-button class="text-red-500" x-data="{ subkriteria: {{ $subkriteria }} }"
                                            x-on:click.prevent="$dispatch('open-modal', {name: 'confirmDeleteModal', subkriteria})">
                                            <i class="fa-solid fa-trash"></i>
                                        </x-secondary-button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        <x-modal name="confirmDeleteModal" :maxWidth="'sm'">
            <div class="p-5" x-data="{ subkriteria: null }"
                x-on:open-modal.window="subkriteria = $event.detail.subkriteria ?? null">
                <h1 class="text-xl">Hapus subkriteria <span x-text="subkriteria?.nama" class="text-red-500"></span>?
                </h1>
                <div class="flex gap-x-3 mt-5">

                    <x-secondary-button class="w-full" x-data x-on:click="$dispatch('close')">
                        <i class="fa-solid fa-ban mr-3"></i>
                        Batal
                    </x-secondary-button>
                    <form method="POST" x-bind:action="`/admin/subkriteria/delete/${subkriteria?.id}`" class="w-full">
                        @csrf
                        <x-primary-button class="w-full bg-red-500">
                            <i class="fa-solid fa-trash mr-3"></i>
                            Ya, hapus
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </x-modal>
    </main>
</x-app-layout>
