<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Pertemuan;
use App\Models\Praktikum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    /**
     * Display a listing of nilai resource.
     * - Praktikan  : hanya dapat melihat nilai miliknya sendiri.
     * - Asisten    : hanya dapat melihat nilai mahasiswa di praktikumnya.
     * - Dosen      : dapat melihat semua nilai untuk validasi.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        try {
            if ($user->role === 'Praktikan') {
                $nilais = Nilai::with(['pertemuan.jadwal.praktikum', 'user'])
                    ->where('id_user', $user->id)
                    ->get();

                return view('mahasiswa.nilai', compact('nilais', 'user'));
            }

            if ($user->role === 'Asisten') {
                return $this->indexAsisten($request, $user);
            }

            if ($user->role === 'Dosen') {
                return $this->indexDosen($user);
            }

            return response()->json(['success' => false, 'message' => 'Role tidak dikenali.'], 403);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching nilai.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilan nilai untuk Asisten (dengan filter).
     */
    private function indexAsisten(Request $request, $user)
    {
        [$jadwalIds, $praktikumIds, $pertemuanIds, $studentIds] = $this->getAsistenScope($user);

        $query = Nilai::with(['pertemuan.jadwal.praktikum', 'user'])
            ->whereIn('id_user', $studentIds)
            ->whereIn('id_pertemuan', $pertemuanIds);

        $this->applyNilaiFilters($query, $request);

        $nilais      = $query->orderBy('created_at', 'desc')->paginate(15);
        $praktikums  = Praktikum::whereIn('id', $praktikumIds)->get();
        $praktikumNames = $praktikums->pluck('nama_praktikum')->unique()->values();
        $pertemuans  = Pertemuan::whereIn('id', $pertemuanIds)->orderBy('pertemuan_ke')->get();

        return view('asisten.nilai_asisten', compact('nilais', 'praktikumNames', 'pertemuans', 'user'));
    }

    /**
     * Tampilan nilai untuk Dosen (validasi nilai).
     */
    private function indexDosen($user)
    {
        $nilais = Nilai::with(['pertemuan.praktikum', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $nilaiData = $nilais->through(fn($nilai) => [
            'id'          => $nilai->id,
            'nama'        => optional($nilai->user)->nama ?? '-',
            'nim'         => optional($nilai->user)->nomor_induk ?? '-',
            'matkul'      => optional($nilai->pertemuan?->praktikum)->nama_praktikum ?? '-',
            'kelas'       => optional($nilai->user)->angkatan ?? '-',
            'pretest'     => $nilai->nilai_pretest ?? 0,
            'laporan'     => $nilai->nilai_laporan ?? 0,
            'nilai_akhir' => $nilai->nilai_akhir ?? 0,
            'validated'   => $nilai->status === 'Terkonfirmasi',
            'status'      => $nilai->status ?? 'Pending',
        ]);

        $praktikums = Praktikum::all();

        return view('dosen.validasinilai', [
            'nilais'          => $nilaiData,
            'praktikums'      => $praktikums,
            'filterPraktikum' => 'all',
            'filterStatus'    => 'all',
            'user'            => $user,
        ]);
    }

    /**
     * Update multiple nilai records sekaligus.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'nilai_updates'                  => 'required|array',
            'nilai_updates.*.id'             => 'required|exists:nilais,id',
            'nilai_updates.*.nilai_pretest'  => 'nullable|integer|min:0|max:100',
            'nilai_updates.*.nilai_laporan'  => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $updatedCount = 0;

            foreach ($request->nilai_updates as $update) {
                $nilai = Nilai::find($update['id']);

                if ($nilai) {
                    $this->saveNilaiData($nilai, $update['nilai_pretest'] ?? null, $update['nilai_laporan'] ?? null);
                    $updatedCount++;
                }
            }

            return redirect()->route('addNilai')->with('success', "{$updatedCount} nilai berhasil disimpan!");

        } catch (\Exception $e) {
            return redirect()->route('addNilai')->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Rekap nilai untuk Asisten.
     */
    public function rekapNilai(Request $request)
    {
        $user = Auth::user();

        [$jadwalIds, $praktikumIds, $pertemuanIds, $studentIds] = $this->getAsistenScope($user);

        $query = Nilai::with(['pertemuan.jadwal.praktikum', 'user'])
            ->whereIn('id_user', $studentIds)
            ->whereIn('id_pertemuan', $pertemuanIds);

        $this->applyNilaiFilters($query, $request);

        $nilais = $query->orderBy('created_at', 'desc')->paginate(15);

        foreach ($nilais as $nilai) {
            $nilai->grade = $this->calculateGrade($nilai->nilai_akhir);
        }

        $praktikums     = Praktikum::whereIn('id', $praktikumIds)->get();
        $praktikumNames = $praktikums->pluck('nama_praktikum')->unique()->values();
        $pertemuans     = Pertemuan::whereIn('id', $pertemuanIds)->orderBy('pertemuan_ke')->get();

        return view('asisten.rekapNilai_asisten', compact('nilais', 'praktikumNames', 'pertemuans', 'user'));
    }

    /**
     * Update single nilai record.
     */
    public function updateNilai(Request $request, $id)
    {
        $request->validate([
            'nilai_pretest' => 'nullable|integer|min:0|max:100',
            'nilai_laporan' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $nilai = Nilai::findOrFail($id);

            $this->saveNilaiData($nilai, $request->nilai_pretest, $request->nilai_laporan);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nilai berhasil disimpan.',
                    'data'    => $nilai,
                ]);
            }

            return back()->with('success', 'Nilai berhasil disimpan!');

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan nilai: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Delete nilai record (web route).
     */
    public function destroyNilai($id)
    {
        try {
            Nilai::findOrFail($id)->delete();

            return redirect()->back()->with('success', 'Nilai berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus nilai: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // API Methods
    // =========================================================================

    public function store(Request $request)
    {
        if (Auth::user()->role === 'Praktikan') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only asisten, dosen, or admin can create nilai.',
            ], 403);
        }

        $validated = $request->validate([
            'id_pertemuan'  => 'required|integer|exists:pertemuans,id',
            'id_user'       => 'required|integer|exists:users,id',
            'nilai_pretest' => 'sometimes|integer|min:0|max:100',
            'nilai_laporan' => 'sometimes|integer|min:0|max:100',
            'nilai_total'   => 'sometimes|integer|min:0|max:100',
            'nilai_akhir'   => 'sometimes|integer|min:0|max:100',
            'komentar'      => 'sometimes|string',
            'status'        => 'sometimes|in:Pending,Terkonfirmasi',
        ]);

        try {
            $nilai = Nilai::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Nilai created successfully.',
                'data'    => $nilai->load('pertemuan.praktikum', 'user'),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating nilai.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        try {
            $nilai = Nilai::with(['pertemuan.praktikum', 'user'])->findOrFail($id);

            if ($user->role === 'Praktikan' && $nilai->id_user !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only view your own nilai.',
                ], 403);
            }

            return response()->json(['success' => true, 'data' => $nilai]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Nilai not found.'], 404);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->role === 'Praktikan') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only asisten, dosen, or admin can edit nilai.',
            ], 403);
        }

        try {
            $nilai = Nilai::findOrFail($id);

            return response()->json(['data' => $nilai]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Nilai not found.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'Praktikan') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only asisten, dosen, or admin can update nilai.',
            ], 403);
        }

        try {
            $nilai = Nilai::findOrFail($id);

            $validated = $request->validate([
                'id_pertemuan'  => 'sometimes|integer|exists:pertemuans,id',
                'id_user'       => 'sometimes|integer|exists:users,id',
                'nilai_pretest' => 'sometimes|integer|min:0|max:100',
                'nilai_laporan' => 'sometimes|integer|min:0|max:100',
                'nilai_total'   => 'sometimes|integer|min:0|max:100',
                'nilai_akhir'   => 'sometimes|integer|min:0|max:100',
                'komentar'      => 'sometimes|string',
                'status'        => 'sometimes|in:Pending,Terkonfirmasi',
            ]);

            $nilai->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Nilai updated successfully.',
                'data'    => $nilai->load('pertemuan.praktikum', 'user'),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Nilai not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating nilai.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'Praktikan') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only asisten, dosen, or admin can delete nilai.',
            ], 403);
        }

        try {
            Nilai::findOrFail($id)->delete();

            return response()->json(['success' => true, 'message' => 'Nilai deleted successfully.']);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Nilai not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting nilai.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getByPraktikum($idPraktikum)
    {
        $user = Auth::user();

        try {
            $query = Nilai::with(['pertemuan.praktikum', 'user'])
                ->whereHas('pertemuan', fn($q) => $q->where('id_praktikum', $idPraktikum));

            if ($user->role === 'Praktikan') {
                $query->where('id_user', $user->id);
            }

            return response()->json(['success' => true, 'data' => $query->get()]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching nilai.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getByUser($userId)
    {
        $user = Auth::user();

        if ($user->role === 'Praktikan' && $user->id !== (int) $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only view your own nilai.',
            ], 403);
        }

        try {
            $nilais = Nilai::with(['pertemuan.praktikum', 'user'])
                ->where('id_user', $userId)
                ->get();

            return response()->json(['success' => true, 'data' => $nilais]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching nilai.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function masterNilai(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Nilai::with(['pertemuan.modul', 'user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q2) => $q2
                    ->where('nama', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('nomor_induk', 'like', "%{$search}%")
                )->orWhereHas('pertemuan', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($status && in_array($status, ['Pending', 'Terkonfirmasi'])) {
            $query->where('status', $status);
        }

        $nilais = $query->orderBy('created_at', 'desc')->paginate(10);

        $statistics = [
            'rata_rata_nilai_akhir' => number_format(Nilai::avg('nilai_akhir') ?? 0, 2),
            'nilai_tertinggi'       => Nilai::max('nilai_akhir') ?? 0,
            'nilai_terendah'        => Nilai::min('nilai_akhir') ?? 0,
            'total_mahasiswa'       => Nilai::distinct('id_user')->count('id_user'),
            'total_terkonfirmasi'   => Nilai::where('status', 'Terkonfirmasi')->count(),
            'total_pending'         => Nilai::where('status', 'Pending')->count(),
        ];

        return view('laboran.kelolaNilai', compact('nilais', 'statistics'));
    }

    // =========================================================================
    // Private Helpers
    // =========================================================================

    /**
     * Ambil scope data (jadwal, praktikum, pertemuan, mahasiswa) milik Asisten.
     */
    private function getAsistenScope($user): array
    {
        $jadwalIds = DB::table('pendaftaran_praktikum')
            ->where('id_user', $user->id)
            ->where('role', 'Asisten')
            ->pluck('id_jadwal')
            ->toArray();

        $praktikumIds = DB::table('jadwals')
            ->whereIn('id', $jadwalIds)
            ->pluck('id_praktikum')
            ->unique()
            ->toArray();

        $pertemuanIds = Pertemuan::whereHas('jadwal', fn($q) => $q->whereIn('id_praktikum', $praktikumIds))
            ->pluck('id')
            ->toArray();

        $studentIds = DB::table('pendaftaran_praktikum')
            ->whereIn('id_jadwal', $jadwalIds)
            ->where('role', 'Praktikan')
            ->pluck('id_user')
            ->unique()
            ->toArray();

        return [$jadwalIds, $praktikumIds, $pertemuanIds, $studentIds];
    }

    /**
     * Terapkan filter umum pada query Nilai.
     */
    private function applyNilaiFilters($query, Request $request): void
    {
        if ($request->filled('praktikum')) {
            $query->whereHas('pertemuan.jadwal.praktikum', fn($q) => $q->where('nama_praktikum', $request->praktikum));
        }

        if ($request->filled('pertemuan_id')) {
            $query->where('id_pertemuan', $request->pertemuan_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q
                ->where('nama', 'like', "%{$search}%")
                ->orWhere('nomor_induk', 'like', "%{$search}%")
            );
        }
    }

    /**
     * Hitung dan simpan nilai pretest, laporan, total, dan akhir.
     */
    private function saveNilaiData(Nilai $nilai, $pretestInput, $laporanInput): void
    {
        $nilaiPretest = $pretestInput ?? $nilai->nilai_pretest;
        $nilaiLaporan = $laporanInput ?? $nilai->nilai_laporan;
        $nilaiTotal   = (int) round(($nilaiPretest + $nilaiLaporan) / 2);

        $nilai->update([
            'nilai_pretest' => $nilaiPretest,
            'nilai_laporan' => $nilaiLaporan,
            'nilai_total'   => $nilaiTotal,
            'nilai_akhir'   => $nilaiTotal,
            'status'        => 'Terkonfirmasi',
        ]);
    }

    /**
     * Konversi nilai angka ke huruf mutu.
     */
    private function calculateGrade(?int $nilai): string
    {
        if ($nilai === null) return '-';

        return match (true) {
            $nilai >= 85 => 'A',
            $nilai >= 80 => 'A-',
            $nilai >= 75 => 'B+',
            $nilai >= 70 => 'B',
            $nilai >= 65 => 'B-',
            $nilai >= 60 => 'C+',
            $nilai >= 55 => 'C',
            $nilai >= 50 => 'D',
            default      => 'E',
        };
    }
}