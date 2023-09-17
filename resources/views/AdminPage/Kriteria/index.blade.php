<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kriteria') }}
        </h2>
        @section('cta')
            <div>
                <a href="{{ route('bobot-preferensi-ahp.show') }}">
                    <x-primary-button class="mr-3"> <i class="fa-solid fa-weight-scale mr-3"></i>
                        Bobot Preferensi AHP
                    </x-primary-button>
                </a>
                <a href="{{ route('kriteria.create') }}">
                    <x-primary-button class="bg-green-500"> <i class="fa-solid fa-plus mr-3"></i> Tambah</x-primary-button>
                </a>
            </div>
        @endsection
    </x-slot>

    <main>
        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto">
            <div class="border-b pb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Tabel Kriteria</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start w-20">No</th>
                        <th class="py-2 px-4 text-start">Kode</th>
                        <th class="py-2 px-4 text-start">Kriteria</th>
                        <th class="py-2 px-4 text-start">Bobot</th>
                        <th class="py-2 px-4 text-start">Jenis</th>
                        <th class="py-2 px-4 text-start w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listKriteria as $kriteria)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">C{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $kriteria->nama }}</td>
                            <td class="py-2 px-4">{{ $kriteria->bobot }}</td>
                            <td class="py-2 px-4">{{ $kriteria->jenis }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ route('kriteria.edit', ['id' => $kriteria->id]) }}">
                                    <x-secondary-button class="mr-1 text-blue-500">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </x-secondary-button>
                                </a>
                                <x-secondary-button class="text-red-500" x-data="{ kriteria: {{ $kriteria }} }"
                                    x-on:click.prevent="$dispatch('open-modal', {name: 'confirmDeleteModal', kriteria})">
                                    <i class="fa-solid fa-trash"></i>
                                </x-secondary-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-modal name="confirmDeleteModal" :maxWidth="'sm'">
            <div class="p-5" x-data="{ kriteria: null }"
                x-on:open-modal.window="kriteria = $event.detail.kriteria ?? null">
                <h1 class="text-xl">Hapus kriteria <span x-text="kriteria?.nama" class="text-red-500"></span>?</h1>
                <div class="flex gap-x-3 mt-5">

                    <x-secondary-button class="w-full" x-data x-on:click="$dispatch('close')">
                        <i class="fa-solid fa-ban mr-3"></i>
                        Batal
                    </x-secondary-button>
                    <form method="POST" x-bind:action="`/admin/kriteria/delete/${kriteria?.id}`" class="w-full">
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
