<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pertemuan</title>
    <link rel="stylesheet" href="{{ asset('lab_css/kelolaPraktikum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .detail-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
    </style>
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main-content">

        <div class="header">
            <h2>Manajemen Pertemuan</h2>
            <div class="actions">
                <button class="btn add" onclick="openAddModal()">+ Tambah Pertemuan</button>
                <form method="GET" action="{{ route('masterPertemuan') }}" style="display: inline; width: 100%;">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                        onchange="this.form.submit()" class="search-box">
                </form>
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
                    style="background: none; border: none; color: #721c24; cursor: pointer; font-size: 20px; padding: 0; margin-left: 10px;">×</button>
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
                    style="background: none; border: none; color: #155724; cursor: pointer; font-size: 20px; padding: 0; margin-left: 10px;">×</button>
            </div>
        @endif

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Pertemuan</th>
                        <th>Pertemuan Ke-</th>
                        <th>Deskripsi</th>
                        <th>Jadwal</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pertemuans as $pertemuan)
                        <tr>
                            <td>{{ $pertemuan->nama_pertemuan }}</td>
                            <td>{{ $pertemuan->pertemuan_ke }}</td>
                            <td>{{ Str::limit($pertemuan->deskripsi_pertemuan, 50) }}</td>
                            <td>
                                @if ($pertemuan->jadwal)
                                    {{ $pertemuan->jadwal->hari ?? '-' }} / {{ $pertemuan->jadwal->jam_mulai ?? '-' }}
                                @else
                                    <span class="badge badge-secondary">Tidak Ada</span>
                                @endif
                            </td>
                            <td>
                                @if ($pertemuan->modul)
                                    {{ $pertemuan->modul->judul_modul ?? '-' }}
                                @else
                                    <span class="badge badge-secondary">Tidak Ada</span>
                                @endif
                            </td>
                            <td>
                                <button class="edit" onclick="openEditModal({{ $pertemuan->id }})">Edit</button>
                                <form method="POST" action="{{ route('deletePertemuan', $pertemuan->id) }}"
                                    style="display:inline;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete">Hapus</button>
                                </form>
                                <button class="detail" onclick="openDetailModal({{ $pertemuan->id }})">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-inbox"></i> Tidak Ada Pertemuan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $pertemuans->appends(request()->query())->links() }}
            </div>
        </div>

    </main>

    <div id="pertemuanDataStore" style="display: none;">
        @foreach ($pertemuans as $pertemuan)
            <div class="pertemuan-data" data-id="{{ $pertemuan->id }}"
                data-nama_pertemuan="{{ $pertemuan->nama_pertemuan }}"
                data-pertemuan_ke="{{ $pertemuan->pertemuan_ke }}"
                data-deskripsi_pertemuan="{{ $pertemuan->deskripsi_pertemuan }}"
                data-id_jadwal="{{ $pertemuan->id_jadwal }}" data-id_modul="{{ $pertemuan->id_modul }}"
                data-jadwal='@json($pertemuan->jadwal)' data-modul='@json($pertemuan->modul)'
                data-created_at="{{ $pertemuan->created_at }}" data-updated_at="{{ $pertemuan->updated_at }}">
            </div>
        @endforeach
    </div>

    <div class="modal" id="addModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Pertemuan Baru</h3>
                <span class="close" onclick="closeAddModal()">&times;</span>
            </div>
            <form method="POST" action="{{ route('addPertemuan') }}">
                @csrf
                <div class="form-group">
                    <label for="nama_pertemuan">Nama Pertemuan <span class="required">*</span></label>
                    <input type="text" id="nama_pertemuan" name="nama_pertemuan"
                        placeholder="e.g., Pengenalan Variabel" required>
                </div>

                <div class="form-group">
                    <label for="pertemuan_ke">Pertemuan Ke- <span class="required">*</span></label>
                    <input type="number" id="pertemuan_ke" name="pertemuan_ke" placeholder="e.g., 1" required
                        min="1">
                </div>

                <div class="form-group">
                    <label for="deskripsi_pertemuan">Deskripsi</label>
                    <textarea id="deskripsi_pertemuan" name="deskripsi_pertemuan" placeholder="Deskripsi pertemuan..." rows="4"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="id_jadwal">Jadwal</label>
                        <select id="id_jadwal" name="id_jadwal">
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}">
                                    {{ $jadwal->hari }} - {{ $jadwal->jam_mulai }}
                                    ({{ $jadwal->praktikum->nama_praktikum ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_modul">Modul (Opsional)</label>
                        <select id="id_modul" name="id_modul">
                            <option value="">-- Tidak Ada Modul --</option>
                            @foreach ($moduls as $modul)
                                <option value="{{ $modul->id }}">{{ $modul->judul_modul }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeAddModal()">Batalkan</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Pertemuan</h3>
                <span class="close"
                    onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" id="editForm" action="{{empty($pertemuan) ? '' : route("updatePertemuan", $pertemuan->id)}}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_nama_pertemuan">Nama Pertemuan <span class="required">*</span></label>
                    <input type="text" id="edit_nama_pertemuan" name="nama_pertemuan" required>
                </div>

                <div class="form-group">
                    <label for="edit_pertemuan_ke">Pertemuan Ke- <span class="required">*</span></label>
                    <input type="number" id="edit_pertemuan_ke" name="pertemuan_ke" required min="1">
                </div>

                <div class="form-group">
                    <label for="edit_deskripsi_pertemuan">Deskripsi</label>
                    <textarea id="edit_deskripsi_pertemuan" name="deskripsi_pertemuan" rows="4"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_id_jadwal">Jadwal (Opsional)</label>
                        <select id="edit_id_jadwal" name="id_jadwal">
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}">
                                    {{ $jadwal->hari }} - {{ $jadwal->jam_mulai }}
                                    ({{ $jadwal->praktikum->nama_praktikum ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_id_modul">Modul (Opsional)</label>
                        <select id="edit_id_modul" name="id_modul">
                            <option value="">-- Tidak Ada Modul --</option>
                            @foreach ($moduls as $modul)
                                <option value="{{ $modul->id }}">{{ $modul->judul_modul }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary"
                        onclick="document.getElementById('editModal').style.display='none'">Batalkan</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="detailModal">
        <div class="modal-content large">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle"></i> Detail Pertemuan</h3>
                <span class="close"
                    onclick="document.getElementById('detailModal').style.display='none'">&times;</span>
            </div>
            <div id="detailContent"></div>
            <div class="detail-actions">
                <button onclick="document.getElementById('detailModal').style.display='none'"
                    class="btn-secondary">Tutup</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('lab_js/kelolaPertemuan_lab.js') }}"></script>
</body>

</html>
