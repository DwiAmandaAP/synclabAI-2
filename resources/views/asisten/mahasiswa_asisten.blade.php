<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Mahasiswa - Praktikum</title>
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .filter-box select,
        .filter-box input {
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
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .filter-box button:hover {
            background: #0056b3;
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

        .table-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination nav {
            display: inline-block;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    @include('asisten/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2>Daftar Mahasiswa</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="GET" action="{{ route('seeMahasiswa') }}" class="filter-box">
            <select name="matkul" id="filterMatkul" onchange="this.form.submit()">
                <option value="">Mata Kuliah</option>
                @foreach($praktikumNames as $namaPraktikum)
                    <option value="{{ $namaPraktikum }}" {{ request('matkul') == $namaPraktikum ? 'selected' : '' }}>
                        {{ $namaPraktikum }}
                    </option>
                @endforeach
            </select>

            <select name="praktikum" id="filterPraktikum" onchange="this.form.submit()">
                <option value="">Semua Praktikum</option>
                @foreach($praktikumNames as $namaPraktikum)
                    <option value="{{ $namaPraktikum }}" {{ request('praktikum') == $namaPraktikum ? 'selected' : '' }}>
                        {{ $namaPraktikum }}
                    </option>
                @endforeach
            </select>

            <select name="kelas" id="filterKelas" onchange="this.form.submit()">
                <option value="">Semua Angkatan</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                        Angkatan {{ $kelas }}
                    </option>
                @endforeach
            </select>

            <select name="pertemuan" id="filterPertemuan" onchange="this.form.submit()">
                <option value="">Semua Pertemuan</option>
                @foreach($pertemuanList as $pertemuan)
                    <option value="{{ $pertemuan }}" {{ request('pertemuan') == $pertemuan ? 'selected' : '' }}>
                        Pertemuan {{ $pertemuan }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" id="searchInput" placeholder="Cari nama / NIM..." value="{{ request('search') }}">
            <button type="submit">Cari</button>
            
            @if(request()->anyFilled(['matkul', 'praktikum', 'kelas', 'pertemuan', 'search']))
                <a href="{{ route('seeMahasiswa') }}" class="reset-btn">Reset Filter</a>
            @endif
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Praktikum</th>
                        <th>Angkatan</th>
                        <th>Pertemuan Terakhir</th>
                        <th>Tanggal Praktikum</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    @forelse($mahasiswas as $mahasiswa)
                        <tr>
                            <td>{{ $mahasiswa->nama }}</td>
                            <td>{{ $mahasiswa->nomor_induk }}</td>
                            <td>{{ $mahasiswa->praktikum_name }}</td>
                            <td>Angkatan {{ $mahasiswa->angkatan }}</td>
                            <td>Pertemuan {{ $mahasiswa->pertemuan_ke }}</td>
                            <td>{{ $mahasiswa->tanggal_praktikum }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">Tidak ada data mahasiswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="pagination">
                {{ $mahasiswas->appends(request()->query())->links() }}
            </div>
        </div>
    </main>

    <style>
        .reset-btn {
            padding: 8px 15px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .reset-btn:hover {
            background: #5a6268;
        }
    </style>

    <script>
        // Auto-submit filter when search input changes with debounce
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
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