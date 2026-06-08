<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Modules - Asisten</title>
    <link rel="stylesheet" href="{{ asset('asisten_css/manageModules.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('asisten/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2>Add Resources</h2>
            <div class="actions">
                <button class="btn add" onclick="openPopup('add')">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="padding:12px 16px;margin:15px 0;background:#d4edda;border:1px solid #c3e6cb;border-radius:4px;color:#155724;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="padding:12px 16px;margin:15px 0;background:#f8d7da;border:1px solid #f5c6cb;border-radius:4px;color:#721c24;">
                <ul style="margin:0;padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Pertemuan Ke</th>
                        <th>Nama Pertemuan</th>
                        <th>Praktikum</th>
                        <th>Deskripsi Pertemuan</th>
                        <th>Judul Modul</th>
                        <th>Deskripsi Modul</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($moduls as $pertemuan)
                        <tr>
                            <td>{{ $pertemuan->pertemuan_ke }}</td>
                            <td>{{ $pertemuan->nama_pertemuan }}</td>
                            <td>{{ $pertemuan->jadwal->praktikum->nama_praktikum ?? '-' }}</td>
                            <td>{{ Str::limit($pertemuan->deskripsi_pertemuan, 50) }}</td>

                            @if($pertemuan->modul)
                                <td>{{ $pertemuan->modul->judul_modul }}</td>
                                <td>{{ Str::limit($pertemuan->modul->deskripsi, 60) }}</td>
                                <td>
                                    <a href="{{ Storage::url($pertemuan->modul->filepath) }}" target="_blank" class="view" style="margin-bottom: 15px; padding: 2.5px;">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <br>
                                    <a href="{{ Storage::url($pertemuan->modul->filepath) }}" download class="download">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                                <td class="actions-btn">
                                    <button class="edit" onclick="openPopup('edit', {{ $pertemuan->modul->id }}, {{ $pertemuan->id }}, '{{ addslashes($pertemuan->modul->judul_modul) }}', '{{ addslashes($pertemuan->modul->deskripsi) }}')">
                                        ✏️
                                    </button>
                                    <form method="POST" action="{{ route('deleteModul', $pertemuan->modul->id) }}" style="display:inline;" onsubmit="return confirm('Hapus modul ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="delete">🗑️</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="3" style="text-align:center;color:#999;font-style:italic;">
                                    Belum ada modul
                                </td>
                                <td>
                                    <button class="btn add" onclick="openPopup('add', null, {{ $pertemuan->id }})" style="border: none; border-radius: 5px; padding: 5px;">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:30px;color:#999;">
                                <i class="fas fa-inbox fa-2x"></i>
                                <p>Belum ada pertemuan yang tersedia</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    {{-- POPUP FORM --}}
    <div class="popup" id="modulePopup" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
        <div class="popup-content" style="background:white;border-radius:12px;padding:30px;width:500px;max-width:95%;max-height:90vh;overflow-y:auto;">

            <span class="close-btn" onclick="closePopup()" style="float:right;font-size:22px;cursor:pointer;">✖</span>
            <h3 id="popupTitle">Tambah Modul</h3>
            <br>

            <form id="modulForm" method="POST" enctype="multipart/form-data">
                @csrf
                <span id="methodField"></span>

                <div class="form-group" style="margin-bottom:15px;">
                    <label>Pertemuan</label>
                    <select name="id_pertemuan" id="p_pertemuan" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                        <option value="">-- Pilih Pertemuan --</option>
                        @foreach($pertemuans as $p)
                            <option value="{{ $p->id }}">
                                Pertemuan {{ $p->pertemuan_ke }} - {{ $p->nama_pertemuan }}
                                ({{ $p->jadwal->praktikum->nama_praktikum ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label>Judul Modul</label>
                    <input type="text" name="judul_modul" id="p_modul" placeholder="Contoh: HTML Dasar"
                           style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;" required>
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label>Deskripsi Modul</label>
                    <textarea name="deskripsi" id="p_descModul" rows="3" placeholder="Deskripsi modul..."
                              style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;" required></textarea>
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label>Upload File <span id="fileNote" style="color:#999;font-size:12px;">(PDF, DOC, PPT — maks 10MB)</span></label>
                    <input type="file" name="file" id="p_file" accept=".pdf,.doc,.docx,.ppt,.pptx"
                           style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                </div>

                <div class="popup-actions" style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                    <button type="button" class="btn-cancel" onclick="closePopup()"
                            style="padding:8px 16px;border-radius:6px;border:1px solid #ddd;cursor:pointer;">
                        Cancel
                    </button>
                    <button type="submit" id="btnSave"
                            style="padding:8px 20px;background:#6366f1;color:white;border:none;border-radius:6px;cursor:pointer;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPopup(mode, modulId = null, pertemuanId = null, judulModul = '', deskripsi = '') {
            const popup     = document.getElementById('modulePopup');
            const form      = document.getElementById('modulForm');
            const title     = document.getElementById('popupTitle');
            const method    = document.getElementById('methodField');
            const fileNote  = document.getElementById('fileNote');

            if (mode === 'edit' && modulId) {
                title.innerText = 'Edit Modul';
                form.action     = `/asisten/modul/${modulId}`;
                method.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
                fileNote.innerText = '(Kosongkan jika tidak ingin mengganti file)';
                document.getElementById('p_file').removeAttribute('required');

                document.getElementById('p_modul').value     = judulModul;
                document.getElementById('p_descModul').value = deskripsi;
                if (pertemuanId) {
                    document.getElementById('p_pertemuan').value = pertemuanId;
                }
            } else {
                title.innerText = 'Tambah Modul';
                form.action     = `{{ route('storeModul') }}`;
                method.innerHTML = '';
                fileNote.innerText = '(PDF, DOC, PPT — maks 10MB)';
                document.getElementById('p_file').setAttribute('required', 'required');

                form.reset();
                if (pertemuanId) {
                    document.getElementById('p_pertemuan').value = pertemuanId;
                }
            }

            popup.style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('modulePopup').style.display = 'none';
        }

        window.onclick = function(e) {
            const popup = document.getElementById('modulePopup');
            if (e.target === popup) closePopup();
        }
    </script>
</body>
</html>