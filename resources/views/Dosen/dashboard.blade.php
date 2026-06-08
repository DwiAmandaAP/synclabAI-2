<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Dashboard Dosen | Portal Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; color: #1e293b; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .main-content { flex: 1; padding: 28px 32px; overflow-x: auto; transition: all 0.3s ease; }
        .page-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; color: #1e293b; }
        
        .hero-card { border-radius: 28px; margin-bottom: 32px; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2); overflow: hidden; background: linear-gradient(135deg, #1e3a8a, #3b82f6); }
        .hero-overlay { background: linear-gradient(95deg, rgba(10,25,47,0.85) 0%, rgba(30,58,80,0.7) 100%); backdrop-filter: blur(2px); padding: 32px; }
        .hero-text h2 { font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: 8px; }
        .hero-text p { color: #cbd5e6; font-size: 0.9rem; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .hero-badge { background: #facc15; color: #1e293b; padding: 6px 14px; border-radius: 40px; font-size: 0.7rem; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; }
        
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat-card { background: white; border-radius: 24px; padding: 20px; display: flex; gap: 16px; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.2s; cursor: pointer; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .stat-icon { width: 56px; height: 56px; background: linear-gradient(135deg, #818cf8, #c084fc); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white; flex-shrink: 0; }
        .stat-info h3 { font-size: 0.85rem; font-weight: 500; color: #64748b; margin-bottom: 4px; }
        .stat-number { font-size: 1.8rem; font-weight: 700; color: #1e293b; }
        .stat-trend { font-size: 0.7rem; color: #10b981; }
        
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
        .section-header h2 { font-size: 1.3rem; display: flex; align-items: center; gap: 8px; }
        
        .praktikum-grid-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px; }
        .praktikum-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: 0.3s; }
        .praktikum-card:hover { transform: translateY(-6px); box-shadow: 0 12px 28px rgba(0,0,0,0.12); }
        .card-header-bg { padding: 20px; color: white; display: flex; align-items: center; gap: 12px; }
        .card-header-bg i { font-size: 32px; }
        .card-header-bg h3 { font-size: 1.2rem; }
        .card-stats { padding: 20px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .stat-item { display: flex; flex-direction: column; }
        .stat-label { font-size: 0.7rem; color: #64748b; }
        .stat-value { font-size: 1.4rem; font-weight: 700; }
        .stat-value.success { color: #10b981; }
        .stat-value.warning { color: #f59e0b; }
        .stat-value.danger { color: #ef4444; }
        .detail-btn { width: calc(100% - 40px); margin: 0 20px 20px 20px; padding: 10px; background: #f1f5f9; border: none; border-radius: 40px; cursor: pointer; font-weight: 500; transition: 0.2s; }
        .detail-btn:hover { background: #3b82f6; color: white; }
        
        .two-columns-dosen { display: flex; gap: 28px; flex-wrap: wrap; }
        .left-col-dosen { flex: 1.2; min-width: 280px; }
        .right-col-dosen { flex: 1; min-width: 280px; }
        .glass-card { background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border-radius: 24px; padding: 22px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid rgba(255,255,255,0.6); }
        .card-title-icon { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .card-title-icon i { font-size: 1.3rem; color: #3b82f6; }
        .card-title-icon h3 { font-size: 1.1rem; flex: 1; }
        .badge-pending { background: #f59e0b; color: white; padding: 4px 12px; border-radius: 40px; font-size: 0.7rem; }
        
        .registration-list { display: flex; flex-direction: column; gap: 18px; }
        .reg-item { display: flex; flex-direction: column; gap: 6px; }
        .reg-info { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
        .reg-name { font-weight: 600; }
        .reg-desc { font-size: 0.75rem; color: #64748b; }
        .progress-bar-container { background: #e2e8f0; border-radius: 40px; height: 8px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 40px; transition: width 0.3s; }
        .reg-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 4px; flex-wrap: wrap; gap: 8px; }
        .detail-small-btn { padding: 4px 12px; background: #3b82f6; color: white; border: none; border-radius: 40px; cursor: pointer; font-size: 0.7rem; transition: 0.2s; }
        .detail-small-btn:hover { background: #2563eb; transform: scale(1.02); }
        
        .validation-summary { display: flex; flex-direction: column; gap: 16px; }
        .validation-course-card { background: #f8fafc; border-radius: 16px; padding: 14px; }
        .course-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; font-size: 0.95rem; flex-wrap: wrap; }
        .validation-stats { display: flex; gap: 20px; margin-bottom: 12px; font-size: 0.8rem; flex-wrap: wrap; }
        .validation-stats .validated { color: #10b981; }
        .validation-stats .pending { color: #f59e0b; }
        
        .filter-group { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .filter-group select { padding: 8px 12px; border-radius: 40px; border: 1px solid #e2e8f0; background: white; cursor: pointer; font-size: 0.8rem; flex: 1; min-width: 120px; }
        
        .attendance-stats { display: flex; gap: 24px; align-items: center; flex-wrap: wrap; margin-bottom: 16px; }
        .attendance-circle { position: relative; width: 120px; flex-shrink: 0; }
        .attendance-circle svg { width: 120px; height: 120px; }
        .circle-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
        .circle-text .percent { font-size: 1.4rem; font-weight: 700; }
        .circle-text .label { font-size: 0.6rem; color: #64748b; display: block; }
        .attendance-breakdown { flex: 1; }
        .break-item { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 0.85rem; flex-wrap: wrap; }
        .break-color { width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0; }
        .card-footer-btn { margin-top: 16px; text-align: right; }

        #performanceChart, #pretestChart { max-height: 250px; }

        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .main-content { padding: 20px; }
            .page-title { font-size: 1.4rem; }
            .two-columns-dosen { gap: 20px; }
            .attendance-stats { flex-direction: column; align-items: center; text-align: center; }
            .hero-text h2 { font-size: 1.2rem; }
        }
    </style>

    <div class="dashboard-container">
        @include('dosen.partials.sidebar')
        
        <main class="main-content">
            <h1 class="page-title"><i class="fas fa-chalkboard-user"></i> Dashboard Dosen</h1>
            
            <!-- HERO BOX -->
            <div class="hero-card">
                <div class="hero-overlay">
                    <div class="hero-text">
                        <h2>Selamat datang, {{ Auth::user()->nama }}! 👋</h2>
                        <p>
                            <span id="fullDateDisplay"></span>
                            <span class="hero-badge"><i class="fas fa-chalkboard"></i> Semester Genap 2025/2026</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- STATS ROW -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3>Total Mahasiswa</h3>
                        <p class="stat-number">{{ $totalMahasiswa ?? '-' }}</p>
                        <span class="stat-trend">Praktikan terdaftar</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-flask"></i></div>
                    <div class="stat-info">
                        <h3>Praktikum Aktif</h3>
                        <p class="stat-number">{{ count($aktivePraktikums) ?? 0}}</p>
                        <span class="stat-trend">Total praktikum</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div class="stat-info">
                        <h3>Menunggu Validasi</h3>
                        <p class="stat-number">{{ $menungguValidasi }}</p>
                        <span class="stat-trend">Nilai perlu divalidasi</span>
                    </div>
                </div>
            </div>
            
            <!-- PRAKTIKUM CARDS -->
            <div class="section-header">
                <h2><i class="fas fa-desktop"></i> Monitoring Praktikum</h2>
            </div>
            <div class="praktikum-grid-cards">
                @forelse($aktivePraktikums as $praktikum)
                <div class="praktikum-card">
                    <div class="card-header-bg" style="background: {{ $praktikum['gradient'] }};">
                        <i class="fas fa-flask"></i>
                        <h3>{{ $praktikum['nama_praktikum'] }}</h3>
                    </div>
                    <div class="card-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Mahasiswa</span>
                            <span class="stat-value">{{ $praktikum['total_mahasiswa'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Hadir Hari Ini</span>
                            <span class="stat-value success">{{ $praktikum['hadir'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Izin</span>
                            <span class="stat-value warning">{{ $praktikum['izin'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Tanpa Keterangan</span>
                            <span class="stat-value danger">{{ $praktikum['alpha'] }}</span>
                        </div>
                    </div>
                    <button class="detail-btn" onclick="window.location.href='{{ route('monitoring') }}?praktikum={{ urlencode($praktikum['nama_praktikum']) }}'">
                        Detail <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: #64748b; grid-column: 1/-1;">
                    <p>Tidak ada praktikum yang tersedia</p>
                </div>
                @endforelse
            </div>
            
            <!-- TWO COLUMNS -->
            <div class="two-columns-dosen">
                <div class="left-col-dosen">
                    <!-- STATUS PENDAFTARAN -->
                    <div class="glass-card">
                        <div class="card-title-icon">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>Status Pendaftaran Praktikum</h3>
                        </div>
                        <div class="registration-list">
                            @forelse($validationSummary as $summary)
                            @php
                                $total = $summary['tervalidasi'] + $summary['belum'];
                                $percentage = $total > 0 ? round(($summary['tervalidasi'] / $total) * 100) : 0;
                            @endphp
                            <div class="reg-item">
                                <div class="reg-info">
                                    <span class="reg-name">{{ $summary['nama_praktikum'] }}</span>
                                    <span class="reg-desc">Tervalidasi: {{ $summary['tervalidasi'] }}/{{ $total }}</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $percentage }}%; background: {{ $percentage >= 80 ? '#10b981' : ($percentage >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                                </div>
                                <div class="reg-footer">
                                    <span style="color: {{ $percentage >= 80 ? '#10b981' : ($percentage >= 50 ? '#f59e0b' : '#ef4444') }}; font-size: 0.8rem; font-weight: 600;">{{ $percentage }}%</span>
                                    <button class="detail-small-btn" onclick="window.location.href='{{ route('validasinilai') }}?praktikum={{ urlencode($summary['nama_praktikum']) }}'">
                                        Detail <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <p style="text-align: center; color: #64748b; padding: 20px;">Tidak ada data validasi</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- VALIDASI NILAI -->
                    <div class="glass-card">
                        <div class="card-title-icon">
                            <i class="fas fa-check-double"></i>
                            <h3>Validasi Nilai</h3>
                            <span class="badge-pending">{{ $menungguValidasi }} Pending</span>
                        </div>
                        <div class="validation-summary">
                            @forelse($validationSummary as $summary)
                            <div class="validation-course-card">
                                <div class="course-header">
                                    <i class="fas fa-flask"></i>
                                    <strong>{{ $summary['nama_praktikum'] }}</strong>
                                </div>
                                <div class="validation-stats">
                                    <span class="validated">✓ Tervalidasi: {{ $summary['tervalidasi'] }}</span>
                                    <span class="pending">⏳ Belum: {{ $summary['belum'] }}</span>
                                </div>
                                <button class="detail-small-btn" onclick="window.location.href='{{ route('validasinilai') }}?praktikum={{ urlencode($summary['nama_praktikum']) }}'">
                                    Lihat Detail
                                </button>
                            </div>
                            @empty
                            <p style="text-align: center; color: #64748b; padding: 20px;">Tidak ada data validasi</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="right-col-dosen">
                    <!-- REKAP PRESENSI -->
                    <div class="glass-card">
                        <div class="card-title-icon">
                            <i class="fas fa-calendar-check"></i>
                            <h3>Rekap Presensi</h3>
                        </div>
                        <div class="filter-group">
                            <select id="presenceClassFilter">
                                <option value="2022">Kelas 2022</option>
                                <option value="2023">Kelas 2023</option>
                                <option value="2024">Kelas 2024</option>
                            </select>
                            <select id="presenceCourseFilter">
                                @foreach($aktivePraktikums as $praktikum)
                                <option value="{{ $praktikum['nama_praktikum'] }}">{{ $praktikum['nama_praktikum'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="attendance-stats" id="attendanceStats">
                            <div class="attendance-circle">
                                <svg viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                                    <circle id="attendanceCircleProgress" cx="50" cy="50" r="45" fill="none" stroke="#3b82f6" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="85" stroke-linecap="round" transform="rotate(-90 50 50)"/>
                                </svg>
                                <div class="circle-text">
                                    <span class="percent" id="attendancePercent">0%</span>
                                    <span class="label">Kehadiran</span>
                                </div>
                            </div>
                            <div class="attendance-breakdown" id="attendanceBreakdown"></div>
                        </div>
                        <div class="card-footer-btn">
                            <button class="detail-small-btn" onclick="window.location.href='{{ route('presensi') }}'">
                                Detail Presensi <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- PERFORMA KELAS -->
                    <div class="glass-card">
                        <div class="card-title-icon">
                            <i class="fas fa-chart-line"></i>
                            <h3>Performa Pretest</h3>
                        </div>
                        <div class="filter-group">
                            <select id="performanceClassFilter">
                                <option value="2022">Kelas 2022</option>
                                <option value="2023">Kelas 2023</option>
                                <option value="2024">Kelas 2024</option>
                            </select>
                            <select id="performanceCourseFilter">
                                @foreach($aktivePraktikums as $praktikum)
                                <option value="{{ $praktikum['nama_praktikum'] }}">{{ $praktikum['nama_praktikum'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <canvas id="pretestChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        (function() {
            // Set date
            const now = new Date();
            const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = now.toLocaleDateString('id-ID', optionsDate);
            const fullDateSpan = document.getElementById('fullDateDisplay');
            if (fullDateSpan) fullDateSpan.innerHTML = `<i class="far fa-calendar-alt"></i> ${formattedDate}`;

            // Data from backend
            const presenceData = @json($presenceData);
            const pretestData = @json($pretestData);

            // Update attendance display
            function updateAttendanceDisplay() {
                const kelas = document.getElementById('presenceClassFilter').value;
                const matkul = document.getElementById('presenceCourseFilter').value;
                const data = presenceData[kelas]?.[matkul];
                
                if (!data) {
                    document.getElementById('attendancePercent').innerText = '0%';
                    document.getElementById('attendanceBreakdown').innerHTML = '<p style="color:#64748b;">Data tidak tersedia</p>';
                    return;
                }

                document.getElementById('attendancePercent').innerText = `${data.percent}%`;
                
                const circle = document.getElementById('attendanceCircleProgress');
                if (circle) {
                    const circumference = 2 * Math.PI * 45;
                    const offset = circumference - (data.percent / 100) * circumference;
                    circle.style.strokeDasharray = circumference;
                    circle.style.strokeDashoffset = offset;
                }

                document.getElementById('attendanceBreakdown').innerHTML = `
                    <div class="break-item"><span class="break-color" style="background: #3b82f6;"></span><span>Hadir: ${data.hadir}</span></div>
                    <div class="break-item"><span class="break-color" style="background: #f59e0b;"></span><span>Izin: ${data.izin}</span></div>
                    <div class="break-item"><span class="break-color" style="background: #10b981;"></span><span>Sakit: ${data.sakit}</span></div>
                    <div class="break-item"><span class="break-color" style="background: #ef4444;"></span><span>Alpha: ${data.alpha}</span></div>
                    <div class="break-item"><span class="break-color" style="background: #64748b;"></span><span>Total: ${data.total}</span></div>
                `;
            }

            document.getElementById('presenceClassFilter').addEventListener('change', updateAttendanceDisplay);
            document.getElementById('presenceCourseFilter').addEventListener('change', updateAttendanceDisplay);
            updateAttendanceDisplay();

            // Performance chart
            let pretestChart;
            const pretestLabels = ['Pretest 1', 'Pretest 2', 'Pretest 3', 'Pretest 4', 'Pretest 5', 'Pretest 6'];

            function initPretestChart() {
                const kelas = document.getElementById('performanceClassFilter').value;
                const matkul = document.getElementById('performanceCourseFilter').value;
                const data = pretestData[kelas]?.[matkul] || [0, 0, 0, 0, 0, 0];
                
                const ctx = document.getElementById('pretestChart').getContext('2d');
                if (pretestChart) pretestChart.destroy();
                
                const chartColor = '#f59e0b';
                
                pretestChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: pretestLabels,
                        datasets: [{
                            label: `${matkul} - Rata-rata Nilai Pretest`,
                            data: data,
                            borderColor: chartColor,
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: chartColor,
                            pointBorderColor: 'white',
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 11 } } },
                            tooltip: { callbacks: { label: (ctx) => `Nilai Rata-rata: ${ctx.raw}` } }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                max: 100,
                                title: { display: true, text: 'Nilai', font: { size: 10 } }
                            },
                            x: { title: { display: true, text: 'Pretest ke-', font: { size: 10 } } }
                        }
                    }
                });
            }

            document.getElementById('performanceClassFilter').addEventListener('change', initPretestChart);
            document.getElementById('performanceCourseFilter').addEventListener('change', initPretestChart);
            initPretestChart();
        })();
    </script>
</body>
</html>