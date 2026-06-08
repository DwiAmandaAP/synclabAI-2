<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Modul;
use App\Models\Pertemuan;
use Illuminate\Http\Request;

class PertemuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pertemuans = Pertemuan::with(['jadwal', 'jadwal.praktikum', 'jadwal.laboratorium', 'modul'])
            ->when($request->search, function ($q, $search) {
                return $q->where('nama_pertemuan', 'like', "%{$search}%")
                    ->orWhere('deskripsi_pertemuan', 'like', "%{$search}%");
            })
            ->orderBy('pertemuan_ke', 'asc')
            ->paginate(15);

        $jadwals = Jadwal::with('praktikum')->get();
        $moduls = Modul::all();

        return view('laboran.kelolaPertemuan_lab', compact('pertemuans', 'jadwals', 'moduls'));
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
        $request->validate([
            'nama_pertemuan'      => 'required|string|max:255',
            'pertemuan_ke'        => 'required|integer|min:1',
            'deskripsi_pertemuan' => 'nullable|string',
            'id_jadwal'           => 'required|exists:jadwals,id',
        ]);

        $jadwal = Jadwal::findOrFail($request->id_jadwal);

        Pertemuan::create([
            'nama_pertemuan'      => $request->nama_pertemuan,
            'pertemuan_ke'        => $request->pertemuan_ke,
            'deskripsi_pertemuan' => $request->deskripsi_pertemuan,
            'id_jadwal'           => $request->id_jadwal,
            'id_praktikum'        => $jadwal->id_praktikum,
        ]);

        return redirect('admin/pertemuan')->with('success', 'Pertemuan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pertemuan $pertemuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pertemuan $pertemuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pertemuan = Pertemuan::findOrFail($id);

        $request->validate([
            'nama_pertemuan' => 'required|string|max:255',
            'pertemuan_ke' => 'required|integer|min:1',
            'deskripsi_pertemuan' => 'nullable|string',
            'id_jadwal' => 'nullable|exists:jadwals,id',
            'id_modul' => 'nullable|exists:moduls,id',
        ]);

        $pertemuan->update([
            'nama_pertemuan' => $request->nama_pertemuan,
            'pertemuan_ke' => $request->pertemuan_ke,
            'deskripsi_pertemuan' => $request->deskripsi_pertemuan,
            'id_jadwal' => $request->id_jadwal,
            'id_modul' => $request->id_modul,
        ]);

         return redirect('admin/pertemuan')->with('success', 'Pertemuan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pertemuan = Pertemuan::findOrFail($id);
        $pertemuan->delete();

        return redirect('admin/pertemuan')->with('success', 'Pertemuan berhasil dihapus');
    }
}
