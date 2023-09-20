<?php

use App\Http\Controllers\Admin\AlternatifController;
use App\Http\Controllers\Admin\BobotPreferensiAHPController;
use App\Http\Controllers\Admin\HasilAkhirController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\SubkriteriaController;
use App\Http\Controllers\Admin\PerhitunganController;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::redirect('/', '/login');


Route::get('/dashboard', function () {
    $jumlahKriteria = count(Kriteria::all());
    $jumlahSubkriteria = count(Subkriteria::all());
    $jumlahAlternatif = count(Alternatif::all());

    return view('dashboard', compact('jumlahKriteria', 'jumlahSubkriteria', 'jumlahAlternatif'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/kriteria', [KriteriaController::class, 'show'])->name("kriteria.show");
    Route::get('/admin/kriteria/create', [KriteriaController::class, 'create'])->name("kriteria.create");
    Route::post('/admin/kriteria/store', [KriteriaController::class, 'store'])->name("kriteria.store");
    Route::get('/admin/kriteria/edit/{id}', [KriteriaController::class, 'edit'])->name("kriteria.edit");
    Route::post('/admin/kriteria/update/{id}', [KriteriaController::class, 'update'])->name("kriteria.update");
    Route::post('/admin/kriteria/delete/{id}', [KriteriaController::class, 'delete'])->name("kriteria.delete");

    Route::get('/admin/bobot-preferensi-ahp', [BobotPreferensiAHPController::class, 'show'])->name("bobot-preferensi-ahp.show");
    Route::post('/admin/bobot-preferensi-ahp/store', [BobotPreferensiAHPController::class, 'store'])->name("bobot-preferensi-ahp.store");

    Route::get('/admin/subkriteria', [SubkriteriaController::class, 'show'])->name("subkriteria.show");
    Route::get('/admin/subkriteria/create/{idKriteria}', [SubkriteriaController::class, 'create'])->name("subkriteria.create");
    Route::post('/admin/subkriteria/store/{idKriteria}', [SubkriteriaController::class, 'store'])->name("subkriteria.store");
    Route::get('/admin/subkriteria/edit/{idKriteria}/{idSubkriteria}', [SubkriteriaController::class, 'edit'])->name("subkriteria.edit");
    Route::post('/admin/subkriteria/update/{idSubkriteria}', [SubkriteriaController::class, 'update'])->name("subkriteria.update");
    Route::post('/admin/subkriteria/delete/{idSubkriteria}', [SubkriteriaController::class, 'delete'])->name("subkriteria.delete");

    Route::get("/admin/alternatif", [AlternatifController::class, 'show'])->name("alternatif.show");
    Route::get("/admin/alternatif/create", [AlternatifController::class, 'create'])->name("alternatif.create");
    Route::post("/admin/alternatif/store", [AlternatifController::class, 'store'])->name("alternatif.store");
    Route::get("/admin/alternatif/edit/{id}", [AlternatifController::class, 'edit'])->name("alternatif.edit");
    Route::post("/admin/alternatif/update/{id}", [AlternatifController::class, 'update'])->name("alternatif.update");
    Route::post("/admin/alternatif/delete/{id}", [AlternatifController::class, 'delete'])->name("alternatif.delete");

    Route::get('/admin/perhitungan', [PerhitunganController::class, 'show'])->name('perhitungan.show');

    Route::get('/admin/hasil', [HasilAkhirController::class, 'show'])->name('hasil.show');
});

require __DIR__ . '/auth.php';
