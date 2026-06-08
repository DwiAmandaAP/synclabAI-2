<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Praktikum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Laboratorium;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jadwal::with(['praktikum', 'laboratorium', 'dosen']);
        
        if ($request->praktikum_id) {
            $query->where('id_praktikum', $request->praktikum_id);
        }
        
        if ($request->hari) {
            $query->where('hari', $request->hari);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('hari', 'like', "%{$request->search}%")
                  ->orWhereHas('praktikum', function($sub) use ($request) {
                      $sub->where('nama_praktikum', 'like', "%{$request->search}%");
                  })
                  ->orWhereHas('laboratorium', function($sub) use ($request) {
                      $sub->where('nama_laboratorium', 'like', "%{$request->search}%");
                  });
            });
        }
        
        $jadwals = $query->orderBy('hari', 'asc')
                         ->orderBy('jam_mulai', 'asc')
                         ->paginate(15);
        
        $praktikums = Praktikum::all();
        $laboratoriums = Laboratorium::all();
        $dosens = User::where('role', 'Dosen')->get();
        
        return view('laboran.kelolaJadwal_lab', compact('jadwals', 'praktikums', 'laboratoriums', 'dosens'));
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
            'id_praktikum' => 'required|exists:praktikums,id',
            'id_dosen'=>'required|exists:users,id',
            'id_laboratorium' => 'required|exists:laboratoriums,id',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'jumlah_max_peserta'=>'required',
            'status' => 'nullable|in:Dibuka,Penuh,Selesai',
        ]);
        
        Jadwal::create($request->all());
        
        return redirect()->route('masterJadwal')->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'id_praktikum' => 'sometimes|exists:praktikums,id',
            'id_dosen'=>'sometimes|exists:users,id',
            'id_laboratorium' => 'sometimes|exists:laboratoriums,id',
            'hari' => 'sometimes',
            'jam_mulai' => 'sometimes',
            'jam_selesai' => 'sometimes',
            'jumlah_max_peserta'=>'sometimes',
            'status' => 'in:Dibuka,Penuh,Selesai',
        ]);
        
        $jadwal->update($request->all());
        
        return redirect()->route('masterJadwal')->with('success', 'Jadwal berhasil diupdate');
        }
        
        /**
         * Remove the specified resource from storage.
        */
        public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        
        return redirect()->route('masterJadwal')->with('success', 'Jadwal berhasil dihapus');
    }
}
