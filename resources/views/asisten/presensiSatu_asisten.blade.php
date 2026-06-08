<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekam Presensi - Asisten</title>
    <link rel="stylesheet" href="{{ asset('asisten_css/presensi_asisten.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table th {
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
        }

        .data-table tbody tr:hover {
            background: #f9fafb;
        }

        .actions-cell {
            text-align: center;
        }

        .detail-btn {
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .detail-btn.purple {
            background: #ede9fe;
            color: #6d28d9;
        }

        .detail-btn.purple:hover {
            background: #ddd6fe;
            transform: scale(1.02);
        }

        .btn.history {
            background: #fbcfe8;
            color: #be185d;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn.history:hover {
            background: #f9a8d4;
        }

        .empty-row {
            text-align: center;
            padding: 40px !important;
            color: #999;
        }

        .empty-row i {
            display: block;
            margin-bottom: 10px;
        }

        .empty-row p {
            margin: 5px 0;
        }

        .empty-row small {
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 8px;
            }

            .detail-btn {
                padding: 6px 12px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

    <button onclick="toggleSidebar()" class="menu-toggle">☰</button>
    @include('asisten/partials/sidebar')

    <main class="main-content">

        <div class="header">
            <h2>Rekam Presensi</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Praktikum</th>
                        <th>Nama Praktikum</th>
                        <th>Pertemuan Ke</th>
                        <th>Nama Pertemuan</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($praktikumList as $item)
                        <tr>
                            <td>{{ $item['no'] }}</td>
                            <td>{{ $item['kode_praktikum'] }}</td>
                            <td>{{ $item['nama_praktikum'] }}</td>
                            <td>{{ $item['pertemuan_ke'] }}</td>
                            <td>{{ Str::limit($item['nama_pertemuan'], 30) }}</td>
                            <td>{{ $item['hari'] }}</td>
                            <td>{{ $item['jam_mulai'] }} - {{ $item['jam_selesai'] }}</td>
                            <td class="actions-cell">
                                <form action="{{ route('detailPresensi') }}" method="GET">
                                    <input type="hidden" name="pertemuan_id" value="{{ $item['pertemuan_id'] }}">
                                    <input type="hidden" name="praktikum_id" value="{{ $item['id'] }}">
                                    <button type="submit" class="detail-btn purple">
                                        <i class="fas fa-check-circle"></i> Record
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-row">
                                <i class="fas fa-calendar-times fa-2x"></i>
                                <p>Belum ada pertemuan praktikum yang tersedia</p>
                                <small>Hubungi koordinator untuk menambahkan jadwal pertemuan</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }
    </script>

</body>

</html>
