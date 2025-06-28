@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Edit Jurusan: {{ $major->name }}</h1>

    <div class="card p-4">
        <form action="{{ route('majors.update', $major->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Jurusan</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $major->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="quota" class="form-label">Kuota</label>
                <input type="number" class="form-control @error('quota') is-invalid @enderror" id="quota"
                    name="quota" value="{{ old('quota', $major->quota) }}" required min="0">
                @error('quota')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success me-2">Update</button>
            <a href="{{ route('majors.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
