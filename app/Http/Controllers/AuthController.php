<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Praktikum;
use App\Models\Jadwal;
use App\Models\Laporan;
use App\Models\Presensi;
use App\Models\Nilai;
use Illuminate\Support\Facades\DB;
use App\Models\Laboratorium;

class AuthController extends Controller
{
    function index()
    {
        return view('login');
    }

    public function welcome()
    {
        $mahasiswa = User::where('role', 'praktikan')->get()->count();
        $laboratorium = Laboratorium::latest()->take(4)->get();
        $praktikum = Praktikum::latest()
            ->take(9)
            ->withCount(['jadwals as praktikan_count' => function ($query) {
                $query->whereHas('pendaftarans', function ($q) {
                    $q->where('role', 'Praktikan');
                });
            }])
            ->with('moduls')
            ->get();
        $laboratoriumCount = Laboratorium::get()->count();
        $praktikumCount = Praktikum::get()->count();
        return view('landing', compact('mahasiswa', 'laboratorium', 'praktikum', 'laboratoriumCount', 'praktikumCount'));
    }

    function login(Request $request)
    {

        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email Harus Diisi!',
                'password.required' => 'Password Harus Diisi!'
            ]
        );

        $credetials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credetials)) {
            if (Auth::user()->role == 'Admin') {
                return redirect('/admin');
            } else if (Auth::user()->role == 'Praktikan') {
                return redirect('/mahasiswa');
            } else if (Auth::user()->role == 'Asisten') {
                return redirect('/asisten');
            } else if (Auth::user()->role == 'Dosen') {
                return redirect('/dosen');
            }
        } else {
            return redirect('/login')->withErrors('Username dan Password yang Dimasukkan Tidak Sesuai!')->withInput();
        }
    }

    function register()
    {
        return view('register');
    }

    function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nomor_induk' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique'        => 'Email ini sudah terdaftar, gunakan email lain.',
            'nomor_induk.unique'  => 'NIM/NIP ini sudah terdaftar.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'nama.required'       => 'Nama lengkap harus diisi.',
            'email.required'      => 'Email harus diisi.',
            'nomor_induk.required' => 'NIM/NIP harus diisi.',
            'password.required'   => 'Password harus diisi.',
        ]);

        $firstChar = substr($request->nomor_induk, 0, 1);
        $role = (in_array($firstChar, ['0', '1'])) ? 'Dosen' : 'Praktikan';

        User::create([
            'nomor_induk' => $request->nomor_induk,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role,
        ]);

        return redirect('/login')->with('register_success', true);
    }

    function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    function mahasiswa()
    {
        return view('mahasiswa/dashboard');
    }

    function dosen()
    {
        return view('dosen/dashboard');
    }

    function asisten()
    {
        $user = Auth::user();

        $hour = Carbon::now()->hour;
        if ($hour < 12) {
            $greeting = 'Pagi';
        } elseif ($hour < 18) {
            $greeting = 'Siang';
        } else {
            $greeting = 'Malam';
        }

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

        $praktikums = Praktikum::whereIn('id', $praktikumIds)
            ->with(['jadwals.laboratorium', 'jadwals.pertemuan'])
            ->get();

        $processedPraktikums = [];

        foreach ($praktikums as $praktikum) {
            $pendingLaporan = 0;
            $presensiPercent = 0;
            $nilaiPercent = 0;
            $presensiHadir = 0;
            $nilaiLengkap = 0;
            $totalPresensi = 0;
            $praktikumJadwalIds = DB::table('jadwals')
                ->where('id_praktikum', $praktikum->id)
                ->pluck('id')
                ->toArray();

            $totalMahasiswa = DB::table('pendaftaran_praktikum')
                ->whereIn('id_jadwal', $praktikumJadwalIds)
                ->where('role', 'Praktikan')
                ->count();

            // Get first pertemuan if exists
            $firstPertemuan = null;
            foreach ($praktikum->jadwals as $jadwal) {
                if ($jadwal->pertemuan) {
                    $firstPertemuan = $jadwal->pertemuan;
                    break;
                }
            }

            if ($firstPertemuan) {
                $pendingLaporan = DB::table('pengumpulan_laporan')
                    ->where('id_pertemuan', $firstPertemuan->first()->id)
                    ->whereIn('status', ['Disubmit', 'Dalam Review'])
                    ->count();

                $presensiHadir = Presensi::where('id_pertemuan', $firstPertemuan->first()->id)
                    ->where('kehadiran', 'Hadir')
                    ->count();

                $totalPresensi = Presensi::where('id_pertemuan', $firstPertemuan->first()->id)->count();
                $presensiPercent = $totalPresensi > 0 ? round(($presensiHadir / $totalPresensi) * 100) : 0;

                $nilaiLengkap = DB::table('nilais')
                    ->where('id_pertemuan', $firstPertemuan->first()->id)
                    ->whereNotNull('nilai_akhir')
                    ->count();
                $nilaiPercent = $totalMahasiswa > 0 ? round(($nilaiLengkap / $totalMahasiswa) * 100) : 0;
            }

            $processedPraktikums[] = [
                'id' => $praktikum->id,
                'nama_praktikum' => $praktikum->nama_praktikum,
                'kode_praktikum' => $praktikum->kode_praktikum,
                'angkatan' => $praktikum->angkatan,
                'semester' => $praktikum->semester,
                'hari' => $praktikum->jadwals->first()?->hari ?? 'N/A',
                'jam_mulai' => $praktikum->jadwals->first()?->jam_mulai ?? 'N/A',
                'jam_selesai' => $praktikum->jadwals->first()?->jam_selesai ?? 'N/A',
                'laboratorium_nama' => $praktikum->jadwals->first()?->laboratorium?->nama_laboratorium ?? 'N/A',
                'total_mahasiswa' => $totalMahasiswa,
                'pending_laporan' => $pendingLaporan,
                'presensi_hadir' => $presensiHadir,
                'presensi_percent' => $presensiPercent,
                'nilai_lengkap' => $nilaiLengkap,
                'nilai_percent' => $nilaiPercent,
                'total_presensi' => $totalPresensi,
                'has_active_jadwal' => $praktikum->jadwals->where('status', 'Dibuka')->count() > 0,
                'jadwals' => $praktikum->jadwals,
            ];
        }

        $today = Carbon::now()->locale('id')->dayName;

        $dayMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $todayIndo = $dayMapping[$today] ?? $today;

        $todayJadwals = Jadwal::with(['praktikum', 'laboratorium', 'pertemuan'])
            ->whereIn('id_praktikum', $praktikumIds)
            ->where('hari', $todayIndo)
            ->orderBy('jam_mulai')
            ->get();

        if ($todayJadwals->isEmpty()) {
            $todayJadwals = Jadwal::with(['praktikum', 'laboratorium', 'pertemuan'])
                ->whereIn('id_praktikum', $praktikumIds)
                ->where('hari', $today)
                ->orderBy('jam_mulai')
                ->get();
        }

        $processedJadwals = [];

        foreach ($todayJadwals as $jadwal) {
            $pendingLaporan = 0;
            $presensiHadir = 0;
            $totalPresensi = 0;
            $presensiPercent = 0;

            if ($jadwal->pertemuan) {
                $pendingLaporan = DB::table('pengumpulan_laporan')
                    ->where('id_pertemuan', $jadwal->pertemuan->first()->id)
                    ->whereIn('status', ['Disubmit', 'Dalam Review'])
                    ->count();

                $presensiHadir = Presensi::where('id_pertemuan', $jadwal->pertemuan->first()->id)
                    ->where('kehadiran', 'Hadir')
                    ->count();

                $totalPresensi = Presensi::where('id_pertemuan', $jadwal->pertemuan->first()->id)->count();
                $presensiPercent = $totalPresensi > 0 ? round(($presensiHadir / $totalPresensi) * 100) : 0;
            }

            $totalMahasiswa = DB::table('pendaftaran_praktikum')
                ->where('id_jadwal', $jadwal->id)
                ->where('role', 'Praktikan')
                ->count();

            $processedJadwals[] = [
                'id' => $jadwal->id,
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
                'status' => $jadwal->status,
                'praktikum' => $jadwal->praktikum,
                'laboratorium' => $jadwal->laboratorium,
                'pertemuan' => $jadwal->pertemuan,
                'pending_laporan' => $pendingLaporan,
                'presensi_hadir' => $presensiHadir,
                'total_presensi' => $totalPresensi,
                'presensi_percent' => $presensiPercent,
                'total_mahasiswa' => $totalMahasiswa,
            ];
        }

        $stats = [
            'total_praktikum' => count($processedPraktikums),
            'ongoing_jadwal' => collect($processedJadwals)->where('status', 'Dibuka')->count(),
            'upcoming_jadwal' => count($processedJadwals),
        ];

        // Get notifications
        $notifications = [];

        $totalPendingLaporan = collect($processedPraktikums)->sum('pending_laporan');

        if ($totalPendingLaporan > 0) {
            $notifications[] = [
                'type' => 'yellow',
                'icon' => 'fas fa-file-alt',
                'message' => "Ada {$totalPendingLaporan} laporan yang perlu direview"
            ];
        }

        $todayJadwalCount = count($processedJadwals);
        if ($todayJadwalCount > 0) {
            $notifications[] = [
                'type' => 'blue',
                'icon' => 'fas fa-calendar-day',
                'message' => "Hari ini ada {$todayJadwalCount} jadwal praktikum"
            ];
        }

        return view('asisten.dashboard_asistent', compact(
            'user',
            'greeting',
            'processedPraktikums',
            'processedJadwals',
            'stats',
            'notifications'
        ));
    }

    function admin(Request $request)
    {
        $jadwalFilter = $request->get('jadwal_filter', 'hari_ini');
        $praktikumId = $request->get('praktikum_id');
        $pertemuanId = $request->get('pertemuan_id');

        // Basic counts
        $totalPraktikum = Praktikum::count();
        $totalAsisten = User::where('role', 'Asisten')->count();
        $totalPraktikan = User::where('role', 'Praktikan')->count();
        $totalPendaftaran = DB::table('pendaftaran_praktikum')->where('role', 'Praktikan')->count();

        // Today's schedule
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l');
        $jadwalHariIni = Jadwal::where('hari', $hariIni)->count();

        // Presensi stats
        $rataPresensi = Presensi::count() > 0 ? (Presensi::where('kehadiran', 'Hadir')->count() / Presensi::count() * 100) : 0;
        $presensiHariIni = Presensi::whereDate('created_at', Carbon::today())->get();
        $presensiHadir = $presensiHariIni->where('kehadiran', 'Hadir')->count();
        $totalPraktikanHariIni = $presensiHariIni->count();
        $presensiHariIniPersen = $totalPraktikanHariIni > 0 ? ($presensiHadir / $totalPraktikanHariIni * 100) : 0;

        // Nilai stats
        $rataNilai = Nilai::avg('nilai_akhir') ?? 0;
        $nilaiTertinggi = Nilai::max('nilai_akhir') ?? 0;
        $nilaiTerendah = Nilai::min('nilai_akhir') ?? 0;

        // Pengumpulan Laporan stats
        $totalLaporan = Laporan::count();
        $laporanTerkumpul = DB::table('pengumpulan_laporan')->whereIn('status', ['Disubmit', 'Ditandai Selesai', 'Dalam Review', 'Diterima'])->count();
        $laporanTerlambat = DB::table('pengumpulan_laporan')->where('status', 'Terlambat')->count();
        $rataPengumpulan = $totalLaporan > 0 ? ($laporanTerkumpul / $totalLaporan * 100) : 0;

        // Warnings
        $warnings = [];
        if ($laporanTerlambat > 0) {
            $warnings[] = "$laporanTerlambat laporan terlambat";
        }
        $jadwalPenuh = Jadwal::where('status', 'Penuh')->count();
        if ($jadwalPenuh > 0) {
            $warnings[] = "$jadwalPenuh jadwal sudah penuh";
        }

        $lastUpdate = Carbon::now()->format('H:i') . ' WIB';

        // Praktikum list
        $praktikums = Praktikum::latest()
            ->limit(6)
            ->get();

        // Jadwal query
        $jadwalQuery = Jadwal::with(['praktikum', 'laboratorium', 'dosen']);

        if ($jadwalFilter == 'hari_ini') {
            $jadwalQuery->where('hari', $hariIni);
        }

        $jadwals = $jadwalQuery->orderBy('jam_mulai', 'asc')->get();

        $bentrokCount = 0;
        $warningsJadwal = null;
        $labSchedule = [];

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

        $jadwalGrouped = $jadwals->groupBy('hari');

        $asistenAktif = User::where('role', 'Asisten')
            ->withCount('jadwals')
            ->latest()
            ->limit(5)
            ->get();

        $asistenMengajar = $asistenAktif->filter(function ($a) use ($hariIni) {
            return $a->jadwals->where('hari', $hariIni)->count() > 0;
        })->count();

        return view('laboran/dashboard_lab', compact(
            'totalPraktikum',
            'totalPendaftaran',
            'totalAsisten',
            'totalPraktikan',
            'jadwalHariIni',
            'rataPresensi',
            'presensiHariIniPersen',
            'presensiHadir',
            'totalPraktikanHariIni',
            'rataNilai',
            'nilaiTertinggi',
            'nilaiTerendah',
            'rataPengumpulan',
            'laporanTerkumpul',
            'totalLaporan',
            'laporanTerlambat',
            'warnings',
            'lastUpdate',
            'praktikums',
            'jadwalGrouped',
            'bentrokCount',
            'warningsJadwal',
            'asistenAktif',
            'asistenMengajar',
            'jadwalFilter',
            'hariIni'
        ));
    }
}
