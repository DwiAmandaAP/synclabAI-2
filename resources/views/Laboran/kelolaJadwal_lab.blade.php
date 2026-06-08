<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management</title>
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

        .badge-tersedia {
            background: #d4edda;
            color: #155724;
        }

        .badge-terpakai {
            background: #f8d7da;
            color: #721c24;
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

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .search-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main-content">

        <div class="header">
            <h2>Schedule Management</h2>
            <div class="actions">
                <button class="btn add" onclick="openAddModal()">+ Add Jadwal</button>

                <form method="GET" action="{{ route('masterJadwal') }}"
                    style="display: inline-flex; gap: 10px; flex-wrap: wrap;">
                    <select id="filterPraktikum" name="praktikum_id" onchange="this.form.submit()">
                        <option value="">Semua Praktikum</option>
                        @foreach ($praktikums as $praktikum)
                            <option value="{{ $praktikum->id }}"
                                {{ request('praktikum_id') == $praktikum->id ? 'selected' : '' }}>
                                {{ $praktikum->nama_praktikum }}
                            </option>
                        @endforeach
                    </select>

                    <select id="filterHari" name="hari" onchange="this.form.submit()">
                        <option value="">Semua Hari</option>
                        <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ request('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                    </select>

                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                        onchange="this.form.submit()">
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
                        <th>Praktikum</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>laboratorium</th>
                        <th>Dosen Pengampu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $jadwal)
                        <tr>
                            <td>{{ $jadwal->praktikum->nama_praktikum ?? '-' }}</td>
                            <td>{{ $jadwal->hari ?? '-' }}</td>
                            <td>{{ $jadwal->jam_mulai ?? '-' }}</td>
                            <td>{{ $jadwal->jam_selesai ?? '-' }}</td>
                            <td>{{ $jadwal->laboratorium->nama_laboratorium ?? '-' }}</td>
                            <td>{{ $jadwal->dosen ? Str::limit($jadwal->dosen->nama, 20, '...') : '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $jadwal->status ?? 'active' }}">
                                    {{ ucfirst($jadwal->status ?? 'Active') }}
                                </span>
                            </td>
                            <td>
                                <button class="edit" onclick="openEditModal({{ $jadwal->id }})">Edit</button>
                                <form method="POST" action="{{ route('deleteJadwal', $jadwal->id) }}"
                                    style="display:inline;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete">Hapus</button>
                                </form>
                                <button class="detail" onclick="openDetailModal({{ $jadwal->id }})">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-calendar-alt"></i> Tidak Ada Jadwal
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $jadwals->appends(request()->query())->links() }}
            </div>
        </div>

    </main>

    <div id="jadwalDataStore" style="display: none;">
        @foreach ($jadwals as $jadwal)
            <div class="jadwal-data" data-id="{{ $jadwal->id }}" data-id_praktikum="{{ $jadwal->id_praktikum }}"
                data-praktikum_nama="{{ $jadwal->praktikum->nama_praktikum ?? '' }}"
                data-hari="{{ $jadwal->hari }}" data-jam_mulai="{{ $jadwal->jam_mulai }}"
                data-jam_selesai="{{ $jadwal->jam_selesai }}" data-id_laboratorium="{{ $jadwal->id_laboratorium }}"
                data-laboratorium_nama="{{ $jadwal->laboratorium->nama_laboratorium ?? '' }}" data-id_dosen="{{ $jadwal->dosen?->id ?? '' }}"
data-dosen_nama="{{ $jadwal->dosen?->nama ?? '-' }}"
                data-status="{{ $jadwal->status ?? 'active' }}" data-created_at="{{ $jadwal->created_at }}"
                data-updated_at="{{ $jadwal->updated_at }}" data-praktikum='@json($jadwal->praktikum)'
                data-laboratorium='@json($jadwal->laboratorium)' data-asisten='@json($jadwal->asisten)'
                data-pertemuans='@json($jadwal->pertemuans)'>
            </div>
        @endforeach
    </div>

    <div class="modal" id="addModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Jadwal Baru</h3>
                <span class="close" onclick="closeAddModal()">&times;</span>
            </div>
            <form method="POST" action="{{ route('addJadwal') }}">
                @csrf
                <div class="form-group">
                    <label for="id_praktikum">Praktikum</label>
                    <select id="id_praktikum" name="id_praktikum" required>
                        <option value="">Pilih Praktikum</option>
                        @foreach ($praktikums as $praktikum)
                            <option value="{{ $praktikum->id }}">{{ $praktikum->kode_praktikum }} -
                                {{ $praktikum->nama_praktikum }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="hari">Hari</label>
                    <select id="hari" name="hari" required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" required>
                    </div>

                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id_laboratorium">laboratorium</label>
                    <select id="id_laboratorium" name="id_laboratorium" required>
                        <option value="">Pilih laboratorium</option>
                        @foreach ($laboratoriums as $laboratorium)
                            <option value="{{ $laboratorium->id }}">{{ $laboratorium->kode_laboratorium }} -
                                {{ $laboratorium->nama_laboratorium }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_dosen">Dosen</label>
                    <select id="id_dosen" name="id_dosen" required>
                        <option value="">Pilih dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nomor_induk }} -
                                {{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah_max_peserta">Jumlah Peserta</label>
                    <input type="number" id="jumlah_max_peserta" name="jumlah_max_peserta" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Dibuka">Dibuka</option>
                        <option value="Penuh">Penuh</option>
                        <option value="Selesai">Selesai</option>
                    </select>
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
                <h3>Edit Jadwal</h3>
                <span class="close"
                    onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" id="editForm" action="{{empty($jadwal) ? '' : route("updateJadwal", $jadwal->id)}}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_id_praktikum">Praktikum</label>
                    <select id="edit_id_praktikum" name="id_praktikum" required>
                        <option value="">Pilih Praktikum</option>
                        @foreach ($praktikums as $praktikum)
                            <option value="{{ $praktikum->id }}">{{ $praktikum->kode_praktikum }} -
                                {{ $praktikum->nama_praktikum }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_hari">Hari</label>
                    <select id="edit_hari" name="hari" required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_jam_mulai">Jam Mulai</label>
                        <input type="time" id="edit_jam_mulai" name="jam_mulai" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_jam_selesai">Jam Selesai</label>
                        <input type="time" id="edit_jam_selesai" name="jam_selesai" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_id_laboratorium">laboratorium</label>
                    <select id="edit_id_laboratorium" name="id_laboratorium" required>
                        <option value="">Pilih laboratorium</option>
                        @foreach ($laboratoriums as $laboratorium)
                            <option value="{{ $laboratorium->id }}">{{ $laboratorium->kode_laboratorium }} -
                                {{ $laboratorium->nama_laboratorium }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_id_dosen">Dosen</label>
                    <select id="edit_id_dosen" name="id_dosen" required>
                        <option value="">Pilih dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nomor_induk }} -
                                {{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status">
                        <option value="Dibuka">Dibuka</option>
                        <option value="Penuh">Penuh</option>
                        <option value="Selesai">Selesai</option>
                    </select>
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
                <h3><i class="fas fa-info-circle"></i> Detail Jadwal</h3>
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

    <script src="{{ asset('lab_js/kelolaJadwal_lab.js') }}"></script>
</body>

</html>
