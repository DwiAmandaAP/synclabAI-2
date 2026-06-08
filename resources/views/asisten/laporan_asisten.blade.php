<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Mahasiswa</title>
    <link rel="stylesheet" href="{{asset('asisten_css/laporan_asisten.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
@includeIf('asisten.partials.sidebar')

    <main class="main-content">
        <div class="header">
            <h2>Reports</h2>
        </div>

        <form method="GET" action="{{ route('nilaiLaporan') }}" class="filter-box">
            <select name="praktikum" id="filterMatkul" onchange="this.form.submit()">
                <option value="">Mata Kuliah</option>
                @foreach($praktikumNames as $namaPraktikum)
                    <option value="{{ $namaPraktikum }}" {{ request('praktikum') == $namaPraktikum ? 'selected' : '' }}>
                        {{ $namaPraktikum }}
                    </option>
                @endforeach
            </select>

            <select name="pertemuan_id" id="filterPertemuan" onchange="this.form.submit()">
                <option value="">Semua Pertemuan</option>
                @foreach($pertemuans as $pertemuan)
                    <option value="{{ $pertemuan->id }}" {{ request('pertemuan_id') == $pertemuan->id ? 'selected' : '' }}>
                        Pertemuan {{ $pertemuan->pertemuan_ke }}: {{ $pertemuan->nama_pertemuan }}
                    </option>
                @endforeach
            </select>

            <select name="status" id="filterStatus" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>

            <input type="text" name="search" id="searchInput" placeholder="Cari nama / NIM..." value="{{ request('search') }}">
            <button type="submit" style="display: none;">Search</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Praktikum</th>
                        <th>Pertemuan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    @forelse($pengumpulanLaporans as $laporan)
                        @php
                            $statusClass = '';
                            $statusText = '';
                            if($laporan->status == 'Diterima') {
                                $statusClass = 'diterima';
                                $statusText = 'Diterima';
                            } elseif($laporan->status == 'Ditolak') {
                                $statusClass = 'revisi';
                                $statusText = 'Revisi';
                            } else {
                                $statusClass = 'pending';
                                $statusText = 'Pending';
                            }
                        @endphp
                        <tr>
                            <td>{{ $laporan->user->nama }}</td>
                            <td>{{ $laporan->user->nomor_induk }}</td>
                            <td>{{ $laporan->pertemuan->jadwal->praktikum->nama_praktikum ?? 'N/A' }}</td>
                            <td>Pertemuan {{ $laporan->pertemuan->pertemuan_ke }}: {{ $laporan->pertemuan->nama_pertemuan }}</td>
                            <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                            <td>
                                <button class="status {{ $statusClass }}" disabled>{{ $statusText }}</button>
                            </td>
                            <td class="actions-btn">
                                <button class="detail" onclick="openReviewModal({{ $laporan->id }})">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Tidak ada data laporan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="pagination">
                {{ $pengumpulanLaporans->appends(request()->query())->links() }}
            </div>
        </div>

    </main>

    <!-- Review Modal -->
    <div class="modal" id="reviewModal" style="display: none;">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2>Review Laporan</h2>
            
            <form method="POST" action="" id="reviewForm">
                @csrf
                <input type="hidden" name="laporan_id" id="laporanId">
                
                <div class="info-box">
                    <p><b>Nama:</b> <span id="mNama">-</span></p>
                    <p><b>NIM:</b> <span id="mNim">-</span></p>
                    <p><b>Praktikum:</b> <span id="mPraktikum">-</span></p>
                    <p><b>Pertemuan:</b> <span id="mPertemuan">-</span></p>
                    <p><b>Tanggal:</b> <span id="mTanggal">-</span></p>
                    <p><b>Keterangan:</b> <span id="mKeterangan">-</span></p>
                    <p><b>File Laporan:</b>
                        <a href="#" id="mFile" target="_blank">Download / Preview</a>
                    </p>
                </div>

                <div class="form-group">
                    <label>Komentar</label>
                    <textarea id="komentar" name="komentar" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Input Nilai</label>
                    <input type="number" id="nilai" name="nilai" min="0" max="100">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="status" name="status">
                        <option value="diterima">Diterima</option>
                        <option value="revisi">Revisi</option>
                        <option value="pending">Pending / Dalam Review</option>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .filter-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-box select, .filter-box input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-box select {
            width: 180px;
        }
        
        .filter-box input {
            width: 200px;
        }
        
        .alert {
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            border: none;
            cursor: default;
        }
        
        .status.diterima {
            background: #d4edda;
            color: #155724;
        }
        
        .status.revisi {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .detail {
            background: #007bff;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .detail:hover {
            background: #0056b3;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }
        
        .close-modal:hover {
            color: #000;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .info-box p {
            margin: 8px 0;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group textarea, .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .btn-save:hover {
            background: #218838;
        }
        
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        
        .pagination nav {
            display: inline-block;
        }
    </style>

    <script>
        const modal = document.getElementById("reviewModal");
        const reviewForm = document.getElementById("reviewForm");
        
        function openReviewModal(laporanId) {
            // Fetch laporan detail using AJAX (this is the only fetch needed for dynamic modal content)
            fetch(`/asisten/laporan/detail/${laporanId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const laporan = data.data;
                    document.getElementById("laporanId").value = laporan.id;
                    document.getElementById("mNama").innerText = laporan.nama;
                    document.getElementById("mNim").innerText = laporan.nim;
                    document.getElementById("mPraktikum").innerText = laporan.praktikum;
                    document.getElementById("mPertemuan").innerText = "Pertemuan " + laporan.pertemuan_ke + ": " + laporan.nama_pertemuan;
                    document.getElementById("mTanggal").innerText = laporan.tanggal;
                    document.getElementById("mKeterangan").innerText = laporan.keterangan || '-';
                    document.getElementById("komentar").value = laporan.komentar || '';
                    document.getElementById("nilai").value = laporan.nilai || '';
                    
                    // Set status dropdown
                    const statusSelect = document.getElementById("status");
                    if (laporan.status === 'Diterima') {
                        statusSelect.value = 'diterima';
                    } else if (laporan.status === 'Ditolak') {
                        statusSelect.value = 'revisi';
                    } else {
                        statusSelect.value = 'pending';
                    }
                    
                    // Set file link
                    const fileLink = document.getElementById("mFile");
                    if (laporan.file_path) {
                        fileLink.href = '/storage/' + laporan.file_path;
                        fileLink.style.display = 'inline';
                    } else {
                        fileLink.style.display = 'none';
                        fileLink.parentElement.innerHTML += ' <span>Tidak ada file</span>';
                    }
                    
                    // Set form action
                    reviewForm.action = `/asisten/laporan/update/${laporan.id}`;
                    
                    modal.style.display = "flex";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail laporan');
            });
        }
        
        function closeModal() {
            modal.style.display = "none";
            reviewForm.reset();
        }
        
        // Close modal when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        
        // Auto-submit filter when search input changes with debounce
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }
    </script>
</body>

</html>