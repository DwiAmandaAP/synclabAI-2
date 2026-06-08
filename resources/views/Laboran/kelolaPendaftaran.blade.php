<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pendaftaran Praktikum</title>
    <link rel="stylesheet" href="{{ asset('lab_css/kelolaPraktikum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .detail-section {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .detail-section h4 {
            background: #f5f5f5;
            padding: 10px 15px;
            margin: 0;
            border-bottom: 1px solid #ddd;
            color: #333;
        }

        .detail-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-section th,
        .detail-section td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .detail-section th {
            background: #fafafa;
            font-weight: 600;
            width: 180px;
        }

        .detail-section tr:last-child td,
        .detail-section tr:last-child th {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-primary {
            background: #cce5ff;
            color: #004085;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }

        .badge-dikonfirmasi {
            background: #d4edda;
            color: #155724;
        }

        .badge-ditolak {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .role-praktikan {
            background: #cce5ff;
            color: #004085;
        }

        .role-asisten {
            background: #d4edda;
            color: #155724;
        }

        .role-dosen {
            background: #d1ecf1;
            color: #0c5460;
        }

        .close {
            font-size: 28px;
            cursor: pointer;
        }

        .search-box {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        @media (max-width: 768px) {
            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background-color: white;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            max-width: 650px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        .modal-content.large {
            max-width: 750px;
        }

        .modal-header {
            background: linear-gradient(135deg, #4a6cf7, #34d399);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 20px;
        }

        .modal-header .close {
            cursor: pointer;
            font-size: 28px;
            font-weight: bold;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .modal-header .close:hover {
            opacity: 1;
        }
        
        .detail-section{
            padding: 1.75rem;
        }

        .detail-actions {
            padding: 20px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-primary, .btn-secondary {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: #4a6cf7;
            color: white;
        }

        .btn-primary:hover {
            background: #3d5ad1;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2><i class="fas fa-clipboard-list"></i> Manajemen Pendaftaran Praktikum</h2>
            <div class="actions">
                <div class="filter-group">

                    <select id="roleFilter" class="filter-select" onchange="applyFilters()">
                        <option value="">Semua Role</option>
                        <option value="Praktikan" {{ request('role') == 'Praktikan' ? 'selected' : '' }}>Praktikan
                        </option>
                        <option value="Asisten" {{ request('role') == 'Asisten' ? 'selected' : '' }}>Asisten</option>
                        <option value="Dosen" {{ request('role') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                    </select>

                    <form method="GET" action="{{ route('kelolaPendaftaran') }}" id="searchForm">
                        <input type="text" name="search" placeholder="Cari nama, NIM, atau praktikum..."
                            value="{{ request('search') }}" onkeyup="if(event.key === 'Enter') this.form.submit()"
                            class="search-box">
                    </form>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success"
                style="padding: 12px 16px; margin: 15px 0; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724; display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <strong><i class="fas fa-check-circle"></i> Sukses!</strong>
                    <p style="margin: 8px 0 0 0;">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.style.display='none'"
                    style="background: none; border: none; color: #155724; cursor: pointer; font-size: 20px;">×</button>
            </div>
        @endif

        <!-- Statistik Ringkasan -->
        @if (count($pendaftarans) > 0)
            <div class="stats-container"
                style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
                <h3 style="margin-bottom: 15px;"><i class="fas fa-chart-bar"></i> Ringkasan Statistik</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 24px; color: #17a2b8;">{{ $statistics['total_praktikan'] }}</div>
                        <div style="color: #666;">Praktikan</div>
                    </div>
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 24px; color: #6c757d;">{{ $statistics['total_asisten_dosen'] }}</div>
                        <div style="color: #666;">Asisten & Dosen</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Praktikum</th>
                        <th>Pendaftar</th>
                        <th>NIM/NIDN</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftarans as $pendaftaran)
                        <tr>
                            <td>{{ $pendaftaran->id }}</td>
                            <td>
                                <strong>{{ $pendaftaran->praktikum_nama ?? ($pendaftaran->nama_praktikum ?? '-') }}</strong>
                                <br>
                                <small style="color: #666;">{{ $pendaftaran->kode_praktikum ?? '-' }} | Angkatan
                                    {{ $pendaftaran->angkatan ?? '-' }} | Semester
                                    {{ $pendaftaran->semester ?? '-' }}</small>
                            </td>
                            <td>
                                <strong>{{ $pendaftaran->user_nama ?? ($pendaftaran->nama ?? '-') }}</strong>
                                <br>
                                <small
                                    style="color: #666;">{{ $pendaftaran->user_email ?? ($pendaftaran->email ?? '-') }}</small>
                            </td>
                            <td>{{ $pendaftaran->user_nomor_induk ?? ($pendaftaran->nomor_induk ?? '-') }}</td>
                            <td>
                                <span class="role-badge role-{{ strtolower($pendaftaran->role) }}">
                                    <i
                                        class="fas {{ $pendaftaran->role == 'Praktikan' ? 'fa-user-graduate' : ($pendaftaran->role == 'Asisten' ? 'fa-chalkboard-user' : 'fa-chalkboard') }}"></i>
                                    {{ $pendaftaran->role }}
                                </span>
                            </td>
                            <td>{{ $pendaftaran->created_at ? \Carbon\Carbon::parse($pendaftaran->created_at)->format('d/m/Y H:i')  : '-' }}
                            </td>
                            <td>
                                <button class="detail" onclick="openDetailModal({{ $pendaftaran->id }})">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-users"></i> Tidak Ada Data Pendaftaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $pendaftarans->appends(request()->query())->links() }}
            </div>
        </div>
    </main>

    <div class="modal" id="detailModal">
        <div class="modal-content large">
            <div class="modal-header">
                <h3><i class="fas fa-file-alt"></i> Detail Pendaftaran Praktikum</h3>
                <span class="close" onclick="closeDetailModal()">&times;</span>
            </div>
            <div id="detailContent">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                </div>
            </div>
            <div class="detail-actions">
                <button onclick="closeDetailModal()" class="btn-secondary">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        // Store pendaftaran data
        const pendaftaranDataMap = new Map();

        document.addEventListener('DOMContentLoaded', function() {
            const pendaftaranData = @json($pendaftarans->items());

            pendaftaranData.forEach(item => {
                pendaftaranDataMap.set(item.id, item);
            });
        });

        function openDetailModal(id) {
            const pendaftaran = pendaftaranDataMap.get(id);
            if (!pendaftaran) {
                console.error('Data pendaftaran not found:', id);
                document.getElementById('detailContent').innerHTML =
                    '<div style="text-align: center; padding: 40px; color: red;"><i class="fas fa-exclamation-circle"></i> Data tidak ditemukan</div>';
                document.getElementById('detailModal').classList.add('show');
                return;
            }

            let html = `
                <div class="detail-section">
                    <h4><i class="fas fa-graduation-cap"></i> Informasi Pendaftar</h4>
                    <table>
                        <tr><th>Nama Lengkap</th><td>${escapeHtml(pendaftaran.user_nama || pendaftaran.nama || '-')}</td>
                        </tr>
                        <tr><th>Nomor Induk (NIM/NIDN)</th><td>${escapeHtml(pendaftaran.user_nomor_induk || pendaftaran.nomor_induk || '-')}</td>
                        </tr>
                        <tr><th>Email</th><td>${escapeHtml(pendaftaran.user_email || pendaftaran.email || '-')}</td>
                        </tr>
                        <tr><th>No. Telepon</th><td>${escapeHtml(pendaftaran.user_nohp || pendaftaran.nohp || '-')}</td>
                        </tr>
                        <tr><th>Role Pendaftaran</th>
                            <td>
                                <span class="role-badge role-${pendaftaran.role ? pendaftaran.role.toLowerCase() : 'praktikan'}">
                                    <i class="fas ${pendaftaran.role === 'Praktikan' ? 'fa-user-graduate' : (pendaftaran.role === 'Asisten' ? 'fa-chalkboard-user' : 'fa-chalkboard')}"></i>
                                    ${escapeHtml(pendaftaran.role || '-')}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-flask"></i> Informasi Praktikum</h4>
                    <table>
                        <tr><th>Kode Praktikum</th><td>${escapeHtml(pendaftaran.kode_praktikum || pendaftaran.praktikum_kode || '-')}</td>
                        </tr>
                        <tr><th>Nama Praktikum</th><td><strong>${escapeHtml(pendaftaran.praktikum_nama || pendaftaran.nama_praktikum || '-')}</strong></td>
                        </tr>
                        <tr><th>Angkatan</th><td>${escapeHtml(pendaftaran.angkatan || '-')}</td>
                        </tr>
                        <tr><th>Semester</th><td>${escapeHtml(pendaftaran.semester || '-')}</td>
                        </tr>
                    </table>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-info-circle"></i> Status Pendaftaran</h4>
                    <table>
                        <tr><th>Status Pendaftaran</th>
                            <td>
                                <span class="badge badge-${pendaftaran.status ? pendaftaran.status.toLowerCase() === 'dikonfirmasi' ? 'success' : (pendaftaran.status.toLowerCase() === 'ditolak' ? 'danger' : 'pending') : 'pending'}">
                                    <i class="fas ${pendaftaran.status === 'Dikonfirmasi' ? 'fa-check-circle' : (pendaftaran.status === 'Ditolak' ? 'fa-times-circle' : 'fa-clock')}"></i>
                                    ${escapeHtml(pendaftaran.status || 'Pending')}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Tanggal Pendaftaran</th><td>${formatDate(pendaftaran.created_at)}</td>
                        </tr>
                        <tr><th>Terakhir Diupdate</th><td>${formatDate(pendaftaran.updated_at)}</td>
                        </tr>
                    </table>
                </div>
            `;
            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('detailModal').classList.add('show');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const role = document.getElementById('roleFilter').value;
            const search = document.querySelector('input[name="search"]').value;
            let url = "{{ route('kelolaPendaftaran') }}?";

            if (status) url += `status=${status}&`;
            if (role) url += `role=${role}&`;
            if (search) url += `search=${search}&`;

            window.location.href = url;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        window.onclick = function(event) {
            if (event.target.classList && event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }
    </script>
</body>

</html>
