<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <div class="nav-brand">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" width="55px">
                <span class="logo-text">TanyaIn</span>
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="fas fa-house me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item dropdown" data-bs-auto-close="outside">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('repository') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-folder-open me-1"></i>Repositori
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Semua Repositori -->
                        <li>
                            <a class="dropdown-item" href="{{ route('repository') }}">
                                <i class="fas fa-list me-2"></i>Semua Repositori
                            </a>
                        </li>

                        <!-- Terpopuler -->
                        <li>
                            <a class="dropdown-item" href="{{ route('repository', ['sort' => 'popular']) }}">
                                <i class="fas fa-fire me-2"></i>Terpopuler
                            </a>
                        </li>

                        <!-- Repositori Saya (hanya muncul jika login) -->
                        @auth
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('profile', ['id' => auth()->id(), 'tab' => 'repos']) }}">
                                    <i class="fas fa-archive me-2"></i>Repositori Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('repo.create') }}">
                                    <i class="fas fa-folder-plus me-2"></i>Buat Repositori
                                </a>
                            </li>
                        @endauth
                    </ul>
                </li>

                <li class="nav-item dropdown" data-bs-auto-close="outside">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('artikel.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-newspaper me-1"></i>Artikel
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Semua Artikel -->
                        <li>
                            <a class="dropdown-item" href="{{ route('article') }}">
                                <i class="fas fa-list me-2"></i>Semua Artikel
                            </a>
                        </li>
                        <!-- Artikel Populer -->
                        <li>
                            <a class="dropdown-item" href="{{ route('article', ['sort' => 'popular']) }}">
                                <i class="fas fa-fire me-2"></i>Artikel Populer
                            </a>
                        </li>
                        <!-- Artikel Saya (hanya muncul jika login) -->
                        @auth
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('profile', ['id' => auth()->id(), 'tab' => 'articles']) }}">
                                    <i class="fas fa-file-pen me-2"></i>Artikel Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('article.create') }}">
                                    <i class="fas fa-pen-nib me-2"></i>Tulis Artikel
                                </a>
                            </li>
                        @endauth
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('file') ? 'active' : '' }}" href="{{ route('file') }}">
                        <i class="fa-regular fa-file me-1"></i>File
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('saran') ? 'active' : '' }}" href="{{ route('saran') }}">
                        <i class="fas fa-info-circle me-1"></i>Tentang
                    </a>
                </li>
            </ul>
            @if (Auth::check())
                <!-- 🔍 Search (desktop: toggle + expand) -->
                <div class="search-wrapper d-none d-lg-flex align-items-center me-3">
                    <!-- toggle button -->
                    <button class="btn btn-outline-secondary toggle-search" type="button" aria-expanded="false"
                        aria-label="Buka pencarian">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- form expand overlay -->
                    <form class="search-form d-flex" role="search" action="{{ route('search.all') }}" method="GET"
                        aria-hidden="true">
                        <input class="form-control search-input" name="search" type="search" placeholder="Cari..."
                            aria-label="Cari" value="{{ request('search') }}" required>
                        <button class="btn btn-search" type="submit" aria-label="Kirim pencarian">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            @else
                <!-- Search Form (desktop only for guest) -->
                <form class="d-none d-lg-flex me-3" role="search" action="{{ route('search.all') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control search-input" type="search" placeholder="Cari..." name="search"
                            aria-label="Search" value="{{ request('search') }}" required>
                        <button class="btn btn-search" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            @endif

            <!-- 🔍 Search (mobile: untuk SEMUA user, login/guest) -->
            <form class="d-flex d-lg-none me-2" role="search" action="{{ route('search.all') }}" method="GET">
                <div class="input-group">
                    <input class="form-control search-input" type="search" placeholder="Cari..." name="search"
                        aria-label="Search" value="{{ request('search') }}" required>
                    <button class="btn btn-search" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>


            <!-- Auth Buttons -->
            <div class="d-flex align-items-center">
                @guest
                    <!-- Kalau belum login -->
                    <a href="{{ route('login') }}" class="btn btn-primary-custom">
                        <i class="fas fa-sign-in-alt me-1"></i>Masuk
                    </a>
                @endguest

                @auth
                    <!-- NOTIFIKASI DROPDOWN — LANGSUNG DI SINI -->
                    <div class="dropdown" style="margin-right:10px">
                        <button class="btn btn-primary-custom dropdown-toggle d-flex align-items-center position-relative"
                            type="button" id="notificationDropdown"
                            data-notif-jumlah-url="{{ route('notifikasi.jumlah') }}"
                            data-notif-baca-semua-url="{{ route('notifikasi.baca-semua') }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-bell fa-2x"></i>

                            @if (Auth::user()->notifikasi()->where('status', 'belum_dibaca')->count() > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                    {{ Auth::user()->notifikasi()->where('status', 'belum_dibaca')->count() }}
                                </span>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 notif-dropdown">
                            <li class="dropdown-header d-flex justify-content-between align-items-center px-3">
                                <strong>Notifikasi</strong>
                                <div class="text-muted baca-semua">
                                    <a href="javascript:void(0)" data-url="{{ route('notifikasi.baca-semua') }}"
                                        class="btn btn-primary btn-sm text-decoration-none mark-all-read">
                                        Tandai Semua Dibaca
                                    </a>

                                </div>
                            </li>

                            @php
                                $notifs = Auth::user()->notifikasi()->orderBy('created_at', 'desc')->limit(5)->get();
                            @endphp

                            @if ($notifs->isEmpty())
                                <li class="dropdown-item text-center text-muted py-3">
                                    <i class="fas fa-bell-slash me-2"></i>Tidak ada notifikasi
                                </li>
                            @else
                                @foreach ($notifs as $notif)
                                    <li>
                                        @php
                                            $url = '#';
                                            $targetExists = true;

                                            if (in_array($notif->tipe, ['komentar', 'balasan', 'tag'])) {
                                                $komentar = \App\Models\Komentar::with('artikel')->find(
                                                    $notif->referensi_id,
                                                );
                                                if ($komentar && $komentar->artikel) {
                                                    $url =
                                                        route('article.detail', ['id' => $komentar->artikel_id]) .
                                                        '#komentar-' .
                                                        $komentar->id;
                                                } else {
                                                    $url = route('article.detail.missing', ['notif_id' => $notif->id]);
                                                    $targetExists = false;
                                                }
                                            } elseif ($notif->tipe == 'like') {
                                                // Semua like saat ini adalah like KOMENTAR
                                                $komentar = \App\Models\Komentar::with('artikel')->find(
                                                    $notif->referensi_id,
                                                );
                                                if ($komentar && $komentar->artikel) {
                                                    $url =
                                                        route('article.detail', ['id' => $komentar->artikel->id]) .
                                                        '#komentar-' .
                                                        $komentar->id;
                                                } else {
                                                    $url = route('article.detail.missing', ['notif_id' => $notif->id]);
                                                    $targetExists = false;
                                                }
                                            }
                                        @endphp

                                        <a href="{{ $url }}"
                                            class="dropdown-item d-flex align-items-center py-2 {{ $notif->status == 'belum_dibaca' ? 'bg-light' : '' }}"
                                            data-notif-id="{{ $notif->id }}">
                                            <div class="me-2">
                                                @if ($notif->tipe == 'komentar')
                                                    <i class="fas fa-comment text-primary"></i>
                                                @elseif($notif->tipe == 'balasan')
                                                    <i class="fas fa-reply text-info"></i>
                                                @elseif($notif->tipe == 'tag')
                                                    <i class="fas fa-at text-warning"></i>
                                                @elseif($notif->tipe == 'like')
                                                    <i class="fas fa-heart text-danger"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <small class="mb-1">{{ $notif->pesan }}</small>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if ($notif->status == 'belum_dibaca')
                                                <span class="badge bg-primary ms-auto">Baru</span>
                                            @endif
                                        </a>

                                    </li>
                                @endforeach
                            @endif

                            <li class="dropdown-divider"></li>
                            <li class="px-3">
                                <a href="{{ route('notifikasi.index') }}" class="dropdown-item text-center">
                                    Lihat Semua Notifikasi
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- PROFIL USER -->
                    <div class="dropdown">
                        <button class="btn btn-primary-custom dropdown-toggle d-flex align-items-center" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Foto Profil -->
                            <img src="{{ Auth::user()->foto
                                ? asset('storage/user-img/' . Auth::user()->foto)
                                : asset('storage/user-img/default-user.jpg') }}"
                                alt="Profile" class="rounded-circle me-2" width="30" height="30">

                            <!-- Nama User -->
                            <span>{{ Auth::user()->nama }}</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 profile-dropdown"
                            style="min-width: 230px;">
                            <!-- Header user -->
                            <li class="px-3 py-2 text-center border-bottom">
                                <img src="{{ Auth::user()->foto
                                    ? asset('storage/user-img/' . Auth::user()->foto)
                                    : asset('storage/user-img/default-user.jpg') }}"
                                    alt="Profile" class="rounded-circle mb-2" width="60" height="60">
                                <h6 class="mb-0">{{ Auth::user()->nama }}</h6>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>

                            <!-- Menu items -->
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2"
                                    href="{{ Route('profile', Auth::user()->id) }}">
                                    <i class="fas fa-user me-2 text-primary"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2"
                                    href="{{ Route('profile.edit', Auth::user()->id) }}">
                                    <i class="fas fa-gear me-2 text-warning"></i> Setting
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2"
                                    href="{{ route('artikel.trash') }}">
                                    <i class="fas fa-trash me-2 text-danger"></i> Sampah
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center py-2">
                                        <i class="fas fa-sign-out-alt me-2 text-secondary"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifBtn = document.getElementById('notificationDropdown');
        if (!notifBtn) return;

        const urlCount = notifBtn.dataset.notifJumlahUrl;
        const urlMarkAll = notifBtn.dataset.notifBacaSemuaUrl;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        async function updateBadge() {
            try {
                const res = await fetch(urlCount);
                const data = await res.json();
                let badge = notifBtn.querySelector('.badge');

                if (data.count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className =
                            'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                        badge.style.fontSize = '0.7rem';
                        badge.style.padding = '0.2rem 0.4rem';
                        notifBtn.appendChild(badge);
                    }
                    badge.textContent = data.count;
                } else if (badge) {
                    badge.remove();
                }
            } catch (err) {
                console.error('Gagal ambil jumlah notifikasi:', err);
            }
        }

        document.addEventListener('click', async function(e) {
            const markAll = e.target.closest('.mark-all-read');
            if (!markAll) return;
            e.preventDefault();

            try {
                await fetch(markAll.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({})
                });

                // update UI
                document.querySelectorAll('.dropdown-item[data-notif-id]').forEach(item => {
                    item.classList.remove('bg-light');
                    const badge = item.querySelector('.badge');
                    if (badge) badge.remove();
                });

                updateBadge();
            } catch (err) {
                console.error('Gagal tandai semua notifikasi:', err);
            }
        });


        // Saat dropdown dibuka → tandai item sebagai dibaca
        notifBtn.addEventListener('show.bs.dropdown', async function() {
            const items = document.querySelectorAll('.dropdown-item[data-notif-id].bg-light');
            for (let item of items) {
                const notifId = item.dataset.notifId;
                try {
                    await fetch(`/notifikasi/${notifId}/baca`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({})
                    });

                    item.classList.remove('bg-light');
                    const badge = item.querySelector('.badge');
                    if (badge) badge.remove();
                } catch (err) {
                    console.error('Gagal tandai notifikasi:', err);
                }
            }
            updateBadge();
        });

        // Auto-refresh badge setiap 30 detik
        updateBadge();
        setInterval(updateBadge, 30000);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbarCollapse = document.getElementById('navbarNav');
        if (!navbarCollapse) return;

        // 💣 NUKLIR: CEKAL BOOTSTRAP COLLAPSE SAAT KLIK DROPDOWN DI MOBILE
        navbarCollapse.addEventListener('click', function(e) {
            if (window.innerWidth >= 992) return; // Hanya mobile

            // Cek apakah klik terjadi di dropdown toggle, menu, atau item
            const isDropdownElement =
                e.target.closest('.dropdown-toggle') ||
                e.target.closest('.dropdown-menu') ||
                e.target.closest('.dropdown-item') ||
                e.target.closest('.search-input');

            if (isDropdownElement) {
                e.stopPropagation(); // HENTIKAN event sebelum Bootstrap sempat nutup navbar
            }
        }, true); // <-- CAPTURE PHASE — TANGKAP EVENT DI AWAL BANGET

        // 💣 EXTRA: CEKAL JUGA EVENT CLICK GLOBAL — JIKA DROPDOWN SEDANG SHOW
        document.addEventListener('click', function(e) {
            if (window.innerWidth >= 992) return;

            const isInsideDropdown =
                e.target.closest('.dropdown-toggle') ||
                e.target.closest('.dropdown-menu') ||
                e.target.closest('.dropdown-item') ||
                e.target.closest('.search-input');

            const isNavbarToggler = e.target.closest('.navbar-toggler');

            // Jika dropdown sedang terbuka dan klik di dalam dropdown — jangan nutup navbar
            if (isInsideDropdown) {
                e.stopPropagation();
                return;
            }

            // Jika klik di luar dropdown & bukan toggler — biarkan navbar collapse
            if (!isInsideDropdown && !isNavbarToggler) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse && navbarCollapse.classList.contains('show')) {
                    bsCollapse.hide();
                }
            }
        }, true);
    });
</script>
@if (session('swal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: "{{ session('swal')['icon'] }}",
                title: "{{ session('swal')['title'] }}",
                text: "{{ session('swal')['text'] }}",
                timer: {{ session('swal')['timer'] }},
                showConfirmButton: {{ session('swal')['showConfirmButton'] ? 'true' : 'false' }}
            });
        });
    </script>
@endif
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const wrapper = document.querySelector(".search-wrapper");
        if (!wrapper) return;

        const toggleBtn = wrapper.querySelector(".toggle-search");
        const form = wrapper.querySelector(".search-form");
        const input = form.querySelector(".search-input");

        // toggle open/close
        toggleBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            const isOpen = wrapper.classList.toggle("active");
            toggleBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");
            form.setAttribute("aria-hidden", isOpen ? "false" : "true");

            if (isOpen) {
                // fokus ke input setelah animasi mulai (biar smooth)
                setTimeout(() => {
                    input.focus();
                    // optional: select existing text
                    // input.select();
                }, 240);
            }
        });

        // jangan biarkan klik di dalam form men-trigger document click
        form.addEventListener("click", function(e) {
            e.stopPropagation();
        });

        // klik di luar -> tutup
        document.addEventListener("click", function(e) {
            if (!wrapper.contains(e.target) && wrapper.classList.contains("active")) {
                wrapper.classList.remove("active");
                toggleBtn.setAttribute("aria-expanded", "false");
                form.setAttribute("aria-hidden", "true");
            }
        });

        // Esc -> tutup
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape" && wrapper.classList.contains("active")) {
                wrapper.classList.remove("active");
                toggleBtn.setAttribute("aria-expanded", "false");
                form.setAttribute("aria-hidden", "true");
                toggleBtn.focus();
            }
        });
    });
</script>
