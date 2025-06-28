<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection; // Untuk mengambil data dari koleksi
use Maatwebsite\Excel\Concerns\WithHeadings;   // Untuk menambahkan header kolom
use Maatwebsite\Excel\Concerns\WithMapping;    // Untuk memetakan data ke baris Excel
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Untuk otomatis menyesuaikan lebar kolom

class SelectionResultsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $statusFilter;

    public function __construct(string $statusFilter = null)
    {
        $this->statusFilter = $statusFilter;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data siswa yang DITERIMA dan TIDAK DITERIMA
        $query = Student::orderBy('accepted_major', 'asc')
            ->orderBy('final_score', 'desc');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        } else {
            // Default: ambil DITERIMA dan TIDAK DITERIMA
            $query->whereIn('status', ['DITERIMA', 'TIDAK DITERIMA']);
        }

        return $query->get();
    }

    /**
     * Menambahkan header untuk kolom Excel.
     * @return array
     */
    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Asal Sekolah',
            'Nilai Akhir',
            'Pilihan 1',
            'Pilihan 2',
            'Status Penerimaan',
            'Jurusan Diterima',
        ];
    }

    /**
     * Memetakan setiap objek Student ke dalam array baris untuk Excel.
     * @param mixed $student
     * @return array
     */
    public function map($student): array
    {
        return [
            $student->nisn,
            $student->name,
            $student->origin_school,
            $student->final_score,
            $student->choice_1,
            $student->choice_2 ?? '-', // Jika pilihan 2 kosong, tampilkan '-'
            $student->status,
            $student->accepted_major ?? '-', // Jika tidak ada jurusan diterima (misal TIDAK DITERIMA), tampilkan '-'
        ];
    }
}
