<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Dashboard Mahasiswa | {{ Auth::user()->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            display: flex;
        }

        a {
            all: unset;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* SIDEBAR CORE */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: sticky;
            top: 0;
            z-index: 1000;
            /* Solusi agar logout tidak hilang saat zoom */
            overflow-y: auto; 
            scrollbar-width: thin;
            scrollbar-color: #475569 transparent;
        }

        /* Header & Profile */
        .sidebar-header {
            padding: 32px 20px 20px;
            position: relative;
        }

        .profile-section {
            padding: 2vh 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .avatar-circle {
            width: 10vh;
            height: 10vh;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 50%;
            margin-bottom: 1vh;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        }

        .avatar-circle i {
            font-size: 5vh;
            color: white;
        }

        .profile-info h3 {
            font-size: 2.8vh;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 0.2vh;
            line-height: 1.2;
        }

        /* Styling Mahasiswa & NIM agar sama */
        .profile-info p {
            font-size: 1.8vh;
            line-height:1.4;
            color: #94a3b8;
            margin-top: 0.1vh;
            font-weight: 400;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 10px 20px 30px;
        }

        .nav-menu {
            list-style: none;
            flex: 1;
        }

        .nav-item {
            margin: 0.5vh 0;
            padding: 1.5vh 2vh;
            border-radius: 12px;
            font-weight: 500;
            font-size: 2.2vh;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
        }

        .nav-item i {
            font-size: 2.4vh;
            width: 3vh;
            text-align: center;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }

        /* Submenu */
        .has-sub {
            flex-direction: column;
            align-items: flex-start;
            padding: 0;
        }

        .sub-trigger {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            cursor: pointer;
        }

        .submenu {
            list-style: none;
            padding-left: 15px;
            width: 100%;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 0 0 12px 12px;
        }

        .submenu li {
            padding: 1vh 2vh;
            font-size: 1.9vh;
            color: #94a3b8;
            transition: 0.2s;
        }

        .submenu li:hover {
            color: white;
            padding-left: 20px;
        }

        /* Logout Button */
        .logout-container {
            margin-top: auto;
            padding-top: 20px;
        }

        .logout-btn {
            margin-top: auto;
            margin-bottom: 2vh;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 1.2vh;
            border-radius: 1vh;
            text-align: center;
            font-weight: 600;
            font-size: 2.2vh;
            color: #fca5a5;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
        }

        /* MOBILE TOGGLE BUTTON */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: #1e293b;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        /* RESPONSIVE MEDIA QUERIES */
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .sidebar {
                position: fixed;
                left: -280px; /* Sembunyikan sidebar */
            }

            .sidebar.show {
                left: 0; /* Tampilkan sidebar */
            }

            /* Overlay saat sidebar muncul di mobile */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Penanganan zoom: mengecilkan sedikit padding agar tetap fit */
        @media (max-height: 600px) {
            .sidebar-header { padding: 15px 20px; }
            .avatar-circle { width: 60px; height: 60px; }
            .nav-item { padding: 8px 16px; }
        }
    </style>
</head>

<body>
    <button class="mobile-toggle" id="btnToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar-overlay" id="overlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="profile-section">
                <div class="avatar-circle">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="profile-info">
                    <h3>{{ Auth::user()->nama }}</h3>
                    <p>Mahasiswa Teknik Informatika</p>
                    <p>NIM {{ Auth::user()->nomor_induk }}</p>
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item {{ request()->routeIs('mahasiswa') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-chalkboard-user"></i> Dashboard</a>
                </li>
                
                <li class="nav-item has-sub">
                    <div class="sub-trigger">
                        <span><i class="fas fa-flask"></i> Praktikum</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                    </div>
                    <ul class="submenu">
                        <li><a href="{{route('pendaftaran')}}"><i class="fas fa-list-ul"></i> Daftar Praktikum</a></li>
                        <li><a href="{{route('praktikum')}}"><i class="fas fa-pen-ruler"></i> Pendaftaran Saya</a></li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('pretest') ? 'active' : '' }}">
                    <a href="{{route('pretest')}}"><i class="fas fa-file-alt"></i> Pretest</a>
                </li>

                <li class="nav-item has-sub">
                    <div class="sub-trigger">
                        <span><i class="fas fa-chart-line"></i> Nilai & Presensi</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                    </div>
                    <ul class="submenu">
                        <li><a href="{{route('nilai')}}"><i class="fas fa-star"></i> Nilai</a></li>
                        <li><a href="{{route('presensi')}}"><i class="fas fa-fingerprint"></i> Presensi</a></li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('riwayat') ? 'active' : '' }}">
                    <a href="{{route('riwayat')}}"><i class="fas fa-history"></i> Riwayat</a>
                </li>
            </ul>

            <div class="logout-container">
                <div class="logout-btn">
                    <a href="{{ route('logout') }}" style="justify-content: center;">
                        <i class="fas fa-sign-out-alt"></i> LogOut
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <script>
        const btnToggle = document.getElementById('btnToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        btnToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
        });
    </script>
</body>

</html>