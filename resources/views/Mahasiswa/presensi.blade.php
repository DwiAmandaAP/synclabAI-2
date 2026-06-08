<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Presensi | Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
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
            width: 100%;
        }

        .main-content {
            flex: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            padding: 28px 32px;
            min-width: 0;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1e293b;
        }

        .filter-section {
            background: white;
            border-radius: 24px;
            padding: 20px 24px;
            margin-bottom: 28px;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: flex-end;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
        }

        .filter-group select {
            padding: 10px 14px;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .apply-filter-btn {
            padding: 10px 24px;
            background: #3b82f6;
            border: none;
            border-radius: 40px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .apply-filter-btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .presensi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            width: 100%;
        }

        .presensi-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .presensi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            padding: 20px;
            color: white;
        }

        .card-header h3 {
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .kehadiran-circle {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 16px;
        }

        .kehadiran-circle svg {
            width: 140px;
            height: 140px;
            transform: rotate(-90deg);
        }

        .kehadiran-circle circle {
            fill: none;
            stroke-width: 10;
        }

        .circle-bg {
            stroke: #e2e8f0;
        }

        .circle-progress {
            stroke: #10b981;
            stroke-linecap: round;
            transition: stroke-dasharray 0.5s ease;
        }

        .circle-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .circle-text .percent {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
        }

        .circle-text .label {
            font-size: 0.7rem;
            color: #64748b;
        }

        .kehadiran-stats {
            display: flex;
            justify-content: space-around;
            margin: 16px 0;
            flex-wrap: wrap;
            gap: 12px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.7rem;
            color: #64748b;
        }

        .stat-hadir .stat-value { color: #10b981; }
        .stat-izin .stat-value  { color: #f59e0b; }
        .stat-sakit .stat-value { color: #8b5cf6; }
        .stat-alpha .stat-value { color: #ef4444; }

        .btn-lihat {
            width: 100%;
            padding: 10px;
            background: #f1f5f9;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-lihat:hover {
            background: #3b82f6;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 28px;
            width: 90%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalFadeIn 0.3s ease;
        }

        .modal-large {
            max-width: 600px;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-header h3 {
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #94a3b8;
        }

        .modal-close:hover { color: #ef4444; }

        .modal-body    { padding: 24px; }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 16px 24px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-cancel {
            padding: 8px 20px;
            background: #f1f5f9;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 500;
        }

        .detail-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .detail-header h2 {
            font-size: 1.3rem;
            margin-bottom: 4px;
        }

        .detail-header p {
            font-size: 0.85rem;
            color: #64748b;
        }

        .detail-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .summary-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 12px;
            text-align: center;
        }

        .summary-box .summary-label  { font-size: 0.7rem; color: #64748b; margin-bottom: 4px; }
        .summary-box .summary-number { font-size: 1.3rem; font-weight: 700; }

        .summary-box.hadir .summary-number { color: #10b981; }
        .summary-box.izin  .summary-number { color: #f59e0b; }
        .summary-box.sakit .summary-number { color: #8b5cf6; }
        .summary-box.alpha .summary-number { color: #ef4444; }

        .pertemuan-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pertemuan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #f8fafc;
            border-radius: 16px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pertemuan-info   { flex: 1; }
        .pertemuan-title  { font-weight: 600; font-size: 0.9rem; }
        .pertemuan-date   { font-size: 0.7rem; color: #64748b; }

        .status-badge {
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-hadir { background: #dcfce7; color: #16a34a; }
        .status-izin  { background: #fef3c7; color: #d97706; }
        .status-sakit { background: #e9d5ff; color: #7c3aed; }
        .status-alpha { background: #fee2e2; color: #dc2626; }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .sidebar { width: 100%; }
            .sidebar-nav { display: none; padding: 0 20px 20px 20px; }
            .sidebar-nav.active { display: flex; }
            .profile-section { flex-direction: row; gap: 16px; text-align: left; }
            .avatar-circle { width: 60px; height: 60px; margin: 0; }
            .main-content { padding: 20px; }
            .page-title { font-size: 1.4rem; margin-bottom: 16px; justify-content: center; width: 100%; text-align: center; }
            .filter-section { flex-direction: column; }
            .filter-group { width: 100%; }
            .presensi-grid { grid-template-columns: 1fr; }
            .detail-summary { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .main-content { padding: 16px; }
            .kehadiran-circle { width: 120px; height: 120px; }
            .kehadiran-circle svg { width: 120px; height: 120px; }
            .modal-content { width: 95%; }
        }
    </style>

    <div class="dashboard-container">
        @include('mahasiswa/partials/sidebar')

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-fingerprint"></i> My Presensi</h1>
            </div>

            <div class="filter-section">
                <div class="filter-group">
                    <label><i class="fas fa-flask"></i> Mata Kuliah</label>
                    <select id="filterMatkul">
                        <option value="all">Semua Mata Kuliah</option>
                        @foreach($presensiPerPraktikum as $praktikum => $data)
                            <option value="{{ $praktikum }}">{{ $praktikum }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="apply-filter-btn" id="applyFilter">
                    <i class="fas fa-search"></i> Terapkan
                </button>
            </div>

            {{-- ✅ Grid pembungkus kartu --}}
            <div class="presensi-grid" id="presensiGrid">
                @forelse($presensiPerPraktikum as $praktikumNama => $presensiList)
                    @php
                        $hadir = $presensiList->where('kehadiran', 'Hadir')->count();
                        $izin  = $presensiList->where('kehadiran', 'Izin')->count();
                        $sakit = $presensiList->where('kehadiran', 'Sakit')->count();
                        $alpha = $presensiList->where('kehadiran', 'Alpha')->count();
                        $total = $presensiList->count();
                        $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
                        $circumference = 377;
                        $dashArray = round($circumference * $persen / 100);
                    @endphp
                    <div class="presensi-card" data-praktikum="{{ $praktikumNama }}">
                        <div class="card-header">
                            <h3>{{ $praktikumNama }}</h3>
                            <p>{{ $total }} Pertemuan</p>
                        </div>
                        <div class="card-body">
                            <div class="kehadiran-circle">
                                <svg viewBox="0 0 140 140">
                                    <circle class="circle-bg" cx="70" cy="70" r="60"></circle>
                                    <circle class="circle-progress" cx="70" cy="70" r="60"
                                        stroke-dasharray="{{ $dashArray }} {{ $circumference }}"
                                        stroke-dashoffset="0">
                                    </circle>
                                </svg>
                                <div class="circle-text">
                                    <div class="percent">{{ $persen }}%</div>
                                    <div class="label">Kehadiran</div>
                                </div>
                            </div>
                            <div class="kehadiran-stats">
                                <div class="stat-item stat-hadir">
                                    <div class="stat-value">{{ $hadir }}</div>
                                    <div class="stat-label">Hadir</div>
                                </div>
                                <div class="stat-item stat-izin">
                                    <div class="stat-value">{{ $izin }}</div>
                                    <div class="stat-label">Izin</div>
                                </div>
                                <div class="stat-item stat-sakit">
                                    <div class="stat-value">{{ $sakit }}</div>
                                    <div class="stat-label">Sakit</div>
                                </div>
                                <div class="stat-item stat-alpha">
                                    <div class="stat-value">{{ $alpha }}</div>
                                    <div class="stat-label">Alpha</div>
                                </div>
                            </div>
                            <button class="btn-lihat" onclick="showDetailModal('{{ $praktikumNama }}')">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada data presensi</div>
                @endforelse
            </div>
            {{-- akhir presensi-grid --}}
        </main>
    </div>

    {{-- ✅ Modal Detail — di luar .dashboard-container --}}
    <div class="modal" id="detailModal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-check"></i> Detail Presensi</h3>
                <button class="modal-close" id="closeDetailModal">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody">
                @foreach($presensiPerPraktikum as $praktikumNama => $presensiList)
                    @php
                        $hadir = $presensiList->where('kehadiran', 'Hadir')->count();
                        $izin  = $presensiList->where('kehadiran', 'Izin')->count();
                        $sakit = $presensiList->where('kehadiran', 'Sakit')->count();
                        $alpha = $presensiList->where('kehadiran', 'Alpha')->count();
                    @endphp
                    <div id="detail-{{ Str::slug($praktikumNama) }}" style="display:none;">
                        <div class="detail-header">
                            <h2>{{ $praktikumNama }}</h2>
                            <p>Rekap Kehadiran per Pertemuan</p>
                        </div>
                        <div class="detail-summary">
                            <div class="summary-box hadir">
                                <div class="summary-label">Hadir</div>
                                <div class="summary-number">{{ $hadir }}</div>
                            </div>
                            <div class="summary-box izin">
                                <div class="summary-label">Izin</div>
                                <div class="summary-number">{{ $izin }}</div>
                            </div>
                            <div class="summary-box sakit">
                                <div class="summary-label">Sakit</div>
                                <div class="summary-number">{{ $sakit }}</div>
                            </div>
                            <div class="summary-box alpha">
                                <div class="summary-label">Alpha</div>
                                <div class="summary-number">{{ $alpha }}</div>
                            </div>
                        </div>
                        <div class="pertemuan-list">
                            @foreach($presensiList as $presensi)
                                <div class="pertemuan-item">
                                    <div class="pertemuan-info">
                                        <div class="pertemuan-title">
                                            {{ $presensi->pertemuan?->nama_pertemuan ?? 'Pertemuan' }}
                                        </div>
                                        <div class="pertemuan-date">
                                            {{ $presensi->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                    <span class="status-badge status-{{ strtolower($presensi->kehadiran) }}">
                                        {{ $presensi->kehadiran }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                {{-- ✅ @foreach tidak butuh @empty, jadi tidak pakai @forelse di sini --}}
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" id="closeDetailModalBtn">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function showDetailModal(praktikumNama) {
                // Sembunyikan semua panel detail
                document.querySelectorAll('[id^="detail-"]').forEach(el => {
                    el.style.display = 'none';
                });

                // Buat slug yang sama persis dengan Blade Str::slug
                const slug = praktikumNama
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-');

                const detailEl = document.getElementById('detail-' + slug);
                if (detailEl) {
                    detailEl.style.display = 'block';
                }

                document.getElementById('detailModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('detailModal').classList.remove('active');
            }

            document.getElementById('closeDetailModal').addEventListener('click', closeModal);
            document.getElementById('closeDetailModalBtn').addEventListener('click', closeModal);
            window.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal')) closeModal();
            });

            // Filter kartu
            document.getElementById('applyFilter').addEventListener('click', function () {
                const selected = document.getElementById('filterMatkul').value;
                document.querySelectorAll('.presensi-card[data-praktikum]').forEach(card => {
                    card.style.display =
                        (selected === 'all' || card.dataset.praktikum === selected)
                            ? 'block' : 'none';
                });
            });

            // Mobile menu toggle
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const sidebarNav   = document.getElementById('sidebarNav');
            if (mobileToggle && sidebarNav) {
                mobileToggle.addEventListener('click', () => {
                    sidebarNav.classList.toggle('active');
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                });
            }

            // Expose ke global agar onclick inline bisa memanggil
            window.showDetailModal = showDetailModal;
        })();
    </script>
</body>

</html>