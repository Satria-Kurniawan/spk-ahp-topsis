<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlternatifController extends Controller
{
    public function show()
    {
        $listKriteria = Kriteria::all();
        $listAlternatif = Alternatif::all();

        return view('AdminPage.Alternatif.index', compact('listKriteria', 'listAlternatif'));
    }

    public function create()
    {
        $listKriteria = Kriteria::all();
        $listSubkriteria = Subkriteria::all();

        return view('AdminPage.Alternatif.create', compact('listKriteria', 'listSubkriteria'));
    }

    public function store(Request $req)
    {
        try {
            $data = $req->all();

            unset($data['_token']);
            unset($data['nama']);

            $rules = [];

            foreach ($data as $key => $value) {
                $rules[$key] = 'required|numeric';
            }

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                // Validasi gagal, ada input yang tidak valid
                $errors = $validator->errors();
                throw new \Exception("Data input tidak valid: " . $errors->first());
            }

            $validatedData = $req->validate([
                'nama' => 'required|string'
            ]);

            Alternatif::create([
                'nama' => $validatedData['nama'],
                'data' => $data
            ]);

            toastr()->success('Data Alternatif berhasil disimpan.', 'Sukses');
            return redirect()->route('alternatif.show');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
            return redirect()->back()->withErrors($err->getMessage());
        }
    }

    public function edit($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $listKriteria = Kriteria::all();
        $listSubkriteria = Subkriteria::all();

        return view('AdminPage.Alternatif.edit', compact('alternatif', 'listKriteria', 'listSubkriteria'));
    }

    public function update(Request $req, $id)
    {
        try {
            $data = $req->all();

            unset($data['_token']);
            unset($data['nama']);

            $rules = [];

            foreach ($data as $key => $value) {
                $rules[$key] = 'required|numeric';
            }

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                // Validasi gagal, ada input yang tidak valid
                $errors = $validator->errors();
                throw new \Exception("Data input tidak valid: " . $errors->first());
            }

            $validatedData = $req->validate([
                'nama' => 'required|string'
            ]);

            $alternatif = Alternatif::findOrFail($id);

            $alternatif->update([
                'nama' => $validatedData['nama'],
                'data' => $data
            ]);

            toastr()->success('Data Alternatif berhasil disimpan.', 'Sukses');
            return redirect()->route('alternatif.show');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
            return redirect()->back()->withErrors($err->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $alternatif = Alternatif::findOrFail($id);
            $alternatif->delete();

            toastr()->success('Data Alternatif berhasil dihapus.', 'Sukses');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
        }

        return redirect()->back();
    }
}
