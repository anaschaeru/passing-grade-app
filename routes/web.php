<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SelectionController;

Route::get('/', function () {
    return view('welcome'); // Halaman depan aplikasi
});

// Admin Routes (Anda bisa tambahkan middleware auth di sini nanti)
Route::prefix('admin')->group(function () {
    Route::resource('majors', MajorController::class);
    Route::resource('students', StudentController::class);
    Route::get('selection', [SelectionController::class, 'index'])->name('selection.index');
    Route::post('selection/process', [SelectionController::class, 'process'])->name('selection.process');
    Route::get('selection/results', [SelectionController::class, 'results'])->name('selection.results');
});

// Public Route (Cek Status Siswa)
Route::get('/check-status', [StudentController::class, 'checkStatusForm'])->name('student.checkStatusForm');
Route::post('/check-status', [StudentController::class, 'checkStatus'])->name('student.checkStatus');

// Rute untuk Import Siswa dari Excel
Route::get('students/import', [StudentController::class, 'importForm'])->name('students.import.form');
Route::post('students/import', [StudentController::class, 'import'])->name('students.import');

// Rute untuk Export Hasil Seleksi ke Excel
Route::get('selection/export/{status?}', [SelectionController::class, 'export'])->name('selection.export');

// Rute untuk menghapus semua data siswa
Route::delete('students/clear-all', [StudentController::class, 'clearAllStudents'])->name('students.clear_all');
