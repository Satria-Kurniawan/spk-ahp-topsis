<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kriteria') }}
        </h2>
    </x-slot>

    <main>
        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto">
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
                        <th class="py-2 px-4 text-start w-20 border-l">Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasilAkhir as $item)
                        <tr class="border-b">
                            <td class="py-2 px-4 border-r">{{ $item['nama'] }}</td>
                            <td class="py-2 px-4">{{ $item['nilai'] }}</td>
                            <td class="py-2 px-4 border-l">{{ $loop->iteration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>
</x-app-layout>
