<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BobotPreferensiAHP;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class BobotPreferensiAHPController extends Controller
{
    public function show()
    {
        $listKriteria = Kriteria::all();
        $dataBobotPreferensiAHP = BobotPreferensiAHP::all();
        $dataMatriksBerpasangan = $this->getDataMatriksBerpasangan($dataBobotPreferensiAHP);
        $jumlahPerKolom = $this->hitungJumlahPerkolom($dataMatriksBerpasangan);
        $dataMatriksNilaiKriteriaNormalisasi  = $this->hitungMatriksNilaiKriteriaNormalisasi($listKriteria, $dataMatriksBerpasangan, $jumlahPerKolom);
        $dataMatriksPenjumlahanTiapBaris = $this->hitungMatriksPenjumlahanTiapBaris($listKriteria, $dataMatriksBerpasangan, $dataMatriksNilaiKriteriaNormalisasi['nilaiPrioritas']);
        $hasilPenjumlahanRasioKonsistensi = $this->hitungPenjumlahanRasioKonsistensi($dataMatriksPenjumlahanTiapBaris['hasilPenjumlahanTiapBaris'], $dataMatriksNilaiKriteriaNormalisasi['nilaiPrioritas']);
        $hasilPerhitunganAkhir = $this->hitungHasilAhir($hasilPenjumlahanRasioKonsistensi, $listKriteria, $dataMatriksBerpasangan);

        return view("AdminPage.Kriteria.bobot-ahp", compact('listKriteria', 'dataBobotPreferensiAHP', 'dataMatriksBerpasangan', 'jumlahPerKolom', 'dataMatriksNilaiKriteriaNormalisasi', 'dataMatriksPenjumlahanTiapBaris', 'hasilPenjumlahanRasioKonsistensi', 'hasilPerhitunganAkhir'));
    }

    public function store(Request $req)
    {
        try {
            $data = json_decode($req->input('data'), true);
            $listKriteria = Kriteria::all();

            foreach ($data as $item) {
                $kriteria1 = $item['kriteria1'];
                $kriteria2 = $item['kriteria2'];
                $skala = $item['nilai'];

                // Cek apakah kriteria1 dan kriteria2 ada di tabel Kriteria
                $kriteria1Obj = $listKriteria->firstWhere('nama', $kriteria1);
                $kriteria2Obj = $listKriteria->firstWhere('nama', $kriteria2);

                if (!$kriteria1Obj && !$kriteria2Obj) return;

                // Data Kriteria ditemukan, lakukan insert/update ke tabel BobotPreferensiAHP
                BobotPreferensiAHP::updateOrInsert(
                    ['kriteria_1' => $kriteria1, 'kriteria_2' => $kriteria2],
                    ['skala' => $skala]
                );
            }

            $dataBobotPreferensiAHP = BobotPreferensiAHP::all();
            $dataMatriksBerpasangan = $this->getDataMatriksBerpasangan($dataBobotPreferensiAHP);
            $jumlahPerKolom = $this->hitungJumlahPerkolom($dataMatriksBerpasangan);
            $dataMatriksNilaiKriteriaNormalisasi  = $this->hitungMatriksNilaiKriteriaNormalisasi($listKriteria, $dataMatriksBerpasangan, $jumlahPerKolom);
            $dataMatriksPenjumlahanTiapBaris = $this->hitungMatriksPenjumlahanTiapBaris($listKriteria, $dataMatriksBerpasangan, $dataMatriksNilaiKriteriaNormalisasi['nilaiPrioritas']);
            $hasilPenjumlahanRasioKonsistensi = $this->hitungPenjumlahanRasioKonsistensi($dataMatriksPenjumlahanTiapBaris['hasilPenjumlahanTiapBaris'], $dataMatriksNilaiKriteriaNormalisasi['nilaiPrioritas']);
            $hasilPerhitunganAkhir = $this->hitungHasilAhir($hasilPenjumlahanRasioKonsistensi, $listKriteria, $dataMatriksBerpasangan);

            if ($hasilPerhitunganAkhir['keterangan'] == 'Konsisten') {
                foreach ($listKriteria as $index => $item) {
                    $kriteria = Kriteria::where("nama", $item->nama)->first();
                    if ($kriteria) {
                        $kriteria->bobot = $dataMatriksNilaiKriteriaNormalisasi['nilaiPrioritas'][$index];
                        $kriteria->save();
                    }
                }
            }

            toastr()->success('Bobot preferensi kriteria (AHP) berhasil disimpan.', 'Sukses');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
        }

        return redirect()->back();
    }

    public static function getDataMatriksBerpasangan($data)
    {
        $kriteria = []; // Ini akan berisi daftar semua kriteria unik
        $matriksBerpasangan = []; // Ini akan menjadi matriks berpasangan

        // Langkah pertama, kumpulkan semua kriteria unik
        foreach ($data as $item) {
            if (!in_array($item->kriteria_1, $kriteria)) {
                $kriteria[] = $item->kriteria_1;
            }
            if (!in_array($item->kriteria_2, $kriteria)) {
                $kriteria[] = $item->kriteria_2;
            }
        }

        // Inisialisasi matriks berpasangan dengan nilai 1 di diagonal
        foreach ($kriteria as $kriteriaA) {
            foreach ($kriteria as $kriteriaB) {
                $matriksBerpasangan[$kriteriaA][$kriteriaB] = ($kriteriaA === $kriteriaB) ? 1 : null;
            }
        }

        // Isi matriks berpasangan dengan nilai-nilai dari data yang Anda miliki
        foreach ($data as $item) {
            $matriksBerpasangan[$item->kriteria_1][$item->kriteria_2] = $item->skala;
            $matriksBerpasangan[$item->kriteria_2][$item->kriteria_1] = 1 / $item->skala;
        }

        return $matriksBerpasangan;
    }

    public static function hitungJumlahPerkolom($dataMatriksBerpasangan)
    {
        $jumlahPerKolom = [];

        foreach (array_keys($dataMatriksBerpasangan) as $kriteriaA) {
            $jumlahPerKolom[$kriteriaA] = 0;
        }

        // Hitung jumlah per kolom
        foreach ($dataMatriksBerpasangan as $kriteriaA => $baris) {
            foreach ($baris as $kriteriaB => $nilai) {
                $jumlahPerKolom[$kriteriaB] += $nilai;
            }
        }

        return $jumlahPerKolom;
    }

    public static function hitungMatriksNilaiKriteriaNormalisasi($listKriteria, $dataMatriksBerpasangan, $jumlahPerKolom)
    {
        $matriksNilaiKriteria = [];

        foreach ($dataMatriksBerpasangan as $kriteriaA => $nilaiA) {
            foreach ($nilaiA as $kriteriaB => $nilai) {

                $normalizedNilai = $nilai / $jumlahPerKolom[$kriteriaB];

                $matriksNilaiKriteria[] = [
                    'kriteria_1' => $kriteriaA,
                    'kriteria_2' => $kriteriaB,
                    'nilai' => $normalizedNilai,
                ];
            }
        }

        // Mencari jumlah nilai perbaris pada matriks nilai kriteria
        $jumlahBaris = count($listKriteria);
        $jumlahPerBaris = array_fill(0, $jumlahBaris, 0);

        foreach ($matriksNilaiKriteria as $item) {
            $index = $listKriteria->search(function ($kriteria) use ($item) {
                return $kriteria->nama === $item['kriteria_1'];
            });

            $nilai = $item['nilai'];
            $jumlahPerBaris[$index] += $nilai;
        }

        // Mencari nilai prioritas
        $nilaiPrioritas = [];
        $jumlahKriteria = count($listKriteria);

        foreach ($jumlahPerBaris as $jumlah) {
            $nilaiPriority = $jumlah / $jumlahKriteria;

            $nilaiPrioritas[] = $nilaiPriority;
        }

        return [
            'matriksNilaiKriteria' => $matriksNilaiKriteria,
            'jumlahPerBaris' => $jumlahPerBaris,
            'nilaiPrioritas' => $nilaiPrioritas
        ];
    }

    public static function hitungMatriksPenjumlahanTiapBaris($listKriteria, $dataMatriksBerpasangan, $nilaiPrioritas)
    {
        $size = count($listKriteria);
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));

        foreach ($dataMatriksBerpasangan as $kriteriaA => $nilaiA) {
            foreach ($nilaiA as $kriteriaB => $nilai) {
                $row = array_search($kriteriaA, array_column($listKriteria->toArray(), 'nama'));
                $col = array_search($kriteriaB, array_column($listKriteria->toArray(), 'nama'));
                $matrix[$row][$col] = $nilai;
            }
        }

        foreach ($matrix as $rowIndex => $row) {
            $resultRow = [];
            foreach ($row as $colIndex => $value) {
                $resultRow[] = $value * $nilaiPrioritas[$colIndex];
            }
            $matriksPenjumlahanTiapBaris[] = $resultRow;
        }

        $dataMatriksPenjumlahanTiapBaris = [];

        foreach ($matriksPenjumlahanTiapBaris as $rowIndex => $row) {
            $hasil = 0;
            foreach ($row as $colIndex => $value) {
                $hasil += $value;
            }
            $hasilPenjumlahanTiapBaris[$rowIndex] = $hasil;
        }

        return [
            'matriksPenjumlahanTiapBaris' => $matriksPenjumlahanTiapBaris,
            'hasilPenjumlahanTiapBaris' => $hasilPenjumlahanTiapBaris,
        ];
    }

    public static function hitungPenjumlahanRasioKonsistensi($hasilPenjumlahanTiapBaris, $nilaiPrioritas)
    {
        $hasilPenjumlahanRasioKonsistensi = [];

        foreach ($hasilPenjumlahanTiapBaris as $index => $value) {
            $hasil = $value + $nilaiPrioritas[$index];
            $hasilPenjumlahanRasioKonsistensi[$index] = $hasil;
        }

        return $hasilPenjumlahanRasioKonsistensi;
    }

    public static function hitungHasilAhir($hasilPenjumlahanRasioKonsistensi, $listKriteria, $dataMatriksBerpasangan)
    {
        $totalHasilPenjumlahanRasioKonsistensi = array_sum($hasilPenjumlahanRasioKonsistensi);
        $n = count($listKriteria);

        if ($n === 0 || $n === 1 || count($dataMatriksBerpasangan) === 0) {
            $lambdaMax = 0;
            $CI = 0;
            $CR = 0;
            $keterangan = '';
        } else {
            $lambdaMax = $totalHasilPenjumlahanRasioKonsistensi / $n;
            $CI = ($lambdaMax - $n) / ($n - 1);

            $nilaiIR = [
                1 => 0,
                2 => 0,
                3 => 0.58,
                4 => 0.90,
                5 => 1.12,
                6 => 1.24,
                7 => 1.32,
                8 => 1.41,
                9 => 1.45,
                10 => 1.49
            ];

            $CR = $n !== 2 ? $CI / $nilaiIR[$n] : 0;

            $keterangan = $CR <= 0.1 ? 'Konsisten' : 'Tidak Konsisten';
        }

        return [
            'jumlah' => $totalHasilPenjumlahanRasioKonsistensi,
            'n' => $n,
            'lambdaMax' => $lambdaMax,
            'CI' => $CI,
            'CR' => $CR,
            'keterangan' => $keterangan
        ];
    }
}
