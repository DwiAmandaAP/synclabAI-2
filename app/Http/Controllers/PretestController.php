<?php

namespace App\Http\Controllers;

use App\Models\Pretest;
use App\Models\Pertemuan;
use App\Models\Praktikum;
use App\Models\Modul;
use App\Models\Question;
use Illuminate\Http\Request;

class PretestController extends Controller
{
    /**
     * Tampilkan halaman manage pretest untuk asisten
     */
    public function addPretest()
    {
        // Ambil semua praktikum dengan pertemuan dan modul-nya
        $praktikums = Praktikum::with(['jadwals.pertemuans.modul', 'jadwals.pertemuans.pretest.questions'])->get();

        return view('asisten.managePretest_asisten', compact('praktikums'));
    }

    /**
     * Ambil pertemuan berdasarkan praktikum (untuk AJAX)
     */
    public function getPertemuanByPraktikum(Request $request)
    {
        $request->validate([
            'id_praktikum' => 'required|exists:praktikums,id',
        ]);

        $pertemuans = Pertemuan::where('id_praktikum', $request->id_praktikum)
            ->with(['modul', 'pretest.questions'])
            ->orderBy('pertemuan_ke')
            ->get();

        return response()->json($pertemuans);
    }

    /**
     * Ambil modul berdasarkan pertemuan (untuk AJAX)
     */
    public function getModulByPertemuan(Request $request)
    {
        $request->validate([
            'id_pertemuan' => 'required|exists:pertemuans,id',
        ]);

        $modul = Modul::where('id_pertemuan', $request->id_pertemuan)->first();

        return response()->json($modul);
    }

    /**
     * Ambil pretest dan questions berdasarkan pertemuan
     */
    public function getPretestByPertemuan(Request $request)
    {
        $request->validate([
            'id_pertemuan' => 'required|exists:pertemuans,id',
        ]);

        $pretest = Pretest::where('id_pertemuan', $request->id_pertemuan)
            ->with('questions')
            ->first();

        return response()->json($pretest);
    }

    /**
     * Store pretest (create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pertemuan' => 'required|exists:pertemuans,id',
            'judul_kuis' => 'required|string|max:255',
        ]);

        try {
            $pretest = Pretest::create($request->only(['id_pertemuan', 'judul_kuis']));

            return response()->json([
                'success' => true,
                'message' => 'Pretest berhasil dibuat!',
                'data' => $pretest,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update pretest
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_kuis' => 'required|string|max:255',
        ]);

        try {
            $pretest = Pretest::findOrFail($id);
            $pretest->update($request->only(['judul_kuis']));

            return response()->json([
                'success' => true,
                'message' => 'Pretest berhasil diperbarui!',
                'data' => $pretest,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus pretest beserta questions-nya
     */
    public function destroy($id)
    {
        try {
            $pretest = Pretest::findOrFail($id);
            $pretest->questions()->delete();
            $pretest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pretest berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show pretest details
     */
    public function show($id)
    {
        try {
            $pretest = Pretest::with(['pertemuan', 'questions'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pretest,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pretest tidak ditemukan.',
            ], 404);
        }
    }
}
