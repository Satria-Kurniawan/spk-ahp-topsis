<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

            Subkriteria::create([
                'nama' => $validatedData['nama'],
                'nilai' => $validatedData['nilai'],
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

            $subkriteria->update($validatedData);

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
