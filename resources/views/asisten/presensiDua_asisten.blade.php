<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Attendance History - Asisten</title>
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

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th,
        .history-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .history-table th {
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
        }

        .history-table tbody tr:hover {
            background: #f9fafb;
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

        .detail-btn.pink {
            background: #fce7f3;
            color: #be185d;
        }

        .detail-btn.pink:hover {
            background: #fbcfe8;
            transform: scale(1.02);
        }

        .detail-btn.green {
            background: #dcfce7;
            color: #166534;
        }

        .detail-btn.green:hover {
            background: #bbf7d0;
            transform: scale(1.02);
        }

        .detail-btn.orange {
            background: #ffedd5;
            color: #c2410c;
        }

        .detail-btn.orange:hover {
            background: #fed7aa;
            transform: scale(1.02);
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

        .praktikum-section {
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .praktikum-header {
            background: #f1f5f9;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .praktikum-header:hover {
            background: #e2e8f0;
        }

        .praktikum-header h3 {
            margin: 0;
            color: #1e293b;
        }

        .praktikum-header .badge {
            background: #6366f1;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .praktikum-content {
            display: none;
            padding: 20px;
        }

        .praktikum-content.open {
            display: block;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sub-table th,
        .sub-table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #f3f4f6;
        }

        .sub-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 500;
        }

        .stats-badge {
            display: inline-flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .stat {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .stat-hadir {
            background: #bbf7d0;
            color: #166534;
        }

        .stat-izin {
            background: #fde68a;
            color: #92400e;
        }

        .stat-sakit {
            background: #bfdbfe;
            color: #1e40af;
        }

        .stat-alpha {
            background: #fecaca;
            color: #991b1b;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .toggle-icon {
            font-size: 18px;
            transition: transform 0.2s;
        }

        .praktikum-header.open .toggle-icon {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {

            .history-table,
            .sub-table {
                font-size: 12px;
            }

            .history-table th,
            .history-table td,
            .sub-table th,
            .sub-table td {
                padding: 8px;
            }

            .detail-btn {
                padding: 6px 12px;
                font-size: 11px;
            }

            .stats-badge {
                flex-direction: column;
                gap: 4px;
            }

            .praktikum-header h3 {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    @include('asisten/partials/sidebar')

    <main class="main-content">

        <div class="header">
            <h2>Riwayat Presensi</h2>
        </div>

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="table-container">
            @forelse($groupedPertemuans as $praktikumName => $data)
                <div class="praktikum-section">
                    <div class="praktikum-header" onclick="toggleSection(this)">
                        <h3>
                            <i class="fas fa-flask"></i>
                            {{ $praktikumName }}
                            <span class="badge">{{ $data['kode_praktikum'] }}</span>
                        </h3>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                    <div class="praktikum-content">
                        <table class="sub-table">
                            <thead>
                                <tr>
                                    <th>Pertemuan Ke</th>
                                    <th>Nama Pertemuan</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Statistik Kehadiran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['pertemuans'] as $pertemuan)
                                    <tr>
                                        <td>{{ $pertemuan['pertemuan_ke'] }}</td>
                                        <td>{{ $pertemuan['nama_pertemuan'] }}</td>
                                        <td>{{ $pertemuan['hari'] }}</td>
                                        <td>{{ $pertemuan['jam_mulai'] }} - {{ $pertemuan['jam_selesai'] }}</td>
                                        <td class="stats-badge">
                                            <span class="stat stat-hadir">Hadir: {{ $pertemuan['hadir'] }}</span>
                                            <span class="stat stat-izin">Izin: {{ $pertemuan['izin'] }}</span>
                                            <span class="stat stat-sakit">Sakit: {{ $pertemuan['sakit'] }}</span>
                                            <span class="stat stat-alpha">Alpha: {{ $pertemuan['alpha'] }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('detailRiwayatPresensi') }}" method="GET">
                                                <input type="hidden" name="pertemuan_id"
                                                    value="{{ $pertemuan['id'] }}">
                                                <input type="hidden" name="praktikum_id"
                                                    value="{{ $data['id_praktikum'] }}">
                                                <button type="submit" class="detail-btn purple">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-3x"></i>
                    <h4>Belum Ada Data Presensi</h4>
                    <p>Belum ada pertemuan praktikum yang tersedia untuk asisten ini.</p>
                    <form action="{{ route('asisten.presensi.record.index') }}" method="GET">
                        <button type="submit" class="detail-btn purple" style="margin-top: 15px;">
                            <i class="fas fa-plus-circle"></i> Record Attendance
                        </button>
                    </form>
                </div>
            @endforelse
        </div>

    </main>

    <script>
        function toggleSection(element) {
            element.classList.toggle('open');
            const content = element.nextElementSibling;
            content.classList.toggle('open');
        }

        // Open first section by default
        document.addEventListener('DOMContentLoaded', function() {
            const firstSection = document.querySelector('.praktikum-header');
            if (firstSection) {
                firstSection.classList.add('open');
                const firstContent = firstSection.nextElementSibling;
                if (firstContent) {
                    firstContent.classList.add('open');
                }
            }
        });
    </script>

</body>

</html>
