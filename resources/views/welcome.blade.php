<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Aplikasi Seleksi Murid Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .welcome-card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
        }

        .welcome-card h1 {
            color: #343a40;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .welcome-card p {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-group .btn {
            padding: 12px 25px;
            font-size: 1.1rem;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="welcome-card">
        <h1>Selamat Datang di Aplikasi Seleksi Penerimaan Murid Baru</h1>
        <p>
            Aplikasi ini dirancang untuk mempermudah proses seleksi penerimaan murid baru, mulai dari pengelolaan data
            siswa dan jurusan, hingga perhitungan status penerimaan berdasarkan nilai akhir dan kuota jurusan.
        </p>

        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
            <a href="{{ route('student.checkStatusForm') }}" class="btn btn-primary btn-lg">
                Cek Status Penerimaan
            </a>
            <a href="{{ url('/admin/majors') }}" class="btn btn-outline-secondary btn-lg">
                Masuk ke Dashboard Admin
            </a>
        </div>
        <p class="mt-4 text-muted"><small>Hak Cipta &copy; {{ date('Y') }} Aplikasi Seleksi Murid Baru</small></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
