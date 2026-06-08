<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Flashcard | Mahasiswa</title>
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
        <h1 class="page-title">📇 Flashcard Saya</h1>
        <div class="container" style="padding: 20px;">
    <div class="header-section">
        <h2>📇 Flashcard Saya</h2>
        <p style="color: #666; margin-top: 5px;">Belajar menggunakan sistem spaced repetition</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Flashcard List -->
    @forelse($groupedFlashcards as $pertemuanId => $cards)
        @php
            $pertemuan = $cards->first()?->pertemuan ?? null;
            $praktikum = $pertemuan?->praktikum ?? null;
        @endphp

        <div style="margin-bottom: 30px;">
            <!-- Section Header -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0;">
                <h3 style="margin: 0 0 5px 0;">{{ $praktikum?->nama_praktikum ?? 'Praktikum' }}</h3>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">
                    Pertemuan {{ $pertemuan?->pertemuan_ke ?? '-' }} 
                    | {{ $cards->count() }} Flashcard
                </p>
            </div>

            <!-- Cards Grid -->
            <div style="background: white; padding: 20px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                    @foreach($cards as $card)
                        <div class="flashcard-summary"
                             onclick="openFlashcardSession({{ $pertemuanId }})"
                             style="background: #f9f9f9; border: 2px solid #e0e0e0; padding: 15px; border-radius: 6px; cursor: pointer; transition: all 0.3s;">
                            
                            <!-- Card Content Preview -->
                            <div style="height: 80px; overflow: hidden; margin-bottom: 10px;">
                                <p style="margin: 0 0 5px 0; font-size: 12px; color: #999; text-transform: uppercase;">Pertanyaan</p>
                                <p style="margin: 0; color: #333; font-size: 14px; line-height: 1.4;">
                                    {{ Str::limit($card->front, 80) }}
                                </p>
                            </div>

                            <!-- Progress Info -->
                            @if($card->progress->count() > 0)
                                @php $progress = $card->progress->first(); @endphp
                                <div style="background: #e8f5e9; padding: 8px; border-radius: 4px; font-size: 12px;">
                                    <span style="color: #2e7d32;">
                                        ✓ Review: {{ $progress->repetitions }}x
                                    </span>
                                </div>
                            @else
                                <div style="background: #fff3e0; padding: 8px; border-radius: 4px; font-size: 12px;">
                                    <span style="color: #e65100;">
                                        ⭐ Baru
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Start Learning Button -->
                <div style="margin-top: 20px; text-align: center;">
                    <button onclick="openFlashcardSession({{ $pertemuanId }})"
                            style="background: #667eea; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#5568d3'"
                            onmouseout="this.style.background='#667eea'">
                        Mulai Belajar ({{ $cards->count() }} Flashcard)
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div style="background: white; padding: 60px 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <p style="font-size: 18px; color: #666; margin-bottom: 10px;">📇 Anda belum memiliki flashcard</p>
            <p style="font-size: 14px; color: #999; margin-bottom: 20px;">
                Generate flashcard dari halaman modul untuk mulai belajar dengan AI
            </p>
            <a href="{{ route('modul') }}"
               style="display: inline-block; background: #667eea; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600; transition: all 0.3s;"
               onmouseover="this.style.background='#5568d3'"
               onmouseout="this.style.background='#667eea'">
                ← Kembali ke Modul
            </a>
        </div>
    @endforelse
        </div>
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
    .flashcard-summary:hover {
        border-color: #667eea;
        background: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }
</style>

<script>
    function openFlashcardSession(pertemuanId) {
        window.location.href = `/mahasiswa/flashcard/${pertemuanId}`;
    }
</script>
</body>
</html>
