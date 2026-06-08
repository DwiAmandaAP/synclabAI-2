<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Praktikum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        a {
            all: unset;
        }

        .menu-toggle {
            display: none;
        }

        @media (max-width: 1023px) {
            .menu-toggle {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 2000;
                background: #111827;
                color: white;
                border: none;
                padding: 8px 10px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 24px;
            }
        }

        .sidebar {
            width: 250px;
            background: #1e1e2f;
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            max-height: 100vh;
            overflow-y: hidden;
            position: sticky;
        }

        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #a78bfa, #6366f1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .avatar i {
            font-size: 28px;
            color: white;
        }

        .profile h4 {
            margin: 5px 0 2px;
            font-size: 16px;
        }

        .profile span {
            font-size: 13px;
            color: #cbd5f5;
        }

        .profile small {
            font-size: 12px;
            color: #94a3b8;
        }

        .menu {
            list-style: none;
        }

        .menu li {
            margin: 10px 0;
            cursor: pointer;
            opacity: 0.8;
        }

        .menu li:hover {
            opacity: 1;
        }

        .menu .active {
            font-weight: 600;
            opacity: 1;
        }

        .dropdown-menu {
            display: none;
            margin-left: 15px;
            margin-top: 5px;
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .dropdown-menu li {
            font-size: 14px;
            padding-left: 20px;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-menu li i {
            width: 20px;
            margin-right: 8px;
        }

        .avatar i {
            font-size: 22px;
        }

        .logout {
            margin-top: auto;
            padding: 10px;
            background: #ff4d4d;
            border: none;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="toggle menu-toggle" onclick="toggleSidebar()">☰</div>

    <aside class="sidebar" id="sidebar">
        <div class="profile">
            <div class="avatar">
                <i class="fas fa-user-graduate"></i>
            </div>

            <h4>{{ Auth::user()->nama }}</h4>
            <span>{{ Auth::user()->role }}</span>
            <small>NIP {{ Auth::user()->nomor_induk }}</small>
        </div>

        <ul class="menu">
            <li class="menu-item {{ request()->routeIs('admin') ? 'active' : '' }}"><a href="{{ route('admin') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>

            <li class="menu-item dropdown">
                <div class="dropdown-btn" onclick="toggleDropdown(event)">
                    <span><i class="fas fa-folder"></i> Manajemen Sistem</span>
                    <span><i class="fas fa-chevron-down"></i></span>
                </div>

                <ul class="dropdown-menu" id="dropdown1">
                    <li class="{{ request()->routeIs('masterPraktikum') ? 'active' : '' }}"><a
                            href="{{ route('masterPraktikum') }}"><i class="fas fa-flask"></i> Kelola Praktikum </a>
                    </li>
                    <li class="{{ request()->routeIs('masterUser') ? 'active' : '' }}"><a
                            href="{{ route('masterUser') }}"><i class="fas fa-users"></i> Kelola Pengguna</a>
                    </li>
                    <li class="{{ request()->routeIs('masterLaboratorium') ? 'active' : '' }}"><a
                            href="{{ route('masterLaboratorium') }}"><i class="fa-solid fa-users-viewfinder"></i>
                            Kelola Laboratorium</a>
                    </li>
                    <li class="{{ request()->routeIs('masterPertemuan') ? 'active' : '' }}"><a
                            href="{{ route('masterPertemuan') }}"><i class="fa-solid fa-chalkboard-user"></i> Kelola
                            Pertemuan</a>
                    </li>
                    <li class="{{ request()->routeIs('masterJadwal') ? 'active' : '' }}"><a
                            href="{{ route('masterJadwal') }}"><i class="fas fa-calendar"></i>Kelola Jadwal</a>
                    </li>
                </ul>
            </li>

            <li class="menu-item dropdown">
                <div class="dropdown-btn"  style="display: flex;" onclick="toggleDropdown1(event)">
                    <span><i class="fa-solid fa-computer"></i> Monitor Praktikum</span>
                    <span><i class="fas fa-chevron-down"></i></span>
                </div>

                <ul class="dropdown-menu" id="dropdown2">
                    <li class="{{ request()->routeIs('kelolaPendaftaran') ? 'active' : '' }}"><a
                            href="{{ route('kelolaPendaftaran') }}"><i class="fa-solid fa-address-card"></i> Kelola Pendaftaran </a>
                    </li>
                    <li class="{{ request()->routeIs('kelolaLaporan') ? 'active' : '' }}"><a
                            href="{{ route('kelolaLaporan') }}"><i class="fa-solid fa-laptop-file"></i>Kelola Laporan</a>
                    </li>
                    <li class="{{ request()->routeIs('kelolaNilai') ? 'active' : '' }}"><a
                            href="{{ route('kelolaNilai') }}"><i class="fa-solid fa-table-list"></i>
                            Kelola Nilai</a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ request()->routeIs('masterLaporan') ? 'active' : '' }}"><a
                    href="{{ route('masterLaporan') }}">
                    <i class="fas fa-file-alt"></i> Rekap Praktikum</a>
            </li>
        </ul>

        <button class="logout"><a href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt"></i> Logout</a>
        </button>
    </aside>
</body>
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const body = document.body;
  
  sidebar.classList.toggle('active');
  
  if (sidebar.classList.contains('active')) {
    body.classList.add('sidebar-open');
  } else {
    body.classList.remove('sidebar-open');
  }
}

// Close sidebar when clicking backdrop (mobile only)
document.addEventListener('click', function(e) {
  const sidebar = document.getElementById('sidebar');
  const toggle = document.querySelector('.toggle');
  const isMobile = window.innerWidth <= 1023;
  
  if (isMobile && 
      e.target === document.body && 
      sidebar.classList.contains('active') &&
      !sidebar.contains(e.target) && 
      !toggle?.contains(e.target)) {
    toggleSidebar();
  }
});

function toggleDropdown(e) {
  if (e) {
    e.stopPropagation();
  }
  const dropdown1 = document.getElementById('dropdown1');
  const dropdown2 = document.getElementById('dropdown2');
  
  dropdown1.style.display = dropdown1.style.display === 'block' ? 'none' : 'block';
  dropdown2.style.display = 'none';
}

function toggleDropdown1(e) {
  if (e) {
    e.stopPropagation();
  }
  const dropdown1 = document.getElementById('dropdown1');
  const dropdown2 = document.getElementById('dropdown2');
  
  dropdown2.style.display = dropdown2.style.display === 'block' ? 'none' : 'block';
  dropdown1.style.display = 'none';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
  const dropdown1 = document.getElementById('dropdown1');
  const dropdown2 = document.getElementById('dropdown2');
  const isClickingBtn = e.target.closest('.dropdown-btn') || 
                        e.target.closest('.dropdown-menu');
  
  if (!isClickingBtn) {
    dropdown1.style.display = 'none';
    dropdown2.style.display = 'none';
  }
});

// Prevent body scroll when sidebar open on mobile
document.addEventListener('DOMContentLoaded', function() {
  // Handle window resize
  window.addEventListener('resize', function() {
    if (window.innerWidth > 1023 && document.getElementById('sidebar').classList.contains('active')) {
      document.body.classList.remove('sidebar-open');
      document.getElementById('sidebar').classList.remove('active');
    }
  });
});
</script>
