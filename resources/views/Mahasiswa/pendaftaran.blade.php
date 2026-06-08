{{--
    pendaftaran.blade.php  –  Halaman Pendaftaran Saya (Mahasiswa)
    ─────────────────────────────────────────────────────────────
    KONSEP BARU:
    • User terdaftar di 1 Jadwal → semua Pertemuan dalam jadwal ditampilkan.
    • Setiap pertemuan memiliki status sendiri:
        - Selesai    → pertemuan yang sudah berlangsung (hadir / presensi tercatat)
        - Aktif      → pertemuan yang sedang berlangsung saat ini
        - Upcoming   → pertemuan yang belum tiba
    • Tabel menampilkan detail per baris pertemuan: nama praktikum, ruangan, hari, jam, dosen.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Pendaftaran Saya | Mahasiswa</title>
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
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
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
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            gap: 16px;
            flex-wrap: wrap;
        }
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
            background: linear-gradient(135deg, #10b981, #047857);
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
        .btn-export {
            padding: 10px 22px;
            background: linear-gradient(135deg, #10b981, #047857);
            border: none;
            border-radius: var(--radius-full);
            color: white;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-family: inherit;
            transition: 0.2s;
            box-shadow: 0 4px 12px rgba(16,185,129,0.25);
            white-space: nowrap;
        }
        .btn-export:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16,185,129,0.35); }

        /* ── Jadwal Info Card ── */
        .jadwal-info-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1.5px solid var(--border);
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }
        .ji-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 22px;
            flex-shrink: 0;
        }
        .ji-info { flex: 1; min-width: 180px; }
        .ji-nama { font-size: 1.05rem; font-weight: 800; color: var(--text-primary); }
        .ji-kode { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-top: 2px; }
        .ji-pills { display: flex; flex-wrap: wrap; gap: 8px; }
        .ji-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: var(--radius-full); padding: 5px 13px;
            font-size: 0.78rem; font-weight: 600; color: var(--text-primary);
        }
        .ji-pill i { color: var(--blue); font-size: 0.71rem; }

        /* No-registration banner */
        .no-reg-banner {
            background: var(--amber-light);
            border: 1.5px solid #fcd34d;
            border-radius: var(--radius-lg);
            padding: 18px 24px;
            margin-bottom: 24px;
            display: flex; align-items: center; gap: 14px;
        }
        .no-reg-banner i { color: var(--amber-text); font-size: 20px; }
        .no-reg-banner p { font-size: 0.87rem; color: var(--amber-text); font-weight: 600; }
        .no-reg-banner a {
            margin-left: auto; padding: 8px 20px;
            background: var(--amber); color: white;
            border-radius: var(--radius-full); font-size: 0.83rem;
            font-weight: 700; text-decoration: none; transition: 0.2s; white-space: nowrap;
        }
        .no-reg-banner a:hover { background: var(--amber-text); }

        /* ── Summary Cards ── */
        .summary-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .sum-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow-sm); border: 1.5px solid var(--border);
        }
        .sum-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: white; flex-shrink: 0;
        }
        .sum-info h4 { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 2px; }
        .sum-num { font-size: 2rem; font-weight: 800; color: var(--text-primary); line-height: 1; }

        /* ── Timeline-style pertemuan list ── */
        .pertemuan-section { margin-bottom: 28px; }
        .section-heading {
            display: flex; align-items: center; gap: 10px;
            font-size: 0.82rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.6px; color: var(--text-secondary);
            margin-bottom: 14px; padding-left: 2px;
        }
        .section-heading .heading-dot {
            width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
        }
        .dot-selesai  { background: var(--green); }
        .dot-aktif    { background: var(--blue); }
        .dot-upcoming { background: var(--text-muted); }

        /* ── Table Card ── */
        .table-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1.5px solid var(--border);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .table-card-head {
            display: flex; justify-content: space-between; align-items: center;
            padding: 18px 22px; border-bottom: 1.5px solid var(--border);
            flex-wrap: wrap; gap: 12px;
        }
        .table-card-head h3 {
            font-size: 1rem; font-weight: 700;
            display: flex; align-items: center; gap: 8px; color: var(--text-primary);
        }
        .table-card-head h3 i { color: var(--blue); }

        .search-wrap { position: relative; }
        .search-wrap input {
            padding: 9px 16px 9px 38px;
            border: 1.5px solid var(--border); border-radius: var(--radius-full);
            font-size: 0.84rem; width: 240px; font-family: inherit; transition: 0.2s;
        }
        .search-wrap input:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .search-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px; }

        .table-responsive { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f8fafc; }
        th {
            padding: 12px 14px; text-align: left; font-size: 0.72rem;
            font-weight: 700; color: var(--text-secondary); text-transform: uppercase;
            letter-spacing: 0.5px; border-bottom: 1.5px solid var(--border); white-space: nowrap;
        }
        td { padding: 13px 14px; font-size: 0.83rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: var(--text-primary); }
        tbody tr { transition: background 0.15s; }
        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }

        /* Row accent for active row */
        tbody tr.row-aktif { background: #eff6ff; }
        tbody tr.row-aktif:hover { background: #dbeafe; }
        tbody tr.row-aktif td:first-child {
            border-left: 3px solid var(--blue);
        }
        tbody tr.row-selesai td:first-child {
            border-left: 3px solid var(--green);
        }
        tbody tr.row-upcoming td:first-child {
            border-left: 3px solid var(--border);
        }

        /* Pertemuan number badge in table */
        .p-num-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; border-radius: 8px;
            font-size: 0.78rem; font-weight: 800; color: white; flex-shrink: 0;
        }
        .p-num-selesai  { background: linear-gradient(135deg, var(--green), #047857); }
        .p-num-aktif    { background: linear-gradient(135deg, var(--blue), var(--blue-dark)); }
        .p-num-upcoming { background: linear-gradient(135deg, var(--slate), #334155); }

        /* ── Status Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: var(--radius-full);
            font-size: 0.7rem; font-weight: 700; white-space: nowrap;
        }
        .badge-selesai  { background: var(--green-light); color: var(--green-text); }
        .badge-aktif    { background: var(--blue-light); color: var(--blue-dark); }
        .badge-upcoming { background: var(--slate-light); color: var(--slate); }

        /* Blinking dot for active */
        .blink-dot {
            width: 7px; height: 7px;
            background: var(--blue); border-radius: 50%;
            display: inline-block; animation: blink 1.2s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        .btn-detail {
            padding: 6px 14px; background: var(--blue-light); color: var(--blue-dark);
            border: none; border-radius: var(--radius-full); cursor: pointer;
            font-size: 0.74rem; font-weight: 700; font-family: inherit; transition: 0.2s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-detail:hover { background: var(--blue); color: white; }

        /* ── Table Footer ── */
        .table-foot {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 22px; border-top: 1.5px solid var(--border);
            flex-wrap: wrap; gap: 10px;
        }
        .table-info { font-size: 0.8rem; color: var(--text-secondary); }
        .pagination { display: flex; gap: 6px; align-items: center; }
        .page-btn {
            width: 34px; height: 34px; background: var(--slate-light);
            border: none; border-radius: var(--radius-full); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: var(--text-secondary); transition: 0.2s;
        }
        .page-btn:hover { background: var(--blue); color: white; }
        .page-info { font-size: 0.8rem; color: var(--text-secondary); padding: 0 4px; }

        /* ── Empty Row ── */
        .empty-row td { text-align: center; padding: 48px 24px; color: var(--text-secondary); }
        .empty-row .empty-icon-sm { font-size: 32px; color: var(--text-muted); margin-bottom: 10px; }
        .empty-row p { font-size: 0.9rem; }

        /* ── Detail Modal ── */
        .modal {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.5); z-index: 9999;
            align-items: center; justify-content: center; backdrop-filter: blur(4px);
        }
        .modal.active { display: flex; }
        .modal-box {
            background: var(--surface); border-radius: var(--radius-lg);
            width: 90%; max-width: 520px; max-height: 90vh; overflow-y: auto;
            animation: modalIn 0.25s cubic-bezier(0.34,1.56,0.64,1); box-shadow: var(--shadow-lg);
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-head {
            display: flex; justify-content: space-between; align-items: center;
            padding: 18px 24px; border-bottom: 1.5px solid var(--border);
        }
        .modal-head h3 { font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .modal-head h3 i { color: var(--blue); }
        .modal-close-btn {
            background: var(--slate-light); border: none; width: 30px; height: 30px;
            border-radius: 50%; cursor: pointer; color: var(--text-secondary); font-size: 13px;
            display: flex; align-items: center; justify-content: center; transition: 0.2s;
        }
        .modal-close-btn:hover { background: var(--red-light); color: var(--red); }
        .modal-body { padding: 22px 24px; }
        .detail-section { background: var(--bg); border-radius: var(--radius-md); overflow: hidden; border: 1.5px solid var(--border); }
        .detail-row { display: flex; padding: 11px 16px; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 140px; font-weight: 700; font-size: 0.8rem; color: var(--text-secondary); flex-shrink: 0; }
        .detail-val { flex: 1; font-size: 0.83rem; color: var(--text-primary); }
        .modal-foot { display: flex; justify-content: flex-end; padding: 14px 24px; border-top: 1.5px solid var(--border); }
        .btn-close-modal {
            padding: 9px 24px; background: var(--slate-light); border: none;
            border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600;
            cursor: pointer; color: var(--text-secondary); font-family: inherit; transition: 0.2s;
        }
        .btn-close-modal:hover { background: var(--border); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .main-content { padding: 20px 16px; }
            .page-title { font-size: 1.35rem; }
            .page-subtitle { margin-left: 0; }
            .summary-row { grid-template-columns: 1fr 1fr; }
            .search-wrap input { width: 100%; }
            .table-foot { flex-direction: column; align-items: flex-start; }
            .jadwal-info-card { flex-direction: column; align-items: flex-start; }
        }
        @media (max-width: 480px) {
            .summary-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('mahasiswa/partials/sidebar')

    <main class="main-content">

        {{-- ── Page Header ── --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <span class="title-icon"><i class="fas fa-pen-ruler"></i></span>
                    Pendaftaran Saya
                </h1>
                <p class="page-subtitle">Daftar seluruh pertemuan dari jadwal praktikum Anda</p>
            </div>
            <button class="btn-export" id="exportBtn">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>

        @php
            $user = Auth::user();

            /*
            ┌────────────────────────────────────────────────────────────────┐
            │  Ambil pendaftaran user di 1 jadwal (role Praktikan).          │
            │  Tampilkan semua pertemuan dari jadwal tersebut,               │
            │  dengan status per-pertemuan:                                  │
            │   - Selesai  → presensi sudah ada / status field = Selesai     │
            │   - Aktif    → pertemuan pertama yang belum selesai            │
            │   - Upcoming → sisanya                                         │
            └────────────────────────────────────────────────────────────────┘
            */
            $pendaftaran = \App\Models\PendaftaranPraktikum::with([
                'jadwal.praktikum',
                'jadwal.laboratorium',
                'jadwal.dosen',
                'jadwal.pertemuan' => fn($q) => $q->orderBy('pertemuan_ke'),
                'jadwal.pertemuan.modul',
                'jadwal.pertemuan.presensis',
                'jadwal.pertemuan.nilais',
                'jadwal.pertemuan.laporan',
            ])
            ->where('id_user', $user->id)
            ->where('role', 'Praktikan')
            ->first();

            $isRegistered = !is_null($pendaftaran);
            $jadwal       = $pendaftaran?->jadwal;
            $praktikum    = $jadwal?->praktikum;

            $pertemuanRows = collect();
            $activeFound   = false;

            if ($isRegistered && $jadwal) {
                $allPertemuan = $jadwal->pertemuan->sortBy('pertemuan_ke');

                foreach ($allPertemuan as $p) {
                    $sudahHadir = $p->presensis->where('id_user', $user->id)->isNotEmpty();
                    $pStatus    = $p->status ?? null;
                    $nilaiUser  = $p->nilais->where('id_user', $user->id)->first();

                    if ($pStatus === 'Selesai' || $sudahHadir) {
                        $rowStatus = 'Selesai';
                    } elseif (!$activeFound && ($pStatus === 'Aktif' || $jadwal->status !== 'Selesai')) {
                        $rowStatus   = 'Aktif';
                        $activeFound = true;
                    } else {
                        $rowStatus = 'Upcoming';
                    }

                    $pertemuanRows->push([
                        'id'            => $p->id,
                        'pertemuan_ke'  => $p->pertemuan_ke,
                        'nama'          => $p->nama_pertemuan,
                        'materi'        => $p->modul?->judul_modul ?? $p->deskripsi_pertemuan ?? '-',
                        'hari'          => $jadwal->hari ?? '-',
                        'jam'           => ($jadwal->jam_mulai ?? '-') . ' – ' . ($jadwal->jam_selesai ?? '-'),
                        'lab'           => $jadwal->laboratorium?->nama_laboratorium ?? '-',
                        'dosen'         => $jadwal->dosen?->nama ?? '-',
                        'praktikum'     => $praktikum?->nama_praktikum ?? '-',
                        'kode'          => $praktikum?->kode_praktikum ?? '-',
                        'status'        => $rowStatus,
                        'nilai_total'   => $nilaiUser?->nilai_total ?? $nilaiUser?->nilai_akhir ?? null,
                        'has_laporan'   => !is_null($p->laporan),
                        // 'laporan_url'   => route('mahasiswa.laporan.submit', $p->id),
                        // 'nilai_url'     => route('mahasiswa.nilai.show', $p->id),
                    ]);
                }
            }

            $totalPertemuan   = $pertemuanRows->count();
            $countSelesai     = $pertemuanRows->where('status', 'Selesai')->count();
            $countAktif       = $pertemuanRows->where('status', 'Aktif')->count();
            $countUpcoming    = $pertemuanRows->where('status', 'Upcoming')->count();
        @endphp

        {{-- ── Jadwal Info Card ── --}}
        @if($isRegistered && $jadwal)
            <div class="jadwal-info-card">
                <div class="ji-icon"><i class="fas fa-flask"></i></div>
                <div class="ji-info">
                    <div class="ji-nama">{{ $praktikum?->nama_praktikum ?? '-' }}</div>
                    <div class="ji-kode">{{ $praktikum?->kode_praktikum ?? '-' }}</div>
                </div>
                <div class="ji-pills">
                    <span class="ji-pill"><i class="fas fa-calendar-day"></i> {{ $jadwal->hari ?? '-' }}</span>
                    <span class="ji-pill"><i class="fas fa-clock"></i> {{ $jadwal->jam_mulai ?? '-' }} – {{ $jadwal->jam_selesai ?? '-' }}</span>
                    <span class="ji-pill"><i class="fas fa-door-open"></i> {{ $jadwal->laboratorium?->nama_laboratorium ?? '-' }}</span>
                    <span class="ji-pill"><i class="fas fa-chalkboard-teacher"></i> {{ $jadwal->dosen?->nama ?? '-' }}</span>
                </div>
            </div>
        @else
            <div class="no-reg-banner">
                <i class="fas fa-exclamation-circle"></i>
                <p>Anda belum terdaftar di jadwal praktikum manapun.</p>
                <a href="{{ route('mahasiswa.praktikum.daftar') }}">Daftar Sekarang</a>
            </div>
        @endif

        {{-- ── Summary Cards ── --}}
        <div class="summary-row">
            <div class="sum-card">
                <div class="sum-icon" style="background:linear-gradient(135deg,#3b82f6,#1e40af)">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="sum-info">
                    <h4>Total Pertemuan</h4>
                    <div class="sum-num">{{ $totalPertemuan }}</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
                    <i class="fas fa-circle-dot"></i>
                </div>
                <div class="sum-info">
                    <h4>Sedang Aktif</h4>
                    <div class="sum-num">{{ $countAktif }}</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon" style="background:linear-gradient(135deg,#10b981,#047857)">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="sum-info">
                    <h4>Selesai</h4>
                    <div class="sum-num">{{ $countSelesai }}</div>
                </div>
            </div>
            <div class="sum-card">
                <div class="sum-icon" style="background:linear-gradient(135deg,#64748b,#334155)">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="sum-info">
                    <h4>Akan Datang</h4>
                    <div class="sum-num">{{ $countUpcoming }}</div>
                </div>
            </div>
        </div>

        {{-- ── Table Card ── --}}
        <div class="table-card">
            <div class="table-card-head">
                <h3><i class="fas fa-table-list"></i> Daftar Pertemuan Praktikum</h3>
                <div class="search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari pertemuan, materi...">
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pertemuan</th>
                            <th>Materi / Modul</th>
                            <th>Hari & Waktu</th>
                            <th>Ruangan</th>
                            <th>Dosen</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>

            <div class="table-foot">
                <span class="table-info" id="tableInfo">—</span>
                <div class="pagination">
                    <button class="page-btn" id="prevPage"><i class="fas fa-chevron-left"></i></button>
                    <span class="page-info" id="pageInfo">Halaman 1</span>
                    <button class="page-btn" id="nextPage"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- ── Detail Modal ── --}}
<div class="modal" id="detailModal">
    <div class="modal-box">
        <div class="modal-head">
            <h3><i class="fas fa-info-circle"></i> Detail Pertemuan</h3>
            <button class="modal-close-btn" id="closeDetailModal"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="detail-section" id="detailContent"></div>
        </div>
        <div class="modal-foot">
            <button class="btn-close-modal" id="closeDetailBtn">Tutup</button>
        </div>
    </div>
</div>

@php
    // Serialize for JS
    $jsData = $pertemuanRows->values()->toArray();
@endphp

<script>
(function () {
    const allData    = @json($jsData);
    let filteredData = [...allData];
    let currentPage  = 1;
    const rowsPerPage = 10;

    function numBadgeClass(status) {
        if (status === 'Selesai')  return 'p-num-selesai';
        if (status === 'Aktif')    return 'p-num-aktif';
        return 'p-num-upcoming';
    }
    function rowClass(status) {
        if (status === 'Aktif')   return 'row-aktif';
        if (status === 'Selesai') return 'row-selesai';
        return 'row-upcoming';
    }
    function statusBadge(status) {
        if (status === 'Selesai')  return `<span class="badge badge-selesai"><i class="fas fa-check"></i> Selesai</span>`;
        if (status === 'Aktif')    return `<span class="badge badge-aktif"><span class="blink-dot"></span> Aktif</span>`;
        return `<span class="badge badge-upcoming"><i class="fas fa-hourglass-half"></i> Akan Datang</span>`;
    }
    function truncate(str, n) {
        return str && str.length > n ? str.substring(0, n) + '…' : (str || '-');
    }

    function renderTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const searched = filteredData.filter(d =>
            d.nama.toLowerCase().includes(search) ||
            d.materi.toLowerCase().includes(search) ||
            d.dosen.toLowerCase().includes(search)
        );

        const total   = searched.length;
        const totalPg = Math.max(1, Math.ceil(total / rowsPerPage));
        if (currentPage > totalPg) currentPage = totalPg;

        const start    = (currentPage - 1) * rowsPerPage;
        const pageData = searched.slice(start, start + rowsPerPage);

        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if (!pageData.length) {
            tbody.innerHTML = `
                <tr class="empty-row">
                    <td colspan="8">
                        <div class="empty-icon-sm"><i class="fas fa-inbox"></i></div>
                        <p>Tidak ada data pertemuan ditemukan.</p>
                    </td>
                </tr>`;
        } else {
            pageData.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = rowClass(item.status);
                tr.innerHTML = `
                    <td>
                        <span class="p-num-badge ${numBadgeClass(item.status)}">${item.pertemuan_ke}</span>
                    </td>
                    <td>
                        <div style="font-weight:700;">${item.nama}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);">${item.praktikum} · ${item.kode}</div>
                    </td>
                    <td style="max-width:200px;">${truncate(item.materi, 60)}</td>
                    <td>
                        <div style="font-weight:600;">${item.hari}</div>
                        <div style="font-size:0.78rem;color:var(--text-secondary);">${item.jam}</div>
                    </td>
                    <td>${item.lab}</td>
                    <td>${item.dosen}</td>
                    <td>${statusBadge(item.status)}</td>
                    <td>
                        <button class="btn-detail" data-id="${item.id}">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>`;
                tbody.appendChild(tr);
            });

            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', () => openDetail(+btn.dataset.id));
            });
        }

        document.getElementById('pageInfo').textContent  = `Halaman ${currentPage} dari ${totalPg}`;
        document.getElementById('tableInfo').textContent = `Menampilkan ${Math.min(start + 1, total)}–${Math.min(start + rowsPerPage, total)} dari ${total} pertemuan`;
    }

    function openDetail(id) {
        const item = allData.find(d => d.id === id);
        if (!item) return;
        document.getElementById('detailContent').innerHTML = `
            <div class="detail-row"><span class="detail-label">Praktikum</span><span class="detail-val">${item.praktikum} (${item.kode})</span></div>
            <div class="detail-row"><span class="detail-label">Pertemuan ke-</span><span class="detail-val">${item.pertemuan_ke} — ${item.nama}</span></div>
            <div class="detail-row"><span class="detail-label">Materi / Modul</span><span class="detail-val">${item.materi}</span></div>
            <div class="detail-row"><span class="detail-label">Hari & Waktu</span><span class="detail-val">${item.hari}, ${item.jam}</span></div>
            <div class="detail-row"><span class="detail-label">Ruangan</span><span class="detail-val">${item.lab}</span></div>
            <div class="detail-row"><span class="detail-label">Dosen</span><span class="detail-val">${item.dosen}</span></div>
            <div class="detail-row"><span class="detail-label">Status</span><span class="detail-val">${item.status}</span></div>
            ${item.nilai_total !== null ? `<div class="detail-row"><span class="detail-label">Nilai</span><span class="detail-val">${item.nilai_total}</span></div>` : ''}
        `;
        document.getElementById('detailModal').classList.add('active');
    }

    function closeDetail() { document.getElementById('detailModal').classList.remove('active'); }

    function exportCSV() {
        let csv = 'Pertemuan ke,Nama Pertemuan,Praktikum,Materi,Hari,Waktu,Ruangan,Dosen,Status\n';
        filteredData.forEach(d => {
            csv += `"${d.pertemuan_ke}","${d.nama}","${d.praktikum}","${d.materi}","${d.hari}","${d.jam}","${d.lab}","${d.dosen}","${d.status}"\n`;
        });
        const url = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8;' }));
        Object.assign(document.createElement('a'), { href: url, download: 'pendaftaran_saya.csv' }).click();
        URL.revokeObjectURL(url);
    }

    // Events
    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; renderTable(); });
    document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); } });
    document.getElementById('nextPage').addEventListener('click', () => {
        const search   = document.getElementById('searchInput').value.toLowerCase();
        const searched = filteredData.filter(d =>
            d.nama.toLowerCase().includes(search) ||
            d.materi.toLowerCase().includes(search) ||
            d.dosen.toLowerCase().includes(search)
        );
        const totalPg = Math.ceil(searched.length / rowsPerPage);
        if (currentPage < totalPg) { currentPage++; renderTable(); }
    });
    document.getElementById('exportBtn').addEventListener('click', exportCSV);
    document.getElementById('closeDetailModal').addEventListener('click', closeDetail);
    document.getElementById('closeDetailBtn').addEventListener('click', closeDetail);
    window.addEventListener('click', e => { if (e.target.id === 'detailModal') closeDetail(); });

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

    renderTable();
})();
</script>
</body>
</html>