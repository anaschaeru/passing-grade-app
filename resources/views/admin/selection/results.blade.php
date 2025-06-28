@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-clipboard2-check me-2"></i>Hasil Seleksi Penerimaan</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('selection.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-people me-1"></i> Data Siswa
            </a>
        </div>
    </div>

    <div class="mb-3">
        <h2 class="h6 fw-bold mb-2"><i class="bi bi-graph-up me-1"></i>Statistik Nilai per Jurusan</h2>
    </div>

    <div class="row g-2">
        @forelse ($majorStatistics as $majorName => $stats)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-xs h-100">
                    <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bookmark-check text-primary me-2"></i>
                            <span class="fw-medium small">{{ Str::limit($majorName, 22) }}</span>
                        </div>
                        <span class="badge bg-{{ $stats['accepted_count'] > 0 ? 'primary' : 'secondary' }} py-1 px-2">
                            {{ $stats['accepted_count'] }}/{{ $stats['quota'] }}
                        </span>
                    </div>
                    <div class="card-body p-2">
                        @if ($stats['accepted_count'] > 0)
                            <div class="d-flex flex-wrap justify-content-between small">
                                <div class="d-flex align-items-center mb-1 px-2">
                                    <i class="bi bi-arrow-up-right text-success me-1"></i>
                                    <span class="text-muted me-1">Tertinggi:</span>
                                    <span class="fw-bold">{{ $stats['max_score'] }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 px-2">
                                    <i class="bi bi-arrow-down-right text-danger me-1"></i>
                                    <span class="text-muted me-1">Terendah:</span>
                                    <span class="fw-bold">{{ $stats['min_score'] }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 px-2">
                                    <i class="bi bi-calculator text-primary me-1"></i>
                                    <span class="text-muted me-1">Rata-rata:</span>
                                    <span class="fw-bold">{{ $stats['average_score'] }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-1 text-muted small">
                                <i class="bi bi-people d-block fs-4 mb-1"></i>
                                Belum ada siswa
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center py-2 small">
                    <i class="bi bi-info-circle me-1"></i> Belum ada data statistik jurusan
                </div>
            </div>
        @endforelse
    </div>
    <div class="card border-0 shadow-sm mb-4 mt-4">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-tabs-card" id="resultsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3" id="accepted-tab" data-bs-toggle="tab"
                        data-bs-target="#accepted" type="button">
                        <i class="bi bi-check-circle me-2"></i> Diterima ({{ $acceptedStudents->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected"
                        type="button">
                        <i class="bi bi-x-circle me-2"></i> Tidak Diterima ({{ $rejectedStudents->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content p-3">
                <div class="tab-pane fade show active" id="accepted" role="tabpanel">
                    <div class="table-responsive">
                        {{-- Tambahkan ID untuk DataTables --}}
                        <table class="table table-hover align-middle" id="acceptedStudentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="120">NISN</th>
                                    <th>Nama Siswa</th>
                                    <th width="100">Nilai</th>
                                    <th width="200">Jurusan</th>
                                    <th width="200">Pilihan 1</th>
                                    <th width="200">Pilihan 2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($acceptedStudents as $student)
                                    <tr>
                                        <td>{{ $student->nisn }}</td>
                                        <td>
                                            <strong>{{ $student->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $student->final_score }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="bi bi-check-circle me-1"></i> {{ $student->accepted_major }}
                                            </span>
                                        </td>
                                        <td class="small">{{ $student->choice_1 }}</td>
                                        <td class="small">{{ $student->choice_2 ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-emoji-frown display-6 d-block mb-2"></i>
                                            Belum ada siswa yang diterima
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="rejected" role="tabpanel">
                    <div class="table-responsive">
                        {{-- Tambahkan ID untuk DataTables --}}
                        <table class="table table-hover align-middle" id="rejectedStudentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="120">NISN</th>
                                    <th>Nama Siswa</th>
                                    <th width="100">Nilai</th>
                                    <th width="200">Pilihan 1</th>
                                    <th width="200">Pilihan 2</th>
                                    <th width="120">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rejectedStudents as $student)
                                    <tr>
                                        <td>{{ $student->nisn }}</td>
                                        <td>
                                            <strong>{{ $student->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $student->final_score }}</span>
                                        </td>
                                        <td class="small">{{ $student->choice_1 }}</td>
                                        <td class="small">{{ $student->choice_2 ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="bi bi-x-circle me-1"></i> {{ $student->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-emoji-smile display-6 d-block mb-2"></i>
                                            Tidak ada siswa yang ditolak
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs-card {
            border-bottom: 1px solid #dee2e6;
        }

        .nav-tabs-card .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs-card .nav-link.active {
            color: #0d6efd;
            background-color: transparent;
            border-bottom: 2px solid #0d6efd;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .shadow-xs {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables untuk tabel siswa Diterima
            $('#acceptedStudentsTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/id.json"
                },
                "order": [
                    [2, "desc"]
                ],
                "pageLength": 10,
                "responsive": true // Tambahkan ini jika Anda ingin fitur responsif DataTables
            });

            // Inisialisasi DataTables untuk tabel siswa Tidak Diterima
            $('#rejectedStudentsTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/id.json"
                },
                "order": [
                    [2, "desc"]
                ],
                "pageLength": 10,
                "responsive": true // Tambahkan ini jika Anda ingin fitur responsif DataTables
            });

            // MODIFIKASI BAGIAN INI
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                // Dapatkan semua instance DataTables yang terlihat
                var table = $($.fn.dataTable.tables(true)).DataTable();

                // Hanya sesuaikan kolom jika DataTables ada
                if (table) {
                    table.columns.adjust();

                    // Panggil responsive.recalc() hanya jika ekstensi 'responsive' diaktifkan
                    // dan objek responsive ada pada instance DataTables.
                    if (table.responsive && typeof table.responsive.recalc === 'function') {
                        table.responsive.recalc();
                    }
                }
            });
        });
    </script>
@endpush
