<!-- Sidebar -->
<nav class="sidebar" :class="{ 'dark': darkMode, 'active': sidebarOpen }">
    <!-- Sidebar Header -->
    <div class="sidebar-header">

        <a href="#" class="logo">
            <div class="logo-icon">
                <img src="{{ asset('images/logo-bogor.png') }}" alt="" width="90px">
            </div>
            <div>
                <h5 class="logo-text">MP - SPBE</h5>
                <div class="logo-subtitle">Admin Panel</div>
                <button class="header-btn d-lg-none" @click="sidebarOpen = !sidebarOpen">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </a>

    </div>

    <!-- Navigation Menu -->
    <div class="nav-menu">
        <!-- Main Section -->
        <div class="nav-section-title">Main</div>

        <div class="nav-item">
            <a href="{{ Route('dashboard.admin') }}"
                class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}"
                @click="setActiveMenu('dashboard', 'Dashboard')">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ Route('admin.aktivitas') }}"
                class="nav-link {{ request()->routeIs('admin.aktivitas') ? 'active' : '' }}"
                @click="setActiveMenu('aktivitas', 'Aktivitas Terbaru')">
                <i class="fas fa-rectangle-list"></i>
                <span>Aktivitas Terbaru</span>
            </a>
        </div>

        <!-- Management Section -->
        <div class="nav-section-title">Management</div>

        <div class="nav-item">
            <a href="{{ Route('admin.user') }}" class="nav-link {{ request()->routeIs('admin.user') ? 'active' : '' }}"
                @click="setActiveMenu('user', 'Kelola Pengguna')">
                <i class="fas fa-users"></i>
                <span>Pengguna</span>
            </a>
            <a href="{{ Route('admin.tag') }}" class="nav-link {{ request()->routeIs('admin.tag') ? 'active' : '' }}"
                @click="setActiveMenu('tag', 'Kelola Tag')">
                <i class="fas fa-tag"></i>
                <span>Tag</span>
            </a>
            <a href="{{ route('admin.saran') }}"
                class="nav-link {{ request()->routeIs('admin.saran') ? 'active' : '' }}"
                @click="setActiveMenu('tag', 'Kelola Tag')">
                <i class="fa-solid fa-comments"></i>
                <span>Saran Dan Masukan</span>

                @if ($saranHariIni > 0 && !$sudahDilihat)
                    <span class="badge bg-danger ms-2">{{ $saranHariIni }}</span>
                @endif
            </a>
            {{-- <a href="#" class="nav-link" @click="setActiveMenu('laporan', 'Laporan')">
                <i class="fas fa-file-alt"></i>
                <span>Laporan</span>
            </a> --}}

            <!-- Dropdown Sampah -->
            <div x-data="{ open: false }">
                <!-- Toggle -->
                <a href="#" class="nav-link" @click.prevent="open = !open">
                    <i class="fas fa-trash"></i>
                    <span>Sampah</span>
                    <i class="fas fa-chevron-down ms-auto" :class="{ 'rotate-180': open }"
                        style="transition: transform 0.3s;"></i>
                </a>

                <!-- Submenu -->
                <div x-show="open" x-transition style="display: none; padding-left: 1rem; margin-top: 4px;">
                    <a href="{{ route('admin.trash.artikel') }}"
                        class="nav-link {{ request()->routeIs('admin.trash.artikel') ? 'active' : '' }}"
                        @click="setActiveMenu('trashArtikel', 'Artikel Di Hapus')">
                        <i class="fa fa-newspaper"></i><span>Artikel</span>
                    </a>
                    <a href="{{ route('admin.trash.repo') }}"
                        class="nav-link {{ request()->routeIs('admin.trash.repo') ? 'active' : '' }}"
                        @click="setActiveMenu('trashRepo', 'Repository Di Hapus')">
                        <i class="fa fa-folder-open"></i><span>Repository</span>
                    </a>
                    <a href="{{ route('admin.trash.user') }}"
                        class="nav-link {{ request()->routeIs('admin.trash.user') ? 'active' : '' }}"
                        @click="setActiveMenu('trashUser', 'Pengguna Di Hapus')">
                        <i class="fa fa-users"></i><span>User</span>
                    </a>
                </div>

            </div>
        </div>

        <!-- System Section -->
        <div class="nav-section-title">System</div>
        <div class="nav-item">
            <form action="{{ Route('logout') }}" method="POST">
                @csrf
                <button class="nav-link btn btn-transparent" style="color: #6c757d; border:transparent; width:100%"
                    @click="setActiveMenu('logout', 'Logout')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>
<script>
    function updateSaranBadge() {
        fetch("{{ route('admin.saran.badge') }}")
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('saran-badge');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(err => console.log(err));
    }

    // Update setiap 30 detik
    setInterval(updateSaranBadge, 30000);

    // Update saat halaman pertama kali load
    document.addEventListener('DOMContentLoaded', updateSaranBadge);
</script>
