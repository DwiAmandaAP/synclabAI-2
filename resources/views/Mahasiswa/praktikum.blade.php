{{--
    praktikum.blade.php  –  Halaman My Praktikum (Mahasiswa)
    ─────────────────────────────────────────────────────────
    KONSEP BARU:
    • Mahasiswa mendaftar ke 1 Jadwal → otomatis tercatat di semua Pertemuan jadwal itu.
    • Tab "Active"    → Pertemuan yang sedang berlangsung (current session).
    • Tab "Upcoming"  → Pertemuan yang belum tiba (pertemuan ke > pertemuan aktif).
    • Tab "Completed" → Pertemuan yang sudah selesai.
    • Di atas grid selalu ada info card: Nama Praktikum, Ruangan, Hari, Jam, Dosen.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>My Praktikum | Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg: #f0f4f8;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --blue: #3b82f6;
            --blue-dark: #2563eb;
            --blue-light: #eff6ff;
            --green: #10b981;
            --green-light: #dcfce7;
            --green-text: #15803d;
            --amber: #f59e0b;
            --amber-light: #fef3c7;
            --amber-text: #b45309;
            --slate: #64748b;
            --slate-light: #f1f5f9;
            --red: #ef4444;
            --red-light: #fee2e2;
            --red-text: #dc2626;
            --purple: #8b5cf6;
            --purple-light: #f5f3ff;
            --purple-text: #6d28d9;
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 32px rgba(0,0,0,0.1);
        }

        body {
            background: var(--bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
        }

        .dashboard-container { display: flex; min-height: 100vh; }
        .main-content { flex: 1; padding: 32px 36px; min-width: 0; overflow-x: hidden; }

        /* ── Page Header ── */
        .page-header { margin-bottom: 28px; }
        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-title .title-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 18px;
        }
        .page-subtitle {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 4px;
            margin-left: 56px;
        }

        /* ── Jadwal Info Banner (shown when registered) ── */
        .jadwal-banner {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }
        .jadwal-banner-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
            min-width: 220px;
        }
        .jadwal-banner-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 22px;
            flex-shrink: 0;
        }
        .jadwal-banner-nama {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-primary);
        }
        .jadwal-banner-kode {
            font-size: 0.76rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-top: 2px;
        }
        .jadwal-banner-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .jadwal-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            padding: 6px 14px;
            font-size: 0.79rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .jadwal-pill i { color: var(--blue); font-size: 0.72rem; }
        .jadwal-pill.pill-hari  { background: var(--blue-light); border-color: #bfdbfe; color: var(--blue-dark); }
        .jadwal-pill.pill-hari i { color: var(--blue-dark); }
        .jadwal-banner-progress {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .progress-label { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); white-space: nowrap; }
        .progress-track {
            width: 120px;
            height: 6px;
            background: var(--slate-light);
            border-radius: var(--radius-full);
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: var(--radius-full);
            background: linear-gradient(90deg, var(--blue), var(--green));
            transition: width 0.6s ease;
        }
        .progress-num {
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--text-primary);
            white-space: nowrap;
        }

        /* ── Not Registered State ── */
        .not-registered-banner {
            background: var(--amber-light);
            border: 1.5px solid #fcd34d;
            border-radius: var(--radius-lg);
            padding: 18px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .not-registered-banner i { color: var(--amber-text); font-size: 20px; flex-shrink: 0; }
        .not-registered-banner p { font-size: 0.87rem; color: var(--amber-text); font-weight: 600; }
        .not-registered-banner a {
            margin-left: auto;
            padding: 8px 20px;
            background: var(--amber);
            color: white;
            border-radius: var(--radius-full);
            font-size: 0.83rem;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
            white-space: nowrap;
        }
        .not-registered-banner a:hover { background: var(--amber-text); }

        /* ── Tab Navigation ── */
        .tab-nav {
            display: flex;
            gap: 4px;
            background: var(--surface);
            border-radius: var(--radius-full);
            padding: 5px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            margin-bottom: 24px;
            width: fit-content;
        }
        .tab-btn {
            padding: 9px 24px;
            background: transparent;
            border: none;
            border-radius: var(--radius-full);
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
            font-family: inherit;
        }
        .tab-btn .tab-count {
            background: var(--slate-light);
            color: var(--text-secondary);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: var(--radius-full);
            transition: all 0.2s;
        }
        .tab-btn.active { background: var(--blue); color: white; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        .tab-btn.active .tab-count { background: rgba(255,255,255,0.25); color: white; }
        .tab-btn:hover:not(.active) { background: var(--slate-light); color: var(--text-primary); }

        .tab-content { display: none; animation: fadeIn 0.25s ease; }
        .tab-content.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Pertemuan Grid ── */
        .pertemuan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }

        /* ── Pertemuan Card ── */
        .pertemuan-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1.5px solid var(--border);
            overflow: hidden;
            transition: all 0.22s ease;
            box-shadow: var(--shadow-sm);
            position: relative;
        }
        .pertemuan-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .pertemuan-card.card-active { border-color: var(--blue); }
        .pertemuan-card.card-completed { border-color: var(--green); }
        .pertemuan-card.card-upcoming { border-color: var(--border); }

        /* Accent stripe on left */
        .pertemuan-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            border-radius: 4px 0 0 4px;
        }
        .card-active::before  { background: var(--blue); }
        .card-completed::before { background: var(--green); }
        .card-upcoming::before  { background: var(--border); }

        .card-top {
            padding: 18px 20px 14px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .card-number {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            font-weight: 800;
            color: white;
            flex-shrink: 0;
        }
        .card-active  .card-number { background: linear-gradient(135deg, var(--blue), var(--blue-dark)); }
        .card-completed .card-number { background: linear-gradient(135deg, var(--green), #047857); }
        .card-upcoming  .card-number { background: linear-gradient(135deg, var(--slate), #334155); }

        .card-meta { flex: 1; min-width: 0; }
        .card-nama {
            font-size: 0.97rem;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card-praktikum-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 2px;
        }
        .card-badge {
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .badge-active   { background: var(--blue-light); color: var(--blue-dark); }
        .badge-completed { background: var(--green-light); color: var(--green-text); }
        .badge-upcoming { background: var(--slate-light); color: var(--slate); }

        .card-details { padding: 14px 20px 14px 24px; }
        .detail-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 3px 0;
            font-size: 0.81rem;
            color: var(--text-secondary);
        }
        .detail-item i { width: 14px; color: var(--blue); font-size: 0.74rem; flex-shrink: 0; }

        .card-footer { padding: 12px 20px 12px 24px; border-top: 1px solid var(--border); }

        /* Modul/laporan action pills */
        .action-pills { display: flex; gap: 8px; flex-wrap: wrap; }
        .action-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: 0.2s;
        }
        .pill-modul { background: var(--blue-light); color: var(--blue-dark); }
        .pill-modul:hover { background: var(--blue); color: white; }
        .pill-laporan { background: var(--green-light); color: var(--green-text); }
        .pill-laporan:hover { background: var(--green); color: white; }
        .pill-nilai { background: var(--purple-light); color: var(--purple-text); }
        .pill-nilai:hover { background: var(--purple); color: white; }
        .pill-presensi { background: var(--amber-light); color: var(--amber-text); }
        .pill-presensi:hover { background: var(--amber); color: white; }
        .pill-locked { background: var(--slate-light); color: var(--text-muted); cursor: not-allowed; }

        /* Active-specific: highlight card status */
        .active-now-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.73rem;
            font-weight: 700;
            color: var(--blue-dark);
            background: var(--blue-light);
            border-radius: var(--radius-full);
            padding: 4px 10px;
            animation: pulse 2s infinite;
        }
        .active-now-label::before {
            content: '';
            width: 7px; height: 7px;
            background: var(--blue);
            border-radius: 50%;
            animation: blink 1.2s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59,130,246,0.2); }
            50% { box-shadow: 0 0 0 5px rgba(59,130,246,0); }
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 64px 24px;
            color: var(--text-secondary);
            grid-column: 1 / -1;
        }
        .empty-icon {
            width: 72px; height: 72px;
            background: var(--slate-light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            color: var(--text-muted);
            margin: 0 auto 16px;
        }
        .empty-state h3 { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
        .empty-state p  { font-size: 0.83rem; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .main-content { padding: 20px 16px; }
            .page-title { font-size: 1.4rem; }
            .page-subtitle { margin-left: 0; }
            .tab-nav { width: 100%; flex-wrap: wrap; }
            .pertemuan-grid { grid-template-columns: 1fr; }
            .jadwal-banner { flex-direction: column; align-items: flex-start; }
            .jadwal-banner-progress { width: 100%; }
            .progress-track { flex: 1; }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('mahasiswa/partials/sidebar')

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <span class="title-icon"><i class="fas fa-flask"></i></span>
                My Praktikum
            </h1>
            <p class="page-subtitle">Progres pertemuan praktikum yang Anda ikuti</p>
        </div>

        @php
            $user = Auth::user();

            /*
            ┌─────────────────────────────────────────────────────────────┐
            │  KONSEP BARU                                                │
            │  User daftar ke 1 Jadwal → semua Pertemuan dalam jadwal itu │
            │  secara otomatis tercakup.                                  │
            │  Pertemuan dikelompokkan:                                   │
            │   - pertemuan_ke <= activeThreshold && status jadwal Selesai │
            │     → Completed                                             │
            │   - pertemuan aktif saat ini  → Active                     │
            │   - sisanya                   → Upcoming                   │
            └─────────────────────────────────────────────────────────────┘
            */

            // Ambil pendaftaran user (hanya role Praktikan)
            $pendaftaran = \App\Models\PendaftaranPraktikum::with([
                'jadwal.praktikum',
                'jadwal.laboratorium',
                'jadwal.dosen',
                'jadwal.pertemuan' => function($q) { $q->orderBy('pertemuan_ke'); },
                'jadwal.pertemuan.modul',
                'jadwal.pertemuan.nilais',
                'jadwal.pertemuan.presensis',
                'jadwal.pertemuan.laporan',
            ])
            ->where('id_user', $user->id)
            ->where('role', 'Praktikan')
            ->first(); // satu jadwal aktif

            $isRegistered = !is_null($pendaftaran);
            $jadwal       = $pendaftaran?->jadwal;
            $praktikum    = $jadwal?->praktikum;
            $pertemuans   = $jadwal ? $jadwal->pertemuan->sortBy('pertemuan_ke') : collect();

            $totalPertemuan = $pertemuans->count();

            /*
             Tentukan posisi pertemuan aktif:
             Gunakan field status pada Jadwal atau logika urutan:
             - Jika tidak ada pertemuan → semua upcoming
             - Pertemuan ke-N dianggap ACTIVE jika jadwal/pertemuan memiliki status "Berlangsung"
               atau fallback: anggap pertemuan ke-3 aktif (sesuai permintaan: 1&2 done, 3 active)
             Strategi robust: cek apakah ada field "status" di pertemuan,
             atau gunakan pertemuan_ke untuk simulasi.
            */

            // Tentukan pertemuan "aktif" sekarang (bisa dari DB atau logika urut)
            // Fallback: pertemuan dengan status tertentu, atau gunakan angka hardcode jika belum ada field
            $activePertemuanKe = null;
            $completedCount    = 0;

            // Coba deteksi dari status jadwal/pertemuan jika ada field 'status' di pertemuan
            // (fallback ke urutan: pertemuan selesai = sebelum aktif)
            // Untuk sementara: semua pertemuan sebelum "aktif" = completed, yang aktif = active, sisanya = upcoming
            // Di sini kita anggap pertemuan_ke paling kecil yang belum selesai = aktif
            // (Implementasi ini bisa diganti logika real jika ada field status di pertemuan)

            $pertemuanActive    = collect();
            $pertemuanUpcoming  = collect();
            $pertemuanCompleted = collect();

            if ($isRegistered && $totalPertemuan > 0) {
                // Logika: temukan pertemuan "aktif" = pertemuan_ke terkecil yang statusnya bukan Selesai
                // Jika Jadwal masih Dibuka/Penuh, cari pertemuan yang paling "current"
                // Fallback sederhana: urutkan, pertemuan ke-1 dst yg jadwal sudah Selesai = completed,
                // sisanya lihat dari urutan.

                // Diasumsikan: di table pertemuan ada field status (Selesai, Aktif, Upcoming)
                // Jika tidak: gunakan heuristik dari jadwal.status
                foreach ($pertemuans as $p) {
                    // Cek apakah ada presensi untuk user ini di pertemuan ini (tanda sudah dilangsungkan)
                    $sudahHadir = $p->presensis->where('id_user', $user->id)->count() > 0;
                    $pStatus    = $p->status ?? null; // field status jika ada

                    if ($pStatus === 'Selesai' || $sudahHadir) {
                        $pertemuanCompleted->push($p);
                        $completedCount++;
                    } elseif ($pStatus === 'Aktif' || ($activePertemuanKe === null && $jadwal->status !== 'Selesai')) {
                        // Jadikan yang pertama belum selesai sebagai aktif
                        $pertemuanActive->push($p);
                        $activePertemuanKe = $p->pertemuan_ke;
                    } else {
                        $pertemuanUpcoming->push($p);
                    }
                }
            }

            $progressPct = $totalPertemuan > 0
                ? round(($completedCount / $totalPertemuan) * 100)
                : 0;
        @endphp

        {{-- ── Jadwal Info Banner ── --}}
        @if($isRegistered && $jadwal)
            <div class="jadwal-banner">
                <div class="jadwal-banner-left">
                    <div class="jadwal-banner-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div>
                        <div class="jadwal-banner-nama">{{ $praktikum?->nama_praktikum ?? '-' }}</div>
                        <div class="jadwal-banner-kode">{{ $praktikum?->kode_praktikum ?? '-' }}</div>
                    </div>
                </div>
                <div class="jadwal-banner-pills">
                    <span class="jadwal-pill pill-hari">
                        <i class="fas fa-calendar-day"></i>
                        {{ $jadwal->hari ?? '-' }}
                    </span>
                    <span class="jadwal-pill">
                        <i class="fas fa-clock"></i>
                        {{ $jadwal->jam_mulai ?? '-' }} – {{ $jadwal->jam_selesai ?? '-' }}
                    </span>
                    <span class="jadwal-pill">
                        <i class="fas fa-door-open"></i>
                        {{ $jadwal->laboratorium?->nama_laboratorium ?? '-' }}
                    </span>
                    <span class="jadwal-pill">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $jadwal->dosen?->nama ?? '-' }}
                    </span>
                </div>
                <div class="jadwal-banner-progress">
                    <span class="progress-label">Progres</span>
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progressPct }}%"></div>
                    </div>
                    <span class="progress-num">{{ $completedCount }}/{{ $totalPertemuan }}</span>
                </div>
            </div>
        @else
            <div class="not-registered-banner">
                <i class="fas fa-exclamation-circle"></i>
                <p>Anda belum terdaftar di jadwal praktikum manapun.</p>
                <a href="{{ route('mahasiswa.praktikum.daftar') }}">Daftar Sekarang</a>
            </div>
        @endif

        {{-- ── Tab Navigation ── --}}
        <div class="tab-nav">
            <button class="tab-btn active" data-tab="active">
                <i class="fas fa-circle-dot"></i> Aktif
                <span class="tab-count" id="count-active">{{ $pertemuanActive->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="upcoming">
                <i class="fas fa-hourglass-half"></i> Upcoming
                <span class="tab-count" id="count-upcoming">{{ $pertemuanUpcoming->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="completed">
                <i class="fas fa-check-double"></i> Selesai
                <span class="tab-count" id="count-completed">{{ $pertemuanCompleted->count() }}</span>
            </button>
        </div>

        {{-- ────────────────────────────────────────────────────────────── --}}
        {{-- TAB: AKTIF                                                     --}}
        {{-- ────────────────────────────────────────────────────────────── --}}
        <div id="tab-active" class="tab-content active">
            <div class="pertemuan-grid">
                @forelse($pertemuanActive as $p)
                    <div class="pertemuan-card card-active">
                        <div class="card-top">
                            <div class="card-number">{{ $p->pertemuan_ke }}</div>
                            <div class="card-meta">
                                <div class="card-praktikum-label">{{ $praktikum?->nama_praktikum }}</div>
                                <div class="card-nama">{{ $p->nama_pertemuan }}</div>
                            </div>
                            <div>
                                <span class="card-badge badge-active">
                                    <span class="active-now-label">Sedang Aktif</span>
                                </span>
                            </div>
                        </div>
                        <div class="card-details">
                            @if($p->modul?->judul_modul)
                                <div class="detail-item">
                                    <i class="fas fa-book-open"></i>
                                    {{ $p->modul->judul_modul }}
                                </div>
                            @endif
                            @if($p->deskripsi_pertemuan)
                                <div class="detail-item">
                                    <i class="fas fa-align-left"></i>
                                    {{ Str::limit($p->deskripsi_pertemuan, 80) }}
                                </div>
                            @endif
                            <div class="detail-item">
                                <i class="fas fa-calendar-day"></i>
                                {{ $jadwal->hari ?? '-' }}, {{ $jadwal->jam_mulai ?? '' }} – {{ $jadwal->jam_selesai ?? '' }}
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-door-open"></i>
                                {{ $jadwal->laboratorium?->nama_laboratorium ?? '-' }}
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $jadwal->dosen?->nama ?? '-' }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="action-pills">
                                @if($p->modul?->filepath)
                                    <a href="{{ asset('storage/' . $p->modul->filepath) }}" target="_blank" class="action-pill pill-modul">
                                        <i class="fas fa-file-pdf"></i> Modul
                                    </a>
                                @endif
                                @if($p->laporan)
                                    {{-- <a href="{{ route('mahasiswa.laporan.submit', $p->id) }}" class="action-pill pill-laporan">
                                        <i class="fas fa-upload"></i> Kumpul Laporan
                                    </a> --}}
                                @endif
                                {{-- <a href="{{ route('mahasiswa.presensi.show', $p->id) }}" class="action-pill pill-presensi">
                                    <i class="fas fa-qrcode"></i> Presensi
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-circle-dot"></i></div>
                        <h3>Tidak Ada Pertemuan Aktif</h3>
                        <p>Pertemuan yang sedang berlangsung akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ────────────────────────────────────────────────────────────── --}}
        {{-- TAB: UPCOMING                                                  --}}
        {{-- ────────────────────────────────────────────────────────────── --}}
        <div id="tab-upcoming" class="tab-content">
            <div class="pertemuan-grid">
                @forelse($pertemuanUpcoming as $p)
                    <div class="pertemuan-card card-upcoming">
                        <div class="card-top">
                            <div class="card-number">{{ $p->pertemuan_ke }}</div>
                            <div class="card-meta">
                                <div class="card-praktikum-label">{{ $praktikum?->nama_praktikum }}</div>
                                <div class="card-nama">{{ $p->nama_pertemuan }}</div>
                            </div>
                            <span class="card-badge badge-upcoming">Akan Datang</span>
                        </div>
                        <div class="card-details">
                            @if($p->modul?->judul_modul)
                                <div class="detail-item">
                                    <i class="fas fa-book-open"></i>
                                    {{ $p->modul->judul_modul }}
                                </div>
                            @endif
                            @if($p->deskripsi_pertemuan)
                                <div class="detail-item">
                                    <i class="fas fa-align-left"></i>
                                    {{ Str::limit($p->deskripsi_pertemuan, 80) }}
                                </div>
                            @endif
                            <div class="detail-item">
                                <i class="fas fa-calendar-day"></i>
                                {{ $jadwal->hari ?? '-' }}, {{ $jadwal->jam_mulai ?? '' }} – {{ $jadwal->jam_selesai ?? '' }}
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-door-open"></i>
                                {{ $jadwal->laboratorium?->nama_laboratorium ?? '-' }}
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $jadwal->dosen?->nama ?? '-' }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="action-pills">
                                <span class="action-pill pill-locked">
                                    <i class="fas fa-lock"></i> Belum Dibuka
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-hourglass-half"></i></div>
                        <h3>Tidak Ada Pertemuan Mendatang</h3>
                        <p>Semua pertemuan sudah berlangsung atau Anda belum terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ────────────────────────────────────────────────────────────── --}}
        {{-- TAB: COMPLETED                                                 --}}
        {{-- ────────────────────────────────────────────────────────────── --}}
        <div id="tab-completed" class="tab-content">
            <div class="pertemuan-grid">
                @forelse($pertemuanCompleted as $p)
                    @php
                        $nilaiUser = $p->nilais->where('id_user', $user->id)->first();
                    @endphp
                    <div class="pertemuan-card card-completed">
                        <div class="card-top">
                            <div class="card-number">{{ $p->pertemuan_ke }}</div>
                            <div class="card-meta">
                                <div class="card-praktikum-label">{{ $praktikum?->nama_praktikum }}</div>
                                <div class="card-nama">{{ $p->nama_pertemuan }}</div>
                            </div>
                            <span class="card-badge badge-completed"><i class="fas fa-check"></i> Selesai</span>
                        </div>
                        <div class="card-details">
                            @if($p->modul?->judul_modul)
                                <div class="detail-item">
                                    <i class="fas fa-book-open"></i>
                                    {{ $p->modul->judul_modul }}
                                </div>
                            @endif
                            <div class="detail-item">
                                <i class="fas fa-calendar-day"></i>
                                {{ $jadwal->hari ?? '-' }}, {{ $jadwal->jam_mulai ?? '' }} – {{ $jadwal->jam_selesai ?? '' }}
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-door-open"></i>
                                {{ $jadwal->laboratorium?->nama_laboratorium ?? '-' }}
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $jadwal->dosen?->nama ?? '-' }}
                            </div>
                            @if($nilaiUser)
                                <div class="detail-item" style="margin-top: 6px; padding: 6px 10px; background: var(--green-light); border-radius: 8px; color: var(--green-text); font-weight: 600;">
                                    <i class="fas fa-star" style="color: var(--green-text);"></i>
                                    Nilai: {{ $nilaiUser->nilai_total ?? $nilaiUser->nilai_akhir ?? '-' }}
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="action-pills">
                                @if($p->modul?->filepath)
                                    <a href="{{ asset('storage/' . $p->modul->filepath) }}" target="_blank" class="action-pill pill-modul">
                                        <i class="fas fa-file-pdf"></i> Modul
                                    </a>
                                @endif
                                @if($nilaiUser)
                                    {{-- <a href="{{ route('mahasiswa.nilai.show', $p->id) }}" class="action-pill pill-nilai">
                                        <i class="fas fa-chart-bar"></i> Nilai
                                    </a> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-flag-checkered"></i></div>
                        <h3>Belum Ada Pertemuan Selesai</h3>
                        <p>Riwayat pertemuan yang telah Anda selesaikan akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>

<script>
(function () {
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // Mobile sidebar
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const sidebarNav   = document.getElementById('sidebarNav');
    if (mobileToggle && sidebarNav) {
        mobileToggle.addEventListener('click', () => {
            sidebarNav.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            icon?.classList.toggle('fa-bars');
            icon?.classList.toggle('fa-times');
        });
    }
})();
</script>
</body>
</html>