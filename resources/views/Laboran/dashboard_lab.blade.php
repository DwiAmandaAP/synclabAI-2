<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Praktikum</title>
    <link rel="stylesheet" href="{{ asset('lab_css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(4px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background-color: white;
            padding: 0;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            max-width: 550px;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
            position: relative;
        }

        .modal-content h2 {
            background: linear-gradient(135deg, #4a6cf7, #34d399);
            color: white;
            padding: 24px;
            margin: 0;
            border-radius: 16px 16px 0 0;
            font-size: 22px;
            font-weight: 600;
        }

        .close {
            position: absolute;
            top: 16px;
            right: 20px;
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            z-index: 10;
        }

        .close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .monitor-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .monitor-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            color: white;
        }

        .monitor-card.c1 {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .monitor-card.c2 {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .monitor-card.c3 {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .monitor-card.c4 {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
        }

        .monitor-card.c5 {
            background: linear-gradient(135deg, #fa709a, #fee140);
        }

        .monitor-card p {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
        }

        .monitor-card h3 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .monitor-bottom {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .monitor-card-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
        }

        .monitor-card-box h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
        }

        .quick-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .progress {
            width: 100px;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress .bar {
            height: 100%;
            border-radius: 3px;
        }

        .progress.yellow .bar {
            background: #f59e0b;
        }

        .progress.blue .bar {
            background: #3b82f6;
        }

        .progress.green .bar {
            background: #10b981;
        }

        .praktikum-item {
            margin-bottom: 15px;
        }

        .praktikum-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .notif-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .notif-box h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .notif-item {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .notif-item.info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .notif-item.warning {
            background: #fef3c7;
            color: #b45309;
        }

        .notif-item.success {
            background: #d1fae5;
            color: #047857;
        }

        .btn-refresh {
            background: #4a6cf7;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .monitor-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 768px) {
            .monitor-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .monitor-bottom {
                grid-template-columns: 1fr;
            }
        }

        /* System Status Styling */
        .system-status {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 13px;
        }

        .status-label {
            color: #666;
            font-weight: 500;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-success {
            background: #10b981;
        }

        .badge-info {
            background: #3b82f6;
        }

        .badge-warning {
            background: #f59e0b;
        }

        .badge-danger {
            background: #ef4444;
        }
    </style>
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main">
        <section class="card">
            <h2>Dashboard</h2>
            <div class="feature-box">
                <div style="padding: 20px; width: 100%;"></div>
            </div>

            <div class="stats-section">
                <div class="stat-card">
                    <h4>Total Praktikum</h4>
                    <p style="color: #4CAF50;">{{ $totalPraktikum }}</p>
                </div>
                <div class="stat-card">
                    <h4>Total Asisten</h4>
                    <p style="color: #2196F3;">{{ $totalAsisten }}</p>
                </div>
                <div class="stat-card">
                    <h4>Total Praktikan</h4>
                    <p style="color: #FF9800;">{{ $totalPraktikan }}</p>
                </div>
                <div class="stat-card">
                    <h4>Jadwal Hari Ini</h4>
                    <p style="color: #9C27B0;">{{ $jadwalHariIni }}</p>
                </div>
            </div>
        </section>

        <div class="grid">
            <div>
                <section class="card">
                    <h3>Kelola Praktikum</h3>
                    <input type="text" class="search" placeholder="🔍 Cari praktikum..." id="searchPraktikum">
                    <div class="praktikum-grid">
                        @forelse ($praktikums as $praktikum)
                            <div class="praktikum-card" data-nama="{{ $praktikum->nama_praktikum }}"
                                data-id="{{ $praktikum->id }}" data-kode="{{ $praktikum->kode_praktikum }}">
                                <h4>📘 {{ $praktikum->nama_praktikum }}</h4>
                                <p>{{ $praktikum->kode_praktikum }}</p>
                                <span class="status praktikum aktif">🟢 Aktif</span>

                                <div class="actions">
                                    <button class="btn-edit"
                                        data-praktikum='{{ json_encode($praktikum) }}'>Edit</button>
                                    <button class="btn-delete" data-id="{{ $praktikum->id }}"
                                        data-nama="{{ $praktikum->nama_praktikum }}">Hapus</button>
                                    <button class="btn-detail"
                                        data-praktikum='{{ json_encode($praktikum) }}'>Detail</button>
                                </div>
                            </div>
                        @empty
                            <p style="grid-column: 1/-1; text-align: center; padding: 20px; color: #999;">Tidak ada
                                praktikum</p>
                        @endforelse
                    </div>
                </section>

                <section class="card">
                    <h3>System Monitoring</h3>

                    <div class="monitor-summary">
                        <div class="monitor-card c1">
                            <p>Pendaftaran</p>
                            <h3>{{ $totalPendaftaran }}</h3>
                        </div>
                        <div class="monitor-card c2">
                            <p>Presensi</p>
                            <h3>{{ round($presensiHariIniPersen) }}%</h3>
                        </div>
                        <div class="monitor-card c3">
                            <p>Nilai</p>
                            <h3>{{ round($rataNilai, 1) }}</h3>
                        </div>
                        <div class="monitor-card c4">
                            <p>Jadwal</p>
                            <h3>{{ $bentrokCount }} Bentrok</h3>
                        </div>
                        <div class="monitor-card c5">
                            <p>Asisten</p>
                            <h3>{{ $totalAsisten }}</h3>
                        </div>
                    </div>

                    <div class="monitor-bottom">
                        <div class="monitor-card-box">
                            <h3>🔍 Status Sistem</h3>
                            
                            <div class="system-status">
                                <div class="status-item">
                                    <span class="status-label">Sistem</span>
                                    <span class="status-badge badge-success">🟢 Normal</span>
                                </div>
                                <div class="status-item">
                                    <span class="status-label">Kehadiran</span>
                                    <span class="status-badge" style="background: {{ $presensiHariIniPersen >= 80 ? '#10b981' : ($presensiHariIniPersen >= 50 ? '#f59e0b' : '#ef4444') }}">
                                        {{ round($presensiHariIniPersen, 0) }}%
                                    </span>
                                </div>
                                <div class="status-item">
                                    <span class="status-label">Nilai Rata-rata</span>
                                    <span class="status-badge" style="background: {{ $rataNilai >= 75 ? '#10b981' : ($rataNilai >= 60 ? '#f59e0b' : '#ef4444') }}">
                                        {{ round($rataNilai, 1) }}
                                    </span>
                                </div>
                                <div class="status-item">
                                    <span class="status-label">Jadwal Aktif</span>
                                    <span class="status-badge badge-info">{{ count($jadwalGrouped) }}  Jadwal Hari Ini</span>
                                </div>
                                <div class="status-item">
                                    <span class="status-label">Asisten Aktif</span>
                                    <span class="status-badge badge-info">{{ $asistenAktif->count() }} Orang</span>
                                </div>
                                <div class="status-item">
                                    <span class="status-label">Jadwal Bentrok</span>
                                    <span class="status-badge" style="background: {{ $bentrokCount > 0 ? '#ef4444' : '#10b981' }}">
                                        {{ $bentrokCount > 0 ? '🔴 ' . $bentrokCount : '🟢 Normal' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notif-box">
                        <h3>Selamat siang, {{ Auth::user()->nama ?? 'User' }} 👋</h3>
                        @forelse ($errors as $notif)
                            <div class="notif-item {{ $notif['type'] }}">
                                {{ $notif['message'] }}
                            </div>
                        @empty
                            <div class="notif-item info">
                                📅 Semua sistem berjalan normal.
                            </div>
                        @endforelse
                    </div>

                    <div class="monitor-footer">
                        <span>Last update: {{ $lastUpdate }}</span>
                        <button class="btn-refresh" onclick="window.location.reload()">Refresh</button>
                    </div>
                </section>

                <section class="card">
                    <h3>Laporan</h3>

                    <div class="filter-bar">
                        <select id="filterPraktikumLaporan">
                            <option value="">Semua Praktikum</option>
                            @foreach ($praktikums as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_praktikum }}</option>
                            @endforeach
                        </select>
                        <select id="filterSemesterLaporan">
                            <option value="">Semester</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                        </select>
                        <input type="date" id="filterDateLaporan">
                    </div>

                    <div class="laporan-grid">
                        <div class="laporan-card">
                            <h4>📊 Rekap Nilai</h4>
                            <div class="stats">
                                <div><strong>{{ round($rataNilai, 2) }}</strong><span>Rata-rata</span></div>
                                <div><strong>{{ $nilaiTertinggi }}</strong><span>Tertinggi</span></div>
                                <div><strong>{{ $nilaiTerendah }}</strong><span>Terendah</span></div>
                            </div>
                            <div class="actions">
                                <button class="btn-detail" onclick="showDetailNilai()">Detail</button>
                                <button class="btn-pdf" onclick="exportLaporan('nilai', 'pdf')">PDF</button>
                                <button class="btn-excel" onclick="exportLaporan('nilai', 'excel')">Excel</button>
                            </div>
                        </div>

                        <div class="laporan-card">
                            <h4>📊 Rekap Laporan</h4>
                            <div class="stats">
                                <div><strong>{{ round($rataPengumpulan, 1) }}%</strong><span>Terkumpul</span></div>
                                <div><strong>{{ $laporanTerkumpul }}</strong><span>dari {{ $totalLaporan }}</span>
                                </div>
                                <div><strong>{{ $laporanTerlambat }}</strong><span>Terlambat</span></div>
                            </div>
                            <div class="actions">
                                <button class="btn-detail" onclick="showDetailLaporan()">Detail</button>
                                <button class="btn-pdf" onclick="exportLaporan('laporan', 'pdf')">PDF</button>
                                <button class="btn-excel" onclick="exportLaporan('laporan', 'excel')">Excel</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div>
                <section class="card">
                    <h3>📅 Jadwal & Status</h3>
                    <div class="summary">
                        <p><strong>Hari Ini ({{ ucfirst($hariIni) }}):</strong> {{ $jadwalHariIni }} kelas |
                            {{ $asistenMengajar }} asisten |
                            {{ $bentrokCount > 0 ? '⚠ ' . $bentrokCount . ' bentrok' : '✅ Tidak ada bentrok' }}
                        </p>
                        <div class="filter-jadwal">
                            <form method="GET" action="{{ route('admin') }}"
                                style="display: flex; gap: 10px;">
                                <button type="submit" name="jadwal_filter" value="hari_ini" class="btn"
                                    {{ $jadwalFilter == 'hari_ini' ? 'style=background-color:#4CAF50;color:white' : '' }}>Hari
                                    Ini</button>
                                <button type="submit" name="jadwal_filter" value="semua" class="btn"
                                    {{ $jadwalFilter == 'semua' ? 'style=background-color:#4CAF50;color:white' : '' }}>Semua
                                    Jadwal</button>
                            </form>
                        </div>
                    </div>

                    @forelse ($jadwalGrouped as $hari => $jadwalList)
                        <div class="jadwal-hari">
                            <h4>{{ ucfirst($hari) }}</h4>
                            <div class="timeline">
                                @foreach ($jadwalList as $jadwal)
                                    <div class="timeline-item {{ $jadwal->bentrok ? 'bentrok' : '' }}"
                                        onclick="showJadwalDetail({{ json_encode($jadwal) }})">
                                        <div class="time">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</div>
                                        <div class="line"></div>
                                        <div class="content">
                                            <h5>📘 {{ $jadwal->praktikum->nama_praktikum ?? 'Unknown' }}</h5>
                                            <p>{{ $jadwal->laboratorium->nama_laboratorium ?? 'Lab Unknown' }}</p>
                                            <span
                                                class="status jadwal {{ $jadwal->bentrok ? 'bentrok' : ($jadwal->status == 'Penuh' ? 'penuh' : 'berjalan') }}">
                                                {{ $jadwal->bentrok ? '❌ Bentrok' : ($jadwal->status == 'Penuh' ? '🔴 Penuh' : '🟢 Berjalan') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="timeline-item last">
                                    <div class="time">{{ $jadwalList->last()->jam_selesai ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="padding: 20px; text-align: center; color: #999;">Tidak ada jadwal untuk filter ini
                        </p>
                    @endforelse

                    @if ($warningsJadwal)
                        <div class="warning">
                            ⚠ {{ $warningsJadwal }}
                        </div>
                    @endif
                </section>

                <section class="card">
                    <h3>Asisten Aktif</h3>
                    <p class="asisten-summary">
                        {{ $totalAsisten }} asisten | {{ $asistenMengajar }} mengajar hari ini
                    </p>

                    @forelse ($asistenAktif as $asisten)
                        <div class="asisten" data-asisten='{{ json_encode($asisten) }}'>
                            <div class="left">
                                <img src="https://i.pravatar.cc/100?img={{ $loop->index }}" class="avatar small">
                                <div>
                                    <strong>{{ $asisten->nama }}</strong>
                                    <p>{{ $asisten->jadwals_count }} kelas</p>
                                    <span
                                        class="status asisten {{ $asisten->jadwals->where('hari', $hariIni)->count() > 0 ? 'aktif' : 'upcoming' }}">
                                        {{ $asisten->jadwals->where('hari', $hariIni)->count() > 0 ? '🟢 Sedang mengajar' : '⏳ Tidak mengajar hari ini' }}
                                    </span>
                                </div>
                            </div>
                            <span class="badge">{{ $asisten->jadwals_count > 2 ? 'overload' : 'active' }}</span>
                            <div class="actions bawah">
                                <button class="btn-detail-asisten"
                                    onclick="showAsistenDetail({{ json_encode($asisten) }})">Detail</button>
                                <button class="btn-jadwal-asisten"
                                    onclick="showAsistenJadwal({{ $asisten->id }}, '{{ $asisten->nama }}')">Jadwal</button>
                            </div>
                        </div>
                    @empty
                        <p style="padding: 20px; text-align: center; color: #999;">Tidak ada asisten</p>
                    @endforelse
                </section>
            </div>
        </div>
    </main>

    <!-- Modal Praktikum Detail -->
    <div id="modalPraktikumDetail" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalPraktikumDetail')">&times;</span>
            <h2>Detail Praktikum</h2>
            <div id="praktikumDetailContent">
                <p><strong>Nama:</strong> <span id="detailNama"></span></p>
                <p><strong>Kode:</strong> <span id="detailKode"></span></p>
                <p><strong>Angkatan:</strong> <span id="detailAngkatan"></span></p>
                <p><strong>Semester:</strong> <span id="detailSemester"></span></p>
            </div>
        </div>
    </div>

    <!-- Modal Praktikum Edit -->
    <div id="modalPraktikumEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalPraktikumEdit')">&times;</span>
            <h2>Edit Praktikum</h2>
            <form id="formEditPraktikum" style="margin: 2rem;">
                <div style="margin-bottom: 15px;">
                    <label>Nama Praktikum:</label>
                    <input type="text" id="editNama"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Kode Praktikum:</label>
                    <input type="text" id="editKode"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Angkatan:</label>
                    <input type="text" id="editAngkatan"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Semester:</label>
                    <input type="text" id="editSemester"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal('modalPraktikumEdit')"
                        style="padding: 8px 16px; background-color: #999; color: white; border: none; border-radius: 4px; cursor: pointer;">Batal</button>
                    <button type="submit"
                        style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Praktikum Delete -->
    <div id="modalPraktikumDelete" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalPraktikumDelete')">&times;</span>
            <h2>Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus praktikum <strong id="deletePraktikumNama"></strong>?</p>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button onclick="closeModal('modalPraktikumDelete')"
                    style="padding: 8px 16px; background-color: #999; color: white; border: none; border-radius: 4px; cursor: pointer;">Batal</button>
                <button id="confirmDeleteBtn"
                    style="padding: 8px 16px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Modal Asisten Detail -->
    <div id="modalAsistenDetail" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalAsistenDetail')">&times;</span>
            <h2>Detail Asisten</h2>
            <div id="asistenDetailContent" style="padding: 20px;"></div>
        </div>
    </div>

    <!-- Modal Asisten Jadwal -->
    <div id="modalAsistenJadwal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalAsistenJadwal')">&times;</span>
            <h2>Jadwal Mengajar - <span id="asistenNamaJadwal"></span></h2>
            <div id="asistenJadwalContent" style="padding: 20px;"></div>
        </div>
    </div>

    <!-- Modal Jadwal Detail -->
    <div id="modalJadwalDetail" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalJadwalDetail')">&times;</span>
            <h2>Detail Jadwal</h2>
            <div id="jadwalDetailContent" style="padding: 20px;"></div>
        </div>
    </div>


    <script src="{{ asset('lab_js/dashboard_lab.js') }}"></script>
</body>

</html>
