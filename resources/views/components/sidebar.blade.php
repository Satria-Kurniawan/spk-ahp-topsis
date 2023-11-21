<aside class="h-screen sticky top-0 w-[20vw] bg-gray-800 p-5 text-white shadow-xl">
    <div>
        <h1 class="font-bold text-xl">SPK-AHP-TOPSIS</h1>
    </div>
    <ul class="mt-10 flex flex-col gap-3">
        <li class="px-3 py-2 rounded-md {{ Route::is('dashboard') ? ' bg-white text-black' : '' }}">
            <a href="{{ route('dashboard') }}" class="flex gap-x-4 items-center text-lg">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <hr />
        <li
            class="px-3 py-2 rounded-md {{ Route::is('kriteria.show') || Route::is('bobot-preferensi-ahp.show') || Route::is('kriteria.create') || Route::is('kriteria.edit') ? ' bg-white text-black' : '' }}">
            <a href="{{ route('kriteria.show') }}" class="flex gap-x-4 items-center text-lg">
                <i class="fa-solid fa-list-ol"></i>
                <span>Data Kriteria</span>
            </a>
        </li>
        <li
            class="px-3 py-2 rounded-md {{ Route::is('subkriteria.show') || Route::is('subkriteria.create') || Route::is('subkriteria.edit') ? ' bg-white text-black' : '' }}">
            <a href="{{ route('subkriteria.show') }}" class="flex gap-x-4 items-center text-lg">
                <i class="fa-solid fa-bars-staggered"></i>
                <span>Data Subkriteria</span>
            </a>
        </li>
        <li
            class="px-3 py-2 rounded-md {{ Route::is('alternatif.show') || Route::is('alternatif.create') || Route::is('alternatif.edit') ? ' bg-white text-black' : '' }}">
            <a href="{{ route('alternatif.show') }}" class="flex gap-x-4 items-center text-lg">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Data Alternatif</span>
            </a>
        </li>
        <li class="px-3 py-2 rounded-md {{ Route::is('perhitungan.show') ? ' bg-white text-black' : '' }}">
            <a href="{{ route('perhitungan.show') }}" class="flex gap-x-4 items-center text-lg">
                <i class="fa-solid fa-calculator"></i>
                <span>Data Perhitungan</span>
            </a>
        </li>
        <li class="px-3 py-2 rounded-md {{ Route::is('hasil.show') ? ' bg-white text-black' : '' }}">
            <a href="{{ route('hasil.show') }}" class="flex gap-x-4 items-center text-lg">
                <i class="fa-solid fa-ranking-star"></i>
                <span>Data Hasil Akhir</span>
            </a>
        </li>
    </ul>
</aside>
