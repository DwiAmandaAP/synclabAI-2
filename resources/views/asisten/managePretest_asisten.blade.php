<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manage Pretest - Asisten</title>
    <link rel="stylesheet" href="{{ asset('asisten_css/managePretest_asisten.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .main-content {
            margin-left: 300px;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        .filter-section {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }

        .filter-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #4CAF50;
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.generated {
            background: #c8e6c9;
            color: #2e7d32;
        }

        .status-badge.empty {
            background: #ffccbc;
            color: #d84315;
        }

        .actions-btn {
            display: flex;
            gap: 8px;
        }

        .actions-btn button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-generate {
            background: #2196F3;
            color: white;
        }

        .btn-generate:hover {
            background: #1976D2;
        }

        .btn-view {
            background: #4CAF50;
            color: white;
        }

        .btn-view:hover {
            background: #388E3C;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #d32f2f;
        }

        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .popup-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 20px;
        }

        .close-btn:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .popup-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .popup-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-cancel {
            background: #ddd;
            color: #333;
        }

        .btn-cancel:hover {
            background: #ccc;
        }

        .btn-save {
            background: #4CAF50;
            color: white;
        }

        .btn-save:hover {
            background: #388E3C;
        }

        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .modul-info {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 13px;
            color: #1976D2;
        }

        .question-count {
            background: #fff3cd;
            color: #856404;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    @include('asisten/partials/sidebar')

    <main class="main-content">
        <div class="header">
            <h2><i class="fas fa-file-pdf"></i> Manage Pretest</h2>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-error">
                    {{ $error }}
                </div>
            @endforeach
        @endif

        @if (session('success'))
            <div class="alert alert-success" id="successAlert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" id="errorAlert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <h3 style="margin-top: 0;">Filter Pretest</h3>
            <div class="filter-group">
                <div>
                    <label for="praktikumSelect">Pilih Praktikum:</label>
                    <select id="praktikumSelect" onchange="loadPertemuanOptions()">
                        <option value="">-- Pilih Praktikum --</option>
                        @foreach ($praktikums as $praktikum)
                            <option value="{{ $praktikum->id }}">{{ $praktikum->nama_praktikum }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pertemuanSelect">Pilih Pertemuan:</label>
                    <select id="pertemuanSelect" onchange="loadModulAndPretest()">
                        <option value="">-- Pilih Pertemuan --</option>
                    </select>
                </div>
            </div>

            <!-- Modul Info -->
            <div id="modulInfo" class="modul-info" style="display: none;">
                <strong>Modul Terkait:</strong> <span id="modulName"></span>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <table id="pretestTable">
                <thead>
                    <tr>
                        <th>Praktikum</th>
                        <th>Pertemuan</th>
                        <th>Judul Pretest</th>
                        <th>Jumlah Soal</th>
                        <th>Status</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">
                            Pilih praktikum dan pertemuan untuk melihat pretest
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Generate Pretest Popup -->
    <div class="popup" id="generatePopup">
        <div class="popup-content">
            <span class="close-btn" onclick="closeGeneratePopup()">&times;</span>
            <h3 id="popupTitle">Generate Soal Pretest</h3>

            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Sedang generate soal dari AI...</p>
            </div>

            <div id="generateForm">
                <div class="form-group">
                    <label>Praktikum</label>
                    <input type="text" id="genPraktikum" readonly>
                </div>

                <div class="form-group">
                    <label>Pertemuan</label>
                    <input type="text" id="genPertemuan" readonly>
                </div>

                <div class="form-group">
                    <label>Modul</label>
                    <input type="text" id="genModul" readonly>
                </div>

                <div class="form-group">
                    <label>Judul Pretest</label>
                    <input type="text" id="genJudul" placeholder="Judul akan di-generate otomatis">
                </div>

                <div class="popup-actions">
                    <button class="btn-cancel" onclick="closeGeneratePopup()">Batal</button>
                    <button class="btn-save" onclick="confirmGenerateSoal()">Generate Soal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Questions Popup -->
    <div class="popup" id="viewQuestionsPopup">
        <div class="popup-content">
            <span class="close-btn" onclick="closeViewPopup()">&times;</span>
            <h3>Daftar Soal Pretest</h3>

            <div id="questionsList" style="max-height: 400px; overflow-y: auto;">
                <!-- Questions will be loaded here -->
            </div>

            <div class="popup-actions">
                <button class="btn-cancel" onclick="closeViewPopup()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        let selectedPertemuanId = null;
        let selectedModulId = null;
        let pretestData = null;

        function loadPertemuanOptions() {
            const praktikumId = document.getElementById('praktikumSelect').value;
            const pertemuanSelect = document.getElementById('pertemuanSelect');

            if (!praktikumId) {
                pertemuanSelect.innerHTML = '<option value="">-- Pilih Pertemuan --</option>';
                document.getElementById('tableBody').innerHTML =
                    '<tr><td colspan="6" style="text-align: center; color: #999;">Pilih praktikum terlebih dahulu</td></tr>';
                return;
            }

            // Fetch pertemuan dari praktikum yang dipilih
            fetch(`/asisten/api/pretest/pertemuan/${praktikumId}`)
                .then(response => response.json())
                .then(data => {
                    pertemuanSelect.innerHTML = '<option value="">-- Pilih Pertemuan --</option>';
                    data.forEach(pertemuan => {
                        const option = document.createElement('option');
                        option.value = pertemuan.id;
                        option.textContent = `Pertemuan ${pertemuan.pertemuan_ke} - ${pertemuan.nama_pertemuan}`;
                        pertemuanSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function loadModulAndPretest() {
            const pertemuanId = document.getElementById('pertemuanSelect').value;
            const tableBody = document.getElementById('tableBody');

            if (!pertemuanId) {
                tableBody.innerHTML =
                    '<tr><td colspan="6" style="text-align: center; color: #999;">Pilih pertemuan terlebih dahulu</td></tr>';
                document.getElementById('modulInfo').style.display = 'none';
                return;
            }

            selectedPertemuanId = pertemuanId;

            // Load modul
            fetch(`/asisten/api/pretest/modul/${pertemuanId}`)
                .then(response => response.json())
                .then(modul => {
                    if (modul) {
                        selectedModulId = modul.id;
                        document.getElementById('modulName').textContent = modul.judul_modul;
                        document.getElementById('modulInfo').style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));

            // Load pretest
            fetch(`/asisten/api/pretest/get/${pertemuanId}`)
                .then(response => response.json())
                .then(data => {
                    pretestData = data;
                    loadPretestTable();
                })
                .catch(error => console.error('Error:', error));
        }

        function loadPretestTable() {
            const tableBody = document.getElementById('tableBody');
            const pertemuanSelect = document.getElementById('pertemuanSelect');
            const selectedPertemuan = pertemuanSelect.options[pertemuanSelect.selectedIndex];
            const praktikumId = document.getElementById('praktikumSelect').value;

            // Get praktikum name
            const praktikumSelect = document.getElementById('praktikumSelect');
            const selectedPraktikum = praktikumSelect.options[praktikumSelect.selectedIndex];

            // Get pertemuan data
            fetch(`/asisten/api/pretest/pertemuan/${praktikumId}`)
                .then(response => response.json())
                .then(data => {
                    const pertemuan = data.find(p => p.id == selectedPertemuanId);
                    if (!pertemuan) return;

                    let html = '';

                    if (pretestData) {
                        // Pretest already exists
                        const questionCount = pretestData.questions ? pretestData.questions.length : 0;
                        html = `
                            <tr>
                                <td>${selectedPraktikum.text}</td>
                                <td>Pertemuan ${pertemuan.pertemuan_ke}</td>
                                <td>${pretestData.judul_kuis}</td>
                                <td><span class="question-count">${questionCount} Soal</span></td>
                                <td><span class="status-badge generated">Sudah Generate</span></td>
                                <td class="actions-btn">
                                    <button class="btn-view" onclick="viewQuestions(${pretestData.id})">Lihat Soal</button>
                                    <button class="btn-delete" onclick="deletePretest(${pretestData.id})">Hapus</button>
                                </td>
                            </tr>
                        `;
                    } else {
                        // No pretest yet
                        html = `
                            <tr>
                                <td>${selectedPraktikum.text}</td>
                                <td>Pertemuan ${pertemuan.pertemuan_ke}</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="status-badge empty">Belum Generate</span></td>
                                <td class="actions-btn">
                                    <button class="btn-generate" onclick="openGeneratePopup()">
                                        <i class="fas fa-magic"></i> Generate Soal
                                    </button>
                                </td>
                            </tr>
                        `;
                    }

                    tableBody.innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        }

        function openGeneratePopup() {
            if (!selectedPertemuanId || !selectedModulId) {
                alert('Silakan pilih pertemuan terlebih dahulu');
                return;
            }

            const praktikumSelect = document.getElementById('praktikumSelect');
            const pertemuanSelect = document.getElementById('pertemuanSelect');
            const modulName = document.getElementById('modulName').textContent;

            document.getElementById('genPraktikum').value = praktikumSelect.options[praktikumSelect.selectedIndex].text;
            document.getElementById('genPertemuan').value = 'Pertemuan ' + pertemuanSelect.options[pertemuanSelect.selectedIndex].text;
            document.getElementById('genModul').value = modulName;

            document.getElementById('generatePopup').style.display = 'block';
        }

        function closeGeneratePopup() {
            document.getElementById('generatePopup').style.display = 'none';
            document.getElementById('loading').style.display = 'none';
            document.getElementById('generateForm').style.display = 'block';
        }

        function confirmGenerateSoal() {
            if (!selectedPertemuanId || !selectedModulId) {
                alert('Data tidak lengkap');
                return;
            }

            // Show loading
            document.getElementById('generateForm').style.display = 'none';
            document.getElementById('loading').style.display = 'block';

            // Call API to generate soal
            fetch(`/asisten/pretest/${selectedPertemuanId}/generate-soal`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_modul: selectedModulId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeGeneratePopup();
                        loadModulAndPretest();
                        showAlert('success', data.message);
                    } else {
                        closeGeneratePopup();
                        showAlert('error', data.message || 'Gagal generate soal');
                    }
                })
                .catch(error => {
                    closeGeneratePopup();
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat generate soal');
                });
        }

        function viewQuestions(pretestId) {
            fetch(`/asisten/pretest/${pretestId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.questions) {
                        const questions = data.data.questions;
                        let html = '';

                        questions.forEach((q, index) => {
                            html += `
                                <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                                    <p><strong>Soal ${index + 1}:</strong> ${q.question_text}</p>
                                    <p>A. ${q.option_a}</p>
                                    <p>B. ${q.option_b}</p>
                                    <p>C. ${q.option_c}</p>
                                    <p>D. ${q.option_d}</p>
                                    <p style="color: #2e7d32; font-weight: 600;">Jawaban Benar: ${q.correct_option}</p>
                                </div>
                            `;
                        });

                        document.getElementById('questionsList').innerHTML = html;
                        document.getElementById('viewQuestionsPopup').style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function closeViewPopup() {
            document.getElementById('viewQuestionsPopup').style.display = 'none';
        }

        function deletePretest(pretestId) {
            if (!confirm('Apakah Anda yakin ingin menghapus pretest ini?')) {
                return;
            }

            fetch(`/asisten/pretest/${pretestId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadModulAndPretest();
                        showAlert('success', data.message);
                    } else {
                        showAlert('error', data.message || 'Gagal menghapus pretest');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function showAlert(type, message) {
            const alertId = type === 'success' ? 'successAlert' : 'errorAlert';
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass}`;
            alertDiv.textContent = message;
            alertDiv.id = alertId;

            const container = document.querySelector('.main-content');
            const existingAlert = container.querySelector(`#${alertId}`);
            if (existingAlert) {
                existingAlert.remove();
            }

            container.insertBefore(alertDiv, container.querySelector('.header').nextElementSibling);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Close popups when clicking outside
        window.onclick = function(event) {
            let generatePopup = document.getElementById('generatePopup');
            let viewPopup = document.getElementById('viewQuestionsPopup');

            if (event.target == generatePopup) {
                generatePopup.style.display = 'none';
            }
            if (event.target == viewPopup) {
                viewPopup.style.display = 'none';
            }
        };

        // Auto-hide success/error alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');

            if (successAlert) {
                setTimeout(() => successAlert.remove(), 5000);
            }
            if (errorAlert) {
                setTimeout(() => errorAlert.remove(), 5000);
            }
        });
    </script>
</body>

</html>
