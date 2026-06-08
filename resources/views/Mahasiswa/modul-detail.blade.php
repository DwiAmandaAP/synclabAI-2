<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Detail Modul | Mahasiswa</title>
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
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="main-content">
        <div class="container" style="padding: 20px; max-width: 900px;">
    <!-- Header -->
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
        <a href="{{ route('modul') }}" style="color: #667eea; text-decoration: none; font-size: 24px;">←</a>
        <div>
            <h2 style="margin: 0 0 5px 0;">{{ $modul->judul_modul ?? 'Modul' }}</h2>
            <p style="margin: 0; color: #666; font-size: 14px;">
                {{ $pertemuan->praktikum?->nama_praktikum ?? 'Praktikum' }} 
                | Pertemuan {{ $pertemuan->pertemuan_ke }}
            </p>
        </div>
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

    <!-- Main Content -->
    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px;">
        
        <!-- Left: Modul Info -->
        <div>
            <!-- Modul Description -->
            <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="margin-top: 0; color: #333;">Deskripsi Modul</h3>
                <p style="color: #666; line-height: 1.6; white-space: pre-wrap;">
                    {{ $modul->deskripsi ?? 'Tidak ada deskripsi' }}
                </p>
            </div>

            <!-- Modul File -->
            @if($modul->filepath)
                <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
                    <h3 style="margin-top: 0; color: #333;">File Modul</h3>
                    <div style="background: #f5f5f5; padding: 15px; border-radius: 4px;">
                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">
                            📄 {{ basename($modul->filepath) }}
                        </p>
                        <a href="{{ asset('storage/' . $modul->filepath) }}" 
                           target="_blank"
                           style="display: inline-block; background: #667eea; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.background='#5568d3'"
                           onmouseout="this.style.background='#667eea'">
                            📥 Download PDF
                        </a>
                    </div>
                </div>
            @endif

            <!-- Pretest Info -->
            @if($pertemuan->pretest)
                <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; color: #333;">📋 Pretest</h3>
                    <div style="background: #e3f2fd; padding: 15px; border-radius: 4px; border-left: 4px solid #2196F3;">
                        <p style="margin: 0 0 10px 0; font-weight: 600; color: #1565c0;">
                            {{ $pertemuan->pretest->judul_kuis }}
                        </p>
                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #666;">
                            Total Soal: <strong>{{ $pertemuan->pretest->questions->count() }}</strong>
                        </p>
                        <a href="{{ route('pretest.questions', $pertemuan->id) }}" 
                           style="display: inline-block; background: #2196F3; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.background='#1976D2'"
                           onmouseout="this.style.background='#2196F3'">
                            Buka Pretest
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Flashcard Section -->
        <div>
            <!-- Flashcard Card -->
            <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: sticky; top: 20px;">
                <h3 style="margin-top: 0; color: #333;">📇 Flashcard</h3>

                @if($flashcards->count() > 0)
                    <!-- Flashcard Stats -->
                    <div style="background: #f0f7ff; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                        <p style="margin: 0 0 10px 0; font-size: 12px; color: #666;">Total Flashcard:</p>
                        <p style="margin: 0; font-size: 24px; font-weight: bold; color: #667eea;">
                            {{ $flashcards->count() }}
                        </p>
                    </div>

                    <!-- Open Flashcard Button -->
                    <a href="{{ route('flashcard.show', $modul->id_pertemuan) }}" 
                       style="display: block; background: #667eea; color: white; padding: 12px; border-radius: 4px; text-decoration: none; text-align: center; font-weight: 600; margin-bottom: 10px; transition: all 0.3s;"
                       onmouseover="this.style.background='#5568d3'"
                       onmouseout="this.style.background='#667eea'">
                        Belajar Flashcard
                    </a>

                    <!-- Regenerate Button -->
                    <button onclick="regenerateFlashcard({{ $modul->id }})" 
                            style="width: 100%; background: #ff9800; color: white; padding: 10px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#f57c00'"
                            onmouseout="this.style.background='#ff9800'"
                            id="regenerateBtn">
                        🔄 Regenerate Flashcard
                    </button>
                @else
                    <!-- Empty State -->
                    <div style="background: #fff3e0; padding: 20px; border-radius: 4px; text-align: center;">
                        <p style="margin: 0 0 15px 0; color: #e65100; font-weight: 600;">
                            Belum ada flashcard untuk modul ini
                        </p>
                        <button onclick="generateFlashcard({{ $modul->id }})" 
                                style="width: 100%; background: #667eea; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                onmouseover="this.style.background='#5568d3'"
                                onmouseout="this.style.background='#667eea'"
                                id="generateBtn">
                            ✨ Generate Flashcard dengan AI
                        </button>
                    </div>
                @endif

                <p style="font-size: 12px; color: #999; margin-top: 15px; text-align: center;">
                    💡 Gunakan spaced repetition untuk belajar lebih efektif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 40px; border-radius: 8px; text-align: center;">
        <div style="width: 50px; height: 50px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
        <p style="font-size: 16px; color: #333; margin: 0;">Sedang generate flashcard dari AI...</p>
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
    #loadingModal.active {
    display: flex !important;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    function generateFlashcard(modulId) {
        if (!confirm('Generate flashcard akan memproses modul menggunakan AI.\nProses ini mungkin memakan waktu beberapa menit.\n\nLanjutkan?')) {
            return;
        }

        showLoading(true);

        fetch(`/mahasiswa/flashcard/${modulId}/generate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ Error: ' + (data.message || 'Gagal generate flashcard'));
            }
        })
        .catch(error => {
            showLoading(false);
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan: ' + error.message);
        });
    }

    function regenerateFlashcard(modulId) {
        if (!confirm('Regenerate akan membuat flashcard baru.\nFlashcard lama akan tetap tersimpan.\n\nLanjutkan?')) {
            return;
        }

        generateFlashcard(modulId);
    }

    function showLoading(show) {
        const modal = document.getElementById('loadingModal');
        modal.style.display = show ? 'flex' : 'none';
    }
</script>
    </div>
</div>
</div>
</body>
</html>
