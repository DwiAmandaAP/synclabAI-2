<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Praktikum</title>
    <link rel="stylesheet" href="{{ asset('lab_css/kelolaPraktikum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main-content">

        <div class="header">
            <h2> Rekapitulasi Praktikum </h2>

            <div class="actions">
                <select id="filterPertemuan">
                    <option>Semua Pertemuan</option>
                </select>

                <select id="filterKelas">
                    <option>Semua Kelas</option>
                </select>

                <select id="filterPraktikum">
                    <option>Semua Praktikum</option>
                </select>

                <select id="export">
                    <option value="">Export</option>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>

                <input type="text" placeholder="Cari..." id="search">
            </div>
        </div>

        <div class="top-section">

            <div class="summary-box">
                <div class="card summary-1">
                    <p>Pendaftaran</p>
                    <h3>120 / 150</h3>
                </div>

                <div class="card summary-2">
                    <p>Presensi</p>
                    <h3>{{ $persenPresen }}%</h3>
                </div>

                <div class="card summary-3">
                    <p>Nilai</p>
                    <h3>{{ $persenNilai }}%</h3>
                </div>

                <div class="card summary-4">
                    <p>Jadwal</p>
                    <h3>{{ $bentrokCount }} Bentrok!</h3>
                </div>
            </div>

            <div class="chart-box">
                <h3 class="chart-title">Statistik</h3>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // 1. Ambil data dari Laravel ke JS
                        const dataNilai = @json($nilai);
                        let chartInstance = null;

                        function initStats(data) {
                            let tinggi = 0;
                            let rendah = 0;
                            let totalNilai = 0;

                            if (data.length === 0) return;

                            data.forEach(d => {
                                // Gunakan properti nilai_akhir sesuai database/table Anda
                                const nilai = parseFloat(d.nilai_akhir) || 0;
                                totalNilai += nilai;

                                if (nilai >= 75) tinggi++;
                                else rendah++;
                            });

                            const rata = Math.round(totalNilai / data.length);

                            // Update Angka di UI
                            document.getElementById("valTinggi").innerText = tinggi;
                            document.getElementById("valRendah").innerText = rendah;
                            document.getElementById("valRata").innerText = rata;

                            // Render Chart
                            const ctx = document.getElementById("chart").getContext('2d');

                            if (chartInstance) chartInstance.destroy();

                            chartInstance = new Chart(ctx, {
                                type: "doughnut",
                                data: {
                                    labels: ["Tinggi", "Rendah"],
                                    datasets: [{
                                        data: [tinggi, rendah],
                                        backgroundColor: ["#86efac", "#fca5a5"],
                                        borderWidth: 0
                                    }]
                                },
                                options: {
                                    cutout: "65%",
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    }
                                }
                            });
                        }

                        // 2. Panggil fungsi saat halaman siap
                        initStats(dataNilai);
                    });
                </script>

                <div class="chart-content">
                    <canvas id="chart">
                    </canvas>

                    <div class="chart-legend">
                        <div><span class="dot tinggi"></span> Nilai Tinggi</div>
                        <div><span class="dot rendah"></span> Nilai Rendah</div>
                        <div><span class="dot rata"></span> Rata-rata</div>

                        <div class="legend-value">
                            <p>Tinggi: <b id="valTinggi">0</b></p>
                            <p>Rendah: <b id="valRendah">0</b></p>
                            <p>Rata-rata: <b id="valRata">0</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Pretest</th>
                        <th>Laporan</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($nilai as $n)
                        <tr>
                            <td>{{ $n->user->nama }}</td>
                            <td>{{ $n->user->nomor_induk }}</td>
                            <td>{{ $n->nilai_pretest }}</td>
                            <td>{{ $n->nilai_laporan }}</td>
                            <td>{{ $n->nilai_akhir }}</td>
                            <td>
                                @if ($n->nilai_akhir >= 85)
                                    A
                                @elseif($n->nilai_akhir >= 75)
                                    B
                                @elseif($n->nilai_akhir >= 65)
                                    C
                                @else
                                    D
                                @endif
                            </td>
                            <td>
                                <span
                                    class="status-laporan {{ $n->nilai_akhir >= 75 ? 'lulus' : ($n->nilai_akhir >= 60 ? 'revisi' : 'tidak-lulus') }}">
                                    {{ $n->nilai_akhir >= 75 ? 'Lulus' : ($n->nilai_akhir >= 60 ? 'Revisi' : 'Tidak Lulus') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <td colspan="7" style="align-content: center">
                            Tidak Ada Data Ditemukan!
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js">
        < /> <
        script >
            function renderStats(nilai) {
                let tinggi = 0,
                    rendah = 0,
                    totalNilai = 0;

                data.forEach(d => {
                    const nilai = hitungNilai(d);
                    totalNilai += nilai;

                    if (nilai >= 75) tinggi++;
                    else rendah++;
                });

                const rata = Math.round(totalNilai / data.length);

                document.getElementById("valTinggi").innerText = tinggi;
                document.getElementById("valRendah").innerText = rendah;
                document.getElementById("valRata").innerText = rata;

                const ctx = document.getElementById("chart");
                if (!ctx) return;

                if (chartInstance) chartInstance.destroy();

                chartInstance = new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        labels: ["Tinggi", "Rendah"],
                        datasets: [{
                            data: [tinggi, rendah],
                            backgroundColor: [
                                "#86efac",
                                "#fca5a5"
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: "65%",
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
    </script>
    <script src="{{ asset('lab_js/laporan_lab.js') }}"></script>
</body>

</html>
