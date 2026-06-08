<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Dashboard Dosen | Portal Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        a {
            all: unset;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 100;
            max-height: 100vh;
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }

        .sidebar-header {
            padding: 28px 20px 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-menu-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            font-size: 1.3rem;
            padding: 8px 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s;
        }

        .mobile-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .profile-section {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .avatar-circle {
            width: 88px;
            height: 88px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 50%;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 22px -8px rgba(0, 0, 0, 0.3);
        }

        .avatar-circle i {
            font-size: 44px;
            color: white;
        }

        .profile-section h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 4px;
        }

        .profile-section p {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        .sidebar-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0 20px 28px 20px;
        }

        .nav-menu {
            list-style: none;
            margin-top: 20px;
            flex: 1;
        }

        .nav-item {
            margin: 8px 0;
            padding: 12px 16px;
            border-radius: 16px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: 0.25s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
        }

        .nav-item i {
            width: 24px;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .logout-btn {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            padding: 12px;
            border-radius: 40px;
            text-align: center;
            font-weight: 600;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: 0.2s;
            color: #fecaca;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="profile-section">
                <div class="avatar-circle">
                    <i class="fas fa-chalkboard-user"></i>
                </div>
                <div class="profile-info">
                    <h3>Dr. Budi Santoso</h3>
                    <p>Dosen Teknik Informatika</p>
                </div>
            </div>
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="sidebar-nav" id="sidebarNav">
            <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('monitoring') ? 'active' : '' }}">
                <a href="{{ route('monitoring') }}">
                    <i class="fas fa-eye"></i> Monitoring
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('validasinilai') ? 'active' : '' }}">
                <a href="{{ route('validasinilai') }}">
                    <i class="fas fa-check-double"></i> Validasi Nilai
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('dosenPresensi') ? 'active' : '' }}">
                <a href="{{ route('dosenPresensi') }}">
                    <i class="fas fa-fingerprint"></i> Presensi
                </a>
            </li>
        </ul>
            <div class="logout-btn">
                <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Log Out</a>
            </div>
        </div>
    </aside>
</body>

</html>
