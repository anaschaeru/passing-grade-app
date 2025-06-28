<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Seleksi Murid App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.bootstrap5.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* Your custom CSS here */
        .nav-tabs-card .nav-link {
            border-bottom: none;
            /* Remove bottom border for tab links */
            background-color: #f8f9fa;
            /* Light background for tabs */
            border-radius: .5rem .5rem 0 0;
            /* Rounded top corners */
            margin-right: 0.25rem;
            /* Small space between tabs */
            border: 1px solid #dee2e6;
            /* Add a subtle border */
            border-bottom: none;
        }

        .nav-tabs-card .nav-link.active {
            background-color: #fff;
            /* White background for active tab */
            border-color: #dee2e6;
            border-bottom-color: transparent;
            /* Hide bottom border for active tab */
            color: #0d6efd !important;
            /* Primary color for active text */
            font-weight: bold;
        }

        .nav-tabs-card {
            border-bottom: none;
            /* Remove default nav-tabs bottom border */
            background-color: #f8f9fa;
            border-radius: .5rem .5rem 0 0;
            padding-top: 0.5rem;
            padding-left: 0.5rem;
        }

        .card-body.p-0 {
            border-radius: .5rem;
            overflow: hidden;
            /* Ensure rounded corners apply to content */
        }

        .table-responsive {
            padding: 1rem;
            /* Add padding inside the tab content */
        }

        /* Adjust for DataTables alignment if needed */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 0.8em !important;
            /* Adjust padding for pagination buttons */
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .sidebar-collapsed {
            width: 80px;
        }

        .sidebar-collapsed .nav-link span {
            display: none;
        }

        .sidebar-collapsed .nav-link {
            text-align: center;
        }

        .sidebar-collapsed .logo-text {
            display: none;
        }

        .sidebar-collapsed .logo-icon {
            margin-right: 0;
        }

        .nav-link {
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .content-area {
            flex: 1;
            padding: 20px;
        }

        .toggle-btn {
            cursor: pointer;
        }
    </style>
</head>

<body class="d-flex inter-regular">
    <!-- Sidebar -->
    <div class="bg-light sidebar" id="sidebar">
        <div class="p-3 border-bottom d-flex align-items-center">
            <i class="bi bi-speedometer2 fs-4 me-3 logo-icon"></i>
            <span class="fs-5 fw-bold logo-text">Admin Panel</span>
        </div>
        <div class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/admin/majors') }}">
                        <i class="bi bi-house-door me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('majors.index') }}">
                        <i class="bi bi-book me-2"></i>
                        <span>Jurusan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('students.index') }}">
                        <i class="bi bi-people me-2"></i>
                        <span>Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('selection.index') }}">
                        <i class="bi bi-clipboard-check me-2"></i>
                        <span>Seleksi</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="d-flex flex-column w-100">
        <header class="bg-white shadow-sm p-3 d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-list toggle-btn fs-4 me-3" id="toggleSidebar"></i>
                <span class="fs-5 fw-bold">Seleksi Murid App</span>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                    id="dropdownUser" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <span>Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </header>

        <main class="content-area">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.1/js/responsive.bootstrap5.min.js"></script>
    @stack('scripts')


    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
        });
    </script>
</body>

</html>
