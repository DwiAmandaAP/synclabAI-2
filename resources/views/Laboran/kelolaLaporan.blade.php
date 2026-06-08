<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Laporan</title>
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
        }

        .filter-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        .file-link {
            color: #007bff;
            text-decoration: none;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .nilai-input {
            width: 80px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .komentar-textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .search-box {
                width: 100%;
            }

            .filter-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2>Manajemen Laporan</h2>
            <div class="actions" style="width: 100%">
                <div class="filter-group">
                    <select id="statusFilter" class="filter-select" onchange="applyFilters()">
                        <option value="">Semua Status</option>
                        <option value="Disubmit">Disubmit</option>
                        <option value="Ditandai Selesai">Ditandai Selesai</option>
                        <option value="Dalam Review">Dalam Review</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Ditolak">Ditolak</option>
                        <option value="Terlambat">Terlambat</option>
                    </select>
                    <form method="GET" action="{{ route('kelolaLaporan') }}" id="searchForm" style="width: 100%">
                        <input type="text" name="search" placeholder="Cari mahasiswa atau pertemuan..." 
                               value="{{ request('search') }}" onkeyup="if(event.key === 'Enter') this.form.submit()" 
                               class="search-box">
                    </form>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger"
                style="padding: 12px 16px; margin: 15px 0; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24; display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <strong><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.style.display='none'"
                    style="background: none; border: none; color: #721c24; cursor: pointer; font-size: 20px;">×</button>
            </div>
        @endif

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

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pertemuan</th>
                        <th>Mahasiswa</th>
                        <th>File Laporan</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Waktu Submit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengumpulanLaporans as $pengumpulan)
                        <tr>
                            <td>{{ $pengumpulan->id }}</td>
                            <td>{{ $pengumpulan->pertemuan->nama ?? 'Pertemuan ' . ($pengumpulan->id_pertemuan ?? '-') }}</td>
                            <td>{{ $pengumpulan->user->nama ?? $pengumpulan->user->name ?? '-' }}</td>
                            <td>
                                @if($pengumpulan->file_path)
                                    <a href="{{ asset('storage/' . $pengumpulan->file_path) }}" target="_blank" class="file-link">
                                        <i class="fas fa-file-pdf"></i> Lihat File
                                    </a>
                                @else
                                    <span class="badge badge-secondary">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ 
                                    $pengumpulan->status === 'Diterima' ? 'success' : 
                                    ($pengumpulan->status === 'Ditolak' ? 'danger' :
                                    ($pengumpulan->status === 'Terlambat' ? 'warning' :
                                    ($pengumpulan->status === 'Dalam Review' ? 'info' : 'secondary'))) 
                                }}">
                                    {{ $pengumpulan->status ?? 'Disubmit' }}
                                </span>
                            </td>
                            <td>
                                @if($pengumpulan->nilai)
                                    {{ $pengumpulan->nilai }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $pengumpulan->created_at ? \Carbon\Carbon::parse($pengumpulan->created_at)->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                {{-- <button class="edit" onclick="openEditModal({{ $pengumpulan->id }})">Edit</button>
                                <form method="POST" action="{{ route('deletePengumpulanLaporan', $pengumpulan->id) }}"
                                    style="display:inline;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete">Hapus</button>
                                </form> --}}
                                <button class="detail" onclick="openDetailModal({{ $pengumpulan->id }})">Detail</button>
                                @if($pengumpulan->status === 'Disubmit' || $pengumpulan->status === 'Terlambat')
                                    <button class="btn-primary-small" onclick="openReviewModal({{ $pengumpulan->id }})">Review</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-file-alt"></i> Tidak Ada Laporan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $pengumpulanLaporans->appends(request()->query())->links() }}
            </div>
        </div>
    </main>

    <!-- Modal Review (Untuk memberikan nilai dan komentar) -->
    <div class="modal" id="reviewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-clipboard-list"></i> Review Laporan</h3>
                <span class="close" onclick="closeReviewModal()">&times;</span>
            </div>
            <form method="POST" id="reviewForm">
                @csrf
                @method('PUT')
                <div id="reviewInfo" style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-radius: 5px;">
                    <!-- Info akan diisi via JS -->
                </div>
                <div class="form-group">
                    <label for="review_nilai">Nilai <span class="required">*</span></label>
                    <input type="number" id="review_nilai" name="nilai" min="0" max="100" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="review_komentar">Komentar / Feedback</label>
                    <textarea id="review_komentar" name="komentar" rows="4" class="komentar-textarea" placeholder="Berikan komentar atau feedback untuk mahasiswa..."></textarea>
                </div>
                <div class="form-group">
                    <label for="review_status">Status Penilaian</label>
                    <select id="review_status" name="status">
                        <option value="Diterima">Diterima</option>
                        <option value="Ditolak">Ditolak</option>
                        <option value="Dalam Review">Dalam Review</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeReviewModal()">Batalkan</button>
                    <button type="submit" class="btn-primary">Simpan Review</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal" id="detailModal">
        <div class="modal-content large">
            <div class="modal-header">
                <h3><i class="fas fa-file-alt"></i> Detail Pengumpulan Laporan</h3>
                <span class="close" onclick="closeDetailModal()">&times;</span>
            </div>
            <div id="detailContent"></div>
            <div class="detail-actions">
                <button onclick="closeDetailModal()" class="btn-secondary">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Store all pengumpulan laporan data
        const pengumpulanDataMap = new Map();

        document.addEventListener('DOMContentLoaded', function () {
            // Data from server-side
            const pengumpulanData = @json($pengumpulanLaporans);
            
            pengumpulanData.forEach(item => {
                pengumpulanDataMap.set(item.id, item);
            });
        });

        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(id) {
            const data = pengumpulanDataMap.get(id);
            if (!data) {
                console.error('Data not found:', id);
                return;
            }
            
            document.getElementById('edit_id_pertemuan').value = data.id_pertemuan || '';
            document.getElementById('edit_id_user').value = data.id_user || '';
            document.getElementById('edit_status').value = data.status || 'Disubmit';
            document.getElementById('edit_nilai').value = data.nilai || '';
            document.getElementById('edit_komentar').value = data.komentar || '';
            
            // Show current file
            const currentFileSpan = document.getElementById('currentFile');
            if (data.filepath) {
                currentFileSpan.innerHTML = `<i class="fas fa-paperclip"></i> File saat ini: <a href="{{ asset('storage') }}/${data.filepath}" target="_blank">Lihat File</a>`;
            } else {
                currentFileSpan.innerHTML = 'Belum ada file';
            }
            
            // Set form action
            document.getElementById('editForm').action = "{{ url('laboran/pengumpulan-laporan') }}/" + id;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openReviewModal(id) {
            const data = pengumpulanDataMap.get(id);
            if (!data) return;
            
            // Fill review info
            const reviewInfo = document.getElementById('reviewInfo');
            reviewInfo.innerHTML = `
                <p><strong>Mahasiswa:</strong> ${escapeHtml(data.user?.nama || data.user?.name || '-')}</p>
                <p><strong>Pertemuan:</strong> ${escapeHtml(data.pertemuan?.nama || 'Pertemuan ' + (data.id_pertemuan || '-'))}</p>
                <p><strong>Status Saat Ini:</strong> <span class="badge badge-${data.status === 'Diterima' ? 'success' : (data.status === 'Ditolak' ? 'danger' : 'warning')}">${escapeHtml(data.status || 'Disubmit')}</span></p>
                ${data.filepath ? `<p><strong>File Laporan:</strong> <a href="{{ asset('storage') }}/${data.filepath}" target="_blank">Lihat File</a></p>` : ''}
            `;
            
            document.getElementById('review_nilai').value = data.nilai || '';
            document.getElementById('review_komentar').value = data.komentar || '';
            document.getElementById('review_status').value = data.status === 'Diterima' || data.status === 'Ditolak' ? data.status : 'Dalam Review';
            
            document.getElementById('reviewForm').action = "{{ url('laboran/pengumpulan-laporan') }}/" + id;
            document.getElementById('reviewModal').style.display = 'flex';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
        }

        function openDetailModal(id) {
            const data = pengumpulanDataMap.get(id);
            if (!data) return;

            let html = `
                <div class="detail-section">
                    <h4>Informasi Laporan</h4>
                    <table>
                        <tr><th>ID Laporan</th><td>${escapeHtml(data.id)}</td></tr>
                        <tr><th>Pertemuan</th><td>${escapeHtml(data.pertemuan?.nama || 'Pertemuan ' + (data.id_pertemuan || '-'))}</td></tr>
                        <tr><th>Mahasiswa</th><td>${escapeHtml(data.user?.nama || data.user?.name || '-')} (${escapeHtml(data.user?.nomor_induk || '-')})</td></tr>
                        <tr><th>File Laporan</th><td>${data.filepath ? `<a href="{{ asset('storage') }}/${data.filepath}" target="_blank" class="file-link"><i class="fas fa-file-pdf"></i> Lihat File</a>` : '-'}</td></tr>
                        <tr><th>Status</th><td><span class="badge badge-${data.status === 'Diterima' ? 'success' : (data.status === 'Ditolak' ? 'danger' : (data.status === 'Terlambat' ? 'warning' : 'secondary'))}">${escapeHtml(data.status || 'Disubmit')}</span></td></tr>
                        <tr><th>Nilai</th><td>${data.nilai ? data.nilai : '-'}</td></tr>
                        <tr><th>Komentar</th><td>${escapeHtml(data.komentar) || '-'}</td></tr>
                        <tr><th>Waktu Submit</th><td>${formatDate(data.created_at)}</td></tr>
                        <tr><th>Terakhir Update</th><td>${formatDate(data.updated_at)}</td></tr>
                    </table>
                </div>
            `;

            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('detailModal').style.display = 'flex';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const search = document.querySelector('input[name="search"]').value;
            let url = "{{ route('kelolaLaporan') }}?";
            
            if (status) url += `status=${status}&`;
            if (search) url += `search=${search}&`;
            
            window.location.href = url;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, function (m) {
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

        window.onclick = function (event) {
            if (event.target.classList && event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>

</html>