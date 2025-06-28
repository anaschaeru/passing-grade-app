@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-clipboard-check me-2"></i>Proses Seleksi Penerimaan</h1>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title fw-medium mb-4"><i class="bi bi-info-circle me-2"></i>Informasi Pra-Seleksi</h5>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="alert alert-light border">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-people-fill fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">Total Siswa Terdaftar</h6>
                                <p class="mb-0 fs-5 fw-bold">{{ $totalStudents }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="fw-medium mb-3"><i class="bi bi-bookmark-check me-2"></i>Daftar Jurusan & Kuota</h6>
            <div class="row g-3 mb-4">
                @forelse ($majors as $major)
                    <div class="col-md-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-medium">{{ $major->name }}</span>
                                    <span class="badge bg-primary rounded-pill">Kuota: {{ $major->quota }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center text-muted">
                            <i class="bi bi-bookmark-x me-2"></i> Belum ada jurusan yang terdaftar
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="alert alert-warning bg-warning bg-opacity-10 border border-warning border-opacity-25">
                <div class="d-flex">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2 mt-1"></i>
                    <div>
                        <strong class="d-block mb-2">Proses Seleksi</strong>
                        <ul class="mb-0 ps-3">
                            <li>Siswa akan diurutkan berdasarkan nilai akhir (tertinggi ke terendah)</li>
                            <li>Penempatan dilakukan sesuai pilihan 1 atau 2 jika kuota tersedia</li>
                            <li class="text-danger">Semua status siswa akan di-reset menjadi 'BELUM DIPROSES'</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route('selection.process') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-danger px-4 py-2"
                    onclick="return confirm('Apakah Anda yakin ingin memulai proses seleksi? Tindakan ini akan me-reset status semua siswa dan tidak dapat dibatalkan.')">
                    <i class="bi bi-play-fill me-2"></i> Mulai Proses Seleksi
                </button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-medium mb-3"><i class="bi bi-file-earmark-bar-graph me-2"></i>Hasil Seleksi</h5>
            <p class="text-muted mb-4">Setelah proses seleksi selesai, Anda dapat melihat hasilnya di halaman berikut:</p>
            <a href="{{ route('selection.results') }}" class="btn btn-outline-primary px-4">
                <i class="bi bi-eye me-2"></i> Lihat Hasil Seleksi
            </a>
        </div>
    </div>
@endsection
