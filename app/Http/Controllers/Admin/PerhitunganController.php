<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class PerhitunganController extends Controller
{
    public function show()
    {
        $listKriteria = Kriteria::all();
        $listAlternatif = Alternatif::all();

        $matriksTernormalisasiR = $this->hitungMatriksTernormalisasiR($listAlternatif);
        $matriksTernormalisasiTerbobotY = $this->hitungMatriksTernormalisasiTerbobotY($matriksTernormalisasiR);
        $dataSolusiIdealPositif = $this->hitungSolusiIdealPositif($matriksTernormalisasiTerbobotY);
        $dataSolusiIdealNegatif = $this->hitungSolusiIdealNegatif($matriksTernormalisasiTerbobotY);
        $dataDPlus = $this->hitungDPlus($dataSolusiIdealPositif, $matriksTernormalisasiTerbobotY);
        $dataDMinus = $this->hitungDMinus($dataSolusiIdealNegatif, $matriksTernormalisasiTerbobotY);
        $dataNilaiPreferensi = $this->hitungNilaiPreferensi($dataDMinus, $dataDPlus);

        return view('AdminPage.Perhitungan.index', compact('listKriteria', 'listAlternatif', 'matriksTernormalisasiR', 'matriksTernormalisasiTerbobotY', 'dataSolusiIdealPositif', 'dataSolusiIdealNegatif', 'dataDPlus', 'dataDMinus', 'dataNilaiPreferensi'));
    }

    public static function hitungMatriksTernormalisasiR($listAlternatif)
    {
        if (empty($listAlternatif->toArray())) {
            return []; // Kembalikan matriks kosong jika $listAlternatif kosong
        }

        $matriks = [];

        foreach ($listAlternatif as $alternatif) {
            $matriks[] = array_values($alternatif->data);
        }

        $matriksHasilPangkat2 = [];

        // Loop melalui matriks awal
        foreach ($matriks as $row) {
            $resultRow = []; // Inisialisasi baris hasil
            foreach ($row as $element) {
                // Hitung pangkat 2 dari setiap elemen
                $hasilPangkat2 = $element ** 2;
                $resultRow[] = $hasilPangkat2; // Masukkan hasil pangkat 2 ke dalam baris hasil
            }
            $matriksHasilPangkat2[] = $resultRow; // Masukkan baris hasil ke dalam matriks baru
        }

        // Inisialisasi matriks hasil akar per kolom
        $matriksHasilAkarKolom = [];

        // Loop melalui kolom matriks hasil pangkat 2
        foreach ($matriksHasilPangkat2[0] as $colIndex => $colValue) {
            $sum = 0;
            // Loop melalui baris matriks hasil pangkat 2 (r11, r21, r31)
            foreach ($matriksHasilPangkat2 as $rowIndex => $row) {
                $sum += $row[$colIndex];
            }
            $hasilAkarKolom = sqrt($sum); // Hitung akar kuadrat dari hasil penjumlahan kolom
            $matriksHasilAkarKolom[] = $hasilAkarKolom; // Masukkan hasil akar ke dalam matriks baru
        }

        $hasilMatriksNormalisasi = [];

        // Loop melalui matriks awal
        foreach ($matriks as $rowIndex => $row) {
            $resultRow = []; // Inisialisasi baris hasil
            foreach ($row as $colIndex => $colValue) {
                // Bagi elemen matriks awal dengan hasil akar kolom yang sesuai
                $hasilPembagian = $colValue / $matriksHasilAkarKolom[$colIndex];
                $resultRow[] = $hasilPembagian; // Masukkan hasil pembagian ke dalam baris hasil
            }
            $hasilMatriksNormalisasi[] = $resultRow; // Masukkan baris hasil ke dalam matriks baru
        }

        return $hasilMatriksNormalisasi;
    }

    public static function hitungMatriksTernormalisasiTerbobotY($matriksTernormalisasiR)
    {
        $listBobot = Kriteria::pluck('bobot')->toArray();

        // Inisialisasi matriks hasil perkalian
        $hasilMatriksTernormalisasiTerbobotY = [];

        // Loop matriks
        foreach ($matriksTernormalisasiR as $rowIndex => $row) {
            $resultRow = []; // Inisialisasi baris hasil
            foreach ($row as $colIndex => $colValue) {
                // Kalikan elemen matriks pertama dengan elemen yang sesuai dari matriks kedua
                $hasilPerkalian = $colValue * $listBobot[$colIndex];
                $resultRow[] = $hasilPerkalian; // Masukkan hasil perkalian ke dalam baris hasil
            }
            $hasilMatriksTernormalisasiTerbobotY[] = $resultRow; // Masukkan baris hasil ke dalam matriks baru
        }

        return $hasilMatriksTernormalisasiTerbobotY;
    }

    public static function hitungSolusiIdealPositif($matriksTernormalisasiTerbobotY)
    {
        $listKriteria = Kriteria::all();

        // Inisialisasi matriks hasil dengan label jenis
        $matriksHasilDenganLabel = [];

        // Loop melalui matriks
        foreach ($matriksTernormalisasiTerbobotY as $rowIndex => $row) {
            $resultRow = []; // Inisialisasi baris hasil
            foreach ($row as $colIndex => $colValue) {
                // Ambil jenis kriteria sesuai dengan kolom
                $jenisKriteria = $listKriteria[$colIndex]['jenis'];
                // Masukkan nilai dengan label jenis ke dalam baris hasil
                $resultRow[] = [$jenisKriteria => $colValue];
            }
            $matriksHasilDenganLabel[] = $resultRow; // Masukkan baris hasil ke dalam matriks baru
        }

        // Inisialisasi variabel untuk solusi ideal
        $solusiIdealPositif = [];

        // Loop melalui matriks hasil dengan label jenis
        foreach ($matriksHasilDenganLabel as $rowIndex => $row) {
            foreach ($row as $colIndex => $colValue) {
                // Loop melalui elemen dalam baris (hanya ada satu elemen dalam array asosiasi)
                foreach ($colValue as $jenisKriteria => $nilai) {
                    if (!isset($solusiIdealPositif[$colIndex])) {
                        // Inisialisasi nilai solusi ideal dengan nilai awal dari jenis kriteria pertama
                        $solusiIdealPositif[$colIndex] = $nilai;
                    } else {
                        // Jika jenis kriteria adalah "Cost", cari nilai minimum per kolom
                        if ($jenisKriteria === 'Cost' && $nilai < $solusiIdealPositif[$colIndex]) {
                            $solusiIdealPositif[$colIndex] = $nilai;
                        }
                        // Jika jenis kriteria adalah "Benefit", cari nilai maksimum per kolom
                        elseif ($jenisKriteria === 'Benefit' && $nilai > $solusiIdealPositif[$colIndex]) {
                            $solusiIdealPositif[$colIndex] = $nilai;
                        }
                    }
                }
            }
        }
        return $solusiIdealPositif;
    }

    public static function hitungSolusiIdealNegatif($matriksTernormalisasiTerbobotY)
    {
        $listKriteria = Kriteria::all();

        // Inisialisasi matriks hasil dengan label jenis
        $matriksHasilDenganLabel = [];

        // Loop melalui matriks
        foreach ($matriksTernormalisasiTerbobotY as $rowIndex => $row) {
            $resultRow = []; // Inisialisasi baris hasil
            foreach ($row as $colIndex => $colValue) {
                // Ambil jenis kriteria sesuai dengan kolom
                $jenisKriteria = $listKriteria[$colIndex]['jenis'];
                // Masukkan nilai dengan label jenis ke dalam baris hasil
                $resultRow[] = [$jenisKriteria => $colValue];
            }
            $matriksHasilDenganLabel[] = $resultRow; // Masukkan baris hasil ke dalam matriks baru
        }

        // Inisialisasi variabel untuk solusi ideal
        $solusiIdealNegatif = [];

        // Loop melalui matriks hasil dengan label jenis
        foreach ($matriksHasilDenganLabel as $rowIndex => $row) {
            foreach ($row as $colIndex => $colValue) {
                // Loop melalui elemen dalam baris (hanya ada satu elemen dalam array asosiasi)
                foreach ($colValue as $jenisKriteria => $nilai) {
                    if (!isset($solusiIdealNegatif[$colIndex])) {
                        // Inisialisasi nilai solusi ideal dengan nilai awal dari jenis kriteria pertama
                        $solusiIdealNegatif[$colIndex] = $nilai;
                    } else {
                        // Jika jenis kriteria adalah "Benefit", cari nilai minimum per kolom
                        if ($jenisKriteria === 'Benefit' && $nilai < $solusiIdealNegatif[$colIndex]) {
                            $solusiIdealNegatif[$colIndex] = $nilai;
                        }
                        // Jika jenis kriteria adalah "Cost", cari nilai maksimum per kolom
                        elseif ($jenisKriteria === 'Cost' && $nilai > $solusiIdealNegatif[$colIndex]) {
                            $solusiIdealNegatif[$colIndex] = $nilai;
                        }
                    }
                }
            }
        }
        return $solusiIdealNegatif;
    }

    public static function hitungDPlus($dataSolusiIdealPositif, $matriksTernormalisasiTerbobotY)
    {
        $dPlus = [];

        foreach ($matriksTernormalisasiTerbobotY as $baris) {
            $total = 0;

            foreach ($baris as $key => $nilai) {
                $selisih = $nilai - $dataSolusiIdealPositif[$key];
                $total += pow($selisih, 2);
            }

            $dPlus[] = sqrt($total);
        }

        return $dPlus;
    }

    public static function hitungDMinus($dataSolusiIdealNegatif, $matriksTernormalisasiTerbobotY)
    {
        $dMinus = [];

        foreach ($matriksTernormalisasiTerbobotY as $baris) {
            $total = 0;

            foreach ($baris as $key => $nilai) {
                $selisih = $nilai - $dataSolusiIdealNegatif[$key];
                $total += pow($selisih, 2);
            }

            $dMinus[] = sqrt($total);
        }

        return $dMinus;
    }

    public static function hitungNilaiPreferensi($dataDMinus, $dataDPlus)
    {
        $nilaiPreferensi = [];

        foreach ($dataDMinus as $index => $dMinus) {
            $nilaiPreferensi[] = $dMinus / ($dMinus + $dataDPlus[$index]);
        }

        return $nilaiPreferensi;
    }
}
