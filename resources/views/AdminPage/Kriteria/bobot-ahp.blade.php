<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bobot Preferensi AHP') }}
        </h2>
        @section('cta')
            <a href="{{ route('kriteria.show') }}">
                <x-primary-button class="bg-red-500"> <i class="fa-solid fa-rotate-left mr-3"></i> Kembali</x-primary-button>
            </a>
        @endsection
    </x-slot>

    <main>
        <div class="bg-white rounded-md shadow-xl p-5 relative w-[77vw] overflow-x-auto mb-5">
            <div class="border-b pb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">Perbandingan Data
                        Antar Kriteria</span>
                </div>
            </div>
            <form action="{{ route('bobot-preferensi-ahp.store') }}" method="POST" class="mt-3">
                @csrf
                <table class="w-full mb-5">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="py-2 px-4 border-r text-start">Nama Kriteria</th>
                            <th class="py-2 px-4">Skala Perbandingan</th>
                            <th class="py-2 px-4 border-l text-start">Nama Kriteria</th>
                        </tr>
                    </thead>
                    <tbody x-data="{
                        data: {},
                        setSkala: function(namaKriteria1, namaKriteria2, nilai) {
                            if (!this.data[namaKriteria1]) {
                                this.data[namaKriteria1] = {};
                            }
                            this.data[namaKriteria1][namaKriteria2] = nilai;
                        }
                    }">
                        @php
                            $skalaPerbandingan = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                        @endphp
                        @foreach ($listKriteria as $kriteria1)
                            @foreach ($listKriteria as $kriteria2)
                                @if ($kriteria1->nama !== $kriteria2->nama)
                                    @php
                                        $bobotAHP = $dataBobotPreferensiAHP->firstWhere(function ($item) use ($kriteria1, $kriteria2) {
                                            return $item->kriteria_1 === $kriteria1->nama && $item->kriteria_2 === $kriteria2->nama;
                                        });
                                        $skalaTerisi = optional($bobotAHP)->skala;
                                    @endphp
                                    <tr class="border-b">
                                        <td class="py-2 border-r px-4">{{ $kriteria1->nama }}</td>
                                        <td class="py-2 flex w-fit mx-auto">
                                            @foreach ($skalaPerbandingan as $nilai)
                                                <span
                                                    class="py-1 px-2 bg-gray-800 text-white hover:bg-blue-500 cursor-pointer {{ $loop->index === 0 ? 'rounded-l-md' : ($loop->last ? 'rounded-r-md' : '') }}"
                                                    x-bind:class="({{ $skalaTerisi }} === {{ $nilai }} && !(data[
                                                        '{{ $kriteria1->nama }}'])) ? 'bg-green-500' : ((data[
                                                        '{{ $kriteria1->nama }}'] && data[
                                                        '{{ $kriteria1->nama }}'][
                                                        '{{ $kriteria2->nama }}'
                                                    ] === {{ $nilai }}) || (
                                                        {{ $skalaTerisi }} === {{ $nilai }}) && !(
                                                        data[
                                                            '{{ $kriteria1->nama }}'][
                                                            '{{ $kriteria2->nama }}'
                                                        ] && data[
                                                            '{{ $kriteria1->nama }}'][
                                                            '{{ $kriteria2->nama }}'
                                                        ] !== {{ $nilai }})) ?
                                                    'bg-green-500' : ''"
                                                    x-on:click="setSkala('{{ $kriteria1->nama }}', '{{ $kriteria2->nama }}', {{ $nilai }})">
                                                    {{ $nilai }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="py-2 border-l px-4">{{ $kriteria2->nama }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach

                        <input type="hidden" name="data" x-bind:value="JSON.stringify(data)">
                    </tbody>
                </table>
                <x-primary-button class="bg-green-500"><i
                        class="fa-solid fa-floppy-disk mr-3"></i>Simpan</x-primary-button>
            </form>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 relative w-[77vw] overflow-x-auto mb-5">
            <div class="border-b pb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">Matriks
                        Perbandingan Berpasangan</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4"></th>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start">{{ $kriteria->nama }}</th>
                        @endforeach
                    </tr>
                </thead>

                @foreach ($listKriteria as $kriteriaA)
                    <tr class="border-b">
                        <th class="py-2 px-4 border-r text-start bg-gray-50">{{ $kriteriaA->nama }}</th>
                        @foreach ($listKriteria as $kriteriaB)
                            <td
                                class="py-2 px-4 border-l text-start {{ $kriteriaA->nama === $kriteriaB->nama ? 'bg-gray-50' : '' }}">
                                {{ $dataMatriksBerpasangan[$kriteriaA->nama][$kriteriaB->nama] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                <tr class="border-t">
                    <th class="py-2 px-4 border-r text-start bg-gray-50">Jumlah</th>
                    @foreach ($jumlahPerKolom as $jumlah)
                        <td class="py-2 px-4 border-l text-start">
                            {{ $jumlah }}
                        </td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 relative w-[77vw] overflow-x-auto mb-5">
            <div class="border-b pb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">Matriks
                        Nilai Kriteria (Normalisasi)</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start"></th>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start">{{ $kriteria->nama }}</th>
                        @endforeach
                        <th class="py-2 px-4 text-start">Jumlah</th>
                        <th class="py-2 px-4 text-start">Prioritas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listKriteria as $kriteriaA)
                        <tr class="border-b">
                            <th class="py-2 px-4 text-start border-r bg-gray-50">{{ $kriteriaA->nama }}</th>
                            @foreach ($listKriteria as $kriteriaB)
                                <td class="py-2 px-4 border-r">
                                    @php
                                        $nilaiNormalisasi = collect($dataMatriksNilaiKriteriaNormalisasi['matriksNilaiKriteria'])
                                            ->where('kriteria_1', $kriteriaA->nama)
                                            ->where('kriteria_2', $kriteriaB->nama)
                                            ->pluck('nilai')
                                            ->first();
                                    @endphp
                                    {{ $nilaiNormalisasi }}
                                </td>
                            @endforeach
                            <td class="py-2 px-4">
                                {{ $dataMatriksNilaiKriteriaNormalisasi['jumlahPerBaris'][$loop->index] }}</td>
                            <td class="py-2 px-4 border-l">
                                {{ $dataMatriksNilaiKriteriaNormalisasi['nilaiPrioritas'][$loop->index] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 relative w-[77vw] overflow-x-auto mb-5">
            <div class="border-b pb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Matriks Penjumlahan Tiap Baris</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start"></th>
                        @foreach ($listKriteria as $kriteria)
                            <th class="py-2 px-4 text-start">{{ $kriteria->nama }}</th>
                        @endforeach
                        <th class="py-2 px-4 text-start">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataMatriksPenjumlahanTiapBaris['matriksPenjumlahanTiapBaris'] as $indexA => $nilaiA)
                        <tr class="border-b">
                            <th class="py-2 px-4 text-start border-r bg-gray-50">{{ $listKriteria[$indexA]->nama }}
                            </th>
                            @foreach ($nilaiA as $indexB => $nilai)
                                <td class="py-2 px-4 border-r">{{ $nilai }}</td>
                            @endforeach
                            <td class="py-2 px-4">
                                {{ $dataMatriksPenjumlahanTiapBaris['hasilPenjumlahanTiapBaris'][$indexA] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 relative w-[77vw] overflow-x-auto mb-5">
            <div class="border-b pb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Perhitungan Rasio Konsistensi</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start"></th>
                        <th class="py-2 px-4 text-start">Jumlah Perbaris</th>
                        <th class="py-2 px-4 text-start">Prioritas</th>
                        <th class="py-2 px-4 text-start">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listKriteria as $index => $kriteria)
                        <tr class="border-b">
                            <th class="py-2 px-4 text-start border-r bg-gray-50">{{ $kriteria->nama }}
                            </th>
                            <td class="py-2 px-4 border-r">
                                {{ $dataMatriksPenjumlahanTiapBaris['hasilPenjumlahanTiapBaris'][$index] }}</td>
                            <td class="py-2 px-4 border-r">
                                {{ $dataMatriksNilaiKriteriaNormalisasi['nilaiPrioritas'][$index] }}</td>
                            <td class="py-2 px-4">
                                {{ $hasilPenjumlahanRasioKonsistensi[$index] }}</td>
                        </tr>
                    @endforeach
                    <tr class="border-b">
                        <th class="py-2 px-4 text-start border-r bg-gray-50">
                            Total
                        </th>
                        <td class="py-2 px-4"></td>
                        <td class="py-2 px-4"></td>
                        <td class="py-2 px-4 border-l">{{ $hasilPerhitunganAkhir['jumlah'] }}</td>
                    </tr>
                </tbody>

            </table>
        </div>

        <div class="bg-white rounded-md shadow-xl p-5 relative w-[77vw] overflow-x-auto mb-5">
            <div class="border-b pb-3">
                <div class="w-fit ml-auto">
                    <span class="py-1 px-3 rounded-md bg-gray-800 text-white text-center uppercase">
                        Hasil Perhitungan</span>
                </div>
            </div>
            <table class="w-full mt-3">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-start">Keterangan</th>
                        <th class="py-2 px-4 text-start">Nilai</th>
                    </tr>
                </thead>
                <tbody>

                    <tr class="border-b">
                        <th class="py-2 px-4 text-start border-r bg-gray-50">
                            Jumlah
                        </th>
                        <td class="py-2 px-4">
                            {{ $hasilPerhitunganAkhir['jumlah'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-start border-r bg-gray-50">
                            n
                        </th>
                        <td class="py-2 px-4">
                            {{ $hasilPerhitunganAkhir['n'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-start border-r bg-gray-50">
                            Lambda Max
                        </th>
                        <td class="py-2 px-4">
                            {{ $hasilPerhitunganAkhir['lambdaMax'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-start border-r bg-gray-50">
                            CI
                        </th>
                        <td class="py-2 px-4">
                            {{ $hasilPerhitunganAkhir['CI'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-start border-r bg-gray-50">
                            CR
                        </th>
                        <td class="py-2 px-4">
                            {{ $hasilPerhitunganAkhir['CR'] }} <span
                                class="py-1 px-2 rounded-md {{ $hasilPerhitunganAkhir['keterangan'] == 'Konsisten' ? 'bg-green-500' : 'bg-red-500' }}  text-white ml-3">{{ $hasilPerhitunganAkhir['keterangan'] }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</x-app-layout>
