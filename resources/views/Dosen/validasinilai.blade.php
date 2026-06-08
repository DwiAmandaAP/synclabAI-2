<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Nilai Dosen | Portal Akademik</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-bg: #1a2035;
            --sidebar-width: 260px;
            --accent: #4f8ef7;
            --accent-green: #22c55e;
            --accent-orange: #f59e0b;
            --accent-purple: #a855f7;
            --accent-red: #ef4444;
            --bg: #f1f5fb;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-mid: #64748b;
            --text-light: #94a3b8;
            --border: #e2e8f0;
            --shadow: 0 2px 12px rgba(0,0,0,0.07);
            --radius: 14px;
            --radius-sm: 8px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-profile {
            padding: 32px 20px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-avatar {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f8ef7, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 28px; color: #fff;
        }

        .sidebar-name {
            font-weight: 700;
            font-size: 15px;
            color: #fff;
            line-height: 1.3;
        }

        .sidebar-role {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-item:hover { background: rgba(255,255,255,0.07); color: #fff; }
        .nav-item.active { background: rgba(79,142,247,0.18); color: #4f8ef7; }
        .nav-item i { width: 18px; text-align: center; font-size: 15px; }

        .sidebar-logout {
            padding: 16px 12px 24px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 11px;
            border-radius: 10px;
            background: rgba(239,68,68,0.15);
            color: #ef4444;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.28); }

        /* ── MAIN ───────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 32px 32px 48px;
            min-height: 100vh;
        }

        /* ── PAGE HEADER ────────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            font-size: 22px;
            color: var(--accent);
        }

        .page-title h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .btn-export {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--accent-green);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn-export:hover { opacity: 0.85; }

        /* ── FILTER CARD ────────────────────────────── */
        .filter-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px 24px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-mid);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-select {
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            background: var(--white);
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2364748b' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            transition: border-color 0.2s;
        }
        .filter-select:focus { outline: none; border-color: var(--accent); }

        .btn-apply {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.2s;
        }
        .btn-apply:hover { opacity: 0.85; }

        /* ── STATS CARDS ────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-icon.blue   { background: rgba(79,142,247,0.12); color: var(--accent); }
        .stat-icon.green  { background: rgba(34,197,94,0.12);  color: var(--accent-green); }
        .stat-icon.orange { background: rgba(245,158,11,0.12); color: var(--accent-orange); }
        .stat-icon.purple { background: rgba(168,85,247,0.12); color: var(--accent-purple); }

        .stat-info { flex: 1; }
        .stat-label { font-size: 12px; color: var(--text-mid); font-weight: 500; }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--text-dark); line-height: 1.2; margin-top: 2px; }

        /* ── TABLE CARD ─────────────────────────────── */
        .table-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
        }

        .table-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .table-search {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            width: 240px;
        }

        .table-search input {
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 13px;
            color: var(--text-dark);
            background: transparent;
            width: 100%;
        }

        .table-search input::placeholder { color: var(--text-light); }
        .table-search i { color: var(--text-light); font-size: 13px; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 13px 18px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-mid);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }

        td {
            padding: 14px 18px;
            font-size: 13.5px;
            color: var(--text-dark);
            vertical-align: middle;
        }

        .td-name { font-weight: 600; }
        .td-nim  { color: var(--text-mid); font-size: 12.5px; }
        .td-num  { font-weight: 600; }
        .td-num.bold { font-weight: 700; }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pending    { background: rgba(245,158,11,0.12); color: #b45309; }
        .badge-validated  { background: rgba(34,197,94,0.12);  color: #15803d; }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Action buttons */
        .actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action {
            width: 32px; height: 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            transition: opacity 0.2s, transform 0.15s;
            text-decoration: none;
        }
        .btn-action:hover { opacity: 0.8; transform: scale(1.08); }

        .btn-validate { background: rgba(34,197,94,0.15);  color: #16a34a; }
        .btn-edit     { background: rgba(79,142,247,0.15); color: #1d4ed8; }
        .btn-detail   { background: rgba(100,116,139,0.15); color: #475569; }
        .btn-delete   { background: rgba(239,68,68,0.15);  color: #dc2626; }

        /* Empty state */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--text-mid);
        }
        .empty-state i { font-size: 40px; margin-bottom: 14px; opacity: 0.4; }
        .empty-state p { font-size: 15px; }

        /* ── PAGINATION ──────────────────────────────── */
        .pagination-wrap {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-mid);
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: var(--text-mid);
            border: 1.5px solid var(--border);
            transition: all 0.15s;
            line-height: 1;
        }

        .pagination li a:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .pagination li.active span,
        .pagination li span[aria-current="page"] {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .pagination li.disabled span {
            opacity: 0.4;
            cursor: default;
        }

        /* Override Laravel default pagination SVG arrows size */
        .pagination li a svg,
        .pagination li span svg {
            width: 12px !important;
            height: 12px !important;
        }

        /* ── MODAL ───────────────────────────────────── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }

        .modal {
            background: var(--white);
            border-radius: var(--radius);
            padding: 28px 32px;
            width: 420px;
            max-width: 95vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        }

        .modal h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-mid);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .btn-cancel {
            padding: 10px 20px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            background: transparent;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-mid);
            cursor: pointer;
        }

        .btn-save {
            padding: 10px 24px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-save:hover { opacity: 0.85; }

        /* Alert */
        .alert {
            padding: 12px 18px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        .alert-success { background: rgba(34,197,94,0.1); color: #15803d; border-left: 4px solid #22c55e; }
        .alert-error   { background: rgba(239,68,68,0.1); color: #dc2626; border-left: 4px solid #ef4444; }

        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .main { padding: 20px 16px 40px; }
        }
    </style>
</head>
<body>

<!-- ── SIDEBAR ────────────────────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-profile">
        <div class="sidebar-avatar"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="sidebar-name">{{ Auth::user()->nama ?? Auth::user()->name ?? 'Dosen' }}</div>
        <div class="sidebar-role">{{ Auth::user()->prodi ?? 'Dosen' }}</div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('monitoring') }}" class="nav-item">
            <i class="fas fa-eye"></i> Monitoring
        </a>
        <a href="{{ route('validasinilai') }}" class="nav-item active">
            <i class="fas fa-check-double"></i> Validasi Nilai
        </a>
        <a href="{{ route('presensi') }}" class="nav-item">
            <i class="fas fa-fingerprint"></i> Presensi
        </a>
    </nav>

    <div class="sidebar-logout">
        <a href="{{ route('logout') }}" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Log Out
        </a>
    </div>
</aside>

<!-- ── MAIN ───────────────────────────────────────────── -->
<main class="main">

    @php
        // Compute stats across ALL records (not just current page)
        $totalNilai      = \App\Models\Nilai::count();
        $totalValidated  = \App\Models\Nilai::where('status', 'Tervalidasi')->count();
        $totalPending    = \App\Models\Nilai::where(function($q){ $q->whereNull('status')->orWhere('status','Pending'); })->count();
        $avgNilaiAkhir   = number_format(\App\Models\Nilai::avg('nilai_akhir') ?? 0, 1);
    @endphp

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-check-double"></i>
            <h1>Validasi Nilai</h1>
        </div>
        <a href="{{ url()->current() }}?export=csv&praktikum={{ $filterPraktikum }}&status={{ $filterStatus }}"
           class="btn-export">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <!-- Filter -->
    <form method="GET" action="{{ route('validasinilai') }}">
        <div class="filter-card">
            <div class="filter-group">
                <span class="filter-label"><i class="fas fa-flask"></i> Praktikum</span>
                <select name="praktikum" class="filter-select">
                    <option value="all" {{ $filterPraktikum === 'all' ? 'selected' : '' }}>Semua Praktikum</option>
                    @foreach($praktikums as $p)
                        <option value="{{ $p->nama_praktikum }}"
                            {{ $filterPraktikum === $p->nama_praktikum ? 'selected' : '' }}>
                            {{ $p->nama_praktikum }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <span class="filter-label"><i class="fas fa-tag"></i> Status</span>
                <select name="status" class="filter-select">
                    <option value="all"       {{ $filterStatus === 'all'       ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending"   {{ $filterStatus === 'pending'   ? 'selected' : '' }}>Belum Tervalidasi</option>
                    <option value="validated" {{ $filterStatus === 'validated' ? 'selected' : '' }}>Sudah Tervalidasi</option>
                </select>
            </div>

            <button type="submit" class="btn-apply">
                <i class="fas fa-search"></i> Terapkan
            </button>
        </div>
    </form>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Nilai</div>
                <div class="stat-value">{{ $totalNilai }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-label">Tervalidasi</div>
                <div class="stat-value">{{ $totalValidated }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="stat-label">Belum Tervalidasi</div>
                <div class="stat-value">{{ $totalPending }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-chart-bar"></i></div>
            <div class="stat-info">
                <div class="stat-label">Rata-rata Nilai</div>
                <div class="stat-value">{{ $avgNilaiAkhir }}</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">
                <i class="fas fa-list-alt" style="color:var(--accent)"></i>
                Daftar Nilai Mahasiswa
            </div>
            <div class="table-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama atau NIM...">
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table id="nilaiTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Mata Kuliah</th>
                        <th>Pretest</th>
                        <th>Laporan</th>
                        <th>Nilai Akhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $nilai)
                    <tr class="data-row"
                        data-nama="{{ strtolower(optional($nilai->user)->nama ?? '') }}"
                        data-nim="{{ optional($nilai->user)->nomor_induk ?? '' }}">
                        <td class="td-name">{{ optional($nilai->user)->nama ?? '-' }}</td>
                        <td class="td-nim">{{ optional($nilai->user)->nomor_induk ?? '-' }}</td>
                        <td>{{ optional($nilai->pertemuan?->praktikum)->nama_praktikum ?? (optional($nilai->pertemuan)->nama_pertemuan ?? '-') }}</td>
                        <td class="td-num">{{ $nilai->nilai_pretest ?? 0 }}</td>
                        <td class="td-num">{{ $nilai->nilai_laporan ?? 0 }}</td>
                        <td class="td-num bold">{{ number_format($nilai->nilai_akhir ?? 0, 1) }}</td>
                        <td>
                            @if($nilai->status === 'Tervalidasi')
                                <span class="badge badge-validated">
                                    <span class="badge-dot"></span> Tervalidasi
                                </span>
                            @else
                                <span class="badge badge-pending">
                                    <span class="badge-dot"></span> Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                @if($nilai->status !== 'Tervalidasi')
                                <button class="btn-action btn-validate"
                                        title="Validasi"
                                        onclick="openValidasiModal({{ $nilai->id }}, {{ $nilai->nilai_akhir ?? 0 }})">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                                <button class="btn-action btn-edit"
                                        title="Edit"
                                        onclick="openEditModal({{ $nilai->id }}, {{ $nilai->nilai_pretest ?? 0 }}, {{ $nilai->nilai_laporan ?? 0 }}, {{ $nilai->nilai_akhir ?? 0 }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-detail"
                                        title="Detail"
                                        onclick="openDetailModal({{ $nilai->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-delete"
                                        title="Hapus"
                                        onclick="confirmDelete({{ $nilai->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Tidak ada data nilai ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($nilais->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Menampilkan {{ $nilais->firstItem() }}–{{ $nilais->lastItem() }}
                dari {{ $nilais->total() }} data
            </div>
            {{ $nilais->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</main>

<!-- ── MODAL: Validasi ──────────────────────────────────── -->
<div class="modal-overlay" id="validasiModal">
    <div class="modal">
        <h3><i class="fas fa-check-circle" style="color:var(--accent-green);margin-right:8px"></i>Validasi Nilai</h3>
        <form id="validasiForm" method="POST">
            @csrf
            @method('POST')
            <div class="form-group">
                <label>Nilai Akhir</label>
                <input type="number" name="nilai_akhir" id="validasiNilaiAkhir"
                       min="0" max="100" step="0.1" required>
            </div>
            <div class="form-group">
                <label>Komentar (opsional)</label>
                <textarea name="komentar" rows="3" style="resize:vertical"
                          placeholder="Tambahkan komentar..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('validasiModal')">Batal</button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-check"></i> Validasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL: Edit ─────────────────────────────────────── -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <h3><i class="fas fa-edit" style="color:var(--accent);margin-right:8px"></i>Edit Nilai</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nilai Pretest</label>
                <input type="number" name="nilai_pretest" id="editPretest" min="0" max="100">
            </div>
            <div class="form-group">
                <label>Nilai Laporan</label>
                <input type="number" name="nilai_laporan" id="editLaporan" min="0" max="100">
            </div>
            <div class="form-group">
                <label>Nilai Akhir</label>
                <input type="number" name="nilai_akhir" id="editNilaiAkhir" min="0" max="100" step="0.1">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete form (hidden) -->
<form id="deleteForm" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

<script>
    // Search filter
    document.getElementById('searchInput').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.data-row').forEach(row => {
            const nama = row.dataset.nama;
            const nim  = row.dataset.nim;
            row.style.display = (nama.includes(q) || nim.includes(q)) ? '' : 'none';
        });
    });

    function openValidasiModal(id, nilaiAkhir) {
        const form = document.getElementById('validasiForm');
        form.action = '/dosen/validasi-nilai/' + id;
        document.getElementById('validasiNilaiAkhir').value = nilaiAkhir;
        document.getElementById('validasiModal').classList.add('open');
    }

    function openEditModal(id, pretest, laporan, nilaiAkhir) {
        const form = document.getElementById('editForm');
        form.action = '/api/nilai/' + id;
        document.getElementById('editPretest').value   = pretest;
        document.getElementById('editLaporan').value   = laporan;
        document.getElementById('editNilaiAkhir').value = nilaiAkhir;
        document.getElementById('editModal').classList.add('open');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    function confirmDelete(id) {
        if (confirm('Yakin ingin menghapus nilai ini?')) {
            const form = document.getElementById('deleteForm');
            form.action = '/api/nilai/' + id;
            form.submit();
        }
    }

    function openDetailModal(id) {
        window.location.href = '/api/nilai/' + id;
    }

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });
    });
</script>

</body>
</html>