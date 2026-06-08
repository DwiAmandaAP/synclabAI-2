<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Input Nilai Mahasiswa</title>
    <link rel="stylesheet" href="{{ asset('asisten_css/nilai_asisten.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .nilai-input {
            width: 70px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .nilai-input:focus {
            border-color: #007bff;
            outline: none;
        }

        .nilai-otomatis {
            font-weight: bold;
            color: #28a745;
        }

        .actions-btn {
            white-space: nowrap;
        }

        .btn-save,
        .btn-delete {
            padding: 5px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-save {
            background: #28a745;
            color: white;
        }

        .btn-save:hover {
            background: #218838;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            margin-right: 5px;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-save-all {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .btn-save-all:hover {
            background: #0056b3;
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

        F .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            z-index: 9999;
        }
    </style>
</head>

<body>
    @include('asisten/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2>Input Nilai Mahasiswa</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="GET" action="{{ route('addNilai') }}" class="filter-box">
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
            <button type="submit">Cari</button>

            @if (request()->anyFilled(['matkul', 'praktikum', 'kelas', 'pertemuan_id', 'search']))
                <a href="{{ route('addNilai') }}" class="reset-btn">Reset Filter</a>
            @endif
        </form>

        <div class="table-container">
            <form method="POST" action="{{ route('bulkUpdateNilai') }}" id="bulkForm">
                @csrf
                <button type="submit" class="btn-save-all" id="saveAllBtn">
                    <i class="fas fa-save"></i> Simpan Semua Perubahan
                </button>

                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Praktikum</th>
                            <th>Pertemuan</th>
                            <th>Nilai Pretest</th>
                            <th>Nilai Laporan</th>
                            <th>Nilai Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nilais as $nilai)
                            <tr data-id="{{ $nilai->id }}">
                                <td>{{ $nilai->user->nama }}</td>
                                <td>{{ $nilai->user->nomor_induk }}</td>
                                <td>{{ $nilai->pertemuan->jadwal->praktikum->nama_praktikum ?? 'N/A' }}</td>
                                <td>Pertemuan {{ $nilai->pertemuan->pertemuan_ke ?? '-' }}</td>
                                <td>
                                    <input type="number" class="nilai-input pretest-input"
                                        data-id="{{ $nilai->id }}" value="{{ $nilai->nilai_pretest ?? 0 }}"
                                        min="0" max="100"
                                        name="nilai_updates[{{ $loop->index }}][nilai_pretest]">
                                </td>
                                <td>
                                    <input type="number" class="nilai-input laporan-input"
                                        data-id="{{ $nilai->id }}" value="{{ $nilai->nilai_laporan ?? 0 }}"
                                        min="0" max="100"
                                        name="nilai_updates[{{ $loop->index }}][nilai_laporan]">
                                </td>
                                <td class="nilai-otomatis" id="total-{{ $nilai->id }}">
                                    {{ $nilai->nilai_total ?? 0 }}
                                </td>
                                <td class="actions-btn">
                                    <input type="hidden" name="nilai_updates[{{ $loop->index }}][id]"
                                        value="{{ $nilai->id }}">
                                    <button type="button" class="btn-delete"
                                        onclick="deleteNilai({{ $nilai->id }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <button type="button" class="btn-save"
                                        onclick="saveSingleNilai({{ $nilai->id }})">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">Tidak ada data nilai</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>

            <div class="" style="display: flex; justify-content: center;">
                <div class="custom-pagination">
                    {{ $nilais->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </main>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Auto-calculate total when pretest or laporan changes
        document.querySelectorAll('.pretest-input, .laporan-input').forEach(input => {
            input.addEventListener('input', function() {
                const row = this.closest('tr');
                const id = row.dataset.id;
                const pretest = parseInt(row.querySelector('.pretest-input').value) || 0;
                const laporan = parseInt(row.querySelector('.laporan-input').value) || 0;
                const total = Math.round((pretest + laporan) / 2);

                const totalSpan = document.getElementById(`total-${id}`);
                if (totalSpan) {
                    totalSpan.textContent = total;
                    // Add visual indicator that total changed
                    totalSpan.style.color = '#ffc107';
                    setTimeout(() => {
                        totalSpan.style.color = '#28a745';
                    }, 500);
                }
            });
        });

        // Save single nilai via AJAX
        function saveSingleNilai(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const pretest = row.querySelector('.pretest-input').value;
            const laporan = row.querySelector('.laporan-input').value;

            const formData = new FormData();
            formData.append('nilai_pretest', pretest);
            formData.append('nilai_laporan', laporan);
            formData.append('_token', csrfToken);

            fetch(`/asisten/nilai/update/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message);
                        const totalSpan = document.getElementById(`total-${id}`);
                        if (data.data.nilai_total) {
                            totalSpan.textContent = data.data.nilai_total;
                        }
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    showNotification('error', 'Terjadi kesalahan');
                    console.error('Error:', error);
                });
        }

        // Delete nilai
        function deleteNilai(id) {
            if (confirm('Apakah Anda yakin ingin menghapus nilai ini?')) {
                fetch(`/asisten/nilai/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showNotification('error', data.message);
                        }
                    })
                    .catch(error => {
                        showNotification('error', 'Terjadi kesalahan');
                        console.error('Error:', error);
                    });
            }
        }

        // Show notification
        function showNotification(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = message;
            alertDiv.style.position = 'fixed';
            alertDiv.style.top = '20px';
            alertDiv.style.right = '20px';
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '250px';

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

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

        // Bulk form submission confirmation
        const bulkForm = document.getElementById('bulkForm');
        if (bulkForm) {
            bulkForm.addEventListener('submit', function(e) {
                const confirmSave = confirm('Simpan semua perubahan nilai?');
                if (!confirmSave) {
                    e.preventDefault();
                }
            });
        }
    </script>
</body>

</html>
