<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel; // Impor Facade Excel
use App\Imports\StudentsImport;     // Impor kelas StudentsImport
use Maatwebsite\Excel\Validators\ValidationException;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('admin.students.index', compact('students'));
    }
    public function create()
    {
        $majors = Major::all();
        return view('admin.students.create', compact('majors'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:students,nisn',
            'name' => 'required',
            'origin_school' => 'required',
            'final_score' => 'required|numeric|min:0|max:200',
            'choice_1' => 'required|exists:majors,name',
            'choice_2' => 'nullable|exists:majors,name|different:choice_1',
        ]);
        Student::create($request->all());
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }
    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }
    public function edit(Student $student)
    {
        $majors = Major::all();
        return view('admin.students.edit', compact('student', 'majors'));
    }
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nisn' => 'required|unique:students,nisn,' . $student->id,
            'name' => 'required',
            'origin_school' => 'required',
            'final_score' => 'required|numeric|min:0|max:200',
            'choice_1' => 'required|exists:majors,name',
            'choice_2' => 'nullable|exists:majors,name|different:choice_1',
        ]);
        $student->update($request->all());
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    // Public functions for checking status
    public function checkStatusForm()
    {
        return view('check_status');
    }
    public function checkStatus(Request $request)
    {
        $request->validate(['nisn' => 'required|string']);
        $student = Student::where('nisn', $request->nisn)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'NISN tidak ditemukan.');
        }
        return view('status_result', compact('student'));
    }

    public function importForm()
    {
        return view('admin.students.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ], [
            'file.required' => 'Pilih file Excel yang akan diunggah.',
            'file.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file.max'      => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        try {
            // Melakukan import
            Excel::import(new StudentsImport, $request->file('file'));

            // Mengambil error yang disimpan oleh StudentsImport::onFailure
            $importErrors = Session::get('import_errors', []);
            Session::forget('import_errors'); // Hapus dari session setelah diambil

            if (empty($importErrors)) {
                return redirect()->route('students.index')->with('success', 'Data siswa berhasil diimpor!');
            } else {
                return redirect()->route('students.index')->with([
                    'warning' => 'Data siswa berhasil diimpor, namun ada beberapa baris yang gagal divalidasi.',
                    'import_errors' => $importErrors // Teruskan array error ke session
                ]);
            }
        } catch (ValidationException $e) { // Tangkap spesifik ValidationException dari Laravel Excel
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                // Ini menangani error yang *menghentikan* proses impor seluruhnya
                // Jika Anda ingin proses berlanjut meski ada error, lebih baik andalkan onFailure()
                $errors[] = "Baris {$failure->row()}: Kolom '{$failure->attribute()}' - " . implode(', ', $failure->errors());
            }
            return redirect()->back()->withErrors($errors)->withInput()->with('error', 'Gagal mengimpor data karena kesalahan validasi yang menghentikan proses.');
        } catch (\Exception $e) {
            Log::error('Kesalahan umum saat import Excel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data. Pastikan format file benar atau hubungi administrator.')->withInput();
        }
    }

    public function clearAllStudents(Request $request)
    {
        // Pastikan hanya admin yang bisa melakukan ini (jika Anda punya sistem otentikasi)
        // if (!auth()->user()->isAdmin()) {
        //     return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        // }

        // Hapus semua data siswa dari tabel
        Student::truncate(); // Menggunakan truncate() lebih cepat untuk menghapus semua baris

        return redirect()->route('students.index')->with('success', 'Semua data siswa berhasil dihapus.');
    }
}
