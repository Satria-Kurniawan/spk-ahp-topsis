<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <main>
        <section class="grid grid-cols-3 gap-5">
            <div class="p-5 rounded-md shadow-md bg-white border-l-4 border-gray-800 flex justify-between">
                <div>
                    <span class="font-extrabold text-xl">{{ $jumlahKriteria }}</span> <span>Kriteria</span>
                </div>
                <i class="fa-solid fa-list-ol"></i>
            </div>
            <div class="p-5 rounded-md shadow-md bg-white border-l-4 border-gray-800 flex justify-between">
                <div>
                    <span class="font-extrabold text-xl">{{ $jumlahSubkriteria }}</span> <span>Subkriteria</span>
                </div>
                <i class="fa-solid fa-bars-staggered"></i>
            </div>
            <div class="p-5 rounded-md shadow-md bg-white border-l-4 border-gray-800 flex justify-between">
                <div>
                    <span class="font-extrabold text-xl">{{ $jumlahAlternatif }}</span> <span>Alternatif</span>
                </div>
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
        </section>
    </main>
</x-app-layout>
