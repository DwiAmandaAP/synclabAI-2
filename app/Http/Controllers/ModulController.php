<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    private function getAsistenPraktikumIds(int $userId): array
    {
        $jadwalIds = DB::table('pendaftaran_praktikum')
            ->where('id_user', $userId)
            ->where('role', 'Asisten')
            ->pluck('id_jadwal')
            ->toArray();

        return DB::table('jadwals')
            ->whereIn('id', $jadwalIds)
            ->pluck('id_praktikum')
            ->unique()
            ->toArray();
    }

    public function addModul()
    {
        $user = Auth::user();
        $praktikumIds = $this->getAsistenPraktikumIds($user->id);
        $moduls = Pertemuan::with(['jadwal.praktikum', 'modul'])
            ->whereHas('jadwal', function ($q) use ($praktikumIds) {
                $q->whereIn('id_jadwal', $praktikumIds);
            })
            ->orderBy('pertemuan_ke')
            ->get();

        $pertemuans = $moduls;

        return view('asisten.manageModules_asisten', compact('moduls', 'pertemuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pertemuan'  => 'required|exists:pertemuans,id',
            'judul_modul'   => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'file'          => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $path = $request->file('file')->store('moduls', 'public');

        Modul::create([
            'id_pertemuan' => $request->id_pertemuan,
            'judul_modul'  => $request->judul_modul,
            'deskripsi'    => $request->deskripsi,
            'filepath'     => $path,
        ]);

        return redirect()->route('addModul')->with('success', 'Modul berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'id_pertemuan' => 'required|exists:pertemuans,id',
            'judul_modul'  => 'required|string|max:255',
            'deskripsi'    => 'required|string',
            'file'         => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $modul = Modul::findOrFail($id);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($modul->filepath);
            $modul->filepath = $request->file('file')->store('moduls', 'public');
        }

        $modul->update([
            'id_pertemuan' => $request->id_pertemuan,
            'judul_modul'  => $request->judul_modul,
            'deskripsi'    => $request->deskripsi,
            'filepath'     => $modul->filepath,
        ]);

        return redirect()->route('addModul')->with('success', 'Modul berhasil diupdate.');
    }

    public function destroy(int $id)
    {
        $modul = Modul::findOrFail($id);
        Storage::disk('public')->delete($modul->filepath);
        $modul->delete();

        return redirect()->route('addModul')->with('success', 'Modul berhasil dihapus.');
    }
}
