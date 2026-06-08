<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Praktikum;
use App\Models\PendaftaranPraktikum;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PraktikumController extends Controller
{
    /**
     * Display listing for Laboran (Admin Lab)
     */
    public function index(Request $request)
    {
        $praktikums = Praktikum::when($request->search, function ($query, $search) {
            return $query->where('kode_praktikum', 'like', "%{$search}%")
                ->orWhere('nama_praktikum', 'like', "%{$search}%");
        })
        ->with(['jadwals.laboratorium'])
        ->paginate(15);

        // Appends helper data to the collection
        $praktikums->getCollection()->transform(function ($praktikum) {
            $praktikum->asisten = $praktikum->getAsistens(); 
            $praktikum->mahasiswa = $praktikum->getPraktikans();
            return $praktikum;
        });

        $dosens = User::where('role', 'Dosen')->get();

        return view('laboran.kelolaPraktikum_lab', compact('praktikums', 'dosens'));
    }

    /**
     * Store a newly created praktikum
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_praktikum' => 'required|string|max:255|unique:praktikums',
            'id_dosen' => 'required|string',
            'nama_praktikum' => 'required|string|max:255',
            'angkatan'       => 'required|string|max:255',
            'semester'       => 'required|string|max:255',
        ], [
            'kode_praktikum.unique' => 'Kode Ini Telah Dipakai, Harap Menggunakan Kode Lain.',
        ]);

        Praktikum::create($validated);

        return redirect('/admin/praktikum')->with('success', 'Praktikum berhasil ditambahkan!');
    }

    /**
     * Update the specified praktikum
     */
    public function update(Request $request, int $id)
    {
        $praktikum = Praktikum::findOrFail($id);

        $validated = $request->validate([
            'kode_praktikum' => 'required|string|max:255|unique:praktikums,kode_praktikum,' . $id,
            'nama_praktikum' => 'required|string|max:255',
            'angkatan'       => 'required|string|max:255',
            'semester'       => 'required|string|max:255',
        ]);

        $praktikum->update($validated);

        return redirect('/admin/praktikum')->with('success', 'Praktikum berhasil diperbarui!');
    }

    /**
     * Remove the specified praktikum
     */
    public function destroy(int $id)
    {
        Praktikum::findOrFail($id)->delete();
        return redirect('/admin/praktikum')->with('success', 'Praktikum dihapus.');
    }

    /**
     * Show registration page for Students
     */
    public function pendaftaranShow()
    {
        $user = Auth::user();
        
        $praktikums = Praktikum::with(['jadwals.laboratorium', 'jadwals.dosen'])->get();
        
        $pendaftarans = PendaftaranPraktikum::with([
            'jadwal.praktikum', 
            'jadwal.laboratorium', 
            'jadwal.dosen',
            'jadwal.pertemuan.modul'
        ])
        ->where('id_user', $user->id)
        ->get();

        return view('mahasiswa.pendaftaran', compact('praktikums', 'pendaftarans'));
    }

    /**
     * Logic for Student Registration to a Schedule
     */
    public function daftarJadwal(Request $request)
    {
        $request->validate(['id_jadwal' => 'required|integer|exists:jadwals,id']);

        $user   = Auth::user();
        $jadwal = Jadwal::withCount(['pendaftarans' => function($q) {
            $q->where('role', 'Praktikan');
        }])->findOrFail($request->id_jadwal);

        // 1. Status Check
        if (!in_array($jadwal->status, ['Dibuka', 'Penuh'])) {
            return response()->json(['success' => false, 'message' => 'Jadwal sudah tidak dapat didaftari.'], 422);
        }

        // 2. Quota Check
        if ($jadwal->jumlah_max_peserta > 0 && $jadwal->pendaftarans_count >= $jadwal->jumlah_max_peserta) {
            return response()->json(['success' => false, 'message' => 'Kuota jadwal sudah penuh.'], 422);
        }

        // 3. Duplicate Meeting Check
        $sudahDaftar = PendaftaranPraktikum::where('id_user', $user->id)
            ->whereHas('jadwal', fn($q) => $q->where('id_pertemuan', $jadwal->id_pertemuan))
            ->exists();

        if ($sudahDaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar di pertemuan ini.',
            ], 422);
        }

        // 4. Create Registration
        PendaftaranPraktikum::create([
            'id_jadwal' => $jadwal->id,
            'id_user'   => $user->id,
            'role'      => 'Praktikan',
        ]);

        // 5. Auto-update status if full
        if ($jadwal->jumlah_max_peserta > 0 && ($jadwal->pendaftarans_count + 1) >= $jadwal->jumlah_max_peserta) {
            $jadwal->update(['status' => 'Penuh']);
        }

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil!']);
    }

    /**
     * Get Student's own Praktikum Dashboard (Categorized)
     */
    public function getMyPraktikum()
    {
        $user = Auth::user();
        $idJadwalTerdaftar = PendaftaranPraktikum::where('id_user', $user->id)->pluck('id_jadwal');
        $idPraktikumTerdaftar = Jadwal::whereIn('id', $idJadwalTerdaftar)->pluck('id_praktikum')->unique();

        // 1. ACTIVE: Registered + currently open/full
        $activePraktikums = Praktikum::whereIn('id', $idPraktikumTerdaftar)
            ->whereHas('jadwals', fn($q) => $q->whereIn('status', ['Dibuka', 'Penuh']))
            ->with(['jadwals.pertemuan.modul', 'jadwals.laboratorium', 'jadwals.dosen'])
            ->get();

        // 2. UPCOMING: Not registered yet + currently open
        $upcomingPraktikums = Praktikum::whereNotIn('id', $idPraktikumTerdaftar)
            ->whereHas('jadwals', fn($q) => $q->where('status', 'Dibuka'))
            ->with(['jadwals' => fn($q) => $q->where('status', 'Dibuka'), 'jadwals.pendaftarans'])
            ->get();

        // 3. COMPLETED: Registered + all schedules marked finished
        $completedPraktikums = Praktikum::whereIn('id', $idPraktikumTerdaftar)
            ->whereDoesntHave('jadwals', fn($q) => $q->whereIn('status', ['Dibuka', 'Penuh']))
            ->with(['jadwals.pertemuan.modul', 'jadwals.laboratorium'])
            ->get();

        return view('mahasiswa.praktikum', compact('activePraktikums', 'upcomingPraktikums', 'completedPraktikums'));
    }

    /**
     * Assistant View: Managed Praktikums and Daily Schedule
     */
    public function asistensiPraktikum()
    {
        $user = Auth::user();

        // Get schedules where user is assigned as Assistant
        $jadwalIds = PendaftaranPraktikum::where('id_user', $user->id)
            ->where('role', 'Asisten')
            ->pluck('id_jadwal')
            ->toArray();

        $praktikumIds = Jadwal::whereIn('id', $jadwalIds)->pluck('id_praktikum')->unique()->toArray();

        $praktikums = Praktikum::whereIn('id', $praktikumIds)
            ->with(['jadwals.laboratorium', 'jadwals.pertemuan'])
            ->get()
            ->map(function ($p) {
                $p->mahasiswa_count = PendaftaranPraktikum::whereIn('id_jadwal', $p->jadwals->pluck('id'))
                    ->where('role', 'Praktikan')->count();
                return $p;
            });

        // Handle Daily Schedule Logic
        $todayIndo = Carbon::now()->locale('id')->dayName; 
        
        $todayJadwals = Jadwal::with(['praktikum', 'laboratorium', 'pertemuan'])
            ->withCount(['pendaftarans as mahasiswa_count' => fn($q) => $q->where('role', 'Praktikan')])
            ->whereIn('id_praktikum', $praktikumIds)
            ->where('hari', $todayIndo)
            ->orderBy('jam_mulai')
            ->get();

        $praktikumNames = $praktikums->pluck('nama_praktikum')->unique()->values();

        return view('asisten.praktikum_asisten', compact('praktikums', 'todayJadwals', 'praktikumNames'));
    }

    public function monitoringPraktikum()
    {
        return view('dosen.monitoring');
    }

    public function cekStatusPendaftaran()
    {
        return view('dosen.statuspendaftaran');
    }
}