<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Status Penerimaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-5 shadow-lg" style="max-width: 600px; width: 100%;">
        <h2 class="card-title text-center mb-4">Hasil Status Penerimaan</h2>

        <div class="mb-4">
            <p><strong>NISN:</strong> {{ $student->nisn }}</p>
            <p><strong>Nama Siswa:</strong> {{ $student->name }}</p>
            <p><strong>Asal Sekolah:</strong> {{ $student->origin_school }}</p>
            <p><strong>Nilai Akhir:</strong> {{ $student->final_score }}</p>
            <p><strong>Pilihan 1:</strong> {{ $student->choice_1 }}</p>
            <p><strong>Pilihan 2:</strong> {{ $student->choice_2 ?? '-' }}</p>
            <p><strong>Status Penerimaan:</strong>
                @if ($student->status == 'DITERIMA')
                    <span class="badge bg-success fs-5">{{ $student->status }}</span>
                @elseif($student->status == 'TIDAK DITERIMA')
                    <span class="badge bg-danger fs-5">{{ $student->status }}</span>
                @else
                    <span class="badge bg-secondary fs-5">{{ $student->status }}</span>
                    <br><small class="text-muted mt-2">Status akan diperbarui setelah proses seleksi dijalankan oleh
                        admin.</small>
                @endif
            </p>
            @if ($student->status == 'DITERIMA' && $student->accepted_major)
                <p><strong>Diterima di Jurusan:</strong> <span
                        class="badge bg-primary fs-5">{{ $student->accepted_major }}</span></p>
            @endif
        </div>

        <div class="d-grid">
            <a href="{{ route('student.checkStatusForm') }}" class="btn btn-secondary btn-lg">Cek Status Lain</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
