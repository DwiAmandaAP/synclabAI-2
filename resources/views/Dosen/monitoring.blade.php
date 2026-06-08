<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Monitoring Dosen | Portal Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; color: #1e293b; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .main-content { flex: 1; padding: 28px 32px; overflow-x: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 1.8rem; font-weight: 700; display: flex; align-items: center; gap: 12px; color: #1e293b; }
        .export-btn { padding: 10px 20px; background: linear-gradient(135deg, #10b981, #047857); border: none; border-radius: 40px; color: white; font-weight: 600; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 8px; }
        .export-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
        
        .filter-section { background: white; border-radius: 24px; padding: 20px 24px; margin-bottom: 24px; display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .filter-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 150px; }
        .filter-group label { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .filter-group select { padding: 10px 14px; border-radius: 40px; border: 1px solid #e2e8f0; background: white; cursor: pointer; font-size: 0.85rem; }
        .apply-filter-btn { padding: 10px 24px; background: #3b82f6; border: none; border-radius: 40px; color: white; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
        .apply-filter-btn:hover { background: #2563eb; transform: translateY(-2px); }
        
        .stats-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; margin-bottom: 28px; }
        .stat-card-primary { background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 24px; padding: 24px; display: flex; align-items: center; gap: 20px; color: white; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .stat-card-primary .stat-icon { width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
        .stat-info h3 { font-size: 0.85rem; font-weight: 500; opacity: 0.9; margin-bottom: 4px; }
        .stat-number { font-size: 2.2rem; font-weight: 700; }
        .stat-desc { font-size: 0.7rem; opacity: 0.8; }
        
        .charts-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px; margin-bottom: 28px; }
        .chart-card { background: white; border-radius: 24px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; }
        .chart-header h3 { font-size: 1rem; display: flex; align-items: center; gap: 8px; }
        
        .data-table-card { background: white; border-radius: 24px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
        .table-header h3 { font-size: 1.1rem; display: flex; align-items: center; gap: 8px; }
        .table-search { position: relative; }
        .table-search input { padding: 8px 16px 8px 40px; border-radius: 40px; border: 1px solid #e2e8f0; width: 250px; font-size: 0.85rem; }
        .table-search i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .table-responsive { overflow-x: auto; }
        
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 12px 12px; background: #f8fafc; font-weight: 600; font-size: 0.8rem; color: #64748b; border-bottom: 2px solid #e2e8f0; }
        .data-table td { padding: 12px 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; }
        
        .status-badge { padding: 4px 12px; border-radius: 40px; font-size: 0.7rem; font-weight: 600; display: inline-block; }
        .status-hadir { background: #dcfce7; color: #16a34a; }
        .status-tidak-hadir { background: #fee2e2; color: #dc2626; }
        
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 12px;
        }
        .table-footer .showing-info {
            font-size: 0.85rem;
            color: #64748b;
        }
 
        /* Override Laravel pagination styles agar rapi */
        .table-footer nav { display: flex; align-items: center; }
        .table-footer nav > div:first-child { display: none; } /* Sembunyikan teks "Showing X to Y" bawaan Laravel (sudah ada manual di kiri) */
 
        /* Pagination links wrapper */
        .table-footer .pagination,
        .table-footer nav span,
        .table-footer nav div {
            display: flex;
            gap: 4px;
            align-items: center;
            flex-wrap: wrap;
        }
 
        /* Semua elemen <a> dan <span> di dalam pagination */
        .table-footer nav a,
        .table-footer nav span[aria-current],
        .table-footer nav span.relative {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: 40px;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
            transition: all 0.2s;
            cursor: pointer;
            /* KUNCI: pastikan tidak ada font-size besar yang diwariskan */
            line-height: 1;
        }
 
        .table-footer nav a:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(59,130,246,0.25);
        }
 
        /* Halaman aktif */
        .table-footer nav span[aria-current="page"] > span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: 40px;
            font-size: 0.82rem;
            font-weight: 600;
            background: #3b82f6;
            color: white;
            border: 1px solid #3b82f6;
            line-height: 1;
        }
 
        /* Tombol disabled (Previous di halaman 1, Next di halaman terakhir) */
        .table-footer nav span[aria-disabled="true"],
        .table-footer nav span.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
 
        /* Elipsis (...) */
        .table-footer nav span.relative span {
            background: transparent;
            border: none;
            color: #94a3b8;
            pointer-events: none;
        }
 
        /* Wrapper flex container utama dari Laravel pagination */
        .table-footer nav > div:last-child {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
 
        /* FIX: Sembunyikan SVG icon besar bawaan Laravel, tampilkan simbol teks */
        .table-footer nav svg { display: none; }
 
        /* Ganti konten Previous / Next dengan teks kecil */
        .table-footer nav a[rel="prev"]::before { content: "‹ Prev"; }
        .table-footer nav a[rel="next"]::after  { content: "Next ›"; }
        .table-footer nav a[rel="prev"] span,
        .table-footer nav a[rel="next"] span { display: none; }
        .page-btn { padding: 6px 12px; background: #f1f5f9; border: none; border-radius: 40px; cursor: pointer; transition: 0.2s; }
        .page-btn:hover { background: #3b82f6; color: white; }
        .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .main-content { padding: 20px; }
            .page-title { font-size: 1.4rem; }
            .charts-row { grid-template-columns: 1fr; }
            .table-search input { width: 100%; }
        }
    </style>

    <div class="dashboard-container">
        @include('dosen.partials.sidebar')
        
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-eye"></i> Monitoring Praktikum</h1>
                <div class="header-actions">
                    <button class="export-btn" id="exportBtn"><i class="fas fa-download"></i> Export Data</button>
                </div>
            </div>

            <!-- FILTER SECTION -->
            <form method="GET" action="{{ route('monitoring') }}" class="filter-section" id="filterForm">
                <div class="filter-group">
                    <label><i class="fas fa-flask"></i> Praktikum</label>
                    <select name="praktikum" id="filterPraktikum">
                        <option value="all" {{ $filterPraktikum == 'all' ? 'selected' : '' }}>Semua Praktikum</option>
                        @foreach($praktikums as $p)
                        <option value="{{ $p }}" {{ $filterPraktikum == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-users"></i> Kelas</label>
                    <select name="kelas" id="filterKelas">
                        <option value="all" {{ $filterKelas == 'all' ? 'selected' : '' }}>Semua Kelas</option>
                        <option value="2022" {{ $filterKelas == '2022' ? 'selected' : '' }}>Kelas 2022</option>
                        <option value="2023" {{ $filterKelas == '2023' ? 'selected' : '' }}>Kelas 2023</option>
                        <option value="2024" {{ $filterKelas == '2024' ? 'selected' : '' }}>Kelas 2024</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar-week"></i> Pertemuan</label>
                    <select name="pertemuan" id="filterPertemuan">
                        <option value="all" {{ $filterPertemuan == 'all' ? 'selected' : '' }}>Semua Pertemuan</option>
                        @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ $filterPertemuan == $i ? 'selected' : '' }}>Pertemuan {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="apply-filter-btn"><i class="fas fa-search"></i> Terapkan</button>
            </form>

            <!-- STATS CARDS -->
            <div class="stats-cards">
                <div class="stat-card-primary">
                    <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                    <div class="stat-info">
                        <h3>Kehadiran</h3>
                        <p class="stat-number">{{ $kehadiranPercent }}%</p>
                        <span class="stat-desc">dari total mahasiswa</span>
                    </div>
                </div>
                <div class="stat-card-primary">
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-info">
                        <h3>Rata-Rata Nilai</h3>
                        <p class="stat-number">{{ $rataNilai }}</p>
                        <span class="stat-desc">pretest</span>
                    </div>
                </div>
                <div class="stat-card-primary">
                    <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="stat-info">
                        <h3>Laporan Selesai</h3>
                        <p class="stat-number">{{ $laporanSelesai }}</p>
                        <span class="stat-desc">dari total mahasiswa</span>
                    </div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-chart-bar"></i> Grafik Kehadiran per Kelas</h3>
                    </div>
                    <canvas id="kehadiranChart" width="400" height="200" style="width:100%; max-height:250px;"></canvas>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-chart-line"></i> Grafik Nilai Pretest</h3>
                    </div>
                    <canvas id="nilaiChart" width="400" height="200" style="width:100%; max-height:250px;"></canvas>
                </div>
            </div>

            <!-- DATA TABLE -->
            <div class="data-table-card">
                <div class="table-header">
                    <h3><i class="fas fa-table-list"></i> Detail Presensi Mahasiswa</h3>
                    <div class="table-search">
                        <input type="text" id="tableSearch" placeholder="Cari mahasiswa...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Praktikum</th>
                                <th>Pertemuan</th>
                                <th>Kehadiran</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presences as $presence)
                            <tr>
                                <td>{{ $presence->user->nama ?? '-' }}</td>
                                <td>{{ $presence->user->nomor_induk ?? '-' }}</td>
                                <td>{{ $presence->pertemuan->praktikum->nama_praktikum ?? '-' }}</td>
                                <td>Pertemuan {{ $presence->pertemuan->pertemuan_ke ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $presence->kehadiran == 'Hadir' ? 'status-hadir' : 'status-tidak-hadir' }}">
                                        {{ $presence->kehadiran }}
                                    </span>
                                </td>
                                <td>{{ $presence->created_at ? $presence->created_at->format('d/m/Y') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">Tidak ada data presensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span>Menampilkan {{ $presences->firstItem() ?? 0 }} - {{ $presences->lastItem() ?? 0 }} dari {{ $presences->total() }} data</span>
                    {{ $presences->appends(request()->query())->links() }}
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            const kehadiranChartData = @json($kehadiranChartData);
            const nilaiChartData = @json($nilaiChartData);
            
            let kehadiranChart, nilaiChart;

            function initKehadiranChart() {
                const ctx = document.getElementById('kehadiranChart').getContext('2d');
                if (kehadiranChart) kehadiranChart.destroy();
                
                const labels = Object.keys(kehadiranChartData);
                const hadirData = labels.map(k => kehadiranChartData[k].hadir || 0);
                const izinData = labels.map(k => kehadiranChartData[k].izin || 0);
                const sakitData = labels.map(k => kehadiranChartData[k].sakit || 0);
                const alphaData = labels.map(k => kehadiranChartData[k].alpha || 0);
                
                kehadiranChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels.map(l => `Kelas ${l}`),
                        datasets: [
                            { label: 'Hadir', data: hadirData, backgroundColor: '#10b981', borderRadius: 8 },
                            { label: 'Izin', data: izinData, backgroundColor: '#f59e0b', borderRadius: 8 },
                            { label: 'Sakit', data: sakitData, backgroundColor: '#3b82f6', borderRadius: 8 },
                            { label: 'Alpha', data: alphaData, backgroundColor: '#ef4444', borderRadius: 8 }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { position: 'bottom' } },
                        scales: { 
                            x: { stacked: true },
                            y: { stacked: true, beginAtZero: true, title: { display: true, text: 'Jumlah' } }
                        }
                    }
                });
            }

            function initNilaiChart() {
                const ctx = document.getElementById('nilaiChart').getContext('2d');
                if (nilaiChart) nilaiChart.destroy();
                
                const labels = Object.keys(nilaiChartData);
                const pretestLabels = ['Pretest 1', 'Pretest 2', 'Pretest 3', 'Pretest 4', 'Pretest 5', 'Pretest 6'];
                
                const datasets = labels.map((kelas, index) => {
                    const colors = ['#3b82f6', '#10b981', '#f59e0b'];
                    return {
                        label: `Kelas ${kelas}`,
                        data: nilaiChartData[kelas],
                        borderColor: colors[index] || '#64748b',
                        backgroundColor: 'transparent',
                        tension: 0.3
                    };
                });
                
                nilaiChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: pretestLabels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { position: 'bottom' } },
                        scales: { 
                            y: { beginAtZero: true, max: 100, title: { display: true, text: 'Nilai' } }
                        }
                    }
                });
            }

            // Table search
            document.getElementById('tableSearch').addEventListener('input', function() {
                const search = this.value.toLowerCase();
                const rows = document.querySelectorAll('.data-table tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(search) ? '' : 'none';
                });
            });

            // Export
            document.getElementById('exportBtn').addEventListener('click', function() {
                const params = new URLSearchParams(window.location.search);
                window.location.href = '{{ route("monitoring") }}/export?' + params.toString();
            });

            initKehadiranChart();
            initNilaiChart();
        })();
    </script>
</body>
</html>