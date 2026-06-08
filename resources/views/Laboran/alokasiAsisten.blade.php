<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alokasi Asisten - {{ $praktikum->nama_praktikum }}</title>
    <link rel="stylesheet" href="{{ asset('lab_css/kelolaPraktikum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .badge-active {
            background: #d4edda;
            color: #155724;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .form-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
            display: block;
        }

        select[multiple] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .card {
            background: white;
            border-radius: 8px;
            border: 1px solid #ddd;
            overflow: hidden;
        }
    </style>
</head>

<body>
    @include('laboran/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2>Alokasi Asisten - {{ $praktikum->nama_praktikum }}</h2>
            <div class="actions">
                <button class="btn add" onclick="window.location.href='{{ route('masterPraktikum') }}'">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success"
                style="padding: 12px 16px; margin: 15px 0; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724;">
                <strong><i class="fas fa-check-circle"></i> Sukses!</strong> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger"
                style="padding: 12px 16px; margin: 15px 0; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;">
                <strong><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header" style="padding: 15px; background: #f8f9fa; border-bottom: 1px solid #ddd;">
                <h4>Informasi Praktikum</h4>
            </div>
            <div class="card-body" style="padding: 15px;">
                <p><strong>Kode Praktikum:</strong> {{ $praktikum->kode_praktikum }}</p>
                <p><strong>Nama Praktikum:</strong> {{ $praktikum->nama_praktikum }}</p>
                <p><strong>Angkatan:</strong> {{ $praktikum->angkatan }}</p>
                <p><strong>Semester:</strong> {{ $praktikum->semester }}</p>
                <p><strong>Jumlah Asisten:</strong> {{ $praktikum->asisten->count() }} asisten</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"
                style="padding: 15px; background: #f8f9fa; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                <h4>Daftar Asisten</h4>
                <button type="button" class="btn-primary"
                    onclick="document.getElementById('addAsistenModal').style.display='flex'">
                    <i class="fas fa-plus"></i> Tambah Asisten
                </button>
            </div>
            <div class="card-body" style="padding: 15px;">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama Asisten</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($praktikum->asisten as $asisten)
                                <tr>
                                    <td>{{ $asisten->nomor_induk }}</td>
                                    <td>{{ $asisten->nama }}</td>
                                    <td>{{ $asisten->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $asisten->pivot->status ?? 'active' }}">
                                            {{ ucfirst($asisten->pivot->status ?? 'Active') }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST"
                                            action="{{ route('deleteAlokasiAsisten', [$praktikum->id, $asisten->id]) }}"
                                            style="display:inline;"
                                            onsubmit="return confirm('Yakin mau hapus asisten ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete" style="padding: 5px 10px;">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                                        <i class="fas fa-users"></i> Belum ada asisten yang dialokasikan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- ADD ASISTEN MODAL -->
    <div class="modal" id="addAsistenModal">
        <div class="modal-content" style="width: 500px; max-width: 90%;">
            <div class="modal-header">
                <h3>Tambah Asisten ke Praktikum</h3>
                <span class="close"
                    onclick="document.getElementById('addAsistenModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" action="{{ route('storeAlokasiAsisten', $praktikum->id) }}">
                @csrf
                <div class="form-group">
                    <label for="asisten_ids">Pilih Asisten</label>
                    <select id="asisten_ids" name="asisten_ids[]" multiple class="form-control"
                        style="height: 200px; width: 100%; padding: 8px;">
                        @foreach ($allAsisten as $asisten)
                            <option value="{{ $asisten->id }}"
                                {{ in_array($asisten->id, $currentAsistenIds) ? 'disabled' : '' }}
                                style="{{ in_array($asisten->id, $currentAsistenIds) ? 'background: #e9ecef; color: #6c757d;' : '' }}">
                                {{ $asisten->nomor_induk }} - {{ $asisten->nama }}
                                @if (in_array($asisten->id, $currentAsistenIds))
                                    (Sudah dialokasikan)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Tekan Ctrl/Cmd untuk memilih lebih dari satu asisten</small>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary"
                        onclick="document.getElementById('addAsistenModal').style.display='none'">Batalkan</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('asisten_ids');
            if (selectElement) {
                const searchInput = document.createElement('input');
                searchInput.type = 'text';
                searchInput.placeholder = 'Cari asisten...';
                searchInput.style.cssText =
                    'width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;';
                selectElement.parentNode.insertBefore(searchInput, selectElement);

                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    Array.from(selectElement.options).forEach(option => {
                        const text = option.text.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });

        window.onclick = function(event) {
            if (event.target.classList && event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>

</html>
