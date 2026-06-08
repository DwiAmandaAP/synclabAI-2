<!-- sidebar.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Asisten</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 90%;
            overflow-x: hidden;
        }

        a {
            all: unset;
        }

        .dropdown ul {
            display: none;
            list-style: none;
            padding-left: 20px;
        }

        .dropdown ul.open {
            display: block;
            max-height: 200px;
        }

        .dropdown ul li {
            font-size: 14px;
            padding: 6px;
        }

        .right {
            float: right !important;
            transition: transform 0.2s;
            display: flex !important;
            justify-content: end !important;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1500;
        }

        .overlay.active {
            display: block;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background: #0f172a;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 2000;
            background: #111827;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .profile {
            text-align: center;
            margin-bottom: 30px;
            flex-shrink: 0;
        }

        .profile h4 {
            margin: 8px 0 4px;
        }

        .avatar {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #a78bfa, #6366f1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .avatar i {
            font-size: 28px;
        }

        .menu {
            list-style: none;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .menu li {
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        .menu li:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu li i {
            margin-right: 10px;
            width: 20px;
        }

        .active {
            background: rgba(255, 255, 255, 0.2);
        }

        .logout {
            background: #ef4444;
            border: none;
            padding: 10px;
            color: white;
            border-radius: 6px;
            margin-top: auto;
            flex-shrink: 0;
            cursor: pointer;
            text-align: center;
        }

        .logout:hover {
            background: #dc2626;
        }

        @media (min-width: 769px) {
            body {
                margin-left: 250px;
            }

            .sidebar {
                transform: translateX(0) !important;
            }

            .menu-toggle {
                display: none !important;
            }

            .overlay {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            body {
                margin-left: 0;
            }

            .menu-toggle {
                display: block !important;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                max-width: 85vw;
                box-shadow: none;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            }

            .profile {
                margin-bottom: 20px;
            }

            .avatar {
                width: 60px;
                height: 60px;
            }

            .avatar i {
                font-size: 24px;
            }

            .profile h4 {
                font-size: 1rem;
            }

            .profile span,
            .profile small {
                font-size: 11px;
            }

            .menu li {
                padding: 14px 12px;
                font-size: 15px;
            }

            .dropdown ul {
                padding-left: 16px;
                max-height: 300px;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            .dropdown ul.open {
                max-height: 400px;
            }

            .dropdown ul li {
                padding: 10px 12px;
                font-size: 14px;
            }

            .logout {
                padding: 14px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .menu-toggle {
                padding: 8px 12px;
                top: 12px;
                left: 12px;
            }

            .sidebar {
                width: 100vw;
                max-width: 100vw;
            }

            .menu li,
            .logout {
                font-size: 14px;
                padding: 12px;
            }
        }

        @media (hover: none) and (pointer: coarse) {
            .menu li:active {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }

        body.sidebar-open {
            overflow: hidden;
        }
    </style>
</head>

<body>

    <button onclick="toggleSidebar()" class="menu-toggle">☰</button>

    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="profile">
            <div class="avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h4>{{ Auth::user()->nama }}</h4>
            <span>{{ Auth::user()->role }}</span>
            <small>NIM {{ Auth::user()->nomor_induk }}</small>
        </div>

        <ul class="menu">
            <li class="{{ request()->routeIs('asisten') ? 'active' : '' }}"><a href="{{ route('asisten') }}">
                    <i class="fas fa-home"></i> Dashboard</a>
            </li>

            <li class="{{ request()->routeIs('asistensi') ? 'active' : '' }}"><a href="{{ route('asistensi') }}">
                    <i class="fas fa-laptop-code"></i> Praktikum</a>
            </li>

            <li class="dropdown">
                <div onclick="toggleDropdown('presensi')">
                    <i class="fas fa-calendar-check"></i> Presensi
                    <i class="fas fa-chevron-down right sb-right"></i>
                </div>

                <ul id="presensi">
                    <li class="{{ request()->routeIs('konfirmasiPresensi') ? 'active' : '' }}"><a
                            href="{{ route('konfirmasiPresensi') }}">Rekam Presensi</a></li>
                    <li class="{{ request()->routeIs('riwayatPresensi') ? 'active' : '' }}"><a
                            href="{{ route('riwayatPresensi') }}">Riwayat Presensi</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <div onclick="toggleDropdown('modul')" style="display: flex;">
                    <i class="fas fa-book"></i> Modul dan Pretest
                    <i class="fas fa-chevron-down right sb-right"></i>
                </div>

                <ul id="modul">
                    <li class="{{ request()->routeIs('addModul') ? 'active' : '' }}"><a
                            href="{{ route('addModul') }}">Tambah Modul</a></li>
                    <li class="{{ request()->routeIs('addPretest') ? 'active' : '' }}"><a
                            href="{{ route('addPretest') }}">Tambah Pretest</a></li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('nilaiLaporan') ? 'active' : '' }}"><a href="{{route('nilaiLaporan')}}">
                <i class="fas fa-file"></i>Laporan</a>
            </li>

            <li class="dropdown">
                <div onclick="toggleDropdown('nilai')">
                    <i class="fas fa-chart-line"></i>Nilai
                    <i class="fas fa-chevron-down right sb-right"></i>
                </div>

                <ul id="nilai">
                    <li class="{{ request()->routeIs('addMilai') ? 'active' : '' }}"><a
                            href="{{ route('addNilai') }}">Simpan Nilai</a></li>  
                    <li class="{{ request()->routeIs('rekapNilai') ? 'active' : '' }}"><a
                            href="{{ route('rekapNilai') }}">Rekap Nilai</a></li>  
                </ul>
            </li>

            <li class="{{ request()->routeIs('seeMahasiswa') ? 'active' : '' }}"><a href="{{route('seeMahasiswa')}}">
                <i class="fas fa-users"></i>Mahasiswa
            </a>
            </li>
        </ul>

        <button class="logout">
            <a href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </button>
    </aside>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");

            sidebar.classList.toggle("show");
            overlay.classList.toggle("active");

            if (sidebar.classList.contains("show")) {
                document.body.classList.add("sidebar-open");
            } else {
                document.body.classList.remove("sidebar-open");
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            sidebar.classList.remove("show");
            overlay.classList.remove("active");
            document.body.classList.remove("sidebar-open");
        }

        function toggleDropdown(id) {
            const menu = document.getElementById(id);
            menu.classList.toggle("open");
        }

        // Close sidebar when clicking outside on desktop as well? Only on mobile.
        document.addEventListener("click", function(e) {
            const sidebar = document.getElementById("sidebar");
            const toggle = document.querySelector(".menu-toggle");
            const overlay = document.getElementById("overlay");
            const isMobile = window.innerWidth <= 768;

            if (isMobile && overlay && overlay.contains(e.target)) {
                closeSidebar();
                return;
            }

            if (isMobile && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById("sidebar");
                const overlay = document.getElementById("overlay");
                sidebar.classList.remove("show");
                overlay.classList.remove("active");
                document.body.classList.remove("sidebar-open");
            }
        });
    </script>
</body>
</html>