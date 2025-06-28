<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; // Impor Facade Excel
use App\Exports\SelectionResultsExport; // Impor kelas Export yang baru dibuat

class SelectionController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $majors = Major::all();
        return view('admin.selection.index', compact('totalStudents', 'majors'));
    }

    public function process()
    {
        // Reset status dan accepted_major untuk semua siswa sebelum proses baru
        Student::query()->update(['status' => 'BELUM DIPROSES', 'accepted_major' => null]);

        // Ambil kuota awal setiap jurusan (salinan yang bisa berkurang)
        $majors = Major::all()->keyBy('name');
        $majorsCurrentQuota = $majors->mapWithKeys(fn($major) => [$major->name => $major->quota])->toArray();

        // Dapatkan semua siswa, urutkan berdasarkan nilai akhir tertinggi
        $students = Student::orderBy('final_score', 'desc')->get();

        foreach ($students as $student) {
            $accepted = false;

            // Coba pilihan 1
            $choice1MajorName = $student->choice_1;
            if (isset($majorsCurrentQuota[$choice1MajorName]) && $majorsCurrentQuota[$choice1MajorName] > 0) {
                $student->status = 'DITERIMA';
                $student->accepted_major = $choice1MajorName;
                $majorsCurrentQuota[$choice1MajorName]--; // Kurangi kuota
                $accepted = true;
            }

            // Jika belum diterima dan ada pilihan 2, coba pilihan 2
            if (!$accepted && $student->choice_2) {
                $choice2MajorName = $student->choice_2;
                if (isset($majorsCurrentQuota[$choice2MajorName]) && $majorsCurrentQuota[$choice2MajorName] > 0) {
                    $student->status = 'DITERIMA';
                    $student->accepted_major = $choice2MajorName;
                    $majorsCurrentQuota[$choice2MajorName]--; // Kurangi kuota
                    $accepted = true;
                }
            }

            // Jika masih belum diterima setelah kedua pilihan
            if (!$accepted) {
                $student->status = 'TIDAK DITERIMA';
                $student->accepted_major = null;
            }

            $student->save();
        }

        return redirect()->route('selection.results')->with('success', 'Proses seleksi selesai.');
    }

    public function results()
    {
        // Ambil siswa yang diterima dan tidak diterima (untuk tab di bawah)
        $acceptedStudents = Student::where('status', 'DITERIMA')->orderBy('accepted_major')->orderBy('final_score', 'desc')->get();
        $rejectedStudents = Student::where('status', 'TIDAK DITERIMA')->orderBy('final_score', 'desc')->get();

        // Hitung nilai tertinggi dan terendah per jurusan
        $majorStatistics = [];
        $majors = Major::all(); // Ambil semua jurusan untuk memastikan semua jurusan tercakup

        foreach ($majors as $major) {
            // !!! PENTING: Koleksi ini HANYA berisi siswa yang DITERIMA di jurusan spesifik ini !!!
            $acceptedInMajor = Student::where('status', 'DITERIMA')
                ->where('accepted_major', $major->name)
                ->orderBy('final_score', 'desc')
                ->get();

            // Maka perhitungan max, min, dan avg di bawah ini secara otomatis hanya dari siswa DITERIMA
            $maxScore = $acceptedInMajor->max('final_score');
            $minScore = $acceptedInMajor->min('final_score');
            $averageScore = $acceptedInMajor->avg('final_score'); // Ini sudah dari yang DITERIMA

            $majorStatistics[$major->name] = [
                'max_score'    => $maxScore ?? 'N/A',
                'min_score'    => $minScore ?? 'N/A',
                'average_score' => round($averageScore, 2) ?? 'N/A',
                'accepted_count' => $acceptedInMajor->count(),
                'quota'          => $major->quota,
            ];
        }

        return view('admin.selection.results', compact(
            'acceptedStudents',
            'rejectedStudents',
            'majorStatistics'
        ));
    }

    public function export(string $status = null)
    {
        $fileName = 'hasil_seleksi';
        $exportStatus = null;

        if ($status === 'accepted') {
            $fileName .= '_diterima';
            $exportStatus = 'DITERIMA';
        } elseif ($status === 'rejected') {
            $fileName .= '_tidak_diterima';
            $exportStatus = 'TIDAK DITERIMA';
        }

        $fileName .= '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new SelectionResultsExport($exportStatus), $fileName);
    }
}
