<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Praktikum;
use App\Models\Pertemuan;
use App\Models\Jadwal;
use App\Models\PendaftaranPraktikum;
use App\Models\Nilai;
use App\Models\Presensi;
use App\Models\PengumpulanLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Display dashboard with real data from database
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get dosen's praktikums
        $praktikums = Praktikum::all();
        
        // Get all registered students
        $totalMahasiswa = User::where('role', 'Praktikan')->count();
        
        // Get praktikum statistics
        $aktivePraktikums = [];
        $validationSummary = [];
        
        foreach ($praktikums as $praktikum) {
            // Count registered students
            $registeredCount = PendaftaranPraktikum::whereHas('jadwal', function($q) use ($praktikum) {
                $q->where('id_praktikum', $praktikum->id);
            })->where('role', 'Praktikan')->distinct('id_user')->count('id_user');
            
            // Count validation status
            $validatedCount = Nilai::whereHas('pertemuan', function($q) use ($praktikum) {
                $q->where('id_praktikum', $praktikum->id);
            })->where('status', 'Tervalidasi')->count();

            $pendingCount = Nilai::whereHas('pertemuan', function($q) use ($praktikum) {
                $q->where('id_praktikum', $praktikum->id);
            })->where(function($q) {
                $q->whereNull('status')->orWhere('status', 'Pending');
            })->count();

            
            // Today's attendance stats
            $today = now()->toDateString();
            $todayPresences = Presensi::whereHas('pertemuan', function($q) use ($praktikum, $today) {
                $q->where('id_praktikum', $praktikum->id);
            })->whereDate('created_at', $today)->get();
            
            $hadir = $todayPresences->where('kehadiran', 'Hadir')->count();
            $izin = $todayPresences->where('kehadiran', 'Izin')->count();
            $sakit = $todayPresences->where('kehadiran', 'Sakit')->count();
            $alpha = $todayPresences->where('kehadiran', 'Alpha')->count();
            
            $aktivePraktikums[] = [
                'nama_praktikum' => $praktikum->nama_praktikum,
                'kode_praktikum' => $praktikum->kode_praktikum,
                'total_mahasiswa' => $registeredCount,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'gradient' => $this->getGradientByPraktikum($praktikum->kode_praktikum),
            ];
            
            $validationSummary[] = [
                'nama_praktikum' => $praktikum->nama_praktikum,
                'kode_praktikum' => $praktikum->kode_praktikum,
                'tervalidasi' => $validatedCount,
                'belum' => $pendingCount,
            ];
        }
        
        // Pending validation count
        $menungguValidasi = Nilai::where(function($q) {
            $q->whereNull('status')->orWhere('status', 'Pending');
        })->count();
        // Attendance data per class and course
        $presenceData = $this->getPresenceDataByClassAndCourse();
        
        // Pretest performance data
        $pretestData = $this->getPretestDataByClassAndCourse();
        
        return view('dosen.dashboard', compact(
            'totalMahasiswa',
            'aktivePraktikums',
            'validationSummary',
            'menungguValidasi',
            'presenceData',
            'pretestData'
        ));
    }
    
    /**
     * Get presence data grouped by class and course
     */
    private function getPresenceDataByClassAndCourse()
    {
        $data = [];
        $classes = ['2022', '2023', '2024']; // Based on nomor_induk year
        
        foreach ($classes as $kelas) {
            $data[$kelas] = [];
            
            $praktikums = Praktikum::all();
            foreach ($praktikums as $praktikum) {
                // Get presences for this class and praktikum
                $presences = Presensi::whereHas('user', function($q) use ($kelas) {
                    $q->where('nomor_induk', 'like', $kelas . '%');
                })
                ->whereHas('pertemuan', function($q) use ($praktikum) {
                    $q->where('id_praktikum', $praktikum->id);
                })
                ->get();
                
                $total = $presences->count();
                $hadir = $presences->where('kehadiran', 'Hadir')->count();
                $izin = $presences->where('kehadiran', 'Izin')->count();
                $sakit = $presences->where('kehadiran', 'Sakit')->count();
                $alpha = $presences->where('kehadiran', 'Alpha')->count();
                
                $percent = $total > 0 ? round(($hadir / $total) * 100) : 0;
                
                $data[$kelas][$praktikum->nama_praktikum] = [
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,
                    'total' => $total,
                    'percent' => $percent,
                ];
            }
        }
        
        return $data;
    }
    
    /**
     * Get pretest data grouped by class and course
     */
    private function getPretestDataByClassAndCourse()
    {
        $data = [];
        $classes = ['2022', '2023', '2024']; // Based on nomor_induk year
        
        foreach ($classes as $kelas) {
            $data[$kelas] = [];
            
            $praktikums = Praktikum::all();
            foreach ($praktikums as $praktikum) {
                // Get nilai pretest for this class and praktikum
                $nilais = Nilai::whereHas('pertemuan', function($q) use ($praktikum) {
                    $q->where('id_praktikum', $praktikum->id);
                })
                ->whereHas('user', function($q) use ($kelas) {
                    $q->where('nomor_induk', 'like', $kelas . '%');
                })
                ->orderByRaw('(SELECT pertemuan_ke FROM pertemuans WHERE pertemuans.id = nilais.id_pertemuan)')
                ->get()
                ->groupBy(function($item) {
                    return $item->pertemuan->pertemuan_ke;
                });
                
                $pretestAverages = [];
                for ($i = 1; $i <= 6; $i++) {
                    $avg = $nilais->get($i)?->avg('nilai_pretest') ?? 0;
                    $pretestAverages[] = round($avg);
                }
                
                $data[$kelas][$praktikum->nama_praktikum] = $pretestAverages;
            }
        }
        
        return $data;
    }
    
    /**
     * Get gradient color based on praktikum code
     */
    private function getGradientByPraktikum($kodePraktikum)
    {
        $gradients = [
            'PD2401' => 'linear-gradient(135deg, #8b5cf6, #6d28d9)',
            'SD2402' => 'linear-gradient(135deg, #06b6d4, #0891b2)',
            'BD2403' => 'linear-gradient(135deg, #10b981, #047857)',
            'JARKOM2404' => 'linear-gradient(135deg, #f59e0b, #b45309)',
            'SO2405' => 'linear-gradient(135deg, #ec4899, #be185d)',
            'PCD2406' => 'linear-gradient(135deg, #f97316, #c2410c)',
            'IOT2407' => 'linear-gradient(135deg, #3b82f6, #1e40af)',
            'RPL2408' => 'linear-gradient(135deg, #14b8a6, #0d9488)',
            'WEB2401' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
        ];
        
        return $gradients[$kodePraktikum] ?? 'linear-gradient(135deg, #6b7280, #4b5563)';
    }

    /**
     * Monitoring praktikum page with detailed data
     */
    public function monitoring(Request $request)
    {
        $filterPraktikum = $request->get('praktikum', 'all');
        $filterKelas = $request->get('kelas', 'all');
        $filterPertemuan = $request->get('pertemuan', 'all');
        
        // Build query
        $query = Presensi::with('user', 'pertemuan.praktikum')
            ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                    $qp->where('nama_praktikum', $filterPraktikum);
                });
            })
            ->when($filterPertemuan != 'all', function($q) use ($filterPertemuan) {
                return $q->whereHas('pertemuan', function($qp) use ($filterPertemuan) {
                    $qp->where('pertemuan_ke', $filterPertemuan);
                });
            });
        
        $presences = $query->paginate(15);
        
        // Get attendance percentage
        $totalPresences = $presences->total();
        $hadirCount = Presensi::where('kehadiran', 'Hadir')
            ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                    $qp->where('nama_praktikum', $filterPraktikum);
                });
            })
            ->count();
        
        $kehadiranPercent = $totalPresences > 0 ? round(($hadirCount / $totalPresences) * 100) : 0;
        
        // Get average nilai
        $rataNilai = Nilai::when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                    $qp->where('nama_praktikum', $filterPraktikum);
                });
            })
            ->when($filterPertemuan != 'all', function($q) use ($filterPertemuan) {
                return $q->whereHas('pertemuan', function($qp) use ($filterPertemuan) {
                    $qp->where('pertemuan_ke', $filterPertemuan);
                });
            })
            ->avg('nilai_pretest');
        
        $rataNilai = round($rataNilai ?? 0);
        
        // Get laporan completion
        $laporanSelesai = PengumpulanLaporan::where('status', 'Diterima')
            ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                    $qp->where('nama_praktikum', $filterPraktikum);
                });
            })
            ->count();
        
        // Get nilai chart data
        $nilaiChartData = $this->getNilaiChartDataByClass($filterPraktikum, $filterPertemuan);
        
        // Get all praktikums for filter
        $praktikums = Praktikum::all()->pluck('nama_praktikum')->unique();
        $kehadiranChartData = $this->getAttendanceChartDataByClass($filterPraktikum, $filterPertemuan);

        return view('Dosen.monitoring', compact(
            'presences',
            'kehadiranPercent',
            'rataNilai',
            'laporanSelesai',
            'kehadiranChartData',
            'nilaiChartData',
            'praktikums',
            'filterPraktikum',
            'filterKelas',
            'filterPertemuan'
        ));
    }
    
    /**
     * Get nilai chart data by class
     */
    private function getNilaiChartDataByClass($filterPraktikum = 'all', $filterPertemuan = 'all')
    {
        $classes = ['2022', '2023', '2024'];
        $data = [];
        
        foreach ($classes as $kelas) {
            $nilais = Nilai::whereHas('user', function($q) use ($kelas) {
                    $q->where('nomor_induk', 'like', $kelas . '%');
                })
                ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                    return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                        $qp->where('nama_praktikum', $filterPraktikum);
                    });
                })
                ->when($filterPertemuan != 'all', function($q) use ($filterPertemuan) {
                    return $q->whereHas('pertemuan', function($qp) use ($filterPertemuan) {
                        $qp->where('pertemuan_ke', $filterPertemuan);
                    });
                })
                ->get()
                ->groupBy(function($item) {
                    return $item->pertemuan->pertemuan_ke;
                });
            
            $averages = [];
            for ($i = 1; $i <= 6; $i++) {
                $avg = $nilais->get($i)?->avg('nilai_pretest') ?? 0;
                $averages[] = round($avg);
            }
            
            $data[$kelas] = $averages;
        }
        
        return $data;
    }
    
    /**
     * Get attendance chart data by class
     */
    private function getAttendanceChartDataByClass($filterPraktikum = 'all', $filterPertemuan = 'all')
    {
        $classes = ['2022', '2023', '2024'];
        $data = [];
        
        foreach ($classes as $kelas) {
            $presences = Presensi::whereHas('user', function($q) use ($kelas) {
                    $q->where('nomor_induk', 'like', $kelas . '%');
                })
                ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                    return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                        $qp->where('nama_praktikum', $filterPraktikum);
                    });
                })
                ->when($filterPertemuan != 'all', function($q) use ($filterPertemuan) {
                    return $q->whereHas('pertemuan', function($qp) use ($filterPertemuan) {
                        $qp->where('pertemuan_ke', $filterPertemuan);
                    });
                })
                ->get();
            
            $hadir = $presences->where('kehadiran', 'Hadir')->count();
            $izin = $presences->where('kehadiran', 'Izin')->count();
            $sakit = $presences->where('kehadiran', 'Sakit')->count();
            $alpha = $presences->where('kehadiran', 'Alpha')->count();
            
            $data[$kelas] = [
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
            ];
        }
        
        return $data;
    }

    /**
     * Presensi page
     */
    public function presensi(Request $request)
    {
        $filterPraktikum = $request->get('praktikum', 'all');
        $filterPertemuan = $request->get('pertemuan', '1');
        
        $presences = Presensi::with('user', 'pertemuan.praktikum')
            ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                    $qp->where('nama_praktikum', $filterPraktikum);
                });
            })
            ->when($filterPertemuan != 'all', function($q) use ($filterPertemuan) {
                return $q->whereHas('pertemuan', function($qp) use ($filterPertemuan) {
                    $qp->where('pertemuan_ke', $filterPertemuan);
                });
            })
            ->paginate(20);
        
        $praktikums = Praktikum::all();
        
        return view('Dosen.presensi', compact('presences', 'praktikums', 'filterPraktikum', 'filterPertemuan'));
    }
    
    /**
     * Update presensi status
     */
    public function updatePresensi(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        
        $request->validate([
            'kehadiran' => 'required|in:Hadir,Izin,Sakit,Alpha',
            'komentar' => 'nullable|string|max:500',
        ]);
        
        $presensi->update([
            'kehadiran' => $request->kehadiran,
            'komentar' => $request->komentar,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Presensi berhasil diperbarui']);
    }

    /**
     * Validasi nilai page
     */
    public function validasiNilai(Request $request)
    {
        $filterPraktikum = $request->get('praktikum', 'all');
        $filterStatus = $request->get('status', 'pending');
        
        $nilais = Nilai::with('user', 'pertemuan.praktikum')
            ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                return $q->whereHas('pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                    $qp->where('nama_praktikum', $filterPraktikum);
                });
            })
            ->when($filterStatus == 'pending', function($q) {
                return $q->where(function($query) {
                    $query->whereNull('status')->orWhere('status', 'Pending');
                });
            })
            ->when($filterStatus == 'validated', function($q) {
                return $q->where('status', 'Tervalidasi');
            })
            ->paginate(20);
        
        $praktikums = Praktikum::all();
        
        return view('Dosen.validasinilai', compact('nilais', 'praktikums', 'filterPraktikum', 'filterStatus'));
    }
    
    /**
     * Store validated nilai
     */
    public function storeValidasiNilai(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);
        
        $request->validate([
            'nilai_akhir' => 'required|numeric|min:0|max:100',
            'komentar' => 'nullable|string|max:500',
        ]);
        
        $nilai->update([
            'nilai_akhir' => $request->nilai_akhir,
            'komentar' => $request->komentar,
            'status' => 'Tervalidasi',
        ]);
        
        return response()->json(['success' => true, 'message' => 'Nilai berhasil divalidasi']);
    }

    /**
     * Status pendaftaran page
     */

    /*
        public function statusPendaftaran(Request $request)
    {
        $filterPraktikum = $request->get('praktikum', 'all');
        
        $pendaftarans = PendaftaranPraktikum::with('user', 'jadwal.pertemuan.praktikum')
            ->when($filterPraktikum != 'all', function($q) use ($filterPraktikum) {
                return $q->whereHas('jadwal.pertemuan.praktikum', function($qp) use ($filterPraktikum) {
                    $qp->where('nama_praktikum', $filterPraktikum);
                });
            })
            ->paginate(20);
        
        $praktikums = Praktikum::all();
        
        return view('Dosen.statuspendaftaran', compact('pendaftarans', 'praktikums', 'filterPraktikum'));
    }*/

    /**
     * Manage Asisten - Show all asisten
     */

    /**
     * Store a newly created asisten
     */
    public function storeAsisten(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'nomor_induk' => $request->nomor_induk,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Asisten',
        ]);

        return redirect()->route('manageAsisten')->with('success', 'Asisten berhasil ditambahkan!');
    }

    /**
     * Update the specified asisten
     */
    public function updateAsisten(Request $request, $id)
    {
        $asisten = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255|unique:users,nomor_induk,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $asisten->update([
            'nama' => $request->nama,
            'nomor_induk' => $request->nomor_induk,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->password) {
            $asisten->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('manageAsisten')->with('success', 'Asisten berhasil diperbarui!');
    }

    /**
     * Remove the specified asisten
     */
    public function destroyAsisten($id)
    {
        $asisten = User::findOrFail($id);
        $asisten->delete();

        return redirect()->route('manageAsisten')->with('success', 'Asisten berhasil dihapus!');
    }

    /*
     Manage Laboran - Show all laboran (Admin)
     
    public function manageLaboran()
    {
        $laborans = User::where('role', 'Admin')->get();
        return view('Dosen.manageLaboran', compact('laborans'));
    }
    */
    /**
     * Store a newly created laboran
     */
    public function storeLaboran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'nomor_induk' => $request->nomor_induk,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Admin', // Laboran menggunakan role Admin
        ]);

        return redirect()->route('manageLaboran')->with('success', 'Laboran berhasil ditambahkan!');
    }

    /**
     * Update the specified laboran
     */
    public function updateLaboran(Request $request, $id)
    {
        $laboran = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255|unique:users,nomor_induk,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $laboran->update([
            'nama' => $request->nama,
            'nomor_induk' => $request->nomor_induk,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->password) {
            $laboran->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('manageLaboran')->with('success', 'Laboran berhasil diperbarui!');
    }

    /**
     * Remove the specified laboran
     */
    public function destroyLaboran($id)
    {
        $laboran = User::findOrFail($id);
        $laboran->delete();

        return redirect()->route('manageLaboran')->with('success', 'Laboran berhasil dihapus!');
    }
}