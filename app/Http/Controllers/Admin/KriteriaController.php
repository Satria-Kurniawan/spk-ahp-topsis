<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Subkriteria;
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

            $listAlternatif = Alternatif::all();

            $kriteria = Kriteria::findOrFail($id);

            $namaKriteriaLama = str_replace(' ', '_', $kriteria->nama);
            $namaKriteriaBaru = str_replace(' ', '_', $validatedData['nama']);

            $listAlternatif = Alternatif::all();

            // Perbarui nilai kriteria lama dengan kriteria baru pada setiap alternatif
            foreach ($listAlternatif as $alternatif) {
                $data = $alternatif->data;

                // Pastikan kriteria lama ada dalam data alternatif
                if (isset($data[$namaKriteriaLama])) {
                    // Buat kunci baru dengan nama kriteria baru dan nilai yang sama
                    $data[$namaKriteriaBaru] = $data[$namaKriteriaLama];

                    // Hapus kunci lama
                    unset($data[$namaKriteriaLama]);

                    $alternatif->data = $data; // Simpan kembali sebagai string JSON
                    $alternatif->save();
                }
            }

            // Perbarui kriteria lama dengan data kriteria baru
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
            $subkriteria = Subkriteria::where('kriteria_id', $id);
            $namaKriteriaLama = str_replace(' ', '_', $kriteria->nama);

            $listAlternatif = Alternatif::all();
            foreach ($listAlternatif as $alternatif) {
                $data = $alternatif->data;

                if (isset($data[$namaKriteriaLama])) {
                    // Hapus key kriteria yang akan dihapus
                    unset($data[$namaKriteriaLama]);

                    $alternatif->data = $data; // Simpan kembali sebagai string JSON
                    $alternatif->save();
                }
            }

            $subkriteria->delete();
            $kriteria->delete(); // Hapus kriteria

            toastr()->success('Data Kriteria berhasil dihapus.', 'Sukses');
        } catch (\Throwable $err) {
            toastr()->error($err->getMessage(), "Error");
        }

        return redirect()->back();
    }
}
