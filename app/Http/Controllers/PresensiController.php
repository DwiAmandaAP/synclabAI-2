<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Praktikum;
use App\Models\Pertemuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    /**
     * Main index to route users to their respective attendance views based on Role
     */
    public function index()
    {
        $user = Auth::user();

        try {
            // 1. Logic for Praktikan (Students)
            if ($user->role === 'Praktikan') {
                $presensis = Presensi::where('id_user', $user->id)
                    ->with(['pertemuan.praktikum', 'user'])
                    ->latest()
                    ->get();

                $presensiPerPraktikum = $presensis->groupBy(function ($presensi) {
                    return $presensi->pertemuan?->praktikum?->nama_praktikum ?? 'Unknown';
                });

                return view('mahasiswa.presensi', compact('presensis', 'presensiPerPraktikum'));
            }

            // 2. Logic for Dosen
            if ($user->role === 'Dosen') {
                $presensis = Presensi::with(['pertemuan.praktikum', 'user'])->latest()->paginate(25);
                return view('dosen.presensi', compact('presensis'));
            }

            // 3. Logic for Asisten (Show List of Managed Praktikum)
            if ($user->role === 'Asisten') {
                $jadwalIds = DB::table('pendaftaran_praktikum')
                    ->where('id_user', $user->id)
                    ->where('role', 'Asisten')
                    ->pluck('id_jadwal');

                $praktikumIds = DB::table('jadwals')
                    ->whereIn('id', $jadwalIds)
                    ->pluck('id_praktikum')
                    ->unique();

                $praktikums = Praktikum::whereIn('id', $praktikumIds)
                    ->with(['jadwals.pertemuan'])
                    ->get();

                $praktikumList = [];
                $counter = 1;

                foreach ($praktikums as $praktikum) {
                    foreach ($praktikum->jadwals as $jadwal) {
                        foreach ($jadwal->pertemuan as $pertemuan) {
                            $praktikumList[] = [
                                'no'             => $counter++,
                                'id'             => $praktikum->id,
                                'kode_praktikum' => $praktikum->kode_praktikum,
                                'nama_praktikum' => $praktikum->nama_praktikum,
                                'pertemuan_id'   => $pertemuan->id,
                                'pertemuan_ke'   => $pertemuan->pertemuan_ke,
                                'nama_pertemuan' => $pertemuan->nama_pertemuan,
                                'jadwal_id'      => $jadwal->id,
                                'hari'           => $jadwal->hari,
                                'jam_mulai'      => $jadwal->jam_mulai,
                                'jam_selesai'    => $jadwal->jam_selesai,
                            ];
                        }
                    }
                }

                return view('asisten.presensiSatu_asisten', compact('praktikumList'));
            }

            return abort(403, 'Role tidak dikenali');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data presensi: ' . $e->getMessage());
        }
    }

    /**
     * Display form for recording attendance (Asisten only)
     */
    public function recordAttendance(Request $request)
    {
        $pertemuanId = $request->get('pertemuan_id');
        $praktikumId = $request->get('praktikum_id');
        $user = Auth::user();

        // Security Check
        $hasAccess = DB::table('pendaftaran_praktikum')
            ->where('id_user', $user->id)
            ->where('id_praktikum', $praktikumId)
            ->where('role', 'Asisten')
            ->exists();

        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $pertemuan = Pertemuan::with(['jadwal.praktikum', 'jadwal.laboratorium'])
            ->findOrFail($pertemuanId);

        $praktikanIds = DB::table('pendaftaran_praktikum')
            ->where('id_praktikum', $praktikumId)
            ->where('role', 'Praktikan')
            ->pluck('id_user');

        $mahasiswas = User::whereIn('id', $praktikanIds)
            ->select('id', 'nomor_induk', 'nama')
            ->orderBy('nama')
            ->get();

        $existingPresensi = Presensi::where('id_pertemuan', $pertemuanId)
            ->get()
            ->keyBy('id_user');

        $attendanceData = $mahasiswas->map(function ($mahasiswa) use ($existingPresensi) {
            $presensi = $existingPresensi->get($mahasiswa->id);
            return [
                'user_id' => $mahasiswa->id,
                'nim'     => $mahasiswa->nomor_induk,
                'nama'    => $mahasiswa->nama,
                'status'  => $presensi ? $presensi->kehadiran : null,
            ];
        });

        return view('asisten.presensi_asisten', compact('pertemuan', 'attendanceData', 'praktikumId'));
    }

    /**
     * Save/Bulk Update Attendance
     */
    public function saveAttendance(Request $request)
    {
        $request->validate([
            'pertemuan_id' => 'required|exists:pertemuans,id',
            'praktikum_id' => 'required|exists:praktikums,id',
            'attendance'   => 'required|array',
        ]);

        foreach ($request->attendance as $userId => $status) {
            if ($status && in_array($status, ['Hadir', 'Izin', 'Sakit', 'Alpha'])) {
                Presensi::updateOrCreate(
                    ['id_user' => $userId, 'id_pertemuan' => $request->pertemuan_id],
                    ['kehadiran' => $status, 'status' => 'Dikonfirmasi']
                );
            }
        }

        return redirect()->route('riwayatPresensi')
            ->with('success', "Presensi berhasil disimpan!");
    }

    /**
     * Show summary of attendance history for Assistants
     */
    public function getHistoryPresensi()
    {
        $user = Auth::user();

        $jadwalIds = DB::table('pendaftaran_praktikum')
            ->where('id_user', $user->id)
            ->where('role', 'Asisten')
            ->pluck('id_jadwal');

        $praktikumIds = DB::table('jadwals')
            ->whereIn('id', $jadwalIds)
            ->pluck('id_praktikum')
            ->unique();

        $pertemuans = Pertemuan::with(['jadwal.praktikum', 'presensi'])
            ->whereHas('jadwal', function ($query) use ($praktikumIds) {
                $query->whereIn('id_praktikum', $praktikumIds);
            })
            ->orderBy('pertemuan_ke')
            ->get();

        $groupedPertemuans = $pertemuans->groupBy(fn($p) => $p->jadwal->praktikum->nama_praktikum)
            ->map(function ($items, $name) {
                $first = $items->first()->jadwal->praktikum;
                return [
                    'id_praktikum'   => $first->id,
                    'kode_praktikum' => $first->kode_praktikum,
                    'nama_praktikum' => $name,
                    'pertemuans'     => $items->map(fn($pertemuan) => [
                        'id'             => $pertemuan->id,
                        'pertemuan_ke'   => $pertemuan->pertemuan_ke,
                        'nama_pertemuan' => $pertemuan->nama_pertemuan,
                        'hari'           => $pertemuan->jadwal->hari,
                        'jam_mulai'      => $pertemuan->jadwal->jam_mulai,
                        'jam_selesai'    => $pertemuan->jadwal->jam_selesai,
                        'tanggal'        => $pertemuan->created_at,
                        'total_presensi' => $pertemuan->presensi->count(),
                        'hadir'          => $pertemuan->presensi->where('kehadiran', 'Hadir')->count(),
                        'izin'           => $pertemuan->presensi->where('kehadiran', 'Izin')->count(),
                        'sakit'          => $pertemuan->presensi->where('kehadiran', 'Sakit')->count(),
                        'alpha'          => $pertemuan->presensi->where('kehadiran', 'Alpha')->count(),
                    ])
                ];
            });

        return view('asisten.presensiDua_asisten', compact('groupedPertemuans'));
    }

    /**
     * API: Get attendance by Praktikum ID
     */
    public function getByPraktikum(int $idPraktikum)
    {
        try {
            $user = Auth::user();
            $query = Presensi::whereHas('pertemuan', fn($q) => $q->where('id_praktikum', $idPraktikum))
                ->with(['pertemuan.praktikum', 'user']);

            if ($user->role === 'Praktikan') {
                $query->where('id_user', $user->id);
            }

            return response()->json(['success' => true, 'data' => $query->get()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function viewAttendanceDetail(Request $request)
    {
        $pertemuanId = $request->get('pertemuan_id');
        $praktikumId = $request->get('praktikum_id');
        $user = Auth::user();
        
        // Verify access using ID
        $hasAccess = DB::table('pendaftaran_praktikum')
            ->where('id_user', $user->id)
            ->where('id_jadwal', $praktikumId)
            ->where('role', 'Asisten')
            ->exists();
        
        // if (!$hasAccess) {
        //     return redirect()->back()
        //         ->with('error', 'Akses ditolak');
        // }
        
        // Get pertemuan details
        $pertemuan = Pertemuan::with(['jadwal.praktikum', 'jadwal.laboratorium'])
            ->where('id', $pertemuanId)
            ->firstOrFail();
        
        // Get all praktikan using ID
        $praktikanIds = DB::table('pendaftaran_praktikum')
            ->where('id_jadwal', $praktikumId)
            ->where('role', 'Praktikan')
            ->where('status', 'Dikonfirmasi')
            ->pluck('id_user')
            ->toArray();
        
        $mahasiswas = User::whereIn('id', $praktikanIds)
            ->select('id', 'nomor_induk', 'nama')
            ->orderBy('nama')
            ->get();
        
        // Get existing presensi
        $existingPresensi = Presensi::where('id_pertemuan', $pertemuanId)
            ->get()
            ->keyBy('id_user');
        
        $attendanceData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $presensi = $existingPresensi->get($mahasiswa->id);
            $attendanceData[] = [
                'user_id' => $mahasiswa->id,
                'nim' => $mahasiswa->nomor_induk,
                'nama' => $mahasiswa->nama,
                'status' => $presensi ? $presensi->kehadiran : 'Alpha',
                'status_konfirmasi' => $presensi ? $presensi->status : 'Pending',
            ];
        }
        
        return view('asisten.detailPresensi', compact('pertemuan', 'attendanceData', 'praktikumId'));
    }

    /**
     * API: Delete Presensi
     */
    public function destroy($id)
    {
        if (Auth::user()->role === 'Praktikan') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        Presensi::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}