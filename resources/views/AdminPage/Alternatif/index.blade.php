<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Alternatif') }}
        </h2>
        @section('cta')
            <div>
                <a href="{{ route('alternatif.create') }}">
                    <x-primary-button class="bg-green-500"> <i class="fa-solid fa-plus mr-3"></i> Tambah</x-primary-button>
                </a>
            </div>
        @endsection
    </x-slot>

    <main>
        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Tabel Alternatif</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start w-20">No</th>
                        <th class="py-2 px-4 text-start">Nama Alternatif</th>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start">{{ $kriteria->nama }}</th>
                        @endforeach
                        <th class="py-2 px-4 text-start w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listAlternatif as $alternatif)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $alternatif->nama }}</td>
                            @foreach ($alternatif->data as $key => $value)
                                <td class="py-2 px-4">{{ $value }}</td>
                            @endforeach
                            <td class="py-2 px-4">
                                <a href="{{ route('alternatif.edit', ['id' => $alternatif->id]) }}">
                                    <x-secondary-button class="mr-1 text-blue-500">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </x-secondary-button>
                                </a>
                                <x-secondary-button class="text-red-500" x-data="{ alternatif: {{ $alternatif }} }"
                                    x-on:click.prevent="$dispatch('open-modal', {name: 'confirmDeleteModal', alternatif})">
                                    <i class="fa-solid fa-trash"></i>
                                </x-secondary-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-modal name="confirmDeleteModal" :maxWidth="'sm'">
            <div class="p-5" x-data="{ alternatif: null }"
                x-on:open-modal.window="alternatif = $event.detail.alternatif ?? null">
                <h1 class="text-xl">Hapus alternatif <span x-text="alternatif?.nama" class="text-red-500"></span>?</h1>
                <div class="flex gap-x-3 mt-5">

                    <x-secondary-button class="w-full" x-data x-on:click="$dispatch('close')">
                        <i class="fa-solid fa-ban mr-3"></i>
                        Batal
                    </x-secondary-button>
                    <form method="POST" x-bind:action="`/admin/alternatif/delete/${alternatif?.id}`" class="w-full">
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
