<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\Request;

class SubkriteriaController extends Controller
{
    public function show()
    {
        $listKriteria = Kriteria::all();
        $listSubkriteria = Subkriteria::all();

        return view('AdminPage.Subkriteria.index', compact('listKriteria', 'listSubkriteria'));
    }

    public function create($idKriteria)
    {
        $kriteria = Kriteria::findOrFail($idKriteria);

        return view('AdminPage.Subkriteria.create', compact('kriteria'));
    }

    public function store(Request $req, $idKriteria)
    {
        try {
            $validatedData = $req->validate([
                'nama' => 'required',
                'nilai' => 'required',
            ]);

            $isInputLokasi = filter_var($req->islokasi, FILTER_VALIDATE_BOOLEAN);

            if ($isInputLokasi) {
                $validatedLokasi = $req->validate([
                    'islokasi' => 'required',
                    'lat' => 'required',
                    'lon' => 'required',
                ]);

                $isLokasi = true;
                $lat =  $validatedLokasi['lat'];
                $lon =  $validatedLokasi['lon'];
            } else {
                $isLokasi = false;
                $lat =  "";
                $lon =  '';
            }

            Subkriteria::create([
                'nama' => $validatedData['nama'],
                'nilai' => $validatedData['nilai'],
                'lat' => $lat,
                'lon' => $lon,
                'isLokasi' => $isLokasi,
                'kriteria_id' => $idKriteria,
            ]);

            toastr()->success('Data Subkriteria berhasil disimpan.', 'Sukses');
            return redirect()->route('subkriteria.show');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
            return redirect()->back();
        }
    }

    public function edit($idKriteria, $idSubkriteria)
    {
        $kriteria = Kriteria::findOrFail($idKriteria);
        $subkriteria = Subkriteria::findOrFail($idSubkriteria);

        return view('AdminPage.Subkriteria.edit', compact('kriteria', 'subkriteria'));
    }

    public function update(Request $req, $idSubkriteria)
    {
        try {
            $subkriteria = Subkriteria::findOrFail($idSubkriteria);

            $validatedData = $req->validate([
                'nama' => 'required',
                'nilai' => 'required',
            ]);

            $listAlternatif = Alternatif::all();

            foreach ($listAlternatif as $alternatif) {
                $data = $alternatif->data;

                if ($data['Lokasi'] == $subkriteria->nilai) {
                    if (isset($data['Lokasi']) || isset($data['lokasi'])) {
                        $data['Lokasi'] = $validatedData['nilai'];

                        $alternatif->data = $data;
                        $alternatif->save();
                    }
                }
            }

            $isInputLokasi = filter_var($req->islokasi, FILTER_VALIDATE_BOOLEAN);

            if ($isInputLokasi) {
                $validatedLokasi = $req->validate([
                    'islokasi' => 'required',
                    'lat' => 'required',
                    'lon' => 'required',
                ]);

                $isLokasi = true;
                $lat =  $validatedLokasi['lat'];
                $lon =  $validatedLokasi['lon'];
            } else {
                $isLokasi = false;
                $lat =  "";
                $lon =  '';
            }

            $subkriteria->update([
                'nama' => $validatedData['nama'],
                'nilai' => $validatedData['nilai'],
                'lat' => $lat,
                'lon' => $lon,
                'isLokasi' => $isLokasi,
            ]);


            toastr()->success('Data Subkriteria berhasil diperbarui.', 'Sukses');
            return redirect()->route('subkriteria.show');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
            return redirect()->back();
        }
    }


    public function delete($idSubkriteria)
    {
        try {
            $subkriteria = Subkriteria::findOrFail($idSubkriteria);
            $subkriteria->delete();

            toastr()->success('Data Subkriteria berhasil dihapus.', 'Sukses');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
        }

        return redirect()->back();
    }
}
