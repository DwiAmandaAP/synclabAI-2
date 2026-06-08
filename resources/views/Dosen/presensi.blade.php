<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Presensi Dosen | Portal Akademik</title>
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
        
        .summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 28px; }
        .summary-card { background: white; border-radius: 24px; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .summary-icon { width: 60px; height: 60px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white; }
        .summary-info h3 { font-size: 0.8rem; font-weight: 500; color: #64748b; margin-bottom: 4px; }
        .summary-number { font-size: 2rem; font-weight: 700; color: #1e293b; }
        
        .data-table-card { background: white; border-radius: 24px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
        .table-header h3 { font-size: 1.1rem; display: flex; align-items: center; gap: 8px; }
        .table-search { position: relative; }
        .table-search input { padding: 8px 16px 8px 40px; border-radius: 40px; border: 1px solid #e2e8f0; width: 250px; font-size: 0.85rem; }
        .table-search i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .table-responsive { overflow-x: auto; }
        
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 12px 12px; background: #f8fafc; font-weight: 600; font-size: 0.8rem; color: #64748b; border-bottom: 2px solid #e2e8f0; }
        .data-table td { padding: 12px 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; }
        
        .status-badge { padding: 4px 12px; border-radius: 40px; font-size: 0.7rem; font-weight: 600; display: inline-block; }
        .status-hadir { background: #dcfce7; color: #16a34a; }
        .status-izin { background: #fef3c7; color: #d97706; }
        .status-sakit { background: #ede9fe; color: #7c3aed; }
        .status-alpha { background: #fee2e2; color: #dc2626; }
        
        .detail-btn { padding: 6px 16px; background: #3b82f6; color: white; border: none; border-radius: 40px; cursor: pointer; font-size: 0.75rem; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .detail-btn:hover { background: #2563eb; }
        .action-buttons { display: flex; gap: 8px; }
        .btn-edit { padding: 6px 12px; background: #f59e0b; color: white; border: none; border-radius: 40px; cursor: pointer; font-size: 0.75rem; }
        .btn-edit:hover { background: #d97706; }
        
        .table-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px; }
        .pagination { display: flex; gap: 8px; align-items: center; }
        .page-btn { padding: 6px 12px; background: #f1f5f9; border: none; border-radius: 40px; cursor: pointer; transition: 0.2s; }
        .page-btn:hover { background: #3b82f6; color: white; }
        .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .page-info-text { font-size: 0.85rem; color: #64748b; }
        
        /* Modal Styles */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 28px; width: 90%; max-width: 700px; max-height: 90vh; overflow-y: auto; animation: modalFadeIn 0.3s ease; }
        @keyframes modalFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e2e8f0; }
        .modal-header h3 { font-size: 1.2rem; display: flex; align-items: center; gap: 8px; }
        .modal-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #94a3b8; transition: 0.2s; }
        .modal-close:hover { color: #ef4444; }
        .modal-body { padding: 24px; }
        
        .detail-header { margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; }
        .detail-header h4 { font-size: 1.2rem; color: #1e293b; margin-bottom: 4px; }
        .detail-header p { font-size: 0.85rem; color: #64748b; }
        
        .detail-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .detail-summary-item { background: #f8fafc; border-radius: 16px; padding: 12px; text-align: center; }
        .detail-summary-item .summary-label { display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 4px; }
        .detail-summary-item .summary-value { display: block; font-size: 1.5rem; font-weight: 700; }
        
        .detail-table-container { overflow-x: auto; }
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table th { text-align: left; padding: 10px 12px; background: #f1f5f9; font-weight: 600; font-size: 0.75rem; color: #64748b; }
        .detail-table td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 0.8rem; }
        
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 600; color: #64748b; margin-bottom: 6px; }
        .form-group select { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 40px; font-size: 0.9rem; }
        .form-group select:focus { outline: none; border-color: #3b82f6; }
        
        .modal-footer { display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px; border-top: 1px solid #e2e8f0; }
        .btn-cancel { padding: 8px 20px; background: #f1f5f9; border: none; border-radius: 40px; cursor: pointer; font-weight: 500; }
        .btn-save { padding: 8px 20px; background: #3b82f6; color: white; border: none; border-radius: 40px; cursor: pointer; font-weight: 500; }
        .btn-save:hover { background: #2563eb; }

        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .main-content { padding: 20px; }
            .page-title { font-size: 1.4rem; }
            .filter-section { flex-direction: column; }
            .filter-group { width: 100%; }
            .summary-cards { grid-template-columns: 1fr; }
            .table-search input { width: 100%; }
            .detail-summary { grid-template-columns: repeat(2, 1fr); }
            .action-buttons { flex-direction: column; }
        }
        
        @media (max-width: 480px) {
            .main-content { padding: 16px; }
            .data-table th, .data-table td { padding: 8px; font-size: 0.75rem; }
        }
    </style>

    <div class="dashboard-container">
        @include('dosen.partials.sidebar')
        
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-fingerprint"></i> Presensi Praktikum</h1>
                <div class="header-actions">
                    <button class="export-btn" id="exportBtn"><i class="fas fa-download"></i> Export CSV</button>
                </div>
            </div>

            <!-- FILTER FORM -->
            <form method="GET" action="{{ route('presensi') }}" class="filter-section" id="filterForm">
                <div class="filter-group">
                    <label><i class="fas fa-flask"></i> Praktikum</label>
                    <select name="praktikum" id="filterPraktikum">
                        <option value="all" {{ $filterPraktikum == 'all' ? 'selected' : '' }}>Semua Praktikum</option>
                        @foreach($praktikums as $p)
                        <option value="{{ $p->nama_praktikum }}" {{ $filterPraktikum == $p->nama_praktikum ? 'selected' : '' }}>
                            {{ $p->nama_praktikum }}
                        </option>
                        @endforeach
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

            <!-- SUMMARY CARDS -->
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="summary-info">
                        <h3>Total Presensi</h3>
                        <p class="summary-number" id="totalPresensi">{{ $presences->total() }}</p>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon" style="background: linear-gradient(135deg, #10b981, #047857);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="summary-info">
                        <h3>Hadir</h3>
                        <p class="summary-number" id="totalHadir">
                            {{ $presences->where('kehadiran', 'Hadir')->count() }}
                        </p>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon" style="background: linear-gradient(135deg, #f59e0b, #b45309);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="summary-info">
                        <h3>Izin</h3>
                        <p class="summary-number" id="totalIzin">
                            {{ $presences->where('kehadiran', 'Izin')->count() }}
                        </p>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="summary-info">
                        <h3>Alpha</h3>
                        <p class="summary-number" id="totalAlpha">
                            {{ $presences->where('kehadiran', 'Alpha')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- DATA TABLE -->
            <div class="data-table-card">
                <div class="table-header">
                    <h3><i class="fas fa-calendar-alt"></i> Daftar Presensi Mahasiswa</h3>
                    <div class="table-search">
                        <input type="text" id="tableSearch" placeholder="Cari nama atau NIM...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Praktikum</th>
                                <th>Pertemuan</th>
                                <th>Kehadiran</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presences as $presence)
                            <tr id="row-{{ $presence->id }}">
                                <td>{{ $presence->user->nama ?? '-' }}</td>
                                <td>{{ $presence->user->nomor_induk ?? '-' }}</td>
                                <td>{{ $presence->pertemuan->praktikum->nama_praktikum ?? '-' }}</td>
                                <td>Pertemuan {{ $presence->pertemuan->pertemuan_ke ?? '-' }}</td>
                                <td>
                                    <span class="status-badge 
                                        @if($presence->kehadiran == 'Hadir') status-hadir
                                        @elseif($presence->kehadiran == 'Izin') status-izin
                                        @elseif($presence->kehadiran == 'Sakit') status-sakit
                                        @else status-alpha
                                        @endif">
                                        {{ $presence->kehadiran }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $presence->status == 'Dikonfirmasi' ? 'status-hadir' : 'status-alpha' }}">
                                        {{ $presence->status ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $presence->created_at ? $presence->created_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="detail-btn" onclick="openDetailModal({{ $presence->id }})">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <button class="btn-edit" onclick="openEditModal({{ $presence->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">
                                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                                    Tidak ada data presensi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span class="page-info-text">
                        Menampilkan {{ $presences->firstItem() ?? 0 }} - {{ $presences->lastItem() ?? 0 }} 
                        dari {{ $presences->total() }} data
                    </span>
                    <div class="pagination">
                        {{ $presences->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- DETAIL MODAL -->
    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle"></i> Detail Presensi</h3>
                <button class="modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-header">
                    <h4 id="detailNama">-</h4>
                    <p id="detailInfo">-</p>
                </div>
                <div class="detail-summary">
                    <div class="detail-summary-item">
                        <span class="summary-label">NIM</span>
                        <span class="summary-value" id="detailNim" style="font-size: 1rem;">-</span>
                    </div>
                    <div class="detail-summary-item">
                        <span class="summary-label">Kehadiran</span>
                        <span class="summary-value" id="detailKehadiran" style="font-size: 1rem;">-</span>
                    </div>
                    <div class="detail-summary-item">
                        <span class="summary-label">Status</span>
                        <span class="summary-value" id="detailStatus" style="font-size: 1rem;">-</span>
                    </div>
                    <div class="detail-summary-item">
                        <span class="summary-label">Tanggal</span>
                        <span class="summary-value" id="detailTanggal" style="font-size: 1rem;">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Presensi</h3>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editPresensiId">
                <div class="detail-header">
                    <h4 id="editNamaDisplay">-</h4>
                    <p id="editInfoDisplay">-</p>
                </div>
                <div class="form-group">
                    <label>Kehadiran</label>
                    <select id="editKehadiran">
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Alpha">Alpha</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Komentar</label>
                    <textarea id="editKomentar" rows="3" style="width: 100%; padding: 10px; border-radius: 12px; border: 1px solid #e2e8f0;" placeholder="Tambahkan komentar..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button class="btn-save" onclick="saveEdit()">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            // Table search functionality
            document.getElementById('tableSearch').addEventListener('input', function() {
                const search = this.value.toLowerCase();
                const rows = document.querySelectorAll('.data-table tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(search) ? '' : 'none';
                });
            });

            // Open detail modal
            window.openDetailModal = function(id) {
                const row = document.getElementById(`row-${id}`);
                if (!row) return;
                
                const cells = row.querySelectorAll('td');
                document.getElementById('detailNama').textContent = cells[0].textContent;
                document.getElementById('detailNim').textContent = cells[1].textContent;
                document.getElementById('detailInfo').textContent = `${cells[2].textContent} - ${cells[3].textContent}`;
                
                // Get kehadiran badge text
                const kehadiranBadge = cells[4].querySelector('.status-badge');
                const statusBadge = cells[5].querySelector('.status-badge');
                
                document.getElementById('detailKehadiran').textContent = kehadiranBadge ? kehadiranBadge.textContent : '-';
                document.getElementById('detailStatus').textContent = statusBadge ? statusBadge.textContent : '-';
                document.getElementById('detailTanggal').textContent = cells[6].textContent;
                
                document.getElementById('detailModal').classList.add('active');
            };

            // Open edit modal
            window.openEditModal = function(id) {
                const row = document.getElementById(`row-${id}`);
                if (!row) return;
                
                const cells = row.querySelectorAll('td');
                const kehadiran = cells[4].querySelector('.status-badge')?.textContent.trim() || 'Hadir';
                
                document.getElementById('editPresensiId').value = id;
                document.getElementById('editNamaDisplay').textContent = cells[0].textContent;
                document.getElementById('editInfoDisplay').textContent = `${cells[1].textContent} - ${cells[2].textContent}`;
                document.getElementById('editKehadiran').value = kehadiran;
                document.getElementById('editKomentar').value = '';
                
                document.getElementById('editModal').classList.add('active');
            };

            // Save edit
            window.saveEdit = async function() {
                const id = document.getElementById('editPresensiId').value;
                const kehadiran = document.getElementById('editKehadiran').value;
                const komentar = document.getElementById('editKomentar').value;
                
                try {
                    const response = await fetch(`/presensi/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            kehadiran: kehadiran,
                            komentar: komentar
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Presensi berhasil diperbarui!');
                        closeEditModal();
                        // Reload the page to show updated data
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal memperbarui presensi');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan perubahan');
                }
            };

            // Close modals
            window.closeDetailModal = function() {
                document.getElementById('detailModal').classList.remove('active');
            };
            
            window.closeEditModal = function() {
                document.getElementById('editModal').classList.remove('active');
            };

            // Close modals on outside click
            window.addEventListener('click', function(e) {
                if (e.target.classList.contains('modal')) {
                    e.target.classList.remove('active');
                }
            });

            // Export functionality
            document.getElementById('exportBtn').addEventListener('click', function() {
                alert('Fitur export akan segera hadir!');
            });
            });
        })();
    </script>
</body>
</html>