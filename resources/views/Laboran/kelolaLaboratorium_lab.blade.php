<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Laboratorium</title>
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
            <h2>Manajemen Laboratorium</h2>
            <div class="actions">
                <button class="btn add" onclick="openAddModal()">+ Tambah Laboratorium</button>
                <form method="GET" action="{{ route('masterLaboratorium') }}" id="searchForm" style="width: 100%;">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                        onkeyup="if(event.key === 'Enter') this.form.submit()" id="search" class="search-box" style="width: 100%">
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
                        <th>Kode</th>
                        <th>Nama Laboratorium</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Kepala Lab</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laboratoriums as $laboratorium)
                        <tr>
                            <td>{{ $laboratorium->kode_laboratorium }}</td>
                            <td>{{ $laboratorium->nama_laboratorium }}</td>
                            <td>{{ $laboratorium->lokasi ?? '-' }}</td>
                            <td>{{ $laboratorium->kapasitas ?? '-' }} </td>
                            <td>{{Str::limit($laboratorium->kepalaLab->nama, 20, "...") ?? '-' }} </td>
                            <td>
                                <span
                                    class="badge badge-{{ strtolower($laboratorium->status) === 'tersedia' ? 'success' : 'warning' }}">
                                    {{ $laboratorium->status ?? 'Tersedia' }}
                                </span>
                            </td>
                            <td>
                                <button class="edit" onclick="openEditModal({{ $laboratorium->id }})">Edit</button>
                                <form method="POST" action="{{ route('deleteLaboratorium', $laboratorium->id) }}"
                                    style="display:inline;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete">Hapus</button>
                                </form>
                                <button class="detail"
                                    onclick="openDetailModal({{ $laboratorium->id }})">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-flask"></i> Tidak Ada Laboratorium
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $laboratoriums->appends(request()->query())->links() }}
            </div>
        </div>

    </main>

    <div id="laboratoriumDataStore" style="display: none;">
        @foreach ($laboratoriums as $laboratorium)
            <div class="laboratorium-data" data-id="{{ $laboratorium->id }}"
                data-kode_laboratorium={{$laboratorium->kode_laboratorium}}
                data-nama_laboratorium="{{ $laboratorium->nama_laboratorium }}"
                data-lokasi="{{ $laboratorium->lokasi }}" data-kapasitas="{{ $laboratorium->kapasitas }}"
                data-id_kepala_lab="{{ $laboratorium->kepalaLab->id }}"
                data-kepala_lab_nama="{{ $laboratorium->kepalaLab->nama }}" data-status="{{ $laboratorium->status }}"
                data-created_at="{{ $laboratorium->created_at }}" data-updated_at="{{ $laboratorium->updated_at }}">
            </div>
        @endforeach
    </div>

    <div class="modal" id="addModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Laboratorium Baru</h3>
                <span class="close" onclick="closeAddModal()">&times;</span>
            </div>
            <form method="POST" action="{{ route('addLaboratorium') }}">
                @csrf
                <div class="form-group">
                    <label for="kode_laboratorium">Kode Laboratorium <span class="required">*</span></label>
                    <input type="text" id="kode_laboratorium" name="kode_laboratorium"
                        placeholder="e.g., LRPL" required>
                </div>

                <div class="form-group">
                    <label for="nama_laboratorium">Nama Laboratorium <span class="required">*</span></label>
                    <input type="text" id="nama_laboratorium" name="nama_laboratorium"
                        placeholder="e.g., Laboratorium Komputer" required>
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" placeholder="e.g., Gedung A Lantai 2">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas</label>
                        <input type="number" id="kapasitas" name="kapasitas" placeholder="e.g., 40" min="1">
                    </div>

                    <div class="form-group">
                        <label for="id_kepala_lab">Kepala Laboratorium</label>
                        <select id="id_kepala_lab" name="kepala_lab">
                            <option value="">Pilih Kepala Lab</option>
                            @foreach ($kepalaLabs as $kepalaLab)
                                <option value="{{ $kepalaLab->id }}">{{ $kepalaLab->nama }}
                                    ({{ $kepalaLab->nomor_induk }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Terpakai">Terpakai</option>
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
                <h3>Edit Laboratorium</h3>
                <span class="close"
                    onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" id="editForm" action="{{empty($laboratorium) ? '' : route("updateLaboratorium", $laboratorium->id)}}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_kode_laboratorium">Kode Laboratorium <span class="required">*</span></label>
                    <input type="text" id="edit_kode_laboratorium" name="kode_laboratorium" required>
                </div>

                <div class="form-group">
                    <label for="edit_nama_laboratorium">Nama Laboratorium <span class="required">*</span></label>
                    <input type="text" id="edit_nama_laboratorium" name="nama_laboratorium" required>
                </div>

                <div class="form-group">
                    <label for="edit_lokasi">Lokasi</label>
                    <input type="text" id="edit_lokasi" name="lokasi" placeholder="e.g., Gedung A Lantai 2">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_kapasitas">Kapasitas</label>
                        <input type="number" id="edit_kapasitas" name="kapasitas" placeholder="e.g., 40"
                            min="1">
                    </div>

                    <div class="form-group">
                        <label for="edit_id_kepala_lab">Kepala Laboratorium</label>
                        <select id="edit_id_kepala_lab" name="kepala_lab">
                            <option value="">Pilih Kepala Lab</option>
                            @foreach ($kepalaLabs as $kepalaLab)
                                <option value="{{ $kepalaLab->id }}">{{ $kepalaLab->nama }}
                                    ({{ $kepalaLab->nomor_induk }})</option>\]
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status" required>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Terpakai">Terpakai</option>
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
                <h3><i class="fas fa-flask"></i> Detail Laboratorium</h3>
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
    <script src="{{ asset('lab_js/kelolaLab_lab.js') }}"></script>
</body>

</html>
