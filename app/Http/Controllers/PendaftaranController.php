<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\PendaftaranPraktikum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{

    public function store(Request $request)
{
    $jadwal = Jadwal::findOrFail($request->id_jadwal);

    // Cek apakah sudah terdaftar di praktikum yang sama
    if (PendaftaranPraktikum::sudahTerdaftarDiPraktikum(auth()->id , $jadwal->id_praktikum)) {
        return back()->withErrors(['msg' => 'Anda sudah terdaftar di jadwal lain untuk praktikum ini.']);
    }

    PendaftaranPraktikum::create([
        'id_jadwal' => $request->id_jadwal,
        'id_user'   => auth()->id,
        'role'      => 'Praktikan',
    ]);

    return redirect()->back()->with('success', 'Pendaftaran berhasil.');
}
    public function masterPendaftaran(Request $request)
    {
       $search = $request->get('search');
        $status = $request->get('status');
        $role   = $request->get('role');

        // pendaftaran_praktikum -> jadwals -> praktikums
        $query = DB::table('pendaftaran_praktikum')
            ->leftJoin('jadwals',    'pendaftaran_praktikum.id_jadwal', '=', 'jadwals.id')
            ->leftJoin('praktikums', 'jadwals.id_praktikum',            '=', 'praktikums.id')
            ->leftJoin('users',      'pendaftaran_praktikum.id_user',   '=', 'users.id')
            ->select(
                'pendaftaran_praktikum.id',
                'pendaftaran_praktikum.id_jadwal',
                'pendaftaran_praktikum.id_user',
                'pendaftaran_praktikum.role',
                'pendaftaran_praktikum.created_at',
                'pendaftaran_praktikum.updated_at',

                // dari jadwals
                'jadwals.hari',
                'jadwals.jam_mulai',
                'jadwals.jam_selesai',

                // dari praktikums (via jadwals)
                'praktikums.id        as id_praktikum',
                'praktikums.kode_praktikum',
                'praktikums.nama_praktikum',
                'praktikums.angkatan',
                'praktikums.semester',

                // dari users
                'users.nomor_induk    as user_nomor_induk',
                'users.nama           as user_nama',
                'users.email          as user_email',
                'users.nohp           as user_nohp'
            );

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama',                  'like', "%{$search}%")
                  ->orWhere('users.nomor_induk',         'like', "%{$search}%")
                  ->orWhere('praktikums.nama_praktikum', 'like', "%{$search}%")
                  ->orWhere('praktikums.kode_praktikum', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($role && in_array($role, ['Praktikan', 'Asisten', 'Dosen'])) {
            $query->where('pendaftaran_praktikum.role', $role);
        }

        $pendaftarans = $query
            ->orderBy('pendaftaran_praktikum.created_at', 'desc')
            ->paginate(10)
            ->through(function ($item) {
                $item->id           = (int) $item->id;
                $item->id_jadwal    = (int) $item->id_jadwal;
                $item->id_user      = (int) $item->id_user;
                $item->id_praktikum = (int) $item->id_praktikum;
                return $item;
            });

        // Statistik — scope tetap ke seluruh tabel, bukan hanya halaman ini
        $statistics = [
            'total_pendaftaran'   => DB::table('pendaftaran_praktikum')->count(),
            'total_praktikan'     => DB::table('pendaftaran_praktikum')->where('role', 'Praktikan')->count(),
            'total_asisten_dosen' => DB::table('pendaftaran_praktikum')->whereIn('role', ['Asisten', 'Dosen'])->count(),
        ];

        return view('laboran.kelolaPendaftaran', compact('pendaftarans', 'statistics'));
    }

    
}
