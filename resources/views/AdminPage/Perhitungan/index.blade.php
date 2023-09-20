<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Perhitungan') }}
        </h2>
    </x-slot>

    <main>
        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Matriks Keputusan (X)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start"></th>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start border-l">C{{ $loop->iteration }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listAlternatif as $alternatif)
                        <tr class="border-b">
                            <th class="py-2 px-4 text-start bg-gray-50">A{{ $loop->iteration }}</th>
                            @foreach ($alternatif->data as $key => $value)
                                <td class="py-2 px-4 border-l">{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Bobot Kriteria (W)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start {{ $loop->index !== 0 ? 'border-l' : '' }}">
                                C{{ $loop->iteration }}
                                <span
                                    class="ml-3 rounded-md py-1 px-3 text-white text-sm {{ $kriteria->jenis == 'Cost' ? 'bg-blue-500' : 'bg-green-500' }}">{{ $kriteria->jenis }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        @foreach ($listKriteria as $kriteria)
                            <td class="py-2 px-4 border-r">{{ $kriteria->bobot }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Matriks Ternormalisasi (R)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start border-r"></th>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start {{ $loop->index !== 0 ? 'border-l' : '' }}">
                                C{{ $loop->iteration }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriksTernormalisasiR as $rowIndex => $items)
                        <tr class="border-b">
                            <th class="py-2 px-4 text-start bg-gray-50">A{{ $loop->iteration }}</th>
                            @foreach ($items as $colIndex => $value)
                                <td class="py-2 px-4 border-l">{{ $matriksTernormalisasiR[$rowIndex][$colIndex] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Matriks Ternormalisasi Terbobot (Y)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start border-r"></th>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start {{ $loop->index !== 0 ? 'border-l' : '' }}">
                                C{{ $loop->iteration }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriksTernormalisasiTerbobotY as $rowIndex => $items)
                        <tr class="border-b">
                            <th class="py-2 px-4 text-start bg-gray-50">A{{ $loop->iteration }}</th>
                            @foreach ($items as $colIndex => $value)
                                <td class="py-2 px-4 border-l">
                                    {{ $matriksTernormalisasiTerbobotY[$rowIndex][$colIndex] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Solusi Ideal Positif (A+)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start {{ $loop->index !== 0 ? 'border-l' : '' }}">
                                C{{ $loop->iteration }} <span
                                    class="ml-3 rounded-md py-1 px-3 bg-gray-800 text-white text-sm">{{ $kriteria->nama }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        @foreach ($dataSolusiIdealPositif as $value)
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $value }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Solusi Ideal Negatif (A-)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start {{ $loop->index !== 0 ? 'border-l' : '' }}">
                                C{{ $loop->iteration }} <span
                                    class="ml-3 rounded-md py-1 px-3 bg-gray-800 text-white text-sm">{{ $kriteria->nama }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        @foreach ($dataSolusiIdealNegatif as $value)
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $value }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Jarak Ideal Positif (D+)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start">
                            No
                        </th>
                        <th class="py-2 px-4 text-start">
                            Nama Alternatif
                        </th>
                        <th class="py-2 px-4 text-start">
                            Jarak Ideal Postif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listAlternatif as $index => $alternatif)
                        <tr class="border-b">
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $alternatif->nama }}</td>
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $dataDPlus[$index] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Jarak Ideal Positif (D-)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start">
                            No
                        </th>
                        <th class="py-2 px-4 text-start">
                            Nama Alternatif
                        </th>
                        <th class="py-2 px-4 text-start">
                            Jarak Ideal Negatif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listAlternatif as $index => $alternatif)
                        <tr class="border-b">
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $alternatif->nama }}</td>
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $dataDMinus[$index] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 overflow-auto mb-5 w-[76vw]">
            <div class="border-b pb-3">
                <div class="w-fit mr-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Kedekatan Alternatif Terhadap Solusi</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start">
                            No
                        </th>
                        <th class="py-2 px-4 text-start">
                            Nama Alternatif
                        </th>
                        <th class="py-2 px-4 text-start">
                            Nilai
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listAlternatif as $index => $alternatif)
                        <tr class="border-b">
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $alternatif->nama }}</td>
                            <td class="py-2 px-4 text-start bg-gray-50">{{ $dataNilaiPreferensi[$index] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</x-app-layout>
