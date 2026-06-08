<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Riwayat | Mahasiswa</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1e293b;
        }

        .export-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #10b981, #047857);
            border: none;
            border-radius: 40px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

        .riwayat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            width: 100%;
        }

        .riwayat-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .riwayat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-header h3 {
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #10b981;
            color: white;
        }

        .status-completed {
            background: #10b981;
        }

        .card-body {
            padding: 20px;
        }

        .riwayat-table {
            width: 100%;
            border-collapse: collapse;
        }

        .riwayat-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
        }

        .riwayat-table td:first-child {
            font-weight: 600;
            color: #64748b;
            width: 40%;
        }

        .riwayat-table td:last-child {
            font-weight: 700;
            color: #1e293b;
        }

        .riwayat-table tr:last-child td {
            border-bottom: none;
        }

        .nilai-akhir {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
        }

        .nilai-akhir span:first-child {
            color: #64748b;
        }

        .nilai-akhir span:last-child {
            font-size: 1.2rem;
            color: #10b981;
        }

        .btn-detail {
            width: 100%;
            margin-top: 16px;
            padding: 10px;
            background: #f1f5f9;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-detail:hover {
            background: #3b82f6;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
            grid-column: 1/-1;
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
            max-width: 450px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .modal-close:hover {
            color: #ef4444;
        }

        .modal-body {
            padding: 24px;
        }

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

        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-label {
            width: 120px;
            font-weight: 600;
            color: #64748b;
        }

        .detail-value {
            flex: 1;
            color: #1e293b;
        }

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

            .riwayat-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 16px;
            }

            .card-header {
                flex-direction: column;
                text-align: center;
            }

            .modal-content {
                width: 95%;
            }
        }
    </style>

    <div class="dashboard-container">
       @include('mahasiswa/partials/sidebar')

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-history"></i> Activity History</h1>
                <div class="header-actions">
                    <button class="export-btn" id="exportBtn"><i class="fas fa-download"></i> Export Data</button>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-group">
                    <label><i class="fas fa-flask"></i> Mata Kuliah</label>
                    <select id="filterMatkul">
                    <option value="all">Semua Mata Kuliah</option>
                    @foreach($nilais as $nilai)
                        {{-- ✅ was: $nilai->praktikum->nama_praktikum (relasi tidak ada) --}}
                        <option value="{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}">
                            {{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}
                        </option>
                    @endforeach
                </select>
                </div>
                <button class="apply-filter-btn" id="applyFilter"><i class="fas fa-search"></i> Terapkan</button>
            </div>

            <div class="riwayat-grid" id="riwayatGrid">
                @forelse($nilais as $nilai)
                <div class="riwayat-card">
                    <div class="card-header">
                        <div>
                            <h3>{{ $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-' }}</h3>
                            <p>{{ $nilai->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="status-badge status-completed">{{ $nilai->status ?? 'Pending' }}</span>
                    </div>
                    <div class="card-body">
                        <table class="riwayat-table">
                            <tr>
                                <td>Nilai Pretest</td>
                                <td>{{ $nilai->nilai_pretest ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nilai Laporan</td>
                                <td>{{ $nilai->nilai_laporan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nilai Total</td>
                                <td>{{ $nilai->nilai_total ?? '-' }}</td>
                            </tr>
                        </table>
                        <div class="nilai-akhir">
                            <span>Nilai Akhir</span>
                            <span>{{ $nilai->nilai_akhir ?? '-' }}</span>
                        </div>
                        <button class="btn-detail"><i class="fas fa-eye"></i> Lihat Detail</button>
                    </div>
                </div>
                @empty
                <div class="empty-state">Belum ada riwayat aktivitas</div>
                @endforelse
            </div>
        </main>
    </div>

    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle"></i> Detail Riwayat</h3>
                <button class="modal-close" id="closeDetailModal">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody"></div>
            <div class="modal-footer">
                <button class="btn-cancel" id="closeDetailModalBtn">Tutup</button>
            </div>
        </div>
    </div>
@php
    $riwayatDataForJs = $nilais->map(function($nilai) {
        return [
            'id'             => $nilai->id,
            'matkul'         => $nilai->pertemuan?->praktikum?->nama_praktikum ?? 'Praktikum',
            'modul'          => $nilai->pertemuan?->nama_pertemuan ?? 'Pertemuan',
            'pretest'        => $nilai->nilai_pretest ?? 0,
            'laporan'        => $nilai->nilai_laporan ?? 0,
            'nilaiAkhir'     => $nilai->nilai_akhir ?? 0,
            'tanggalSelesai' => $nilai->created_at?->format('d M Y') ?? '-',
            'status'         => $nilai->status ?? 'Pending',
        ];
    })->values();
@endphp

<script>
    (function () {
        const riwayatData = @json($riwayatDataForJs);

        let filteredData = [...riwayatData];

        function updateDropdown() {
            const matkuls = [...new Set(riwayatData.map(item => item.matkul))];
            const select = document.getElementById('filterMatkul');
            select.innerHTML = '<option value="all">Semua Mata Kuliah</option>' +
                matkuls.map(m => `<option value="${m}">${m}</option>`).join('');
        }

        function renderRiwayat() {
            const selectedMatkul = document.getElementById('filterMatkul').value;

            filteredData = riwayatData.filter(item => {
                return selectedMatkul === 'all' || item.matkul === selectedMatkul;
            });

            const container = document.getElementById('riwayatGrid');

            if (filteredData.length === 0) {
                container.innerHTML = '<div class="empty-state">Belum ada riwayat praktikum</div>';
                return;
            }

            container.innerHTML = '';
            filteredData.forEach(item => {
                const card = document.createElement('div');
                card.className = 'riwayat-card';
                card.innerHTML = `
                    <div class="card-header">
                        <div>
                            <h3>${item.matkul}</h3>
                            <p>${item.modul}</p>
                        </div>
                        <span class="status-badge status-completed">${item.status}</span>
                    </div>
                    <div class="card-body">
                        <table class="riwayat-table">
                            <tr>
                                <td>Pretest</td>
                                <td>${item.pretest}/100</td>
                            </tr>
                            <tr>
                                <td>Laporan</td>
                                <td>${item.laporan}/100</td>
                            </tr>
                        </table>
                        <div class="nilai-akhir">
                            <span>Nilai Akhir</span>
                            <span>${item.nilaiAkhir}/100</span>
                        </div>
                        <button class="btn-detail" data-id="${item.id}">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });

            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', () => openDetailModal(parseInt(btn.dataset.id)));
            });
        }

        function openDetailModal(id) {
            const item = riwayatData.find(i => i.id === id);
            if (!item) return;

            document.getElementById('detailModalBody').innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">Mata Kuliah</span>
                    <span class="detail-value">${item.matkul}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Modul</span>
                    <span class="detail-value">${item.modul}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pretest</span>
                    <span class="detail-value">${item.pretest}/100</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Laporan</span>
                    <span class="detail-value">${item.laporan}/100</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nilai Akhir</span>
                    <span class="detail-value"><strong>${item.nilaiAkhir}/100</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal</span>
                    <span class="detail-value">${item.tanggalSelesai}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-badge status-completed" style="display:inline-block;">
                            ${item.status}
                        </span>
                    </span>
                </div>
            `;

            document.getElementById('detailModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        function exportData() {
            if (filteredData.length === 0) {
                alert('Tidak ada data untuk diekspor.');
                return;
            }
            let csvContent = "Mata Kuliah,Modul,Pretest,Laporan,Nilai Akhir,Tanggal Selesai,Status\n";
            filteredData.forEach(item => {
                csvContent += `"${item.matkul}","${item.modul}",${item.pretest},${item.laporan},${item.nilaiAkhir},"${item.tanggalSelesai}","${item.status}"\n`;
            });

            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href     = url;
            a.download = 'riwayat_praktikum.csv';
            a.click();
            URL.revokeObjectURL(url);
            alert('📥 Data berhasil diekspor!');
        }

        document.getElementById('applyFilter').addEventListener('click', renderRiwayat);
        document.getElementById('exportBtn').addEventListener('click', exportData);
        document.getElementById('closeDetailModal').addEventListener('click', closeModal);
        document.getElementById('closeDetailModalBtn').addEventListener('click', closeModal);
        window.addEventListener('click', e => {
            if (e.target.classList.contains('modal')) closeModal();
        });

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
            trigger.addEventListener('click', e => {
                e.stopPropagation();
                const sub = trigger.parentElement.querySelector('.submenu');
                if (sub) sub.style.display = sub.style.display === 'none' ? 'block' : 'none';
            });
        });

        // ✅ logout-btn — gunakan optional chaining agar tidak crash jika elemen tidak ada
        document.querySelector('.logout-btn')?.addEventListener('click', () => {
            alert('Anda telah logout.');
        });

        updateDropdown();
        renderRiwayat();
    })();
</script>
</body>

</html>
