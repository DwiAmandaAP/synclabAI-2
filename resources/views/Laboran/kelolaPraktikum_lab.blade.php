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
            <h2>Manajemen Praktikum</h2>
            <div class="actions">
                <button class="btn add" onclick="openAdd()">+ Tambah Praktikum</button>
                <form method="GET" action="{{ route('masterPraktikum') }}" id="searchForm" style="width: 100%;">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                        onkeyup="if(event.key === 'Enter') this.form.submit()" id="search" class="search-box">
                </form>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="padding: 12px 16px; margin: 15px 0; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24; display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <strong><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #721c24; cursor: pointer; font-size: 20px; padding: 0; margin-left: 10px;">×</button>
            </div>
        @endif
        
        @if (session('success'))
            <div class="alert alert-success"
                style="padding: 12px 16px; margin: 15px 0; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724; display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <strong><i class="fas fa-check-circle"></i> Sukses!</strong>
                    <p style="margin: 8px 0 0 0;">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #155724; cursor: pointer; font-size: 20px; padding: 0; margin-left: 10px;">×</button>
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kode Praktikum</th>
                        <th>Nama Praktikum</th>
                        <th>Angkatan</th>
                        <th>Semester</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($praktikums as $prak)
                        <script>
                            const praktikumData = @json($praktikums->items());
                        </script>
                        <tr>
                            <td>{{ $prak->kode_praktikum }}</td>
                            <td>{{$prak->nama_praktikum}}</td>
                            <td>{{$prak->angkatan}}</td>
                            <td>{{$prak->semester}}</td>
                            <td>
                                <button class="edit" onclick="openEditModal({{ $prak->id }})">Edit</button>
                                <form method="POST" action="{{ route('deletePraktikum', $prak->id) }}"
                                    style="display:inline;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="delete">Hapus</button>
                                </form>
                                <button class="detail" onclick="openDetailModal({{ $prak->id }})">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-inbox"></i> Tidak Ada Praktikum
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $praktikums->links() }}
            </div>
        </div>

        <div class="modal" id="editModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Edit Praktikum</h3>
                    <span class="close"
                        onclick="document.getElementById('editModal').style.display='none'">&times;</span>
                </div>
                <form method="POST" id="editForm" action="{{empty($prak) ? '' : route("updatePraktikum", $prak->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_kode">Kode Praktikum</label>
                        <input type="text" id="edit_kode" name="kode_praktikum" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama">Nama Praktikum</label>
                        <input type="text" id="edit_nama" name="nama_praktikum" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_angkatan">Angkatan</label>
                            <input type="text" id="edit_angkatan" name="angkatan" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_semester">Semester</label>
                            <input type="text" id="edit_semester" name="semester" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_id_dosen">Kepala Laboratorium</label>
                        <select id="id_id_dosen" name="id_dosen">
                            <option value="">Pilih Kepala Lab</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->nama }}
                                    ({{ $dosen->nomor_induk }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-secondary"
                            onclick="document.getElementById('editModal').style.display='none'">Batalkan</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal" id="detailModal">
            <div class="modal-content large">
                <div class="modal-header">
                    <h3><i class="fas fa-info-circle"></i> Detail Praktikum</h3>
                    <span class="close"
                        onclick="document.getElementById('detailModal').style.display='none'">&times;</span>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-book"></i> Informasi Praktikum</h4>
                    <div class="detail-grid" id="detailInfo">
                    </div>
                </div>

                <div class="detail-section" id="detailStats" style="display: flex; gap: 15px; justify-content: space-around;">
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-calendar-alt"></i> Jadwal Praktikum</h4>
                    <div class="table-wrapper">
                        <table class="detail-table" id="jadwalTable">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Laboratorium</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="jadwalBody">
                                <tr>
                                    <td colspan="6" style="text-align: center;">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-users"></i> Daftar Asisten</h4>
                    <div class="table-wrapper">
                        <table class="detail-table" id="asistenTable">
                            <thead>
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama Asisten</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="asistenBody">
                                <tr>
                                    <td colspan="5" style="text-align: center;">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="detail-section">
                    <h4><i class="fas fa-user-graduate"></i> Daftar Mahasiswa</h4>
                    <div class="table-wrapper">
                        <table class="detail-table" id="mahasiswaTable">
                            <thead>
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody id="mahasiswaBody">
                                <tr>
                                    <td colspan="5" style="text-align: center;">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="#" id="kelolaJadwalBtn" class="btn-primary"><i class="fas fa-clock"></i> Kelola
                        Jadwal</a>
                    <a href="" id="alokasiAsistenBtn" class="btn-primary"><i class="fas fa-user-plus"></i>
                        Alokasi Asisten</a>
                    <button type="button" class="btn-secondary"
                        onclick="document.getElementById('detailModal').style.display='none'">Tutup</button>
                </div>
            </div>
        </div>

        <div class="modal" id="formModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalTitle">Tambah Pertemuan Baru</h3>
                </div>

                <form id="practicumForm" method="POST" route="{{ route('addPraktikum') }}">
                    @csrf
                    <div class="form-group">
                        <label for="mk">Kode Praktikum</label>
                        <input type="text" id="kode" name="kode_praktikum" placeholder="e.g., 24RPL"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Praktikum</label>
                        <input type="text" id="nama" name="nama_praktikum"
                            placeholder="e.g., Rekayasa Perangkat Lunak">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="angkatan">Angkatan</label>
                            <input type="text" id="angkatan" name="angkatan" placeholder="e.g., 2024" required>
                        </div>

                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <input type="text" id="semester" name="semester" placeholder="e.g., 1, 2, 3"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_id_dosen">Kepala Laboratorium</label>
                        <select id="id_id_dosen" name="id_dosen">
                            <option value="">Pilih Kepala Lab</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->nama }}
                                    ({{ $dosen->nomor_induk }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal()">Batalkan</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>



        <script src="{{ asset('lab_js/kelolaPraktikum_lab.js') }}"></script>
</body>

</html>
