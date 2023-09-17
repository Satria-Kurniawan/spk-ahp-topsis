<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;


class KriteriaController extends Controller
{
    public function show()
    {
        $listKriteria = Kriteria::all();
        return view('AdminPage.Kriteria.index', compact('listKriteria'));
    }

    public function create()
    {
        return view('AdminPage.Kriteria.create');
    }

    public function store(Request $req)
    {
        try {
            $validatedData = $req->validate([
                'nama' => 'required',
                // 'bobot' => 'required|numeric',
                'jenis' => 'required'
            ]);

            Kriteria::create($validatedData);

            toastr()->success('Data Kriteria berhasil disimpan.', 'Sukses');
            return redirect()->route('kriteria.show');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
            return redirect()->back()->withErrors($err->getMessage());
        }
    }

    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);

        return view('AdminPage.Kriteria.edit', compact('kriteria'));
    }

    public function update(Request $req, $id)
    {
        try {
            $validatedData = $req->validate([
                'nama' => 'required',
                // 'bobot' => 'required|numeric',
                'jenis' => 'required'
            ]);

            $kriteria = Kriteria::findOrFail($id);
            $kriteria->update($validatedData);

            toastr()->success('Data Kriteria berhasil perbarui.', 'Sukses');
            return redirect()->route('kriteria.show');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
            return redirect()->back()->withErrors($err->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $kriteria = Kriteria::findOrFail($id);
            $kriteria->delete();

            toastr()->success('Data Kriteria berhasil dihapus.', 'Sukses');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
        }

        return redirect()->back();
    }
}
