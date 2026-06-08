<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Praktikum Asisten</title>
    <link rel="stylesheet" href="{{ asset('asisten_css/praktikum_asisten.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
      <style>
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            margin-top: 8px;
        }
        
        .status-open {
            background: #dbeafe;
            color: #1d4ed8;
        }
        
        .status-full {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-done {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-upcoming {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .btn-presensi:hover, .btn-nilai:hover, .btn-attendance:hover {
            opacity: 0.8;
        }
        
        .btn-attendance {
            background: #ede9fe;
            color: #6d28d9;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .card-actions button {
            transition: all 0.2s ease;
        }
        
        .empty-state, .empty-schedule {
            background: #f9fafb;
            border-radius: 12px;
        }
        
        .filter-box {
            margin-bottom: 20px;
        }
        
        .filter-box input, .filter-box select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        
        .filter-box input {
            width: 200px;
            margin-right: 10px;
        }
        
        .filter-box select {
            width: 180px;
        }
        
        @media (max-width: 768px) {
            .filter-box input, .filter-box select {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    @include('asisten/partials/sidebar')
    
    <main class="main">
        <h2 class="title">Practicum & Schedule Management</h2>
        
        @if(session('error'))
            <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="content">
            <div class="left">
                <h3>List Practicum</h3>

                <div class="filter-box">
                    <input type="text" id="search" placeholder="Cari praktikum...">
                    <select id="filter">
                        <option value="all">Semua</option>
                        @foreach($praktikumNames as $name)
                            <option value="{{ Str::slug($name) }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid" id="praktikumGrid">
                    @forelse($praktikums as $praktikum)
                        <div class="card" data-kategori="{{ Str::slug($praktikum->nama_praktikum) }}" data-nama="{{ strtolower($praktikum->nama_praktikum) }}">
                            <div class="thumb icon">
                                @php
                                    $icons = [
                                        'Pemrograman' => 'fa-code',
                                        'Jaringan' => 'fa-network-wired',
                                        'Rekayasa' => 'fa-code-branch',
                                        'Pengolahan' => 'fa-image',
                                        'default' => 'fa-laptop-code'
                                    ];
                                    $iconClass = 'fa-laptop-code';
                                    foreach($icons as $key => $icon) {
                                        if(str_contains($praktikum->nama_praktikum, $key)) {
                                            $iconClass = $icon;
                                            break;
                                        }
                                    }
                                @endphp
                                <i class="fas {{ $iconClass }} fa-3x"></i>
                            </div>
                            <div class="info">
                                <h4>{{ $praktikum->nama_praktikum }}</h4>
                                <p class="praktikum">Praktikum : {{ $praktikum->nama_pertemuan }}</p>
                                <p>Kode : {{ $praktikum->kode_praktikum }}</p>
                                <p>Jumlah : {{ $praktikum->mahasiswa_count }} Mahasiswa</p>
                                <p>Hari : {{ $praktikum->jadwal_hari }}</p>
                                <p>Jam : {{ $praktikum->jadwal_jam_mulai }} - {{ $praktikum->jadwal_jam_selesai }}</p>
                                <p>Ruang : {{ $praktikum->laboratorium_nama }}</p>
                                <p>Lokasi : {{ $praktikum->laboratorium_lokasi }}</p>
                                <p>Angkatan : {{ $praktikum->angkatan }}</p>
                                <p>Semester : {{ $praktikum->semester }}</p>
                                
                                <div class="card-actions" style="margin-top: 12px;">
                                    <form action="{{ route('konfirmasiPresensi') }}" method="GET" style="display: inline;">
                                        <input type="hidden" name="praktikum_id" value="{{ $praktikum->id }}">
                                        <button type="submit" class="btn-presensi" style="background: #dbeafe; color: #1d4ed8; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">Input Presensi</button>
                                    </form>
                                    <form action="{{ route('addNilai') }}" method="GET" style="display: inline;">
                                        <input type="hidden" name="praktikum_id" value="{{ $praktikum->id }}">
                                        <button type="submit" class="btn-nilai" style="background: #dcfce7; color: #166534; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">Input Nilai</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                            <i class="fas fa-folder-open fa-3x" style="color: #999; margin-bottom: 15px;"></i>
                            <h4>Belum Ada Praktikum</h4>
                            <p>Anda belum ditugaskan sebagai asisten untuk praktikum apapun.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="right">
                <h3>📅 Today Schedule</h3><br>
                <p style="color: #666; font-size: 13px; margin-bottom: 15px;">{{ Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>

                <div class="timeline">
                    @forelse($todayJadwals as $jadwal)
                        <div class="timeline-item">
                            <div class="time" style="margin-top: -25px;">{{ $jadwal->jam_mulai}}</div>
                            <div class="schedule-card">
                                <h4>{{ $jadwal->praktikum->nama_praktikum }}</h4>
                                <p class="praktikum">
                                    <i class="fas {{ str_contains($jadwal->praktikum->nama_praktikum, 'Jaringan') ? 'fa-network-wired' : (str_contains($jadwal->praktikum->nama_praktikum, 'Pemrograman') ? 'fa-code' : 'fa-laptop-code') }}"></i>
                                    Praktikum : {{ $jadwal->pertemuan->first()->nama_pertemuan ?? $jadwal->praktikum->nama_praktikum }}
                                </p>
                                <p><i class="fas fa-clock"></i> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>
                                <p><i class="fas fa-map-marker-alt"></i> {{ $jadwal->laboratorium->nama_laboratorium ?? 'Lab N/A' }} • {{ $jadwal->mahasiswa_count }} Mahasiswa</p>
                                <p><i class="fas fa-building"></i> {{ $jadwal->laboratorium->lokasi ?? '-' }}</p>
                                
                                @php
                                    $statusClass = match($jadwal->status) {
                                        'Dibuka' => 'status-open',
                                        'Penuh' => 'status-full',
                                        'Selesai' => 'status-done',
                                        default => 'status-upcoming'
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $jadwal->status }}</span>
                                
                                @if($jadwal->pertemuan)
                                    <div class="action-buttons" style="margin-top: 12px;">
                                        <form action="{{ route('detailPresensi') }}" method="GET" style="display: inline;">
                                            <input type="hidden" name="pertemuan_id" value="{{ $jadwal->pertemuan->first() ?? '-'}}">
                                            <input type="hidden" name="praktikum_id" value="{{ $jadwal->id_praktikum }}">
                                            <button type="submit" class="btn-attendance">Presensi</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-schedule" style="text-align: center; padding: 30px;">
                            <i class="fas fa-calendar-day fa-2x" style="color: #ccc; margin-bottom: 10px;"></i>
                            <p style="color: #666;">Tidak ada jadwal hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <script>
        // Search and filter functionality
        const searchInput = document.getElementById('search');
        const filterSelect = document.getElementById('filter');
        const cards = document.querySelectorAll('.card');

        function filterCards() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const filterValue = filterSelect ? filterSelect.value : 'all';

            cards.forEach(card => {
                const cardName = card.dataset.nama || '';
                const cardKategori = card.dataset.kategori || '';
                
                const matchesSearch = cardName.includes(searchTerm);
                const matchesFilter = filterValue === 'all' || cardKategori === filterValue;
                
                card.style.display = (matchesSearch && matchesFilter) ? 'flex' : 'none';
            });
        }

        if (searchInput) {
            searchInput.addEventListener('keyup', filterCards);
        }
        
        if (filterSelect) {
            filterSelect.addEventListener('change', filterCards);
        }
    </script>
</body>

</html>