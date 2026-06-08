<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Mahasiswa</title>
    <link rel="stylesheet" href="{{ asset('asisten_css/nilai_asisten.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .filter-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filter-box select,
        .filter-box input,
        .filter-box button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .filter-box select {
            min-width: 150px;
        }

        .filter-box input {
            min-width: 200px;
        }

        .filter-box button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .filter-box button:hover {
            background: #0056b3;
        }

        .reset-btn {
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
        }

        .reset-btn:hover {
            background: #5a6268;
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .grade-A,
        .grade-A- {
            color: #28a745;
            font-weight: bold;
        }

        .grade-B,
        .grade-B-plus,
        .grade-B-minus {
            color: #17a2b8;
            font-weight: bold;
        }

        .grade-C,
        .grade-C-plus {
            color: #ffc107;
            font-weight: bold;
        }

        .grade-D {
            color: #fd7e14;
            font-weight: bold;
        }

        .grade-E {
            color: #dc3545;
            font-weight: bold;
        }

        .nilai-value {
            font-weight: 500;
        }

        .custom-pagination nav svg {
            width: 20px;
            /* Fixes large Tailwind icons if they appear broken */
        }

        .custom-pagination .pagination {
            display: flex;
            list-style: none;
            gap: 10px;
        }

        .custom-pagination a {
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #ddd;
            color: #333;
        }

        .custom-pagination .active {
            background-color: #007bff;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .results-count {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    @include('asisten/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2><i class="fas fa-chart-line"></i> Grade Summary</h2>
            <p style="color: #666; margin-top: 5px;">Rekap nilai mahasiswa - Hanya tampilan (Read Only)</p>
        </div>

        <form method="GET" action="{{ route('rekapNilai') }}" class="filter-box">
            <select name="matkul" onchange="this.form.submit()">
                <option value="">Mata Kuliah</option>
                @foreach ($praktikumNames as $nama)
                    <option value="{{ $nama }}" {{ request('matkul') == $nama ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                @endforeach
            </select>

            <select name="praktikum" onchange="this.form.submit()">
                <option value="">Semua Praktikum</option>
                @foreach ($praktikumNames as $nama)
                    <option value="{{ $nama }}" {{ request('praktikum') == $nama ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                @endforeach
            </select>

            <select name="pertemuan_id" onchange="this.form.submit()">
                <option value="">Semua Pertemuan</option>
                @foreach ($pertemuans as $pertemuan)
                    <option value="{{ $pertemuan->id }}"
                        {{ request('pertemuan_id') == $pertemuan->id ? 'selected' : '' }}>
                        Pertemuan {{ $pertemuan->pertemuan_ke }}: {{ $pertemuan->nama_pertemuan }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" placeholder="Cari nama / NIM..." value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i> Cari</button>

            @if (request()->anyFilled(['matkul', 'praktikum', 'pertemuan_id', 'search']))
                <a href="{{ route('rekapNilai') }}" class="reset-btn"><i class="fas fa-undo"></i> Reset Filter</a>
            @endif
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Praktikum</th>
                        <th>Pertemuan</th>
                        <th>Pretest</th>
                        <th>Laporan</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $index => $nilai)
                        @php
                            $gradeClass = '';
                            $gradeValue = $nilai->grade;
                            if (str_starts_with($gradeValue, 'A')) {
                                $gradeClass = 'grade-A';
                            } elseif (str_starts_with($gradeValue, 'B')) {
                                $gradeClass = 'grade-B';
                            } elseif (str_starts_with($gradeValue, 'C')) {
                                $gradeClass = 'grade-C';
                            } elseif ($gradeValue == 'D') {
                                $gradeClass = 'grade-D';
                            } elseif ($gradeValue == 'E') {
                                $gradeClass = 'grade-E';
                            }
                        @endphp
                        <tr>
                            <td>{{ $nilais->firstItem() + $index }}</td>
                            <td>{{ $nilai->user->nama ?? '-' }}</td>
                            <td>{{ $nilai->user->nomor_induk ?? '-' }}</a></td>
                            <td>{{ $nilai->pertemuan->jadwal->praktikum->nama_praktikum ?? '-' }}</td>
                            <td>Pertemuan {{ $nilai->pertemuan->pertemuan_ke ?? '-' }}:
                                {{ $nilai->pertemuan->nama_pertemuan ?? '-' }}</a></td>
                            <td><span class="nilai-value">{{ $nilai->nilai_pretest ?? 0 }}</span></td>
                            <td><span class="nilai-value">{{ $nilai->nilai_laporan ?? 0 }}</span></td>
                            <td><span class="nilai-value">{{ $nilai->nilai_akhir ?? 0 }}</span></td>
                            <td><span class="{{ $gradeClass }}">{{ $gradeValue }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="fas fa-folder-open"
                                    style="font-size: 48px; color: #ddd; margin-bottom: 10px; display: block;"></i>
                                Tidak ada data nilai
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($nilais->isNotEmpty())
                <div class="pagination-wrapper">
                    <div class="results-count">
                        <i class="fas fa-chart-simple"></i> Showing {{ $nilais->firstItem() }} to
                        {{ $nilais->lastItem() }} of {{ $nilais->total() }} results
                    </div>
                    <div class="" style="display: flex; justify-content: center;">
                        <div class="custom-pagination">
                            {{ $nilais->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <script>
        // Auto-submit filter when search input changes with debounce
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            const form = searchInput.closest('form');
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    form.submit();
                }, 500);
            });
        }
    </script>
</body>

</html>
