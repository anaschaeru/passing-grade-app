@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-people me-2"></i>Daftar Siswa</h1>

        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-success dropdown-toggle" type="button" id="exportDropdown"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('selection.export') }}"><i
                                class="bi bi-file-earmark-excel me-2"></i>Semua Hasil</a></li>
                    <li><a class="dropdown-item" href="{{ route('selection.export', 'accepted') }}"><i
                                class="bi bi-check-circle me-2"></i>Diterima</a></li>
                    <li><a class="dropdown-item" href="{{ route('selection.export', 'rejected') }}"><i
                                class="bi bi-x-circle me-2"></i>Tidak Diterima</a></li>
                </ul>
            </div>

            <a href="{{ route('students.import.form') }}" class="btn btn-outline-primary">
                <i class="bi bi-upload me-1"></i>Import
            </a>

            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Tambah
            </a>
            <div>
                {{-- Tombol Hapus Semua Data Siswa --}}
                <form action="{{ route('students.clear_all') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('APAKAH ANDA YAKIN INGIN MENGHAPUS SEMUA DATA SISWA? TINDAKAN INI TIDAK DAPAT DIBATALKAN DAN SEMUA DATA SISWA AKAN HILANG PERMANEN!')">
                        Hapus Semua Data Siswa
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if (session('import_errors'))
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Data Terimpor dengan Peringatan</strong>
            <p class="mb-1">Beberapa baris data gagal divalidasi:</p>
            <ul class="mb-1">
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">ID</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Sekolah</th>
                            <th width="100">Nilai</th>
                            <th width="120">Pilihan 1</th>
                            <th width="120">Pilihan 2</th>
                            <th width="120">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td class="text-muted">#{{ $student->id }}</td>
                                <td>{{ $student->nisn }}</td>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                    @if ($student->accepted_major)
                                        <div class="text-success small">
                                            <i class="bi bi-check-circle-fill"></i> {{ $student->accepted_major }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $student->origin_school }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $student->final_score }}</span>
                                </td>
                                <td class="small">{{ $student->choice_1 }}</td>
                                <td class="small">{{ $student->choice_2 ?? '-' }}</td>
                                <td>
                                    @if ($student->status == 'DITERIMA')
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="bi bi-check-circle me-1"></i> Diterima
                                        </span>
                                    @elseif($student->status == 'TIDAK DITERIMA')
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <i class="bi bi-x-circle me-1"></i> Tidak Diterima
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <i class="bi bi-dash-circle me-1"></i> {{ $student->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('students.edit', $student->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Hapus data {{ $student->name }}?')"
                                                title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-people display-6 d-block mb-2"></i>
                                    Belum ada data siswa
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
