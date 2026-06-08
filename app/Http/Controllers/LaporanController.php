<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Laporan;
use App\Models\Nilai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\Pertemuan;
use App\Http\Controllers\Controller;
use App\Models\PengumpulanLaporan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get jadwal IDs where user is Asisten
        $jadwalIds = DB::table('pendaftaran_praktikum')
            ->where('id_user', $user->id)
            ->where('role', 'Asisten')
            ->pluck('id_jadwal')
            ->toArray();

        // Get praktikum IDs from those jadwals
        $praktikumIds = DB::table('jadwals')
            ->whereIn('id', $jadwalIds)
            ->pluck('id_praktikum')
            ->unique()
            ->toArray();

        // Get all meetings for those praktikums
        $pertemuans = Pertemuan::whereHas('jadwal', function ($query) use ($praktikumIds) {
            $query->whereIn('id_praktikum', $praktikumIds);
        })->get();

        // Get all pengumpulan laporan with relations
        $query = PengumpulanLaporan::with(['user', 'pertemuan.jadwal.praktikum']);

        // Apply filters
        if ($request->has('praktikum') && $request->praktikum) {
            $query->whereHas('pertemuan.jadwal.praktikum', function ($q) use ($request) {
                $q->where('nama_praktikum', $request->praktikum);
            });
        }

        if ($request->has('pertemuan_id') && $request->pertemuan_id) {
            $query->where('id_pertemuan', $request->pertemuan_id);
        }

        if ($request->has('status') && $request->status) {
            $statusMap = [
                'diterima' => 'Diterima',
                'revisi' => 'Ditolak',
                'pending' => 'Disubmit'
            ];
            $query->where('status', $statusMap[$request->status] ?? $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('nomor_induk', 'like', "%{$search}%");
                });
            });
        }

        $pengumpulanLaporans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get unique praktikum names for filter dropdown
        $praktikumNames = Pertemuan::whereHas('jadwal', function ($query) use ($praktikumIds) {
            $query->whereIn('id_praktikum', $praktikumIds);
        })->with('jadwal.praktikum')->get()->map(function ($pertemuan) {
            return $pertemuan->jadwal->praktikum->nama_praktikum;
        })->unique()->values();

        return view('asisten/laporan_asisten', compact('pengumpulanLaporans', 'pertemuans', 'praktikumNames'));
    }

    public function updateLaporan(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'nullable|string',
            'nilai' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:diterima,revisi,pending'
        ]);

        try {
            $pengumpulan = PengumpulanLaporan::findOrFail($id);

            $statusMap = [
                'diterima' => 'Diterima',
                'revisi' => 'Ditolak',
                'pending' => 'Dalam Review'
            ];

            $pengumpulan->update([
                'komentar' => $request->komentar,
                'nilai' => $request->nilai,
                'status' => $statusMap[$request->status]
            ]);

            // Also update Nilai table if needed
            $nilai = Nilai::where('id_pertemuan', $pengumpulan->id_pertemuan)
                ->where('id_user', $pengumpulan->id_user)
                ->first();

            if ($nilai) {
                $nilai->update([
                    'nilai_laporan' => $request->nilai,
                    'komentar' => $request->komentar,
                    'status' => 'Terkonfirmasi'
                ]);
            }

            return redirect()->route('nilaiLaporan')->with('success', 'Laporan berhasil direview!');
        } catch (\Exception $e) {
            return redirect()->route('nilaiLaporan')->with('error', 'Gagal mereview laporan: ' . $e->getMessage());
        }
    }

    public function showLaporan($id)
    {
        $pengumpulan = PengumpulanLaporan::with(['user', 'pertemuan.jadwal.praktikum', 'pertemuan.laporan'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pengumpulan->id,
                'nama' => $pengumpulan->user->nama,
                'nim' => $pengumpulan->user->nomor_induk,
                'praktikum' => $pengumpulan->pertemuan->jadwal->praktikum->nama_praktikum,
                'pertemuan_ke' => $pengumpulan->pertemuan->pertemuan_ke,
                'nama_pertemuan' => $pengumpulan->pertemuan->nama_pertemuan,
                'tanggal' => $pengumpulan->created_at->format('d-m-Y'),
                'file_path' => $pengumpulan->file_path,
                'keterangan' => $pengumpulan->keterangan,
                'komentar' => $pengumpulan->komentar,
                'nilai' => $pengumpulan->nilai,
                'status' => $pengumpulan->status
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Laporan $laporan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laporan $laporan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Laporan $laporan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laporan $laporan)
    {
        //
    }

    public function masterLaporan()
    {
        $nilaiLatest = Nilai::with('user')->latest()->limit(5)->get();
        $nilai = Nilai::all();
        $bentrokCount = 0;
        $labSchedule = [];

        $presKon = Presensi::where('status', 'Dikonfirmasi')->count();
        $presAll = Presensi::all()->count();

        $persenPresen = ($presKon / ($presAll > 0 ? $presAll : 1)) * 100;

        $nilaiKon = Nilai::where('status', 'Dikonfirmasi')->count();
        $nilaiAll = Nilai::all()->count();


        $persenNilai = ($nilaiKon / ($nilaiAll > 0 ? $nilaiAll : 1)) * 100;

        $jadwalQuery = Jadwal::with(['praktikum', 'laboratorium', 'dosen']);
        $jadwals = $jadwalQuery->orderBy('jam_mulai', 'asc')->get();

        foreach ($jadwals as $jadwal) {
            $key = $jadwal->jam_mulai . '-' . $jadwal->id_laboratorium;
            if (isset($labSchedule[$key])) {
                $jadwal->bentrok = true;
                $bentrokCount++;
                $warningsJadwal = "Lab {$jadwal->laboratorium->nama_laboratorium} digunakan 2 jadwal di jam yang sama";
            } else {
                $jadwal->bentrok = false;
                $labSchedule[$key] = true;
            }
        }

        return view('laboran/laporan_lab', compact('nilai', 'nilaiLatest', 'bentrokCount', 'persenPresen', 'persenNilai'));
    }

    public function adminShowLaporan(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        // Query menggunakan DB Builder untuk pengumpulan_laporan
        $query = DB::table('pengumpulan_laporan')
            ->leftJoin('pertemuans', 'pengumpulan_laporan.id_pertemuan', '=', 'pertemuans.id')
            ->leftJoin('users', 'pengumpulan_laporan.id_user', '=', 'users.id')
            ->select(
                'pengumpulan_laporan.*',
                'pertemuans.nama_pertemuan as pertemuan_nama',
                'users.nama as user_name',
                'users.nomor_induk as user_nomor_induk'
            );

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('users.nomor_induk', 'like', "%{$search}%")
                    ->orWhere('pertemuans.nama_pertemuan', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status) {
            $query->where('pengumpulan_laporan.status', $status);
        }

        $pengumpulanLaporans = $query->orderBy('pengumpulan_laporan.created_at', 'desc')
            ->paginate(10)
            ->through(function ($item) {
                // Convert to object with proper casting
                $item->id = (int) $item->id;
                $item->id_pertemuan = (int) $item->id_pertemuan;
                $item->id_user = (int) $item->id_user;
                $item->nilai = $item->nilai ? (float) $item->nilai : null;
                return $item;
            });

        // Get data for dropdowns
        $pertemuans = Pertemuan::all();
        $mahasiswas = User::where('role', 'mahasiswa')->get();

        return view('laboran.kelolaLaporan', compact('pengumpulanLaporans', 'pertemuans', 'mahasiswas'));
    }
}
