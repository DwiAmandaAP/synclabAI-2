<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Modul | Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f0f4f8;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            padding: 28px 32px;
            overflow-x: auto;
        }
        .page-title {
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 24px;
            color: #1e293b;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="main-content">
        <h1 class="page-title">📚 Daftar Modul</h1>
        <div class="container" style="padding: 20px;">
    <div class="header-section">
        <h2>📚 Daftar Modul Praktikum</h2>
        <p style="color: #666; margin-top: 5px;">Pilih modul untuk melihat detail dan membuat flashcard</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Praktikum -->
    <div class="filter-box" style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <label style="font-weight: 600; display: block; margin-bottom: 10px;">Filter Praktikum:</label>
        <select id="praktikumFilter" onchange="filterModul()" 
                style="width: 100%; max-width: 300px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">-- Semua Praktikum --</option>
            @foreach($matkulList as $matkul)
                <option value="{{ $matkul }}">{{ $matkul }}</option>
            @endforeach
        </select>
    </div>

    <!-- Modul Grid -->
    <div class="moduls-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
        @forelse($moduls as $modul)
            <div class="modul-card" data-matkul="{{ $modul->pertemuan?->praktikum?->nama_praktikum ?? 'Praktikum' }}"
                 style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; transition: all 0.3s;">
                
                <!-- Card Header -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; color: white;">
                    <h3 style="margin: 0 0 5px 0; font-size: 18px;">
                        {{ $modul->judul_modul ?? 'Modul' }}
                    </h3>
                    <p style="margin: 0; font-size: 12px; opacity: 0.9;">
                        <strong>{{ $modul->pertemuan?->praktikum?->nama_praktikum ?? 'Praktikum' }}</strong>
                        - Pertemuan {{ $modul->pertemuan?->pertemuan_ke ?? '-' }}
                    </p>
                </div>

                <!-- Card Body -->
                <div style="padding: 20px;">
                    <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.5;">
                        {{ Str::limit($modul->deskripsi ?? 'Tidak ada deskripsi', 100) }}
                    </p>

                    <!-- Status Badges -->
                    <div style="display: flex; gap: 8px; margin-bottom: 15px; flex-wrap: wrap;">
                        @if($modul->flashcards->count() > 0)
                            <span style="background: #c8e6c9; color: #2e7d32; padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                📇 {{ $modul->flashcards->count() }} Flashcard
                            </span>
                        @else
                            <span style="background: #ffccbc; color: #d84315; padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                📇 Belum ada Flashcard
                            </span>
                        @endif

                        @if($modul->pertemuan?->pretest)
                            <span style="background: #bbdefb; color: #1565c0; padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                ✅ {{ $modul->pertemuan->pretest->questions->count() }} Soal Pretest
                            </span>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('modul.show', $modul->id_pertemuan) }}" 
                           style="flex: 1; background: #667eea; color: white; padding: 10px; border-radius: 4px; text-align: center; text-decoration: none; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.background='#5568d3'" 
                           onmouseout="this.style.background='#667eea'">
                            Buka Modul
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #999;">
                <p style="font-size: 18px;">📚 Anda belum mendaftar praktikum apapun</p>
                <p style="font-size: 14px; margin-top: 10px;">
                    <a href="{{ route('praktikum') }}" style="color: #667eea; text-decoration: none;">Daftar praktikum terlebih dahulu</a>
                </p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .alert {
        padding: 15px 20px;
        border-radius: 4px;
        border: 1px solid;
    }
    .alert-success {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .alert-danger {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .modul-card:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
</style>

<script>
    function filterModul() {
        const filterValue = document.getElementById('praktikumFilter').value;
        const cards = document.querySelectorAll('.modul-card');

        cards.forEach(card => {
            if (filterValue === '' || card.dataset.matkul === filterValue) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
        </div>
    </div>
</div>
</body>
</html>
