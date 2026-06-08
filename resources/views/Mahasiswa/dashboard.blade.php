<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Dashboard Mahasiswa | {{ Auth::user()->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f4f8;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 28px 32px;
            overflow-x: auto;
            transition: all 0.3s ease;
        }

        .page-title {
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #1e293b, #2d3a4b);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ── Hero Card ─────────────────────────────────────────── */
        .hero-card {
            border-radius: 28px;
            margin-bottom: 32px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background-image: url('https://placehold.co/1200x300/2c3e50/ffffff?text=Lab+Computer+Science');
            background-size: cover;
            background-position: center 30%;
        }

        .hero-overlay {
            background: linear-gradient(95deg, rgba(10, 25, 47, 0.85) 0%, rgba(30, 58, 80, 0.7) 100%);
            backdrop-filter: blur(2px);
            padding: 32px;
        }

        .hero-text h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
        }

        .hero-text p {
            color: #cbd5e6;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .hero-badge {
            background: #facc15;
            color: #1e293b;
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Two-Column Layout ─────────────────────────────────── */
        .two-columns {
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
        }

        .left-col {
            flex: 2;
            min-width: 240px;
        }

        .right-col {
            flex: 1.2;
            min-width: 260px;
        }

        /* ── Glass Card ────────────────────────────────────────── */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 28px;
            padding: 22px 24px;
            margin-bottom: 28px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03), 0 2px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* ── Notifikasi ────────────────────────────────────────── */
        .notif-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .notif-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4b5563;
            font-weight: 600;
        }

        .greeting-name {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .date-chip {
            font-size: 0.8rem;
            background: #eef2ff;
            padding: 4px 12px;
            border-radius: 40px;
            color: #4338ca;
            display: inline-block;
            margin-top: 6px;
        }

        .notif-list {
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .notif-card {
            background: white;
            border-radius: 20px;
            padding: 14px 18px;
            display: flex;
            gap: 14px;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-left: 6px solid;
        }

        .notif-card.info    { border-left-color: #3b82f6; background: #f0f9ff; }
        .notif-card.warning { border-left-color: #f59e0b; background: #fffbeb; }
        .notif-card.success { border-left-color: #10b981; background: #ecfdf5; }

        .notif-icon { font-size: 1.5rem; }

        .notif-text {
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1.4;
        }

        /* ── Section Title ─────────────────────────────────────── */
        .section-title {
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ── Praktikum Grid (Enhanced) ─────────────────────────── */
        .praktikum-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .course-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: 0.25s ease;
            box-shadow: 0 6px 16px -4px rgba(0, 0, 0, 0.07);
            cursor: pointer;
            border: 1px solid #e8edf5;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 28px -8px rgba(0, 0, 0, 0.14);
            border-color: #c7d2fe;
        }

        .course-img {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .course-img::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 30px;
            background: linear-gradient(to top, rgba(0,0,0,0.15), transparent);
        }

        .course-body {
            padding: 12px 14px;
        }

        .course-matkul {
            font-size: 0.7rem;
            font-weight: 600;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .course-pertemuan {
            font-weight: 700;
            font-size: 0.82rem;
            color: #1e293b;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .course-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
        }

        .course-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            color: #6b7280;
        }

        .course-meta-item i {
            width: 14px;
            color: #94a3b8;
            font-size: 0.68rem;
            flex-shrink: 0;
        }

        /* ── Card Header ───────────────────────────────────────── */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-header h3 {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .see-all-link {
            font-size: 0.75rem;
            font-weight: 600;
            color: #4f46e5;
            text-decoration: none;
        }

        /* ── Reminder Section (Redesigned) ─────────────────────── */
        .reminder-section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            margin-top: 4px;
        }

        .reminder-section-label.today {
            color: #0891b2;
        }

        .reminder-section-label.tomorrow {
            color: #7c3aed;
        }

        .reminder-section-label .label-line {
            flex: 1;
            height: 1px;
            background: currentColor;
            opacity: 0.2;
        }

        .reminder-section-label .label-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        .reminder-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 14px;
        }

        .reminder-card {
            background: linear-gradient(115deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #eef2ff;
            transition: all 0.2s;
        }

        .reminder-card:hover {
            background: white;
            transform: scale(1.01);
            border-color: #c7d2fe;
        }

        .reminder-card.today-card {
            border-color: #bae6fd;
            background: linear-gradient(115deg, #f0f9ff, #e0f2fe);
        }

        .reminder-card.today-card:hover {
            border-color: #7dd3fc;
        }

        .reminder-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .reminder-icon.today-icon {
            background: #e0f2fe;
        }

        .reminder-icon.tomorrow-icon {
            background: #ede9fe;
        }

        .reminder-detail { flex: 1; min-width: 0; }

        .reminder-title {
            font-weight: 700;
            font-size: 0.88rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .reminder-time {
            font-size: 0.68rem;
            color: #6b7280;
            margin-top: 2px;
        }

        .reminder-badge {
            font-size: 0.62rem;
            padding: 2px 8px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 4px;
            font-weight: 600;
        }

        .reminder-badge.today-b  { background: #e0f2fe; color: #0369a1; }
        .reminder-badge.tomorrow-b { background: #ede9fe; color: #6d28d9; }
        .reminder-badge.upload-b { background: #fee2e2; color: #dc2626; }

        .reminder-empty {
            text-align: center;
            padding: 14px;
            color: #9ca3af;
            font-size: 0.8rem;
        }

        /* ── Presensi (Enhanced) ───────────────────────────────── */
        .task-list { margin-top: 14px; }

        .task-item {
            background: white;
            border-radius: 16px;
            padding: 12px 14px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.2s;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            flex-wrap: wrap;
        }

        .task-item.done {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .task-status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .task-status-dot.hadir   { background: #22c55e; }
        .task-status-dot.absen   { background: #ef4444; }
        .task-status-dot.izin    { background: #f59e0b; }
        .task-status-dot.default { background: #94a3b8; }

        .task-info { flex: 1; min-width: 0; }

        .task-pertemuan {
            font-weight: 600;
            font-size: 0.85rem;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .task-matkul {
            font-size: 0.7rem;
            color: #6b7280;
            margin-top: 2px;
        }

        .task-item.done .task-pertemuan { color: #16a34a; }

        .task-badge {
            font-size: 0.68rem;
            padding: 4px 10px;
            border-radius: 40px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .task-badge.not-done  { background: #fee2e2; color: #dc2626; }
        .task-badge.done-badge { background: #dcfce7; color: #16a34a; }
        .task-badge.izin-badge { background: #fef3c7; color: #d97706; }

        .progress-footer {
            margin-top: 16px;
            font-size: 0.7rem;
            background: #f1f5f9;
            border-radius: 40px;
            padding: 6px 12px;
            text-align: center;
        }

        /* ── Responsive ────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .main-content { padding: 24px; }
            .page-title   { font-size: 1.6rem; }
            .hero-text h2 { font-size: 1.5rem; }
            .praktikum-grid { grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 14px; }
            .glass-card { padding: 18px 20px; }
        }

        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .sidebar { width: 100%; padding-bottom: 0; }
            .sidebar-header { padding: 16px 20px; }
            .mobile-menu-toggle { display: block; }
            .sidebar-nav { display: none; padding: 0 20px 20px 20px; }
            .sidebar-nav.active { display: flex; }
            .profile-section { flex-direction: row; text-align: left; gap: 16px; margin-bottom: 0; }
            .avatar-circle { width: 60px; height: 60px; margin: 0; }
            .avatar-circle i { font-size: 30px; }
            .profile-info h3 { font-size: 1.1rem; }
            .profile-info p  { font-size: 0.65rem; }
            .nav-menu { margin-top: 20px; }
            .nav-item { padding: 10px 14px; font-size: 0.9rem; }
            .main-content { padding: 20px; }
            .page-title { font-size: 1.4rem; margin-bottom: 16px; justify-content: center; width: 100%; text-align: center; }
            .hero-card { margin-bottom: 24px; }
            .hero-overlay { padding: 24px; }
            .hero-text h2 { font-size: 1.2rem; }
            .hero-text p  { font-size: 0.75rem; }
            .two-columns { gap: 20px; }
            .glass-card  { padding: 16px; margin-bottom: 20px; }
            .greeting-name { font-size: 1.2rem; }
            .notif-card { padding: 12px 14px; }
            .notif-icon { font-size: 1.2rem; }
            .notif-text { font-size: 0.75rem; }
            .section-title { font-size: 1.1rem; margin-bottom: 16px; }
            .card-header h3 { font-size: 1rem; }
        }

        @media (max-width: 600px) {
            .main-content { padding: 16px; }
            .page-title   { font-size: 1.3rem; }
            .hero-overlay { padding: 20px; }
            .hero-text h2 { font-size: 1rem; }
            .hero-badge   { font-size: 0.6rem; padding: 4px 10px; }
            .praktikum-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .course-img   { height: 65px; }
            .glass-card   { padding: 14px; border-radius: 20px; }
            .reminder-card { padding: 10px 12px; gap: 10px; }
            .reminder-icon { width: 32px; height: 32px; font-size: 0.9rem; border-radius: 10px; }
            .reminder-title { font-size: 0.82rem; }
            .task-item { padding: 10px 12px; gap: 10px; }
            .task-pertemuan { font-size: 0.8rem; }
            .task-badge { font-size: 0.62rem; padding: 3px 8px; }
        }

        @media (max-width: 480px) {
            .praktikum-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .course-img  { height: 60px; }
            .notif-header { flex-direction: column; align-items: flex-start; }
            .card-header  { flex-direction: column; align-items: flex-start; }
        }

        @media (min-width: 1200px) {
            .praktikum-grid { grid-template-columns: repeat(3, 1fr); }
        }

        @media (hover: none) and (pointer: coarse) {
            .nav-item, .course-card, .task-item, .reminder-card, .logout-btn {
                cursor: pointer;
                -webkit-tap-highlight-color: transparent;
            }
            .nav-item:active, .course-card:active { transform: scale(0.98); transition: 0.05s; }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        @include('mahasiswa/partials/sidebar')

        <main class="main-content">
            <h1 class="page-title"><i class="fas fa-chart-simple"></i> Dashboard</h1>

            {{-- ── Hero Card ──────────────────────────────────────────── --}}
            <div class="hero-card">
                <div class="hero-overlay">
                    <div class="hero-text">
                        <h2 id="topGreetingText">Selamat Siang, {{ Auth::user()->nama }}! 👋</h2>
                        <p>
                            <span id="fullDateDisplay"></span>
                            <span class="hero-badge"><i class="fas fa-chalkboard"></i> Semester Genap 2025/2026</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="two-columns">

                {{-- ══ LEFT COLUMN ══════════════════════════════════════ --}}
                <div class="left-col">

                    {{-- ── Notifikasi ──────────────────────────────────── --}}
                    <div class="glass-card">
                        <div class="notif-header">
                            <div>
                                <p class="notif-title"><i class="far fa-bell"></i> NOTIFIKASI</p>
                                <div class="greeting-name" id="greetingName"></div>
                                <div class="date-chip" id="todayDateChip"></div>
                            </div>
                        </div>
                        <div class="notif-list">
                            @forelse($nilais->take(3) as $nilai)
                            <div class="notif-card info">
                                <div class="notif-icon">📚</div>
                                <div class="notif-text">
                                    <b>{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? 'Praktikum' }}</b> -
                                    {{ $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan' }}<br>
                                    <span style="font-size:0.75rem;color:#64748b;">
                                        Nilai Pretest: {{ $nilai->nilai_pretest ?? '-' }} | Laporan: {{ $nilai->nilai_laporan ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="notif-card info">
                                <div class="notif-icon">📅</div>
                                <div class="notif-text"><b>Belum ada data nilai</b> - Silakan daftar praktikum terlebih dahulu</div>
                            </div>
                            @endforelse
                            <div class="notif-card success">
                                <div class="notif-icon">✅</div>
                                <div class="notif-text">Jangan lupa isi presensi setelah praktikum &amp; upload laporan.</div>
                            </div>
                        </div>
                    </div>

                    {{-- ── All Praktikum (Enhanced Cards) ─────────────── --}}
                    <div class="glass-card">
                        <div class="section-title">
                            <i class="fas fa-microscope"></i> All Praktikum
                        </div>
                        <div class="praktikum-grid">
                            @php
                                $gradients = [
                                    'linear-gradient(145deg, #3b82f6, #1e40af)',
                                    'linear-gradient(145deg, #8b5cf6, #5b21b6)',
                                    'linear-gradient(145deg, #06b6d4, #0e7490)',
                                    'linear-gradient(145deg, #10b981, #065f46)',
                                    'linear-gradient(145deg, #f59e0b, #b45309)',
                                    'linear-gradient(145deg, #ef4444, #991b1b)',
                                ];
                                $icons = ['fa-code', 'fa-database', 'fa-network-wired', 'fa-brain', 'fa-server', 'fa-microchip'];
                                $i = 0;
                            @endphp

                            @forelse($nilais as $nilai)
                            @php
                                $grad  = $gradients[$i % count($gradients)];
                                $icon  = $icons[$i % count($icons)];
                                // Akses jadwal via pertemuan (pastikan controller eager-load 'pertemuan.jadwal')
                                $jadwal  = $nilai->pertemuan?->jadwal;
                                $dosen   = $jadwal?->dosen?->nama ?? $nilai->pertemuan?->praktikum?->nama_dosen ?? '-';
                                $jam     = $jadwal?->jam_mulai ?? '-';
                                $ruangan = $jadwal?->laboratorium?->nama_laboratorium ?? '-';
                                $i++;
                            @endphp
                            <div class="course-card">
                                <div class="course-img" style="background: {{ $grad }};">
                                    <i class="fas {{ $icon }} fa-2x" style="opacity:0.9;"></i>
                                </div>
                                <div class="course-body">
                                    <div class="course-matkul">
                                        {{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? 'Praktikum' }}
                                    </div>
                                    <div class="course-pertemuan">
                                        {{ $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan' }}
                                    </div>
                                    <div class="course-meta">
                                        <div class="course-meta-item">
                                            <i class="fas fa-user-tie"></i>
                                            <span>{{ $dosen }}</span>
                                        </div>
                                        <div class="course-meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $jam }} WIB</span>
                                        </div>
                                        <div class="course-meta-item">
                                            <i class="fas fa-door-open"></i>
                                            <span>{{ $ruangan }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="course-card">
                                <div class="course-img" style="background: linear-gradient(145deg, #9ca3af, #6b7280);">
                                    <i class="fas fa-book fa-2x" style="opacity:0.9;"></i>
                                </div>
                                <div class="course-body">
                                    <div class="course-matkul">—</div>
                                    <div class="course-pertemuan">Belum Ada Nilai</div>
                                    <div class="course-meta">
                                        <div class="course-meta-item"><i class="fas fa-info-circle"></i><span>Daftar praktikum dulu</span></div>
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ══ RIGHT COLUMN ════════════════════════════════════ --}}
                <div class="right-col">

                    {{-- ── Reminder (Redesigned: Hari Ini + Besok) ──── --}}
                    <div class="glass-card">
                        <div class="card-header">
                            <h3><i class="fas fa-bell"></i> Reminder</h3>
                            <a href="#" class="see-all-link" id="seeAllReminder">
                                See All <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>

                        {{--
                            ── HARI INI ──────────────────────────────────
                            Membutuhkan variabel $jadwalHariIni dari controller.
                            Tambahkan di controller (lihat komentar di bawah).
                        --}}
                        <div class="reminder-section-label today">
                            <span class="label-dot"></span>
                            <span>Hari Ini</span>
                            <span class="label-line"></span>
                        </div>

                        <div class="reminder-list">
                            @php $adaHariIni = false; @endphp
                            @isset($jadwalHariIni)
                                @forelse($jadwalHariIni as $jHariIni)
                                @php $adaHariIni = true; @endphp
                                <div class="reminder-card today-card">
                                    <div class="reminder-icon today  -icon">📅</div>
                                    <div class="reminder-detail">
                                        <div class="reminder-title">{{ $jHariIni['praktikum'] }}</div>
                                        <div class="reminder-time">
                                            <i class="fas fa-clock"></i> {{ $jHariIni['jam'] }} WIB
                                            &nbsp;·&nbsp;
                                            <i class="fas fa-door-open"></i> {{ $jHariIni['ruangan'] }}
                                        </div>
                                        <span class="reminder-badge today-b">Sedang Berlangsung / Hari Ini</span>
                                    </div>
                                </div>
                                @empty
                                @endforelse
                            @endisset
                            @if(!$adaHariIni)
                            <div class="reminder-empty">
                                <i class="fas fa-calendar-check" style="font-size:1.3rem;color:#bae6fd;margin-bottom:4px;display:block;"></i>
                                Tidak ada jadwal hari ini
                            </div>
                            @endif
                        </div>

                        {{-- ── BESOK ─────────────────────────────────── --}}
                        <div class="reminder-section-label tomorrow">
                            <span class="label-dot"></span>
                            <span>Besok</span>
                            <span class="label-line"></span>
                        </div>

                        <div class="reminder-list">
                            @php
                                // Filter hanya reminder bertipe 'Jadwal Besok' atau 'Pretest Besok'
                                $reminderBesok = collect($reminders)->filter(function($r) {
                                    return in_array($r['status'], ['Jadwal Besok', 'Pretest Besok']);
                                });
                                $reminderUpload = collect($reminders)->filter(function($r) {
                                    return $r['status'] === 'Belum Upload';
                                });
                            @endphp

                            @forelse($reminderBesok as $reminder)
                            <div class="reminder-card">
                                <div class="reminder-icon tomorrow-icon">
                                    {{ $reminder['status'] === 'Pretest Besok' ? '📝' : '📚' }}
                                </div>
                                <div class="reminder-detail">
                                    <div class="reminder-title">{{ $reminder['praktikum'] }}</div>
                                    <div class="reminder-time">
                                        <i class="far fa-hourglass-half"></i>
                                        {{ $reminder['pertemuan'] }} &nbsp;·&nbsp; {{ $reminder['modul'] }}
                                    </div>
                                    <span class="reminder-badge tomorrow-b">{{ $reminder['status'] }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="reminder-empty">
                                <i class="fas fa-moon" style="font-size:1.3rem;color:#ddd6fe;margin-bottom:4px;display:block;"></i>
                                Tidak ada jadwal besok
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- ── Presensi (Enhanced) ──────────────────────── --}}
                    <div class="glass-card">
                        <h3 style="font-weight:700;display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <i class="fas fa-clipboard-check"></i> Presensi
                        </h3>
                        <div id="assignmentsList" class="task-list">
                            @forelse($presensis as $presensi)
                            @php
                                $kehadiran  = $presensi->kehadiran ?? 'Tidak Hadir';
                                $dotClass   = match(strtolower($kehadiran)) {
                                    'hadir'  => 'hadir',
                                    'izin'   => 'izin',
                                    default  => 'absen',
                                };
                                $badgeClass = match(strtolower($kehadiran)) {
                                    'hadir' => 'done-badge',
                                    'izin'  => 'izin-badge',
                                    default => 'not-done',
                                };
                                $isDone = strtolower($kehadiran) === 'hadir';
                            @endphp
                            <div class="task-item {{ $isDone ? 'done' : '' }}">
                                <span class="task-status-dot {{ $dotClass }}"></span>
                                <div class="task-info">
                                    <div class="task-pertemuan">
                                        {{ $presensi->pertemuan?->nama_pertemuan ?? 'Presensi' }}
                                    </div>
                                    <div class="task-matkul">
                                        <i class="fas fa-microscope" style="font-size:0.6rem;"></i>
                                        {{ $presensi->pertemuan?->praktikum?->nama_praktikum ?? 'Praktikum' }}
                                    </div>
                                </div>
                                <span class="task-badge {{ $badgeClass }}">{{ $kehadiran }}</span>
                            </div>
                            @empty
                            <div class="task-item">
                                <span class="task-status-dot default"></span>
                                <div class="task-info">
                                    <div class="task-pertemuan">Belum ada presensi</div>
                                    <div class="task-matkul">Silakan daftar praktikum</div>
                                </div>
                                <span class="task-badge not-done">Kosong</span>
                            </div>
                            @endforelse
                        </div>
                        <div class="progress-footer" id="progressHint">
                            📊 Kehadiran: {{ $attendanceRate }}% &nbsp;·&nbsp; Rata-rata Nilai: {{ round($avgNilai) }}
                        </div>
                    </div>

                </div>{{-- end right-col --}}
            </div>{{-- end two-columns --}}
        </main>
    </div>

    <script>
        (function () {
            const profileName = `{{ Auth::user()->nama }}`;
            const now  = new Date();
            const hour = now.getHours();

            let greetingTime = "Selamat pagi";
            if (hour >= 12 && hour < 18) greetingTime = "Selamat siang";
            else if (hour >= 18)         greetingTime = "Selamat malam";

            const topGreetingSpan = document.getElementById("topGreetingText");
            if (topGreetingSpan) topGreetingSpan.innerText = `${greetingTime}, ${profileName}! 👋`;

            const greetingNameDiv = document.getElementById("greetingName");
            if (greetingNameDiv) greetingNameDiv.innerText = `${greetingTime}, ${profileName} 👋`;

            const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = now.toLocaleDateString('id-ID', optionsDate);

            const fullDateSpan = document.getElementById("fullDateDisplay");
            if (fullDateSpan) fullDateSpan.innerHTML = `<i class="far fa-calendar-alt"></i> ${formattedDate}`;

            const todayChip = document.getElementById("todayDateChip");
            if (todayChip) todayChip.innerText = formattedDate;

            // ── See All Reminder ───────────────────────────────────────
            const seeAllBtn = document.getElementById("seeAllReminder");
            if (seeAllBtn) {
                seeAllBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    const reminders = @json($reminders);
                    let text = "📋 Semua Reminder:\n\n";
                    reminders.forEach(r => {
                        text += `• ${r.praktikum} — ${r.pertemuan} (${r.status})\n`;
                    });
                    alert(text);
                });
            }

            // ── Mobile Sidebar Toggle ──────────────────────────────────
            const mobileToggle = document.getElementById("mobileMenuToggle");
            const sidebarNav   = document.getElementById("sidebarNav");

            if (mobileToggle && sidebarNav) {
                mobileToggle.addEventListener("click", () => {
                    sidebarNav.classList.toggle("active");
                    const icon = mobileToggle.querySelector("i");
                    if (sidebarNav.classList.contains("active")) {
                        icon.classList.replace("fa-bars", "fa-times");
                    } else {
                        icon.classList.replace("fa-times", "fa-bars");
                    }
                });
            }

            // ── Submenu Toggle ─────────────────────────────────────────
            document.querySelectorAll(".has-sub").forEach(item => {
                const subUl      = item.querySelector(".submenu");
                const triggerDiv = item.querySelector(".sub-trigger");
                if (subUl && triggerDiv) {
                    subUl.style.display = "block";
                    triggerDiv.addEventListener("click", (e) => {
                        e.stopPropagation();
                        const open    = subUl.style.display !== "none";
                        subUl.style.display = open ? "none" : "block";
                        const chevron = triggerDiv.querySelector(".fa-chevron-down");
                        if (chevron) chevron.style.transform = open ? "rotate(-90deg)" : "rotate(0deg)";
                    });
                }
            });

            // ── Close sidebar on nav click (mobile) ───────────────────
            document.querySelectorAll(".nav-item").forEach(item => {
                item.addEventListener("click", () => {
                    if (window.innerWidth <= 768 && sidebarNav?.classList.contains("active")) {
                        sidebarNav.classList.remove("active");
                        const icon = mobileToggle?.querySelector("i");
                        if (icon) { icon.classList.replace("fa-times", "fa-bars"); }
                    }
                });
            });
        })();
    </script>
</body>
</html>