@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-upload me-2"></i>Upload Data Siswa</h1>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25">
                <div class="d-flex">
                    <i class="bi bi-info-circle-fill text-info me-2 mt-1"></i>
                    <div>
                        <strong class="d-block mb-2">Format File Excel</strong>
                        <p class="mb-2">Gunakan header kolom berikut (huruf kecil, underscore sebagai pemisah):</p>
                        <code class="d-block mb-2">nisn | nama_siswa | asal_sekolah | nilai_akhir | pilihan_1 | pilihan_2
                            (opsional)</code>
                        <p class="mb-0 small">Pastikan nama jurusan di kolom pilihan sesuai dengan yang ada di sistem.</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Terjadi Kesalahan</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('import_errors'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Data Terimpor dengan Peringatan</strong>
                    <p class="mb-1 mt-2">Beberapa baris gagal divalidasi:</p>
                    <ul class="mb-0">
                        @foreach (session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="file" class="form-label fw-medium">Pilih File Excel (.xlsx/.xls)</label>
                    <div class="input-group">
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file"
                            name="file" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Upload
                        </button>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text small">Maksimal ukuran file: 5MB</div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-file-earmark-excel me-2"></i>Template File</h5>
        </div>
        <div class="card-body">
            <p>Unduh template untuk memastikan format file yang benar:</p>
            <a href="#" class="btn btn-outline-success">
                <i class="bi bi-download me-1"></i> Download Template Excel
            </a>
        </div>
    </div>
@endsection
