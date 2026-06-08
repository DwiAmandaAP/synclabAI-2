<?php

use App\Http\Controllers\AsistenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\PretestController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PertemuanController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AiController;

Route::get('/', [AuthController::class, 'welcome']);

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirect berdasarkan role
    Route::get('/mahasiswa', [MahasiswaController::class, 'dashboard'])->middleware('checkRole:Praktikan')->name('mahasiswa');
    Route::get('/dosen', [DosenController::class, 'index'])->middleware('checkRole:Dosen')->name('dosen');
    Route::get('/asisten', [AuthController::class, 'asisten'])->middleware('checkRole:Asisten')->name('asisten');
    Route::get('/admin', [AuthController::class, 'admin'])->middleware('checkRole:Admin')->name('admin');

    // ==========================================
    // MAHASISWA ROUTES
    // ==========================================
    Route::prefix('mahasiswa')->middleware('checkRole:Praktikan')->group(function () {
        Route::get('/pendaftaran', [PraktikumController::class, 'pendaftaranShow'])->name('praktikum');
        Route::get('/praktikum', [PraktikumController::class, 'getMyPraktikum'])->name('pendaftaran');
        Route::post('/praktikum/daftar', [PraktikumController::class, 'daftarJadwal']);
        
        // Modul Routes
        Route::get('/modul', [MahasiswaController::class, 'getMyModul'])->name('modul');
        Route::get('/modul/{id_pertemuan}', [MahasiswaController::class, 'showModul'])->name('modul.show');
        
        // Flashcard Routes
        Route::get('/flashcard', [MahasiswaController::class, 'getMyFlashcard'])->name('flashcard');
        Route::get('/flashcard/{id_pertemuan}', [MahasiswaController::class, 'showFlashcardByPertemuan'])->name('flashcard.show');
        Route::post('/flashcard/{id}/review', [AiController::class, 'reviewFlashcard'])->name('ai.flashcard.review');
        Route::post('/flashcard/{id_modul}/generate', [AiController::class, 'generateFlashcard'])->name('ai.generateFlashcard');
        
        // Pretest Routes
        Route::get('/pretest', [MahasiswaController::class, 'getMyPretest'])->name('pretest');
        Route::get('/pretest/questions/{pertemuanId}', [MahasiswaController::class, 'showPretestQuestions'])->name('pretest.questions');
        Route::post('/pretest/submit/{pertemuanId}', [MahasiswaController::class, 'submitPretestAnswers'])->name('pretest.submit');
        Route::post('/pretest/absen/{modulId}', [MahasiswaController::class, 'absenPretest'])->name('pretest.absen');
        Route::post('/pretest/start/{modulId}', [MahasiswaController::class, 'startPretest'])->name('pretest.start');
        Route::post('/pretest/upload/{modulId}', [MahasiswaController::class, 'uploadLaporan'])->name('pretest.upload');
        
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai');
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi');
        Route::get('/riwayat', [MahasiswaController::class, 'getMyHistory'])->name('riwayat');
    });

    // ==========================================
    // DOSEN ROUTES
    // ==========================================
    Route::prefix('dosen')->middleware('checkRole:Dosen')->group(function () {
        Route::get('/dosen', [DosenController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [DosenController::class, 'monitoring'])->name('monitoring');
        Route::get('/presensi', [DosenController::class, 'presensi'])->name('dosenPresensi');
        Route::put('/presensi/{id}', [DosenController::class, 'updatePresensi']);
        Route::get('/validasi-nilai', [DosenController::class, 'validasiNilai'])->name('validasinilai');
        Route::post('/validasi-nilai/{id}', [DosenController::class, 'storeValidasiNilai']);
        Route::get('/status-pendaftaran', [DosenController::class, 'statusPendaftaran'])->name('statuspendaftaran');
        Route::get('/manage-asisten', [DosenController::class, 'manageAsisten'])->name('manageAsisten');
        Route::post('/manage-asisten', [DosenController::class, 'storeAsisten']);
        Route::put('/manage-asisten/{id}', [DosenController::class, 'updateAsisten']);
        Route::delete('/manage-asisten/{id}', [DosenController::class, 'destroyAsisten']);
        Route::get('/manage-laboran', [DosenController::class, 'manageLaboran'])->name('manageLaboran');
        Route::post('/manage-laboran', [DosenController::class, 'storeLaboran']);
        Route::put('/manage-laboran/{id}', [DosenController::class, 'updateLaboran']);
        Route::delete('/manage-laboran/{id}', [DosenController::class, 'destroyLaboran']);
    });

    // ==========================================
    // ASISTEN ROUTES
    // ==========================================
    Route::prefix('asisten')->middleware('checkRole:Asisten')->group(function () {
        Route::get('/praktikum', [PraktikumController::class, 'asistensiPraktikum'])->name('asistensi');
        Route::get('/presensi', [PresensiController::class, 'index'])->name('konfirmasiPresensi');
        Route::get('/presensi/detail', [PresensiController::class, 'recordAttendance'])->name('detailPresensi');
        Route::get('/presensi/history', [PresensiController::class, 'getHistoryPresensi'])->name('riwayatPresensi');
        Route::get('/presensi/history/detail', [PresensiController::class, 'viewAttendanceDetail'])->name('detailRiwayatPresensi');
        Route::post('/presensi/save', [PresensiController::class, 'saveAttendance'])->name('savePresensi');
        Route::get('/modul', [ModulController::class, 'addModul'])->name('addModul');
        Route::post('/modul', [ModulController::class, 'store'])->name('storeModul');
        Route::put('/modul/{id}', [ModulController::class, 'update'])->name('updateModul');
        Route::delete('/modul/{id}', [ModulController::class, 'destroy'])->name('deleteModul');
        
        // Pretest Management Routes
        Route::get('/pretest', [PretestController::class, 'addPretest'])->name('addPretest');
        Route::post('/pretest', [PretestController::class, 'store'])->name('storePretest');
        Route::put('/pretest/{id}', [PretestController::class, 'update'])->name('updatePretest');
        Route::delete('/pretest/{id}', [PretestController::class, 'destroy'])->name('destroyPretest');
        Route::get('/pretest/{id}', [PretestController::class, 'show'])->name('showPretest');
        
        // AJAX endpoints untuk pretest
        Route::get('/api/pretest/pertemuan/{id_praktikum}', [PretestController::class, 'getPertemuanByPraktikum']);
        Route::get('/api/pretest/modul/{id_pertemuan}', [PretestController::class, 'getModulByPertemuan']);
        Route::get('/api/pretest/get/{id_pertemuan}', [PretestController::class, 'getPretestByPertemuan']);
        
        // AI Generate endpoints untuk asisten
        Route::post('/modul/{id}/generate-ai', [AiController::class, 'generateFromModul'])->name('ai.generate');
        Route::post('/pretest/{id_pertemuan}/generate-soal', [AiController::class, 'generateSoal'])->name('ai.generateSoal');
        

        Route::get('/laporan', [LaporanController::class, 'index'])->name('nilaiLaporan');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('nilaiLaporan');
        Route::post('/laporan/update/{id}', [LaporanController::class, 'updateLaporan'])->name('updateLaporan');
        Route::get('/laporan/detail/{id}', [LaporanController::class, 'showLaporan'])->name('detailLaporan');

        Route::get('/nilai', [NilaiController::class, 'index'])->name('addNilai');
        Route::post('/nilai/bulk-update', [NilaiController::class, 'bulkUpdate'])->name('bulkUpdateNilai');
        Route::post('/nilai/update/{id}', [NilaiController::class, 'updateNilai'])->name('updateNilai');
        Route::get('/nilai/rekap', [NilaiController::class, 'rekapNilai'])->name('rekapNilai');

        Route::get('/mahasiswa', [MahasiswaController::class, 'getMahasiswa'])->name('seeMahasiswa');
    });

    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    Route::prefix('admin')->middleware('checkRole:Admin')->group(function () {
        Route::get('/praktikum', [PraktikumController::class, 'index'])->name('masterPraktikum');
        Route::post('/praktikum', [PraktikumController::class, 'store'])->name('addPraktikum');
        Route::put('/praktikum/{id}', [PraktikumController::class, 'update'])->name('updatePraktikum');
        Route::delete('/praktikum/{id}', [PraktikumController::class, 'destroy'])->name('deletePraktikum');
        Route::get('/user', [UserController::class, 'index'])->name('masterUser');
        Route::post('/user', [UserController::class, 'store'])->name('addUser');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('updateUser');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('deleteUser');
        Route::get('/laboratorium', [LaboratoriumController::class, 'index'])->name('masterLaboratorium');
        Route::post('/laboratorium', [LaboratoriumController::class, 'store'])->name('addLaboratorium');
        Route::put('/laboratorium/{id}', [LaboratoriumController::class, 'update'])->name('updateLaboratorium');
        Route::delete('/laboratorium/{id}', [LaboratoriumController::class, 'destroy'])->name('deleteLaboratorium');
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('masterJadwal');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('addJadwal');
        Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('updateJadwal');
        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('deleteJadwal');
        Route::get('/pertemuan', [PertemuanController::class, 'index'])->name('masterPertemuan');
        Route::post('/pertemuan', [PertemuanController::class, 'store'])->name('addPertemuan');
        Route::put('/pertemuan/{id}', [PertemuanController::class, 'update'])->name('updatePertemuan');
        Route::delete('/pertemuan/{id}', [PertemuanController::class, 'destroy'])->name('deletePertemuan');
        Route::get('/asisten/{id}', [AsistenController::class, 'index'])->name('alokasiAsisten');
        Route::post('/asisten/{id}', [AsistenController::class, 'store'])->name('storeAlokasiAsisten');
        Route::delete('/asisten/{praktikumId}/{asistenId}', [AsistenController::class, 'destroy'])->name('deleteAlokasiAsisten');
        Route::get('/laporan', [LaporanController::class, 'masterLaporan'])->name('masterLaporan');
        Route::get('/pengumpulanLaporan', [LaporanController::class, 'adminShowLaporan'])->name('kelolaLaporan');
        Route::get('/nilai', [NilaiController::class, 'masterNilai'])->name('kelolaNilai');
        Route::get('/pendaftaran', [PendaftaranController::class, 'masterPendaftaran'])->name('kelolaPendaftaran');
    });

    // ==========================================
    // API ROUTES
    // ==========================================
    Route::prefix('api')->group(function () {
        // Laboratorium
        Route::get('/laboratorium', [LaboratoriumController::class, 'index']);
        Route::get('/laboratorium/{id}', [LaboratoriumController::class, 'show']);
        Route::post('/laboratorium', [LaboratoriumController::class, 'store']);
        Route::put('/laboratorium/{id}', [LaboratoriumController::class, 'update']);
        Route::delete('/laboratorium/{id}', [LaboratoriumController::class, 'destroy']);

        // Nilai
        Route::get('/nilai/{id}', [NilaiController::class, 'show']);
        Route::post('/nilai', [NilaiController::class, 'store']);
        Route::put('/nilai/{id}', [NilaiController::class, 'update']);
        Route::delete('/nilai/{id}', [NilaiController::class, 'destroy']);
        Route::get('/nilai/praktikum/{idPraktikum}', [NilaiController::class, 'getByPraktikum']);
        Route::get('/nilai/user/{userId}', [NilaiController::class, 'getByUser']);

        // Presensi
        Route::get('/presensi', [PresensiController::class, 'index']);
        Route::get('/presensi/{id}', [PresensiController::class, 'show']);
        Route::post('/presensi', [PresensiController::class, 'store']);
        Route::put('/presensi/{id}', [PresensiController::class, 'update']);
        Route::delete('/presensi/{id}', [PresensiController::class, 'destroy']);
        Route::get('/presensi/praktikum/{idPraktikum}', [PresensiController::class, 'getByPraktikum']);
        Route::get('/presensi/user/{userId}', [PresensiController::class, 'getByUser']);
    });
});