<?php

namespace App\Imports;

use Throwable;
use App\Models\Student;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use App\Models\Major; // Perlu diimpor untuk validasi jurusan
use Maatwebsite\Excel\Concerns\WithValidation; // Untuk validasi data
use Maatwebsite\Excel\Concerns\WithBatchInserts; // Untuk insert batch (kinerja)
use Maatwebsite\Excel\Concerns\SkipsOnError;   // Untuk melewati baris yang error
use Maatwebsite\Excel\Concerns\WithChunkReading; // Untuk membaca chunk (kinerja)
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Untuk membaca header/judul kolom
use Maatwebsite\Excel\Concerns\SkipsOnFailure; // Untuk melewati baris yang gagal validasi

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!isset($row['nisn']) || empty($row['nisn'])) {
            return null;
        }

        return new Student([
            'nisn'          => $row['nisn'],
            'name'          => $row['nama_siswa'],
            'origin_school' => $row['asal_sekolah'],
            'final_score'   => $row['nilai_akhir'],
            'choice_1'      => $row['pilihan_1'],
            'choice_2'      => $row['pilihan_2'] ?? null,
            'status'        => 'BELUM DIPROSES',
            'accepted_major' => null,
        ]);
    }

    /**
     * Definisikan aturan validasi untuk setiap kolom.
     * Nama kunci array harus sesuai dengan nama header di Excel.
     */
    public function rules(): array
    {
        $majorNames = Major::pluck('name')->toArray();
        // Pastikan array ini tidak kosong, jika kosong validasi 'in' akan error
        if (empty($majorNames)) {
            // Berikan nilai default atau tangani jika tidak ada jurusan
            $majorNames = ['_DUMMY_MAJOR_']; // Dummy untuk mencegah error, tapi validasi akan gagal
        }

        // PERBAIKAN DI SINI: Pisahkan aturan menjadi elemen array terpisah
        return [
            'nisn'         => ['required', 'string', 'unique:students,nisn'],
            'nama_siswa'   => ['required', 'string'],
            'asal_sekolah' => ['required', 'string'],
            'nilai_akhir'  => ['required', 'numeric', 'min:0', 'max:100'],
            // Pilihan 1: 'required' DAN 'in:...'
            'pilihan_1'    => ['required', 'in:' . implode(',', $majorNames)],
            // Pilihan 2: 'nullable' DAN 'in:...' DAN 'different:...'
            'pilihan_2'    => ['nullable', 'in:' . implode(',', $majorNames), 'different:pilihan_1'],
        ];
    }

    /**
     * Custom pesan error validasi (opsional).
     */
    public function customValidationMessages()
    {
        return [
            'nisn.unique'         => 'NISN :attribute sudah terdaftar.',
            'pilihan_1.in'        => 'Pilihan 1 (:attribute) tidak valid, jurusan tidak ditemukan.',
            'pilihan_2.in'        => 'Pilihan 2 (:attribute) tidak valid, jurusan tidak ditemukan.',
            'pilihan_2.different' => 'Pilihan 2 tidak boleh sama dengan Pilihan 1.',
            'nilai_akhir.min'     => 'Nilai akhir harus antara 0 dan 100.',
            'nilai_akhir.max'     => 'Nilai akhir harus antara 0 dan 100.',
        ];
    }

    public function onError(Throwable $error)
    {
        Log::error('Error saat mengimpor data siswa: ' . $error->getMessage());
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $row = $failure->row();
            $attribute = $failure->attribute();
            $errors = implode(', ', $failure->errors());
            Session::push('import_errors', "Baris {$row}: Kolom '{$attribute}' - {$errors}");
            Log::warning("Gagal validasi baris {$row}: {$errors}");
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
