<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Nilai dan Presensi | Mahasiswa</title>
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

        .tab-navigation {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 8px 24px;
            background: transparent;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: 0.2s;
            border-radius: 40px;
        }

        .tab-btn:hover {
            color: #3b82f6;
            background: #eff6ff;
        }

        .tab-btn.active {
            color: #3b82f6;
            background: #eff6ff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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

        .scores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            width: 100%;
        }

        .score-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .score-card:hover {
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
        }

        .score-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .score-item:last-child {
            border-bottom: none;
        }

        .score-info h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .score-info p {
            font-size: 0.7rem;
            color: #64748b;
        }

        .score-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #10b981;
        }

        .final-scores-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .final-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .final-header {
            background: linear-gradient(135deg, #8b5cf6, #5b21b6);
            padding: 20px;
            color: white;
        }

        .final-header h3 {
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        .final-body {
            padding: 20px;
        }

        .final-table {
            width: 100%;
            border-collapse: collapse;
        }

        .final-table th {
            text-align: left;
            padding: 10px 8px;
            background: #f8fafc;
            font-weight: 600;
            font-size: 0.75rem;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }

        .final-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
        }

        .final-table tr:last-child td {
            border-bottom: none;
        }

        .total-score {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .total-label {
            font-weight: 700;
            font-size: 1rem;
        }

        .total-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #10b981;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .sidebar-nav {
                display: none;
                padding: 0 20px 20px 20px;
            }

            .sidebar-nav.active {
                display: flex;
            }

            .profile-section {
                flex-direction: row;
                gap: 16px;
                text-align: left;
            }

            .avatar-circle {
                width: 60px;
                height: 60px;
                margin: 0;
            }

            .main-content {
                padding: 20px;
            }

            .page-title {
                font-size: 1.4rem;
                margin-bottom: 16px;
                justify-content: center;
                width: 100%;
                text-align: center;
            }

            .filter-section {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }

            .scores-grid {
                grid-template-columns: 1fr;
            }

            .final-table {
                display: block;
                overflow-x: auto;
            }

            .total-score {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 16px;
            }

            .card-header {
                padding: 16px;
            }

            .card-body {
                padding: 16px;
            }
        }
    </style>

    <div class="dashboard-container">
        @include('mahasiswa/partials/sidebar')

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-star"></i> My Score</h1>
            </div>

            <div class="tab-navigation">
                <button class="tab-btn active" data-tab="pretest">Nilai Pretest</button>
                <button class="tab-btn" data-tab="laporan">Nilai Laporan</button>
                <button class="tab-btn" data-tab="akhir">Nilai Akhir</button>
            </div>

            <div id="tab-pretest" class="tab-content active">
                <div class="filter-section">
                    <div class="filter-group">
                        <label><i class="fas fa-flask"></i> Mata Kuliah</label>
                        <select id="filterPretestMatkul">
                            <option value="all">Semua Mata Kuliah</option>
                            @foreach($nilais as $nilai)
                            <option value="{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}">{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="apply-filter-btn" id="applyPretestFilter"><i class="fas fa-search"></i>
                        Terapkan</button>
                </div>
                <div class="scores-grid" id="pretestGrid">
                    @forelse($nilais as $nilai)
                    <div class="score-card">
                        <div class="card-header">
                            <h3>{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}</h3>
                            <p>{{ $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan' }}</p>
                        </div>
                        <div class="card-body">
                            <div class="score-item">
                                <div class="score-info">
                                    <h4>Nilai Pretest</h4>
                                    <p>Total nilai pretest</p>
                                </div>
                                <div class="score-value">{{ $nilai->nilai_pretest ?? '-' }}/100</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">Belum ada nilai pretest</div>
                    @endforelse
                </div>
            </div>

            <div id="tab-laporan" class="tab-content">
                <div class="filter-section">
                    <div class="filter-group">
                        <label><i class="fas fa-flask"></i> Mata Kuliah</label>
                        <select id="filterLaporanMatkul">
                            <option value="all">Semua Mata Kuliah</option>
                            @foreach($nilais as $nilai)
                            <option value="{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}">{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="apply-filter-btn" id="applyLaporanFilter"><i class="fas fa-search"></i>
                        Terapkan</button>
                </div>
                <div class="scores-grid" id="laporanGrid">
                    @forelse($nilais as $nilai)
                    <div class="score-card">
                        <div class="card-header">
                            <h3>{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}</h3>
                            <p>{{ $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan' }}</p>
                        </div>
                        <div class="card-body">
                            <div class="score-item">
                                <div class="score-info">
                                    <h4>Nilai Laporan</h4>
                                    <p>Total nilai laporan</p>
                                </div>
                                <div class="score-value">{{ $nilai->nilai_laporan ?? '-' }}/100</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">Belum ada nilai laporan</div>
                    @endforelse
                </div>
            </div>

            <div id="tab-akhir" class="tab-content">
                <div class="filter-section">
                    <div class="filter-group">
                        <label><i class="fas fa-flask"></i> Mata Kuliah</label>
                        <select id="filterAkhirMatkul">
                            <option value="all">Semua Mata Kuliah</option>
                            @foreach($nilais as $nilai)
                            <option value="{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}">{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="apply-filter-btn" id="applyAkhirFilter"><i class="fas fa-search"></i>
                        Terapkan</button>
                </div>
                <div class="final-scores-container" id="akhirContainer">
                    @forelse($nilais as $nilai)
                    <div class="final-card">
                        <div class="final-header">
                            <h3>{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}</h3>
                            <p>{{ $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan' }}</p>
                        </div>
                        <div class="final-body">
                            <table class="final-table">
                                <thead>
                                    <tr><th>Komponen</th><th>Nilai</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nilai Pretest</td>
                                        <td class="score-value" style="font-size:0.9rem">{{ $nilai->nilai_pretest ?? '-' }}/100</td>
                                    </tr>
                                    <tr>
                                        <td>Nilai Laporan</td>
                                        <td class="score-value" style="font-size:0.9rem">{{ $nilai->nilai_laporan ?? '-' }}/100</td>
                                    </tr>
                                    <tr>
                                        <td>Nilai Total</td>
                                        <td class="score-value" style="font-size:0.9rem">{{ $nilai->nilai_total ?? '-' }}/100</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="total-score">
                                <span class="total-label">Nilai Akhir</span>
                                <span class="total-value">{{ $nilai->nilai_akhir ?? '-' }}/100</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">Belum ada data nilai akhir</div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
    {{-- ✅ Siapkan data di PHP block, BUKAN di dalam @json() --}}
@php
    $nilaiDataForJs = $nilais->groupBy(function($nilai) {
        return $nilai->pertemuan?->praktikum?->nama_praktikum ?? 'Praktikum';
    })->map(function($nilaisPerPraktikum) {
        return [
            'pretest' => $nilaisPerPraktikum->map(function($nilai) {
                return [
                    'modul'  => $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan',
                    'materi' => $nilai->pertemuan?->modul?->judul_modul ?? 'Materi',
                    'nilai'  => $nilai->nilai_pretest ?? 0,
                ];
            })->values()->toArray(),
            'laporan' => $nilaisPerPraktikum->map(function($nilai) {
                return [
                    'modul'  => $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan',
                    'materi' => $nilai->pertemuan?->modul?->judul_modul ?? 'Materi',
                    'nilai'  => $nilai->nilai_laporan ?? 0,
                ];
            })->values()->toArray(),
        ];
    });
@endphp

<script>
    (function () {
        const nilaiData = @json($nilaiDataForJs);

        function hitungNilaiAkhir(matkul) {
            const pretests = nilaiData[matkul]?.pretest || [];
            const laporans = nilaiData[matkul]?.laporan || [];
            let totalPretest = 0, totalLaporan = 0;
            pretests.forEach(p => totalPretest += p.nilai);
            laporans.forEach(l => totalLaporan += l.nilai);
            const avgPretest = pretests.length ? totalPretest / pretests.length : 0;
            const avgLaporan = laporans.length ? totalLaporan / laporans.length : 0;
            return Math.round((avgPretest + avgLaporan) / 2);
        }

        function updateDropdowns() {
            const matkuls = Object.keys(nilaiData);
            const options = '<option value="all">Semua Mata Kuliah</option>' +
                matkuls.map(m => `<option value="${m}">${m}</option>`).join('');
            document.getElementById('filterPretestMatkul').innerHTML = options;
            document.getElementById('filterLaporanMatkul').innerHTML = options;
            document.getElementById('filterAkhirMatkul').innerHTML = options;
        }

        function renderPretest() {
            const selectedMatkul = document.getElementById('filterPretestMatkul').value;
            const container = document.getElementById('pretestGrid');
            const filteredMatkuls = selectedMatkul === 'all' ? Object.keys(nilaiData) : [selectedMatkul];

            if (!filteredMatkuls.some(m => nilaiData[m]?.pretest?.length)) {
                container.innerHTML = '<div class="empty-state">Belum ada nilai pretest</div>';
                return;
            }

            container.innerHTML = '';
            filteredMatkuls.forEach(matkul => {
                const pretests = nilaiData[matkul]?.pretest || [];
                if (!pretests.length) return;

                const card = document.createElement('div');
                card.className = 'score-card';
                card.innerHTML = `
                    <div class="card-header">
                        <h3>${matkul}</h3>
                        <p>Nilai Pretest</p>
                    </div>
                    <div class="card-body">
                        ${pretests.map(p => `
                            <div class="score-item">
                                <div class="score-info">
                                    <h4>${p.modul}</h4>
                                    <p>${p.materi}</p>
                                </div>
                                <div class="score-value">${p.nilai}/100</div>
                            </div>
                        `).join('')}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderLaporan() {
            const selectedMatkul = document.getElementById('filterLaporanMatkul').value;
            const container = document.getElementById('laporanGrid');
            const filteredMatkuls = selectedMatkul === 'all' ? Object.keys(nilaiData) : [selectedMatkul];

            if (!filteredMatkuls.some(m => nilaiData[m]?.laporan?.length)) {
                container.innerHTML = '<div class="empty-state">Belum ada nilai laporan</div>';
                return;
            }

            container.innerHTML = '';
            filteredMatkuls.forEach(matkul => {
                const laporans = nilaiData[matkul]?.laporan || [];
                if (!laporans.length) return;

                const card = document.createElement('div');
                card.className = 'score-card';
                card.innerHTML = `
                    <div class="card-header">
                        <h3>${matkul}</h3>
                        <p>Nilai Laporan</p>
                    </div>
                    <div class="card-body">
                        ${laporans.map(l => `
                            <div class="score-item">
                                <div class="score-info">
                                    <h4>${l.modul}</h4>
                                    <p>${l.materi}</p>
                                </div>
                                <div class="score-value">${l.nilai}/100</div>
                            </div>
                        `).join('')}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderNilaiAkhir() {
            const selectedMatkul = document.getElementById('filterAkhirMatkul').value;
            const container = document.getElementById('akhirContainer');
            const filteredMatkuls = selectedMatkul === 'all' ? Object.keys(nilaiData) : [selectedMatkul];

            if (!filteredMatkuls.length) {
                container.innerHTML = '<div class="empty-state">Belum ada data nilai akhir</div>';
                return;
            }

            container.innerHTML = '';
            filteredMatkuls.forEach(matkul => {
                const pretests  = nilaiData[matkul]?.pretest  || [];
                const laporans  = nilaiData[matkul]?.laporan  || [];
                const nilaiAkhir = hitungNilaiAkhir(matkul);
                const maxLength  = Math.max(pretests.length, laporans.length);

                const rows = [];
                for (let i = 0; i < maxLength; i++) {
                    rows.push({
                        pretest: pretests[i] || { modul: '-', materi: '-', nilai: '-' },
                        laporan: laporans[i] || { modul: '-', materi: '-', nilai: '-' },
                    });
                }

                const card = document.createElement('div');
                card.className = 'final-card';
                card.innerHTML = `
                    <div class="final-header">
                        <h3>${matkul}</h3>
                        <p>Rekap Nilai Pretest &amp; Laporan</p>
                    </div>
                    <div class="final-body">
                        <table class="final-table">
                            <thead>
                                <tr>
                                    <th>Pertemuan (Pretest)</th><th>Materi</th><th>Nilai</th>
                                    <th>Pertemuan (Laporan)</th><th>Materi</th><th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows.map(row => `
                                    <tr>
                                        <td>${row.pretest.modul}</td>
                                        <td>${row.pretest.materi}</td>
                                        <td class="score-value" style="font-size:0.9rem">
                                            ${row.pretest.nilai !== '-' ? row.pretest.nilai + '/100' : '-'}
                                        </td>
                                        <td>${row.laporan.modul}</td>
                                        <td>${row.laporan.materi}</td>
                                        <td class="score-value" style="font-size:0.9rem">
                                            ${row.laporan.nilai !== '-' ? row.laporan.nilai + '/100' : '-'}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        <div class="total-score">
                            <span class="total-label">Nilai Akhir (Pretest + Laporan)</span>
                            <span class="total-value">${nilaiAkhir}/100</span>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = {
            pretest: document.getElementById('tab-pretest'),
            laporan: document.getElementById('tab-laporan'),
            akhir:   document.getElementById('tab-akhir'),
        };

        function switchTab(tabId) {
            Object.values(tabContents).forEach(c => c.classList.remove('active'));
            tabContents[tabId].classList.add('active');
            tabBtns.forEach(btn => btn.classList.toggle('active', btn.dataset.tab === tabId));
            if (tabId === 'pretest')      renderPretest();
            else if (tabId === 'laporan') renderLaporan();
            else if (tabId === 'akhir')   renderNilaiAkhir();
        }

        tabBtns.forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset.tab)));

        document.getElementById('applyPretestFilter').addEventListener('click', renderPretest);
        document.getElementById('applyLaporanFilter').addEventListener('click', renderLaporan);
        document.getElementById('applyAkhirFilter').addEventListener('click', renderNilaiAkhir);

        // Init
        updateDropdowns();
        renderPretest();

        // Mobile menu
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

        document.querySelectorAll('.has-sub .sub-trigger').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const sub = trigger.parentElement.querySelector('.submenu');
                if (sub) sub.style.display = sub.style.display === 'none' ? 'block' : 'none';
            });
        });
    })();
</script>
</body>

</html>
