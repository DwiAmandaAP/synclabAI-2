<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Flashcard Study | Mahasiswa</title>
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
        <div class="container" style="padding: 20px; max-width: 800px;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 40px;">
        <a href="{{ route('flashcard') }}" style="color: #667eea; text-decoration: none; font-size: 18px;">← Kembali</a>
        <h2 style="margin: 15px 0 5px 0;">{{ $modul->judul_modul ?? 'Flashcard' }}</h2>
        <p style="color: #666; font-size: 14px; margin: 0;">
            {{ $pertemuan->praktikum?->nama_praktikum ?? 'Praktikum' }} 
            - Pertemuan {{ $pertemuan->pertemuan_ke }}
        </p>
    </div>

    @if (session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Progress Bar -->
    @if($flashcardsToReview->count() > 0)
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 600; color: #333;">Progress Belajar</p>
                <span style="font-weight: 600; color: #667eea;" id="progressText">0 / {{ $flashcardsToReview->count() }}</span>
            </div>
            <div style="width: 100%; height: 8px; background: #e0e0e0; border-radius: 4px; overflow: hidden;">
                <div id="progressBar" style="height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); width: 0%; transition: width 0.3s;"></div>
            </div>
        </div>
    @endif

    <!-- Main Flashcard Area -->
    <div id="flashcardContainer" style="perspective: 1000px;">
        @if($flashcardsToReview->count() > 0)
            <!-- Flashcard -->
                <<div id="flashcard" class="flashcard" 
                style="background: white; 
                min-height: 300px; 
                border-radius: 12px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
                padding: 40px; 
                cursor: pointer;
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;">
                
                <div style="pointer-events: none;">
                    <p id="cardLabel" style="margin: 0 0 20px 0; font-size: 12px; text-transform: uppercase; color: #999; letter-spacing: 1px;">
                        Pertanyaan
                    </p>
                    <p id="cardContent" style="margin: 0; font-size: 28px; color: #333; line-height: 1.6; font-weight: 500;">
                        Click to reveal answer
                    </p>
                </div>
            </div>

            <!-- Navigation & Rating -->
            <div style="margin-top: 40px;">
                <!-- Rating Buttons -->
                <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <p style="margin: 0 0 20px 0; text-align: center; color: #666; font-weight: 600;">
                        Seberapa mudah Anda mengingat kartu ini?
                    </p>
                    
                    <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; margin-bottom: 20px;">
                        <button class="rating-btn" data-score="0" style="background: #ffcdd2; color: #c62828; border: 2px solid transparent;">
                            <span style="display: block; font-size: 20px;">😞</span>
                            <span style="font-size: 12px;">Lupa</span>
                        </button>
                        <button class="rating-btn" data-score="1" style="background: #ffb74d; color: #e65100; border: 2px solid transparent;">
                            <span style="display: block; font-size: 20px;">😕</span>
                            <span style="font-size: 12px;">Sulit</span>
                        </button>
                        <button class="rating-btn" data-score="2" style="background: #fff9c4; color: #f57f17; border: 2px solid transparent;">
                            <span style="display: block; font-size: 20px;">😐</span>
                            <span style="font-size: 12px;">Biasa</span>
                        </button>
                        <button class="rating-btn" data-score="3" style="background: #c8e6c9; color: #558b2f; border: 2px solid transparent;">
                            <span style="display: block; font-size: 20px;">🙂</span>
                            <span style="font-size: 12px;">Lumayan</span>
                        </button>
                        <button class="rating-btn" data-score="4" style="background: #81c784; color: #2e7d32; border: 2px solid transparent;">
                            <span style="display: block; font-size: 20px;">😊</span>
                            <span style="font-size: 12px;">Mudah</span>
                        </button>
                        <button class="rating-btn" data-score="5" style="background: #4caf50; color: #1b5e20; border: 2px solid transparent;">
                            <span style="display: block; font-size: 20px;">🤩</span>
                            <span style="font-size: 12px;">Sempurna</span>
                        </button>
                    </div>

                    <p style="margin: 0; text-align: center; font-size: 12px; color: #999;">
                        💡 Skor 3-5: Akan di-review dalam beberapa hari | Skor 0-2: Review besok
                    </p>
                </div>

                <!-- Navigation -->
                <div style="display: flex; gap: 10px;">
                    <button onclick="previousCard()" style="flex: 1; background: #ddd; color: #333; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#ccc'"
                            onmouseout="this.style.background='#ddd'">
                        ← Sebelumnya
                    </button>
                    <button onclick="nextCard()" style="flex: 1; background: #667eea; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#5568d3'"
                            onmouseout="this.style.background='#667eea'">
                        Berikutnya →
                    </button>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div style="background: white; padding: 60px 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <p style="font-size: 18px; color: #666; margin-bottom: 10px;">✨ Sempurna!</p>
                <p style="font-size: 14px; color: #999; margin-bottom: 20px;">
                    Anda sudah menyelesaikan semua flashcard untuk saat ini.
                    Kembali lagi besok untuk melanjutkan spaced repetition!
                </p>
                <a href="{{ route('flashcard') }}"
                   style="display: inline-block; background: #667eea; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600; transition: all 0.3s;"
                   onmouseover="this.style.background='#5568d3'"
                   onmouseout="this.style.background='#667eea'">
                    ← Kembali ke Flashcard
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .alert {
        padding: 15px 20px;
        border-radius: 4px;
        border: 1px solid;
    }
    .alert-danger {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .rating-btn {
        padding: 12px 8px;
        border-radius: 6px;
        border: 2px solid transparent;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .rating-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .rating-btn:active {
        transform: translateY(0);
    }
    .flashcard {
        margin-bottom: 0;
    }
    .flashcard:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    /* Animasi flip yang benar — pakai scaleX bukan rotateY */
    #flashcard {
        transition: transform 0.15s ease-in;
    }
    #flashcard.is-flipping {
        transform: scaleX(0) !important;
    }

    /* Warna berbeda saat menampilkan jawaban */
    #flashcard.showing-back {
        background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%) !important;
        border: 2px solid #c5cae9;
    }

</style>

<script>
    const flashcardsData = @json($flashcardsJson);

    let currentIndex = 0;
    let isFlipped = false;

    function updateCard() {
        const card = flashcardsData[currentIndex];
        if (!card) return;

        const flashcardDiv   = document.getElementById('flashcard');
        const cardContent    = document.getElementById('cardContent');
        const cardLabel      = document.getElementById('cardLabel');
        const progressText   = document.getElementById('progressText');
        const progressBar    = document.getElementById('progressBar');

        // Reset ke sisi depan tanpa animasi
        isFlipped = false;
        flashcardDiv.classList.remove('is-flipping', 'showing-back');
        flashcardDiv.style.transform = '';

        cardLabel.textContent   = 'Pertanyaan';
        cardContent.textContent = card.front;

        // Update progress
        progressText.textContent = (currentIndex + 1) + ' / ' + flashcardsData.length;
        const progress = ((currentIndex + 1) / flashcardsData.length) * 100;
        progressBar.style.width = progress + '%';

        flashcardDiv.onclick = flipCard;
    }

    function flipCard() {
        if (!flashcardsData[currentIndex]) return;

        const card         = flashcardsData[currentIndex];
        const flashcardDiv = document.getElementById('flashcard');

        // Step 1: Collapse kartu (scaleX → 0)
        flashcardDiv.style.transition = 'transform 0.15s ease-in';
        flashcardDiv.classList.add('is-flipping');

        setTimeout(() => {
            // Step 2: Saat kartu tidak terlihat, ganti konten
            isFlipped = !isFlipped;

            const cardContent = document.getElementById('cardContent');
            const cardLabel   = document.getElementById('cardLabel');

            if (isFlipped) {
                cardLabel.textContent   = 'Jawaban';
                cardContent.textContent = card.back;
                flashcardDiv.classList.add('showing-back');
            } else {
                cardLabel.textContent   = 'Pertanyaan';
                cardContent.textContent = card.front;
                flashcardDiv.classList.remove('showing-back');
            }

            // Step 3: Expand kartu kembali
            flashcardDiv.style.transition = 'transform 0.15s ease-out';
            flashcardDiv.classList.remove('is-flipping');

            // Bersihkan inline transition setelah animasi selesai
            setTimeout(() => {
                flashcardDiv.style.transition = '';
            }, 150);

        }, 150); // Tunggu collapse selesai
    }

    function submitRating(score) {
        const card = flashcardsData[currentIndex];
        if (!card) return;

        fetch(`/mahasiswa/flashcard/${card.id}/review`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ score: score })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) console.log(data.message);
            nextCard();
        })
        .catch(error => console.error('Error:', error));
    }

    function nextCard() {
        if (currentIndex < flashcardsData.length - 1) {
            currentIndex++;
            updateCard();
        } else {
            alert('🎉 Selesai! Anda telah meninjau semua flashcard untuk saat ini.');
            window.location.href = '{{ route("flashcard") }}';
        }
    }

    function previousCard() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCard();
        }
    }

    // Rating buttons
    document.querySelectorAll('.rating-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            submitRating(parseInt(this.dataset.score));
        });
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', updateCard);

    // Keyboard shortcuts: Spasi = flip, ← → = navigasi
    document.addEventListener('keydown', function(e) {
        if (e.key === ' ')           { e.preventDefault(); flipCard(); }
        else if (e.key === 'ArrowLeft')  previousCard();
        else if (e.key === 'ArrowRight') nextCard();
    });
</script>
    </div>
</div>
</div>
</body>
</html>
