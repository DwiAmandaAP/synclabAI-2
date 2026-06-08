<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="{{ asset('lab_css/kelolaPraktikum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        }

        .detail-section tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
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

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .detail-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main-content">

        <div class="header">
            <h2>Manajemen Pengguna</h2>
            <div class="actions">
                <button class="btn add" onclick="document.getElementById('addModal').style.display='flex'">+ Tambah
                    User</button>
                <form method="GET" action="{{ route('masterUser') }}" style="display: inline; width: 100%;">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                        onchange="this.form.submit()">
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
                        <th>Nomor Induk</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->nomor_induk }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ strtolower($user->role) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <button class="edit" onclick="openEditModal({{ $user->id }})">Edit</button>
                                <form method="POST" action="{{ route('deleteUser', $user->id) }}"
                                    style="display:inline;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete">Hapus</button>
                                </form>
                                <button class="detail" onclick="openDetailModal({{ $user->id }})">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                                <i class="fas fa-inbox"></i> Tidak Ada Pengguna
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>

    </main>

    <div id="userDataStore" style="display: none;">
        @foreach ($users as $user)
            <div class="user-data" data-id="{{ $user->id }}" data-nomor_induk="{{ $user->nomor_induk }}"
                data-nama="{{ $user->nama }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                data-nohp="{{ $user->nohp }}"
                data-created_at="{{ $user->created_at }}" data-updated_at="{{ $user->updated_at }}">
            </div>
        @endforeach
    </div>

    <div class="modal" id="addModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah User Baru</h3>
                <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" action="{{ route('addUser') }}">
                @csrf
                <div class="form-group">
                    <label for="nomor_induk">Nomor Induk</label>
                    <input type="text" id="nomor_induk" name="nomor_induk" placeholder="e.g., 12345678" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="e.g., Ahmad Fauzi" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="e.g., ahmad@example.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group">
                    <label for="no_hp">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" placeholder="e.g., 08123456789">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="Praktikan">Praktikan</option>
                            <option value="Asisten">Asisten</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary"
                        onclick="document.getElementById('addModal').style.display='none'">Batalkan</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User</h3>
                <span class="close"
                    onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" id="editForm" action="{{ empty($user) ? '' : route('updateUser', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_nomor_induk">Nomor Induk</label>
                    <input type="text" id="edit_nomor_induk" name="nomor_induk" required>
                </div>
                <div class="form-group">
                    <label for="edit_nama">Nama Lengkap</label>
                    <input type="text" id="edit_nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="edit_password">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" id="edit_password" name="password" placeholder="Minimal 6 karakter">
                </div>
                <div class="form-group">
                    <label for="edit_no_hp">No. HP</label>
                    <input type="text" id="edit_nohp" name="no_hp">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_role">Role</label>
                        <select id="edit_role" name="role" required>
                            <option value="Asisten">Asisten</option>
                            <option value="Praktikan">Praktikan</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Admin">Admin</option>
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
            <div class="modal-header" style="display: flex; justify-content: space-between;">
                <h3>Detail User</h3>
                <span class="close"
                    onclick="document.getElementById('detailModal').style.display='none'">&times;</span>
            </div>
            <div id="detailContent">
            </div>
        </div>
    </div>
    <script src="{{ asset('lab_js/kelolaAsisten_lab.js') }}"></script>
    <script>
        window.onclick = function(event) {
            if (event.target.classList && event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>

</html>
