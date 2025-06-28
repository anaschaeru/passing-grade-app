@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Tambah Data Siswa Baru</h1>

    <div class="card p-4">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nisn" class="form-label">NISN</label>
                <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn"
                    value="{{ old('nisn') }}" required>
                @error('nisn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Siswa</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="origin_school" class="form-label">Asal Sekolah</label>
                <input type="text" class="form-control @error('origin_school') is-invalid @enderror" id="origin_school"
                    name="origin_school" value="{{ old('origin_school') }}" required>
                @error('origin_school')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="final_score" class="form-label">Nilai Akhir</label>
                <input type="number" step="0.01" class="form-control @error('final_score') is-invalid @enderror"
                    id="final_score" name="final_score" value="{{ old('final_score') }}" required min="0"
                    max="100">
                @error('final_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="choice_1" class="form-label">Pilihan 1</label>
                <select class="form-select @error('choice_1') is-invalid @enderror" id="choice_1" name="choice_1" required>
                    <option value="">Pilih Jurusan</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->name }}" {{ old('choice_1') == $major->name ? 'selected' : '' }}>
                            {{ $major->name }}</option>
                    @endforeach
                </select>
                @error('choice_1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="choice_2" class="form-label">Pilihan 2 (Opsional)</label>
                <select class="form-select @error('choice_2') is-invalid @enderror" id="choice_2" name="choice_2">
                    <option value="">Tidak Ada Pilihan 2</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->name }}" {{ old('choice_2') == $major->name ? 'selected' : '' }}>
                            {{ $major->name }}</option>
                    @endforeach
                </select>
                @error('choice_2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success me-2">Simpan</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
