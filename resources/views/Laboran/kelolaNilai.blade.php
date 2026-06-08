<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Nilai</title>
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

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-terkonfirmasi {
            background: #d4edda;
            color: #155724;
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

        .nilai-box {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .nilai-rendah {
            background: #f8d7da;
            color: #721c24;
        }

        .nilai-sedang {
            background: #fff3cd;
            color: #856404;
        }

        .nilai-tinggi {
            background: #d4edda;
            color: #155724;
        }

        .nilai-sempurna {
            background: #cce5ff;
            color: #004085;
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
            <h2><i class="fas fa-chart-line"></i> Manajemen Nilai</h2>
            <div class="actions">
                <div class="filter-group">
                    <select id="statusFilter" class="filter-select" onchange="applyFilters()">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Terkonfirmasi" {{ request('status') == 'Terkonfirmasi' ? 'selected' : '' }}>Terkonfirmasi</option>
                    </select>
                    <form method="GET" action="{{ route('kelolaNilai') }}" id="searchForm">
                        <input type="text" name="search" placeholder="Cari mahasiswa atau pertemuan..." 
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

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pertemuan</th>
                        <th>Mahasiswa</th>
                        <th>Nilai Pretest</th>
                        <th>Nilai Laporan</th>
                        <th>Nilai Total</th>
                        <th>Nilai Akhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($nilais as $nilai)
                        <tr>
                            <td>{{ $nilai->id }}</td>
                            <td>
                                {{ $nilai->pertemuan->nama ?? 'Pertemuan ' . ($nilai->id_pertemuan ?? '-') }}
                                @if($nilai->pertemuan && $nilai->pertemuan->modul)
                                    <br><small style="color: #666;">{{ $nilai->pertemuan->modul->nama_modul ?? '' }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $nilai->user->nama ?? $nilai->user->name ?? '-' }}
                                <br><small style="color: #666;">{{ $nilai->user->nomor_induk ?? '-' }}</small>
                            </td>
                            <td>
                                @php
                                    $pretestClass = $nilai->nilai_pretest >= 80 ? 'nilai-tinggi' : ($nilai->nilai_pretest >= 60 ? 'nilai-sedang' : 'nilai-rendah');
                                @endphp
                                <span class="nilai-box {{ $pretestClass }}">
                                    {{ $nilai->nilai_pretest ?? 0 }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $laporanClass = $nilai->nilai_laporan >= 80 ? 'nilai-tinggi' : ($nilai->nilai_laporan >= 60 ? 'nilai-sedang' : 'nilai-rendah');
                                @endphp
                                <span class="nilai-box {{ $laporanClass }}">
                                    {{ $nilai->nilai_laporan ?? 0 }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $totalClass = $nilai->nilai_total >= 80 ? 'nilai-tinggi' : ($nilai->nilai_total >= 60 ? 'nilai-sedang' : 'nilai-rendah');
                                @endphp
                                <span class="nilai-box {{ $totalClass }}">
                                    {{ $nilai->nilai_total ?? 0 }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $akhirClass = $nilai->nilai_akhir >= 80 ? 'nilai-tinggi' : ($nilai->nilai_akhir >= 60 ? 'nilai-sedang' : 'nilai-rendah');
                                    if ($nilai->nilai_akhir >= 90) $akhirClass = 'nilai-sempurna';
                                @endphp
                                <span class="nilai-box {{ $akhirClass }}">
                                    <strong>{{ $nilai->nilai_akhir ?? 0 }}</strong>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower($nilai->status) === 'terkonfirmasi' ? 'success' : 'pending' }}">
                                    <i class="fas {{ $nilai->status === 'Terkonfirmasi' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                    {{ $nilai->status ?? 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <button class="detail" onclick="openDetailModal({{ $nilai->id }})">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-chart-simple"></i> Tidak Ada Data Nilai
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $nilais->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Statistik Ringkasan -->
        @if(count($nilais) > 0)
        <div class="stats-container" style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
            <h3 style="margin-bottom: 15px;"><i class="fas fa-chart-bar"></i> Ringkasan Statistik</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; color: #007bff;">{{ $statistics['rata_rata_nilai_akhir'] }}</div>
                    <div style="color: #666;">Rata-rata Nilai Akhir</div>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; color: #28a745;">{{ $statistics['nilai_tertinggi'] }}</div>
                    <div style="color: #666;">Nilai Tertinggi</div>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; color: #dc3545;">{{ $statistics['nilai_terendah'] }}</div>
                    <div style="color: #666;">Nilai Terendah</div>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; color: #ffc107;">{{ $statistics['total_mahasiswa'] }}</div>
                    <div style="color: #666;">Total Mahasiswa</div>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; color: #17a2b8;">{{ $statistics['total_terkonfirmasi'] }}</div>
                    <div style="color: #666;">Terkonfirmasi</div>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; color: #6c757d;">{{ $statistics['total_pending'] }}</div>
                    <div style="color: #666;">Pending</div>
                </div>
            </div>
        </div>
        @endif

    </main>

    <!-- Modal Detail Nilai (Menggunakan Model Nilai) -->
    <div class="modal" id="detailModal">
        <div class="modal-content large">
            <div class="modal-header">
                <h3><i class="fas fa-file-alt"></i> Detail Nilai Mahasiswa</h3>
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
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
                <button onclick="window.print()" class="btn-primary">
                    <i class="fas fa-print"></i> Cetak
                </button>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Store nilai data from Laravel Collection (using Model)
        const nilaiDataMap = new Map();

        document.addEventListener('DOMContentLoaded', function() {
            // Data from server-side (already includes relations from Model)
            const nilaiData = @json($nilais);
            
            nilaiData.forEach(item => {
                nilaiDataMap.set(item.id, item);
            });
        });

        function openDetailModal(id) {
            const nilai = nilaiDataMap.get(id);
            if (!nilai) {
                console.error('Data nilai not found:', id);
                document.getElementById('detailContent').innerHTML = '<div style="text-align: center; padding: 40px; color: red;">Data tidak ditemukan</div>';
                document.getElementById('detailModal').style.display = 'flex';
                return;
            }

            // Helper function to get nilai class
            function getNilaiClass(nilai) {
                if (nilai >= 90) return 'nilai-sempurna';
                if (nilai >= 80) return 'nilai-tinggi';
                if (nilai >= 60) return 'nilai-sedang';
                return 'nilai-rendah';
            }

            // Calculate grade letter
            function getGradeLetter(nilai) {
                if (nilai >= 85) return 'A';
                if (nilai >= 75) return 'B';
                if (nilai >= 60) return 'C';
                if (nilai >= 50) return 'D';
                return 'E';
            }

            const pretestClass = getNilaiClass(nilai.nilai_pretest);
            const laporanClass = getNilaiClass(nilai.nilai_laporan);
            const totalClass = getNilaiClass(nilai.nilai_total);
            const akhirClass = getNilaiClass(nilai.nilai_akhir);
            const gradeLetter = getGradeLetter(nilai.nilai_akhir);

            let html = `
                <div class="detail-section">
                    <h4><i class="fas fa-user-graduate"></i> Informasi Mahasiswa</h4>
                    <table>
                        <tr><th>Nama Lengkap</th><td>${escapeHtml(nilai.user?.nama || nilai.user?.name || '-')}</td></tr>
                        <tr><th>Nomor Induk</th><td>${escapeHtml(nilai.user?.nomor_induk || '-')}</td></tr>
                        <tr><th>Program Studi</th><td>${escapeHtml(nilai.user?.program_studi || '-')}</td></tr>
                        <tr><th>Email</th><td>${escapeHtml(nilai.user?.email || '-')}</td></tr>
                    </table>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-book"></i> Informasi Pertemuan</h4>
                    <table>
                        <tr><th>Pertemuan Ke-</th><td>${escapeHtml(nilai.pertemuan?.pertemuan_ke || nilai.id_pertemuan)}</td></tr>
                        <tr><th>Nama Pertemuan</th><td>${escapeHtml(nilai.pertemuan?.nama || '-')}</td></tr>
                        <tr><th>Modul</th><td>${escapeHtml(nilai.pertemuan?.modul?.nama_modul || '-')}</td></tr>
                        <tr><th>Tanggal Pelaksanaan</th><td>${formatDate(nilai.pertemuan?.tanggal) || '-'}</td></tr>
                    </table>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-chart-line"></i> Detail Nilai</h4>
                    <table>
                        <tr style="background: #f0f0f0;">
                            <th style="width: 200px;">Komponen Penilaian</th>
                            <th>Nilai</th>
                            <th>Kategori</th>
                            <th>Bobot</th>
                        </tr>
                        <tr>
                            <td><strong>Nilai Pretest</strong></td>
                            <td><span class="nilai-box ${pretestClass}">${nilai.nilai_pretest ?? 0}</span></td>
                            <td>${getKategoriNilai(nilai.nilai_pretest)}</td>
                            <td>30%</td>
                        </tr>
                        <tr>
                            <td><strong>Nilai Laporan</strong></td>
                            <td><span class="nilai-box ${laporanClass}">${nilai.nilai_laporan ?? 0}</span></td>
                            <td>${getKategoriNilai(nilai.nilai_laporan)}</td>
                            <td>70%</td>
                        </tr>
                        <tr style="border-top: 2px solid #ddd;">
                            <td><strong>Nilai Total (Bobot)</strong></td>
                            <td><span class="nilai-box ${totalClass}">${nilai.nilai_total ?? 0}</span></td>
                            <td>${getKategoriNilai(nilai.nilai_total)}</td>
                            <td>-</td>
                        </tr>
                        <tr style="background: #f8f9fa;">
                            <td><strong><i class="fas fa-star"></i> Nilai Akhir</strong></td>
                            <td><span class="nilai-box ${akhirClass}" style="font-size: 18px; padding: 8px 16px;">${nilai.nilai_akhir ?? 0}</span></td>
                            <td><strong>Grade: ${gradeLetter}</strong></td>
                            <td>-</td>
                        </tr>
                    </table>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-comment"></i> Komentar & Feedback</h4>
                    <div style="padding: 15px; background: #fafafa; border-radius: 5px;">
                        ${escapeHtml(nilai.komentar) || '<em style="color: #999;">Tidak ada komentar</em>'}
                    </div>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-info-circle"></i> Informasi Lainnya</h4>
                    <table>
                        <tr><th>Status Penilaian</th>
                            <td>
                                <span class="badge badge-${nilai.status === 'Terkonfirmasi' ? 'success' : 'pending'}">
                                    <i class="fas ${nilai.status === 'Terkonfirmasi' ? 'fa-check-circle' : 'fa-clock'}"></i>
                                    ${nilai.status || 'Pending'}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Tanggal Penilaian</th><td>${formatDate(nilai.created_at)}</td></tr>
                        <tr><th>Terakhir Diupdate</th><td>${formatDate(nilai.updated_at)}</td></tr>
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
            let url = "{{ route('kelolaNilai') }}?";
            
            if (status) url += `status=${status}&`;
            if (search) url += `search=${search}&`;
            
            window.location.href = url;
        }

        function getKategoriNilai(nilai) {
            if (nilai >= 85) return '<span style="color: #28a745;">Sangat Baik</span>';
            if (nilai >= 75) return '<span style="color: #17a2b8;">Baik</span>';
            if (nilai >= 60) return '<span style="color: #ffc107;">Cukup</span>';
            if (nilai >= 50) return '<span style="color: #fd7e14;">Kurang</span>';
            return '<span style="color: #dc3545;">Sangat Kurang</span>';
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
                event.target.style.display = 'none';
            }
        }
    </script>
</body>

</html>