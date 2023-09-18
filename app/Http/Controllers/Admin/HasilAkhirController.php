<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class HasilAkhirController extends Controller
{
    public function show()
    {
        $listKriteria = Kriteria::all();
        $listAlternatif = Alternatif::all();

        $matriksTernormalisasiR = PerhitunganController::hitungMatriksTernormalisasiR($listAlternatif);
        $matriksTernormalisasiTerbobotY = PerhitunganController::hitungMatriksTernormalisasiTerbobotY($matriksTernormalisasiR);
        $dataSolusiIdealPositif = PerhitunganController::hitungSolusiIdealPositif($matriksTernormalisasiTerbobotY);
        $dataSolusiIdealNegatif = PerhitunganController::hitungSolusiIdealNegatif($matriksTernormalisasiTerbobotY);
        $dataDPlus = PerhitunganController::hitungDPlus($dataSolusiIdealPositif, $matriksTernormalisasiTerbobotY);
        $dataDMinus = PerhitunganController::hitungDMinus($dataSolusiIdealNegatif, $matriksTernormalisasiTerbobotY);
        $dataNilaiPreferensi = PerhitunganController::hitungNilaiPreferensi($dataDMinus, $dataDPlus);

        $hasilAkhir = [];

        foreach ($listAlternatif as $index => $alternatif) {
            $nama = $alternatif->nama;
            $nilai = $dataNilaiPreferensi[$index];

            $hasilAkhir[] = [
                'nama' => $nama,
                'nilai' => $nilai,
            ];
        }

        usort($hasilAkhir, function ($a, $b) {
            return $b["nilai"] <=> $a["nilai"];
        });

        return view('AdminPage.Hasil.index', compact('hasilAkhir'));
    }
}
