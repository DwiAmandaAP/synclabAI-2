<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Asisten - {{ $user->nama }}</title>
    <link rel="stylesheet" href="{{ asset('asisten_css/dashboard_asisten.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('Asisten/partials/sidebar')

    <main class="main">
        <section class="hero">
            <h2 class="page-title">Dashboard</h2>
            <div class="hero-image"></div>
        </section>

        <section class="top-section">
            <div class="notifikasi">
                <h3>Selamat {{ $greeting }}, {{ $user->nama }} 👋</h3>
                @forelse($notifications as $notif)
                    <div class="notif {{ $notif['type'] }}">
                        <i class="{{ $notif['icon'] }}"></i>
                        {{ $notif['message'] }}
                    </div>
                @empty
                    <div class="notif blue">
                        <i class="fas fa-info-circle"></i>
                        Dashboard siap digunakan!
                    </div>
                @endforelse
            </div>

            <div class="calendar">
                <div class="calendar-header">
                    <button type="button" onclick="prevMonth()">❮</button>
                    <h4 id="monthYear"></h4>
                    <button type="button" onclick="nextMonth()">❯</button>
                </div>

                <div class="calendar-days">
                    <span>Mo</span><span>Tu</span><span>We</span>
                    <span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
                </div>

                <div class="calendar-dates" id="calendarDates"></div>
            </div>
        </section>

        <section class="practicum">
            <h4>My Practicum</h4>
            <div class="practicum-list">
                @forelse(array_slice($processedPraktikums, 0, 4) as $praktikum)
                    <div class="prac-card">
                        <h5>📘 {{ Str::limit($praktikum['nama_praktikum'], 25) }}</h5>
                        <p>{{ $praktikum['hari'] }}, {{ $praktikum['jam_mulai'] }} - {{ $praktikum['jam_selesai'] }}</p>
                        <p>{{ number_format($praktikum['total_mahasiswa']) }} Mahasiswa</p>

                        <div class="progress">
                            <span>Presensi</span>
                            <div class="bar">
                                <div style="width:{{ $praktikum['presensi_percent'] }}%"></div>
                            </div>
                            <small>{{ $praktikum['presensi_percent'] }}% ({{ $praktikum['presensi_hadir'] }}/{{ $praktikum['total_mahasiswa'] }})</small>
                        </div>

                        <div class="progress">
                            <span>Nilai</span>
                            <div class="bar">
                                <div style="width:{{ $praktikum['nilai_percent'] }}%"></div>
                            </div>
                            <small>{{ $praktikum['nilai_percent'] }}% ({{ $praktikum['nilai_lengkap'] }}/{{ $praktikum['total_mahasiswa'] }})</small>
                        </div>

                        @if($praktikum['pending_laporan'] > 0)
                            <p class="warning">⚠ {{ $praktikum['pending_laporan'] }} laporan belum direview</p>
                        @endif
                        <p class="status {{ $praktikum['has_active_jadwal'] ? 'active' : 'pending' }}">
                            {{ $praktikum['has_active_jadwal'] ? '🟢 Aktif' : '⏳ Akan datang' }}
                        </p>

                        <div class="btn-group">
                            <form action="{{ route('konfirmasiPresensi') }}" method="GET" style="display: inline;">
                                <input type="hidden" name="praktikum_id" value="{{ $praktikum['id'] }}">
                                <button type="submit" class="presensi-btn">Presensi</button>
                            </form>
                            <form action="{{ route('nilai') }}" method="GET" style="display: inline;">
                                <input type="hidden" name="praktikum_id" value="{{ $praktikum['id'] }}">
                                <button type="submit" class="nilai-btn">Nilai</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="prac-card empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <h5>Belum ada praktikum ditugaskan</h5>
                        <p>Praktikum akan muncul di sini setelah alokasi</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="card">
            <h3>📅 Today Schedule</h3>

            <div class="summary">
                <p><strong>Hari Ini:</strong> {{ $stats['total_praktikum'] }} praktikum |
                    {{ $stats['ongoing_jadwal'] }} berlangsung | {{ $stats['upcoming_jadwal'] }} akan datang</p>

                <div class="filter-jadwal">
                    <form action="{{ route('asistensi') }}" method="GET" style="display: inline;">
                        <button type="submit">Semua Jadwal</button>
                    </form>
                </div>
            </div>

            <div class="jadwal-hari">
                <h4>{{ Carbon\Carbon::now()->locale('id')->translatedFormat('l') }}</h4>

                @forelse($processedJadwals as $jadwal)
                    <div class="timeline-item {{ $loop->last ? 'last' : '' }}">
                        <div class="time">{{ $jadwal['jam_mulai'] }}</div>

                        <div class="line"></div>

                        <div class="content">
                            <h5>📘 {{ $jadwal['praktikum']->nama_praktikum }}</h5>
                            <p>🕒 {{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }}</p>
                            <p>📍 {{ $jadwal['laboratorium']->nama_laboratorium ?? 'Lab N/A' }} • {{ $jadwal['total_mahasiswa'] }} Mahasiswa</p>

                            @php
                                $statusClass = match($jadwal['status']) {
                                    'Dibuka' => 'active',
                                    'Penuh' => 'warning',
                                    'Selesai' => 'finished',
                                    default => 'upcoming',
                                };
                                $statusIcon = match($jadwal['status']) {
                                    'Dibuka' => '🟢',
                                    'Penuh' => '🟡',
                                    'Selesai' => '🔵',
                                    default => '⏳',
                                };
                            @endphp
                            <span class="status jadwal {{ $statusClass }}">{{ $statusIcon }} {{ ucfirst($jadwal['status']) }}</span>

                            <p>Presensi: {{ $jadwal['presensi_hadir'] }}/{{ $jadwal['total_presensi'] }} ({{ $jadwal['presensi_percent'] }}%)</p>

                            @if($jadwal['pending_laporan'] > 0)
                                <p class="warning-text">⚠ {{ $jadwal['pending_laporan'] }} laporan belum dicek</p>
                            @endif

                            <div class="actions">
                                @if($jadwal['pertemuan'])
                                    <form action="{{ route('detailPresensi') }}" method="GET" style="display: inline;">
                                        <input type="hidden" name="pertemuan_id" value="{{ $jadwal['pertemuan']->first()->id }}">
                                        <input type="hidden" name="praktikum_id" value="{{ $jadwal['praktikum']->id }}">
                                        <button type="submit" class="btn primary">Input Presensi</button>
                                    </form>
                                    @if($jadwal['pending_laporan'] > 0)
                                        <form action="{{ route('laporan.index') }}" method="GET" style="display: inline;">
                                            <input type="hidden" name="pertemuan_id" value="{{ $jadwal['pertemuan']->first()->id }}">
                                            <button type="submit" class="btn pink">Review Laporan</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="timeline-item">
                        <div class="content empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>Tidak ada jadwal hari ini</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="bottom-section">
            <div class="attendance">
                <h4>Attendance</h4>
                <div class="attendance-container">
                    <div class="attendance-list">
                        @foreach(array_slice($processedPraktikums, 0, 4) as $index => $praktikum)
                            <div class="pill {{ $loop->first ? 'active' : '' }}" onclick="showDetail({{ $index }})">
                                {{ $praktikum['nama_praktikum'] }}
                            </div>
                        @endforeach
                    </div>

                    <div class="attendance-detail" id="detailBox">
                        @if(!empty($processedPraktikums))
                            @php $firstPraktikum = $processedPraktikums[0] ?? null;@endphp
                            <div class="detail-title">{{ $firstPraktikum['nama_praktikum'] }}</div>
                            <div class="detail-item"><b>Practicum:</b> {{ $firstPraktikum['kode_praktikum'] }}</div>
                            <div class="detail-item"><b>Ruangan:</b> {{ $firstPraktikum['laboratorium_nama'] }}</div>
                            <div class="detail-item"><b>Hari/Tgl:</b> {{ $firstPraktikum['hari'] }}</div>
                            <div class="detail-item"><b>Jam:</b> {{ $firstPraktikum['jam_mulai'] }} - {{ $firstPraktikum['jam_selesai'] }}</div>
                            <div class="detail-item"><b>Jumlah:</b> {{ $firstPraktikum['total_mahasiswa'] }} mhs</div>
                            <form action="{{ route('konfirmasiPresensi') }}" method="GET">
                                <input type="hidden" name="praktikum_id" value="{{ $firstPraktikum['id'] }}">
                                <button type="submit" class="input-btn">Input</button>
                            </form>
                        @else
                            <div class="detail-title">Belum Ada Praktikum</div>
                            <div class="detail-item">Tidak ada data praktikum yang tersedia</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        let currentDate = new Date();
        const praktikums = @json(array_slice($processedPraktikums, 0, 4));
        const colors = ["pink", "blue", "green", "yellow"];

        function renderCalendar() {
            const monthYear = document.getElementById("monthYear");
            const calendarDates = document.getElementById("calendarDates");

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const firstDay = new Date(year, month, 1).getDay();
            const lastDate = new Date(year, month + 1, 0).getDate();

            const monthNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            monthYear.innerText = `${monthNames[month]} ${year}`;
            calendarDates.innerHTML = "";

            let start = firstDay === 0 ? 6 : firstDay - 1;

            for (let i = 0; i < start; i++) {
                calendarDates.innerHTML += `<div></div>`;
            }

            const today = new Date();

            for (let i = 1; i <= lastDate; i++) {
                let isToday = i === today.getDate() && month === today.getMonth() && year === today.getFullYear();

                calendarDates.innerHTML += `<div class="${isToday ? "today" : ""}">${i}</div>`;
            }
        }

        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        function showDetail(index) {
            const pills = document.querySelectorAll(".pill");
            pills.forEach(p => p.classList.remove("active"));
            if (pills[index]) pills[index].classList.add("active");

            const praktikum = praktikums[index];
            if (!praktikum) return;

            const box = document.getElementById("detailBox");
            box.className = "attendance-detail " + (colors[index] || "pink");

            box.innerHTML = `
                <div class="detail-title">${escapeHtml(praktikum.nama_praktikum)}</div>
                <div class="detail-item"><b>Practicum:</b> ${escapeHtml(praktikum.kode_praktikum)}</div>
                <div class="detail-item"><b>Ruangan:</b> ${escapeHtml(praktikum.laboratorium_nama)}</div>
                <div class="detail-item"><b>Hari/Tgl:</b> ${escapeHtml(praktikum.hari)}</div>
                <div class="detail-item"><b>Jam:</b> ${escapeHtml(praktikum.jam_mulai)} - ${escapeHtml(praktikum.jam_selesai)}</div>
                <div class="detail-item"><b>Jumlah:</b> ${praktikum.total_mahasiswa} mhs</div>
                <form action="{{ route('konfirmasiPresensi') }}" method="GET">
                    <input type="hidden" name="praktikum_id" value="${praktikum.id}">
                    <button type="submit" class="input-btn">Input</button>
                </form>
            `;
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

        document.addEventListener("DOMContentLoaded", function() {
            renderCalendar();
            if (praktikums.length > 0) {
                showDetail(0);
            }
        });
    </script>
</body>

</html>