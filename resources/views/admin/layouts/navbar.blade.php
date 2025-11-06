<header class="header" :class="{ 'dark': darkMode }">
    <div class="header-content">
        <div class="breadcrumb-section">
            <h1 class="page-title" x-text="pageTitle"></h1>
            <div class="breadcrumb-nav">
                <template x-for="(crumb, index) in breadcrumb" :key="index">
                    <span>
                        <template x-if="index > 0"> / </template>
                        <span x-text="crumb"></span>
                    </span>
                </template>
            </div>
        </div>
        <div class="header-actions">
            <!-- Hamburger button (muncul kalau kecil) -->
            <button class="header-btn d-lg-none" @click="sidebarOpen = !sidebarOpen"
                style="position: relative; z-index: 50;">
                <i class="fas fa-bars"></i>
            </button>
            <!-- Notification Button -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" x-ref="btn" class="header-btn notification-btn">
                    <i class="fa fa-bell"></i>
                    <!-- Badge Angka -->
                    @php
                        $unreadCount = \App\Models\Notifikasi::where('user_id', Auth::id())
                            ->where('status', 'belum_dibaca')
                            ->whereIn('tipe', ['balasan_komentar_admin', 'like_komentar_admin', 'tag_komentar_admin'])
                            ->count();
                    @endphp
                    @if ($unreadCount > 0)
                        <span class="badge">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <!-- Popover Notifikasi -->
                <div x-show="open" x-ref="pop" x-init="$watch('open', value => {
                    if (value) {
                        const rect = $refs.btn.getBoundingClientRect();
                        const popWidth = window.innerWidth <= 768 ? window.innerWidth - 16 : 256;

                        if (window.innerWidth <= 768) {
                            $refs.pop.style.width = (window.innerWidth - 32) + 'px';
                            $refs.pop.style.left = '16px';
                            $refs.pop.style.top = rect.bottom + 8 + 'px';
                        } else {
                            let left = rect.left - 560;
                            if (left + popWidth > window.innerWidth) {
                                left = window.innerWidth - popWidth - 8;
                            }
                            $refs.pop.style.top = rect.bottom + 8 + 'px';
                            $refs.pop.style.left = left + 'px';
                            $refs.pop.style.width = '35%';
                        }

                        // Tandai semua notifikasi yang belum dibaca sebagai dibaca
                        if (window.csrfToken) {
                            document.querySelectorAll('.notif-item-baru').forEach(el => {
                                const notifId = el.dataset.notifId;
                                fetch(`/notifikasi/${notifId}/baca`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': window.csrfToken
                                    },
                                    body: JSON.stringify({})
                                }).then(() => {
                                    el.classList.remove('notif-item-baru');
                                    const badge = el.querySelector('.badge');
                                    if (badge) badge.remove();
                                }).catch(console.error);
                            });

                            // Update badge global
                            fetch('/notifikasi/jumlah')
                                .then(r => r.json())
                                .then(data => {
                                    const badge = document.querySelector('.notification-btn .badge');
                                    if (data.count > 0 && badge) {
                                        badge.textContent = data.count;
                                    } else if (badge) {
                                        badge.remove();
                                    }
                                });
                        }
                    }
                })" @click.away="open = false" x-transition
                    style="position: absolute; display: none; z-index: 900; border-radius:10px"
                    :class="darkMode ? 'bg-dark border-dark text-white' : 'bg-white border-gray-200 text-gray-800 shadow-md'"
                    class="rounded-lg shadow-lg popover1">

                    @php
                        $adminNotif = \App\Models\Notifikasi::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->with(['komentar.artikel', 'komentar.user'])
                            ->take(5)
                            ->get();
                    @endphp

                    <div class="p-3 border-b font-semibold flex items-center"
                        :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                        <div class="header-notif">
                            <b><i class="fa fa-bell" style="margin-right:10px"></i> Notifikasi</b>
                            <a href="{{ route('notifikasi.baca-semua') }}" class="btn btn-sm text-sm mark-all-read"
                                @click.prevent="
                    if (window.csrfToken) {
                        fetch('/notifikasi/baca-semua', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window.csrfToken
                            },
                            body: JSON.stringify({})
                        }).then(() => {
                            document.querySelectorAll('.notif-item-baru').forEach(el => {
                                el.classList.remove('notif-item-baru');
                                const badge = el.querySelector('.badge');
                                if (badge) badge.remove();
                            });
                            // Update badge
                            fetch('/notifikasi/jumlah')
                                .then(r => r.json())
                                .then(data => {
                                    const badge = document.querySelector('.notification-btn .badge');
                                    if (data.count > 0 && badge) {
                                        badge.textContent = data.count;
                                    } else if (badge) {
                                        badge.remove();
                                    }
                                });
                        });
                    }
                ">
                                Tandai Semua Dibaca
                            </a>
                        </div>
                    </div>

                    <ul class="max-h-60 overflow-y-auto p-2 list-box-saran">
                        @forelse($adminNotif as $notif)
                            <li class="mb-2">
                                @php
                                    $url = '#';
                                    if ($notif->komentar && $notif->komentar->artikel_id) {
                                        $url =
                                            route('admin.artikel.detail', $notif->komentar->artikel_id) .
                                            '#komentar-' .
                                            $notif->referensi_id;
                                    }
                                @endphp
                                <a href="{{ $url }}" class="block">
                                    <div class="item-saran flex justify-between items-center p-3 rounded-lg shadow-sm {{ $notif->status == 'belum_dibaca' ? 'notif-item-baru bg-gray-100 dark:bg-gray-700' : '' }}"
                                        data-notif-id="{{ $notif->id }}">
                                        <div class="row flex w-full">
                                            <div class="col-md-6 flex items-center">
                                                <i class="fa-solid fa-comment fa-2x"></i>
                                                <span class="text-sm truncate flex-1 min-w-0 ml-3">
                                                    {{ Str::limit($notif->pesan, 30) }}
                                                </span>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <small class="text-xs text-gray-400">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </small>
                                                @if ($notif->status == 'belum_dibaca')
                                                    <span class="badge bg-primary text-xs ml-2">Baru</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li>
                                <div class="rounded-lg p-3 text-sm shadow-sm text-center"
                                    :class="darkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-50 text-gray-500'">
                                    Tidak ada notifikasi
                                </div>
                            </li>
                        @endforelse
                    </ul>

                    <div class="p-3 border-t flex notif-admin"
                        :class="darkMode ? 'border-gray-700' : 'border-gray-200'">

                        <a href="#" class="btn btn-sm text-sm font-medium text-white"
                            style="background-color: orange;">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
            <!-- Theme Toggle Button -->
            <button class="header-btn" @click="toggleTheme()">
                <i :class="darkMode ? 'fas fa-lightbulb' : 'fas fa-moon'"></i>
            </button>

            <div class="dropdown"
                :class="darkMode ? 'bg-dark border-white text-white' : 'bg-light border-orange text-dark'">
                <a href="#" class="user-profile dropdown-toggle" data-bs-toggle="dropdown">
                    @if (!Auth::user()->foto)
                        <img class="user-avatar" src="{{ asset('storage/user-img/default-user.jpg') }}" alt="">
                    @else
                        <img class="user-avatar" src="{{ asset('storage/user-img/' . Auth::user()->foto) }}"
                            alt="">
                    @endif
                    <div class="user-info">
                        <h6>{{ Auth::user()->nama }}</h6>
                        <small>{{ Auth::user()->role == 'super_admin' ? 'Super Admin' : '' }}</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ Route('admin.profile', Auth::user()->id) }}"><i
                                class="fas fa-user me-2"></i>Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ Route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<script>
    // Simpan CSRF Token global untuk dipakai di Alpine
    window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Auto-refresh badge notifikasi setiap 30 detik
    function autoRefreshNotifBadge() {
        if (!window.csrfToken) return;

        fetch('/notifikasi/jumlah')
            .then(r => r.json())
            .then(data => {
                const badge = document.querySelector('.absolute.-top-1.-right-1');
                if (data.count > 0) {
                    if (!badge) {
                        const btn = document.querySelector('[x-ref="btn"]');
                        if (btn) {
                            const newBadge = document.createElement('span');
                            newBadge.className =
                                'absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center';
                            newBadge.textContent = data.count;
                            btn.appendChild(newBadge);
                        }
                    } else {
                        badge.textContent = data.count;
                    }
                } else if (badge) {
                    badge.remove();
                }
            })
            .catch(console.error);
    }

    // Jalankan pertama kali
    document.addEventListener('DOMContentLoaded', function() {
        autoRefreshNotifBadge();
        setInterval(autoRefreshNotifBadge, 30000); // 30 detik
    });
</script>
