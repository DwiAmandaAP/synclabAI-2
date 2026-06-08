<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Pretest | {{ $modul->judul_modul ?? 'Pretest' }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            min-height: 100vh;
            padding: 20px;
        }

        .pretest-container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header */
        .pretest-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .header-info h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-info p {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 6px;
        }

        .timer-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 16px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .timer-box.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .timer-box.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        /* Progress Bar */
        .progress-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Main Content */
        .pretest-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        /* Question */
        .question-item {
            margin-bottom: 40px;
        }

        .question-item:last-child {
            margin-bottom: 0;
        }

        .question-number {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .question-text {
            font-size: 1.15rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .options-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .option-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
        }

        .option-radio {
            margin-top: 4px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .option-label {
            flex: 1;
            padding: 14px 18px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            color: #1e293b;
            font-weight: 500;
        }

        .option-item input[type="radio"]:checked + .option-label {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-color: #667eea;
            color: #0f172a;
            font-weight: 600;
        }

        /* Answer Summary */
        .answer-summary {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-top: 20px;
        }

        .answer-summary h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .answer-summary-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
        }

        .answer-box {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px;
            text-align: center;
            font-weight: 600;
            font-size: 0.85rem;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .answer-box.answered {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-color: #667eea;
            color: #0f172a;
        }

        /* Footer / Action Buttons */
        .pretest-footer {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #1e293b;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.3rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
        }

        .modal-close:hover {
            opacity: 0.8;
        }

        .modal-body {
            padding: 30px 24px;
        }

        .result-score {
            text-align: center;
            margin-bottom: 24px;
        }

        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0 auto 20px;
            font-weight: 700;
        }

        .score-value {
            font-size: 3rem;
        }

        .score-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .result-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            color: #64748b;
        }

        .result-info strong {
            color: #0f172a;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .modal-footer .btn {
            padding: 10px 24px;
            font-size: 0.95rem;
        }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #10b981;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pretest-header {
                padding: 20px;
            }

            .header-top {
                flex-direction: column;
                gap: 16px;
            }

            .pretest-content {
                padding: 20px;
            }

            .header-info h1 {
                font-size: 1.4rem;
            }

            .question-text {
                font-size: 1rem;
            }

            .option-label {
                padding: 12px 14px;
                font-size: 0.9rem;
            }

            .pretest-footer {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .back-btn {
            color: #667eea;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            font-weight: 500;
            text-decoration: none;
        }

        .back-btn:hover {
            color: #764ba2;
        }
    </style>
</head>

<body>
    <div class="pretest-container">
        <!-- Back Button -->
        <a href="{{ route('pretest') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Header -->
        <div class="pretest-header">
            <div class="header-top">
                <div class="header-info">
                    <h1>
                        <i class="fas fa-file-alt"></i>
                        {{ $pretest->judul_kuis ?? 'Pretest' }}
                    </h1>
                    <p><strong>Mata Kuliah:</strong> {{ $pertemuan->praktikum->nama_praktikum ?? '-' }}</p>
                    <p><strong>Modul:</strong> {{ $modul->judul_modul ?? '-' }}</p>
                    <p><strong>Total Soal:</strong> {{ $totalQuestions }} Pertanyaan</p>
                </div>
                <div class="timer-box" id="timerBox">
                    <i class="fas fa-clock"></i><br>
                    <span id="timerDisplay">30:00</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-section">
                <div class="progress-info">
                    <span>Progres Pengerjaan</span>
                    <span><span id="answeredCount">0</span>/<span id="totalQuestionCount">{{ $totalQuestions }}</span> Terjawab</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <form id="pretestForm" class="pretest-content">
            @csrf
            @if ($pretest->questions->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Belum ada soal yang tersedia untuk pretest ini.</span>
                </div>
            @else
                <!-- Questions -->
                @foreach ($pretest->questions as $index => $question)
                    <div class="question-item" data-question-id="{{ $question->id }}">
                        <div class="question-number">{{ $index + 1 }}</div>
                        <div class="question-text">{{ $question->question_text }}</div>

                        <div class="options-container">
                            @foreach (['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $optionKey => $optionText)
                                <label class="option-item">
                                    <input type="radio" class="option-radio" name="answers[{{ $question->id }}]"
                                        value="{{ $optionKey }}" onchange="updateProgress()">
                                    <div class="option-label">
                                        <strong>{{ $optionKey }}.</strong> {{ $optionText }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Answer Summary -->
                <div class="answer-summary">
                    <h4><i class="fas fa-list-check"></i> Ringkasan Jawaban</h4>
                    <div class="answer-summary-list" id="answerSummary">
                        @for ($i = 1; $i <= $totalQuestions; $i++)
                            <div class="answer-box" data-question-num="{{ $i }}">Soal {{ $i }}</div>
                        @endfor
                    </div>
                </div>
            @endif

            <!-- Footer Buttons -->
            <div class="pretest-footer" style="margin-top: 40px;">
                <button type="button" class="btn btn-secondary" onclick="history.back()">
                    <i class="fas fa-times"></i> Batalkan
                </button>
                <button type="button" class="btn btn-primary" id="submitBtn" onclick="submitPretest()"
                    @if ($pretest->questions->isEmpty()) disabled @endif>
                    <i class="fas fa-paper-plane"></i> Submit Pretest
                </button>
            </div>
        </form>
    </div>

    <!-- Result Modal -->
    <div class="modal" id="resultModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Hasil Pretest</h3>
                <button type="button" class="modal-close" onclick="closeResultModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="result-score">
                    <div class="score-circle">
                        <div class="score-value" id="finalScore">0</div>
                        <div class="score-label">dari 100</div>
                    </div>
                    <h4 style="color: #0f172a; margin-bottom: 20px;">Selamat!</h4>
                </div>

                <div class="result-info">
                    <p><strong>Soal Benar:</strong> <span id="resultCorrect">0</span> dari <span id="resultTotal">0</span></p>
                    <p style="margin-top: 8px;"><strong>Persentase:</strong> <span id="resultPercentage">0</span>%</p>
                </div>

                <div class="result-info" id="feedbackBox" style="background: #dcfce7; border-left: 4px solid #10b981;">
                    <i class="fas fa-star" style="color: #10b981;"></i>
                    <span id="feedbackText">Bagus! Anda sudah memahami materi dengan baik.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="redirectToDashboard()">
                    <i class="fas fa-home"></i> Kembali ke Dashboard
                </button>
            </div>
        </div>
    </div>

    <script>
        // ────────────────────────────────────────────────────────────────────────
        // VARIABLES & CONSTANTS
        // ────────────────────────────────────────────────────────────────────────
        const PRETEST_TIME = 30 * 60; // 30 minutes in seconds
        const TOTAL_QUESTIONS = {{ $totalQuestions }};
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const PERTEMUAN_ID = {{ $pertemuan->id }};

        let timeRemaining = PRETEST_TIME;
        let timerInterval = null;

        // ────────────────────────────────────────────────────────────────────────
        // TIMER FUNCTIONALITY
        // ────────────────────────────────────────────────────────────────────────
        function startTimer() {
            timerInterval = setInterval(() => {
                timeRemaining--;

                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                document.getElementById('timerDisplay').textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                const timerBox = document.getElementById('timerBox');
                if (timeRemaining <= 60) {
                    timerBox.classList.add('danger');
                    timerBox.classList.remove('warning');
                } else if (timeRemaining <= 300) {
                    timerBox.classList.add('warning');
                    timerBox.classList.remove('danger');
                }

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    autoSubmitPretest();
                }
            }, 1000);
        }

        // ────────────────────────────────────────────────────────────────────────
        // PROGRESS TRACKING
        // ────────────────────────────────────────────────────────────────────────
        function updateProgress() {
            const form = document.getElementById('pretestForm');
            const answeredQuestions = form.querySelectorAll('input[type="radio"]:checked').length;
            document.getElementById('answeredCount').textContent = answeredQuestions;

            const percentage = (answeredQuestions / TOTAL_QUESTIONS) * 100;
            document.getElementById('progressFill').style.width = percentage + '%';

            // Update answer summary
            const answerBoxes = document.querySelectorAll('.answer-box');
            const answers = {};
            form.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                const questionId = input.name.match(/\d+/)[0];
                answers[questionId] = input.value;
            });

            answerBoxes.forEach((box, idx) => {
                box.classList.remove('answered');
                if (answers[idx + 1]) {
                    box.textContent = answers[idx + 1];
                    box.classList.add('answered');
                } else {
                    box.textContent = `Soal ${idx + 1}`;
                }
            });
        }

        // ────────────────────────────────────────────────────────────────────────
        // SUBMIT PRETEST
        // ────────────────────────────────────────────────────────────────────────
        async function submitPretest() {
            const form = document.getElementById('pretestForm');
            const answeredCount = form.querySelectorAll('input[type="radio"]:checked').length;

            if (answeredCount === 0) {
                alert('Harap jawab minimal satu soal sebelum submit.');
                return;
            }

            // Ask confirmation
            const confirmSubmit = confirm(
                `Anda telah menjawab ${answeredCount} dari ${TOTAL_QUESTIONS} soal.\n\nApakah Anda yakin ingin mengirimkan jawaban?`
            );

            if (!confirmSubmit) return;

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

            try {
                // Collect answers
                const answers = {};
                form.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                    const questionId = input.name.match(/\d+/)[0];
                    answers[questionId] = input.value;
                });

                // Send to server
                const response = await fetch(`/mahasiswa/pretest/submit/${PERTEMUAN_ID}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ answers })
                });

                const data = await response.json();

                if (data.success) {
                    clearInterval(timerInterval);
                    showResult(data.score, data.correct, data.total);
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Pretest';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal mengirim jawaban. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Pretest';
            }
        }

        // ────────────────────────────────────────────────────────────────────────
        // AUTO SUBMIT (when time is up)
        // ────────────────────────────────────────────────────────────────────────
        async function autoSubmitPretest() {
            const form = document.getElementById('pretestForm');
            const answers = {};

            form.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                const questionId = input.name.match(/\d+/)[0];
                answers[questionId] = input.value;
            });

            try {
                const response = await fetch(`/mahasiswa/pretest/submit/${PERTEMUAN_ID}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ answers })
                });

                const data = await response.json();

                if (data.success) {
                    showResult(data.score, data.correct, data.total);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // ────────────────────────────────────────────────────────────────────────
        // SHOW RESULT
        // ────────────────────────────────────────────────────────────────────────
        function showResult(score, correct, total) {
            const percentage = Math.round((correct / total) * 100);

            document.getElementById('finalScore').textContent = score;
            document.getElementById('resultCorrect').textContent = correct;
            document.getElementById('resultTotal').textContent = total;
            document.getElementById('resultPercentage').textContent = percentage;

            // Feedback based on score
            const feedbackBox = document.getElementById('feedbackBox');
            const feedbackText = document.getElementById('feedbackText');

            if (score >= 80) {
                feedbackBox.style.background = '#dcfce7';
                feedbackBox.style.borderLeftColor = '#10b981';
                feedbackText.innerHTML = '<i class="fas fa-star" style="color: #10b981;"></i> Excellent! Anda menguasai materi dengan sangat baik.';
            } else if (score >= 60) {
                feedbackBox.style.background = '#fef3c7';
                feedbackBox.style.borderLeftColor = '#f59e0b';
                feedbackText.innerHTML = '<i class="fas fa-check" style="color: #f59e0b;"></i> Good! Anda memahami sebagian besar materi.';
            } else {
                feedbackBox.style.background = '#fee2e2';
                feedbackBox.style.borderLeftColor = '#ef4444';
                feedbackText.innerHTML = '<i class="fas fa-exclamation" style="color: #ef4444;"></i> Anda perlu lebih banyak belajar tentang materi ini.';
            }

            // Show modal
            document.getElementById('resultModal').classList.add('active');
        }

        // ────────────────────────────────────────────────────────────────────────
        // MODAL HELPERS
        // ────────────────────────────────────────────────────────────────────────
        function closeResultModal() {
            document.getElementById('resultModal').classList.remove('active');
        }

        function redirectToDashboard() {
            window.location.href = '{{ route('mahasiswa') }}';
        }

        // ────────────────────────────────────────────────────────────────────────
        // INITIALIZATION
        // ────────────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            startTimer();
            updateProgress();
        });

        // Warn user before leaving
        window.addEventListener('beforeunload', (e) => {
            if (timeRemaining > 0) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });
    </script>
</body>

</html>
