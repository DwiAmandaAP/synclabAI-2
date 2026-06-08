<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Kehadiran - {{ $pertemuan->nama_pertemuan }}</title>
    <link rel="stylesheet" href="{{ aIsset('asisten_css/presensi_asisten.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .detail-header {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .detail-header h3 {
            margin-bottom: 10px;
            color: #1e293b;
        }
        
        .detail-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .stat-card {
            background: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-card .count {
            font-size: 24px;
            font-weight: bold;
        }
        
        .stat-card.hadir .count { color: #166534; }
        .stat-card.izin .count { color: #92400e; }
        .stat-card.sakit .count { color: #1e40af; }
        .stat-card.alpha .count { color: #991b1b; }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .attendance-table th,
        .attendance-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .attendance-table th {
            background: #e0f2fe;
            color: #0369a1;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-hadir { background: #bbf7d0; color: #166534; }
        .status-izin { background: #fde68a; color: #92400e; }
        .status-sakit { background: #bfdbfe; color: #1e40af; }
        .status-alpha { background: #fecaca; color: #991b1b; }
        
        .btn-back {
            background: #6366f1;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
        }
        
        .btn-back:hover {
            background: #4f46e5;
        }
        
        @media (max-width: 768px) {
            .attendance-table {
                font-size: 12px;
            }
            .attendance-table th,
            .attendance-table td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>

    @include('asisten.partials.sidebar')

    <main class="main-content">
        <form action="{{ route('riwayatPresensi') }}" method="GET">
            <button type="submit" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke History
            </button>
        </form>

        <div class="detail-header">
            <h3>{{ $pertemuan->jadwal->praktikum->nama_praktikum }}</h3>
            <p><strong>Pertemuan {{ $pertemuan->pertemuan_ke }}:</strong> {{ $pertemuan->nama_pertemuan }}</p>
            <p><strong>Hari/Jam:</strong> {{ $pertemuan->jadwal->hari }}, {{ $pertemuan->jadwal->jam_mulai }} - {{ $pertemuan->jadwal->jam_selesai }}</p>
            <p><strong>Ruangan:</strong> {{ $pertemuan->jadwal->laboratorium->nama_laboratorium ?? 'N/A' }}</p>
            
            <div class="detail-stats">
                @php
                    $hadir = collect($attendanceData)->where('status', 'Hadir')->count();
                    $izin = collect($attendanceData)->where('status', 'Izin')->count();
                    $sakit = collect($attendanceData)->where('status', 'Sakit')->count();
                    $alpha = collect($attendanceData)->where('status', 'Alpha')->count();
                @endphp
                <div class="stat-card hadir">
                    <div class="count">{{ $hadir }}</div>
                    <div>Hadir</div>
                </div>
                <div class="stat-card izin">
                    <div class="count">{{ $izin }}</div>
                    <div>Izin</div>
                </div>
                <div class="stat-card sakit">
                    <div class="count">{{ $sakit }}</div>
                    <div>Sakit</div>
                </div>
                <div class="stat-card alpha">
                    <div class="count">{{ $alpha }}</div>
                    <div>Alpha</div>
                </div>
                <div class="stat-card">
                    <div class="count">{{ count($attendanceData) }}</div>
                    <div>Total</div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Status Kehadiran</th>
                        <th>Status Konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data['nim'] }}</td>
                            <td>{{ $data['nama'] }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($data['status']) }}">
                                    {{ $data['status'] }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($data['status_konfirmasi']) == 'dikonfirmasi' ? 'hadir' : 'alpha' }}">
                                    {{ $data['status_konfirmasi'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">
                                <i class="fas fa-users fa-2x"></i>
                                <p>Belum ada data presensi untuk pertemuan ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>