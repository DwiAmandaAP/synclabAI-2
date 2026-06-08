<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Presensi Praktikan - {{ $pertemuan->nama_pertemuan }}</title>
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

        .info-box {
            display: flex;
            justify-content: space-between;
            background: #ede9fe;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            color: #4c1d95;
            flex-wrap: wrap;
            gap: 15px;
        }

        .info-box div {
            flex: 1;
        }

        .info-box p {
            margin: 5px 0;
        }

        .status-btn {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .status-option {
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.2s ease;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-option.active {
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .status-option.hadir {
            background: #bbf7d0;
            color: #166534;
        }

        .status-option.izin {
            background: #fde68a;
            color: #92400e;
        }

        .status-option.sakit {
            background: #bfdbfe;
            color: #1e40af;
        }

        .status-option.alpha {
            background: #fecaca;
            color: #991b1b;
        }

        .status-option:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        .save-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            padding: 15px;
        }

        .btn-save {
            background: #86efac;
            color: #14532d;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save:hover {
            background: #4ade80;
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
            font-weight: 600;
        }

        .attendance-table tbody tr:hover {
            background: #f9fafb;
        }

        .empty-row {
            text-align: center;
            padding: 40px !important;
            color: #999;
        }

        @media (max-width: 768px) {
            .info-box {
                flex-direction: column;
            }

            .status-btn {
                flex-direction: column;
                align-items: center;
            }

            .status-option {
                width: 100%;
                justify-content: center;
            }

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

    <button onclick="toggleSidebar()" class="menu-toggle">☰</button>
    @include('asisten/partials/sidebar')

    <main class="main-content">

        <div class="header">
            <h2>Student Attendance</h2>
            <div class="actions">
                <form action="{{ route('riwayatPresensi') }}" method="GET">
                    <button type="submit" class="btn history">
                        <i class="fas fa-clock"></i> History
                    </button>
                </form>
            </div>
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

        <div class="info-box">
            <div>
                <p><b>Mata Kuliah :</b> {{ $pertemuan->jadwal->praktikum->nama_praktikum }}</p>
                <p><b>Pertemuan :</b> {{ $pertemuan->pertemuan_ke }} - {{ $pertemuan->nama_pertemuan }}</p>
            </div>
            <div>
                <p><b>Praktikum :</b> {{ $pertemuan->jadwal->praktikum->kode_praktikum }}</p>
                <p><b>Hari/Tanggal :</b> {{ $pertemuan->jadwal->hari }},
                    {{ Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p><b>Jam :</b> {{ $pertemuan->jadwal->jam_mulai }} - {{ $pertemuan->jadwal->jam_selesai }}</p>
                <p><b>Ruangan :</b> {{ $pertemuan->jadwal->laboratorium->nama_laboratorium ?? 'N/A' }}</p>
            </div>
        </div>

        <form action="{{ route('savePresensi') }}" method="POST" id="attendanceForm">
            @csrf
            <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">
            <input type="hidden" name="praktikum_id" value="{{ $praktikumId }}">

            <div class="table-container">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceData as $data)
                            <tr>
                                <td>{{ $data['nim'] }}</td>
                                <td>{{ $data['nama'] }}</td>
                                <td class="status-btn">
                                    <button type="button"
                                        class="status-option hadir {{ $data['status'] == 'Hadir' ? 'active' : '' }}"
                                        onclick="setStatus(this, 'Hadir', {{ $data['user_id'] }})">
                                        <i class="fas fa-check-circle"></i> Hadir
                                    </button>
                                    <button type="button"
                                        class="status-option izin {{ $data['status'] == 'Izin' ? 'active' : '' }}"
                                        onclick="setStatus(this, 'Izin', {{ $data['user_id'] }})">
                                        <i class="fas fa-user-clock"></i> Izin
                                    </button>
                                    <button type="button"
                                        class="status-option sakit {{ $data['status'] == 'Sakit' ? 'active' : '' }}"
                                        onclick="setStatus(this, 'Sakit', {{ $data['user_id'] }})">
                                        <i class="fas fa-thermometer-half"></i> Sakit
                                    </button>
                                    <button type="button"
                                        class="status-option alpha {{ $data['status'] == 'Alpha' ? 'active' : '' }}"
                                        onclick="setStatus(this, 'Alpha', {{ $data['user_id'] }})">
                                        <i class="fas fa-times-circle"></i> Alpha
                                    </button>
                                    <input type="hidden" name="attendance[{{ $data['user_id'] }}]"
                                        id="status_{{ $data['user_id'] }}" value="{{ $data['status'] }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-row">
                                    <i class="fas fa-users fa-2x"></i>
                                    <p>Belum ada mahasiswa terdaftar untuk praktikum ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if (count($attendanceData) > 0)
                    <div class="save-container">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Simpan Presensi
                        </button>
                    </div>
                @endif
            </div>
        </form>

    </main>

    <script>
        function setStatus(button, status, userId) {
            const btnGroup = button.parentElement;
            const allButtons = btnGroup.querySelectorAll('.status-option');
            allButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const hiddenInput = document.getElementById(`status_${userId}`);
            if (hiddenInput) {
                hiddenInput.value = status;
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }
    </script>

</body>

</html>
