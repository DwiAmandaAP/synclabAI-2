<?php

namespace App\Http\Controllers;

use App\Models\Praktikum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenController extends Controller
{
    public function index(int $id)
    {
        $praktikum = Praktikum::with(['asisten'])->findOrFail($id);
        $allAsisten = User::where('role', 'asisten')->orderBy('nama')->get();
        $currentAsistenIds = $praktikum->asisten()->pluck('users.id')->toArray();

        return view('laboran.alokasiAsisten', compact('praktikum', 'allAsisten', 'currentAsistenIds'));
    }
    
    public function store(Request $request, int $id)
    {
        $praktikum = Praktikum::findOrFail($id);

        $request->validate([
            'asisten_ids' => 'array',
            'asisten_ids.*' => 'exists:users,id',
            'status' => 'nullable|string|in:active,inactive,pending',
        ]);

        $existingIds = $praktikum->asisten()->pluck('users.id')->toArray();
        $toAdd = array_diff($request->asisten_ids ?? [], $existingIds);
        $toRemove = array_diff($existingIds, $request->asisten_ids ?? []);

        if (!empty($toRemove)) {
            DB::table('pendaftaran_praktikum')->where('id_praktikum', $id)
                ->where('role', 'asisten')
                ->whereIn('id_user', $toRemove)
                ->delete();
        }

        foreach ($toAdd as $asistenId) {
            DB::table('pendaftaran_praktikum')->insert([
                'id_praktikum' => $id,
                'id_user' => $asistenId,
                'role' => 'asisten',
                'status' => 'Dikonfirmasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('masterPraktikum')
            ->with('success', 'Alokasi asisten berhasil diperbarui');
    }

    /**
     * Delete single asisten from praktikum
     */
    public function destroy(int $praktikumId, int $asistenId)
    {
        $deleted = DB::table('pendaftaran_praktikum')->where('id_praktikum', $praktikumId)
            ->where('id_user', $asistenId)
            ->where('role', 'asisten')
            ->delete();

        if ($deleted) {
            return redirect()->back()->with('success', 'Asisten berhasil dihapus dari alokasi');
        }

        return redirect()->back()->with('error', 'Gagal menghapus asisten');
    }
}
