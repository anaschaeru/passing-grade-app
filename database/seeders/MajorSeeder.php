<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Major; // Pastikan ini ada untuk mengimpor model Major

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel Major kosong sebelum menambahkan data baru (opsional, tapi disarankan)
        Major::truncate();

        $majors = [
            ['name' => 'Teknik Instalasi Tenaga Listrik', 'quota' => 105],
            ['name' => 'Teknik Otomasi Industri', 'quota' => 36],
            ['name' => 'Teknik Pemesinan', 'quota' => 72],
            ['name' => 'Teknik Perawatan Gedung', 'quota' => 33],
            ['name' => 'Teknik Mekanik Industri', 'quota' => 36],
            ['name' => 'Teknik Geomatika', 'quota' => 34],
            ['name' => 'Desain Pemodelan dan Informasi Bangunan', 'quota' => 72],
            ['name' => 'Rekayasa Perangkat Lunak', 'quota' => 72],
            ['name' => 'Desain Gambar Mesin', 'quota' => 36],
            ['name' => 'Teknik Konsturksi dan Perumahan', 'quota' => 35], // Perbaikan typo "Konsturksi"
        ];

        foreach ($majors as $major) {
            Major::create($major);
        }
    }
}
