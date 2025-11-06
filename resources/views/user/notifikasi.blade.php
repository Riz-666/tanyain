@extends('layouts.app')

@section('title', 'Notifikasi - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/notif.css') }}">
@endsection

@section('content')
    <div class="container-notif">
        <!-- Header Section -->
        <div class="notifications-header">
            <div class="header-top">
                <h1 class="page-title">
                    <div class="title-icon">
                        <i class="fa fa-bell" style="font-style: normal;"></i>
                    </div>
                    Notifikasi
                </h1>
                <div class="header-actions">
                    <button type="button" class="action-btn" id="markAllRead">
                        <i class="fa fa-trash" style="font-style: normal;"></i>
                        Hapus Semua
                    </button>
                    <button class="action-btn" id="markAllRead">
                        <i class="fa fa-check" style="font-style: normal;"></i>
                        Tandai Sudah Dibaca
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-tabs">
                    <a href="#" class="filter-tab active" data-filter="all">
                        <span>Semua</span>
                        <span class="notification-count" id="count-all">{{ $notifikasi->total() }}</span>
                    </a>
                    <a href="#" class="filter-tab" data-filter="unread">
                        <span>Belum Dibaca</span>
                        <span class="notification-count"
                            id="count-unread">{{ Auth::user()->notifikasi()->where('status', 'belum_dibaca')->count() }}</span>
                    </a>
                    <a href="#" class="filter-tab" data-filter="mentions">
                        <span>Mention</span>
                        <span class="notification-count"
                            id="count-mentions">{{ Auth::user()->notifikasi()->where('tipe', 'tag')->where('status', 'belum_dibaca')->count() }}</span>
                    </a>
                    <a href="#" class="filter-tab" data-filter="system">
                        <span>Sistem</span>
                        <span class="notification-count"
                            id="count-system">{{ Auth::user()->notifikasi()->where('tipe', 'system')->where('status', 'belum_dibaca')->count() }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="notifications-container">
            @if ($notifikasi->isEmpty())
                <div class="notification-item text-center py-5">
                    <i class="fas fa-bell-slash text-muted" style="font-size: 48px; margin-bottom: 16px;"></i>
                    <p class="text-muted">Tidak ada notifikasi</p>
                </div>
            @else
                @foreach ($notifikasi as $notif)
                    <div class="notification-item {{ $notif->status == 'belum_dibaca' ? 'unread' : '' }}"
                        data-type="{{ $notif->tipe }}" data-id="{{ $notif->id }}">

                        <!-- Icon -->
                        <div class="notification-icon {{ $notif->tipe }}">
                            @if ($notif->tipe == 'komentar')
                                <i class="fa fa-comment" style="font-style: normal;"></i>
                            @elseif($notif->tipe == 'balasan')
                                <i class="fa fa-reply" style="font-style: normal;"></i>
                            @elseif($notif->tipe == 'tag')
                                <i class="fa fa-user" style="font-style: normal;color:rgb(255, 255, 255);"></i>
                            @elseif($notif->tipe == 'like')
                                <i class="fa fa-heart" style="font-style: normal;"></i>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="notification-content">
                            <!-- Title -->
                            <div class="notification-title">
                                @if ($notif->tipe == 'komentar')
                                    Komentar Baru pada Artikel Anda
                                @elseif($notif->tipe == 'balasan')
                                    Balasan atas Komentarmu
                                @elseif($notif->tipe == 'tag')
                                    Anda Disebut di Komentar
                                @elseif($notif->tipe == 'like')
                                    Komentar Anda Disukai
                                @endif
                            </div>

                            <!-- Message -->
                            <div class="notification-message">
                                @if ($notif->tipe == 'komentar')
                                    @if ($notif->komentar && $notif->komentar->user)
                                        <strong>{{ $notif->komentar->user->nama ?? 'Pengguna' }}</strong> memberikan
                                        komentar pada artikel "{{ $notif->komentar->artikel->judul ?? 'Artikel' }}":
                                        "{{ strip_tags(Str::limit($notif->komentar->isi, 120)) }}"
                                    @else
                                        <strong>Pengguna</strong> memberikan komentar pada artikel
                                        "{{ $notif->artikel->judul ?? 'Artikel' }}":
                                        "{{ strip_tags(Str::limit($notif->pesan, 120)) }}"
                                    @endif
                                @elseif($notif->tipe == 'balasan')
                                    @if ($notif->komentar && $notif->komentar->user)
                                        <strong>{{ $notif->komentar->user->nama ?? 'Pengguna' }}</strong> membalas komentar
                                        Anda: "{{ strip_tags(Str::limit($notif->komentar->isi, 120)) }}"
                                    @else
                                        <strong>Pengguna</strong> membalas komentar Anda:
                                        "{{ strip_tags(Str::limit($notif->pesan, 120)) }}"
                                    @endif
                                @elseif($notif->tipe == 'tag')
                                    @if ($notif->komentar && $notif->komentar->tags->first() && $notif->komentar->tags->first()->taggedUser)
                                        <strong>{{ $notif->komentar->user->nama ?? 'Pengguna' }}</strong> menyebut Anda
                                        di komentar:
                                        "{{ strip_tags(Str::limit($notif->komentar->isi, 120)) }}"
                                    @else
                                        <strong>Pengguna</strong> menyebut Anda di komentar:
                                        "{{ strip_tags(Str::limit($notif->pesan, 120)) }}"
                                    @endif
                                @elseif($notif->tipe == 'like')
                                    @if ($notif->komentar && $notif->komentar->user)
                                        @php
                                            $likeCount = \App\Models\Notifikasi::where(
                                                'referensi_id',
                                                $notif->referensi_id,
                                            )
                                                ->where('tipe', 'like')
                                                ->count();
                                            $otherUsers = $likeCount - 1;
                                        @endphp
                                        @if ($otherUsers > 0)
                                            <strong>{{ $notif->pengirim->nama ?? 'Pengguna' }}</strong> dan <span
                                                class="text-muted">{{ $otherUsers }} orang lainnya</span> menyukai
                                            komentar Anda: "{{ strip_tags(Str::limit($notif->komentar->isi, 120)) }}"
                                        @else
                                            <strong>{{ $notif->pengirim->nama ?? 'Pengguna' }}</strong> menyukai komentar
                                            Anda: "{{ strip_tags(Str::limit($notif->komentar->isi, 120)) }}"
                                        @endif
                                    @else
                                        <strong>Pengguna</strong> menyukai komentar Anda:
                                        "{{ strip_tags(Str::limit($notif->pesan, 120)) }}"
                                    @endif
                                @endif
                            </div>

                            <!-- Meta -->
                            <div class="notification-meta">
                                <span class="notification-time">{{ $notif->created_at->diffForHumans() }}</span>
                                <div class="notification-actions">
                                    @if ($notif->tipe == 'komentar' || $notif->tipe == 'balasan' || $notif->tipe == 'tag')
                                        @if ($notif->komentar)
                                            <button class="notification-action primary"
                                                onclick="window.location.href='{{ route('article.detail', ['id' => $notif->komentar->artikel_id]) }}#komentar-{{ $notif->komentar->id }}'">Lihat</button>
                                        @endif
                                    @elseif($notif->tipe == 'like')
                                        @if ($notif->komentar)
                                            <button class="notification-action primary"
                                                onclick="window.location.href='{{ route('article.detail', ['id' => $notif->komentar->artikel_id]) }}#komentar-{{ $notif->komentar->id }}'">Lihat
                                                Komentar</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Pagination — Bootstraps 5 Style -->
            <div class="pagination-wrapper">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{ $notifikasi->links('pagination::bootstrap-5') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial counts from server (PHP-rendered)
            const initialCounts = {
                all: parseInt(document.getElementById('count-all').textContent),
                unread: parseInt(document.getElementById('count-unread').textContent),
                mentions: parseInt(document.getElementById('count-mentions').textContent),
                system: parseInt(document.getElementById('count-system').textContent)
            };

            // Update all counters dynamically
            function updateAllCounts() {
                const items = document.querySelectorAll('.notification-item');
                let total = 0;
                let unread = 0;
                let mentions = 0;
                let system = 0;

                items.forEach(item => {
                    total++;
                    if (item.classList.contains('unread')) {
                        unread++;
                        const type = item.dataset.type;
                        if (type === 'tag') mentions++;
                        if (type === 'system') system++;
                    }
                });

                document.getElementById('count-all').textContent = total;
                document.getElementById('count-unread').textContent = unread;
                document.getElementById('count-mentions').textContent = mentions;
                document.getElementById('count-system').textContent = system;
            }

            // Filter functionality
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove(
                        'active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    filterNotifications(filter);
                });
            });

            function filterNotifications(filter) {
                const notifications = document.querySelectorAll('.notification-item');

                notifications.forEach(notification => {
                    const type = notification.dataset.type;
                    const isUnread = notification.classList.contains('unread');

                    let shouldShow = true;

                    switch (filter) {
                        case 'unread':
                            shouldShow = isUnread;
                            break;
                        case 'mentions':
                            shouldShow = type === 'tag';
                            break;
                        case 'system':
                            shouldShow = type === 'system';
                            break;
                        case 'all':
                        default:
                            shouldShow = true;
                    }

                    notification.style.display = shouldShow ? 'flex' : 'none';
                });

                // 🔥 Update ALL counters after filtering
                updateAllCounts();
            }

            // MARK ALL AS READ — SILENT UPDATE, NO ALERT
            document.getElementById('markAllRead').addEventListener('click', async function() {
                try {
                    const response = await fetch('{{ route('notifikasi.baca-semua') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        // Remove 'unread' class from ALL items
                        document.querySelectorAll('.notification-item.unread').forEach(item => {
                            item.classList.remove('unread');
                        });

                        // 🔥 RESET ALL COUNTERS TO 0
                        document.getElementById('count-all').textContent = '0';
                        document.getElementById('count-unread').textContent = '0';
                        document.getElementById('count-mentions').textContent = '0';
                        document.getElementById('count-system').textContent = '0';

                        // Optional: auto-switch to "Semua" tab after mark all read
                        document.querySelector('.filter-tab[data-filter="all"]').click();
                    } else {
                        const data = await response.json();
                        throw new Error(data.message || 'Gagal menandai semua sebagai dibaca');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // Silently fail — no alert, no disruption
                }
            });

            // SETTINGS BUTTON — tetap seperti aslinya
            document.getElementById('settingsBtn').addEventListener('click', function() {
                alert('Pengaturan notifikasi akan dibuka');
            });

            // CLICK NOTIFICATION ITEM → TANDAI SEBAGAI DIBACA
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', async function(e) {
                    if (!e.target.classList.contains('notification-action')) {
                        const id = this.dataset.id;
                        if (this.classList.contains('unread')) {
                            this.classList.remove('unread');
                            try {
                                await fetch(`{{ route('notifikasi.baca', ':id') }}`.replace(
                                    ':id', id), {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                });
                                // 🔥 UPDATE COUNTS AUTOMATICALLY
                                updateAllCounts();
                            } catch (err) {
                                console.error('Gagal update status:', err);
                            }
                        }
                    }
                });
            });

            // ACTION BUTTON HANDLERS — TANDAI SEBAGAI DIBACA JUGA
            document.querySelectorAll('.notification-action').forEach(btn => {
                btn.addEventListener('click', async function(e) {
                    e.stopPropagation();
                    const action = this.textContent.trim();
                    console.log('Action clicked:', action);

                    const item = this.closest('.notification-item');
                    if (item.classList.contains('unread')) {
                        item.classList.remove('unread');
                        const id = item.dataset.id;
                        try {
                            await fetch(`{{ route('notifikasi.baca', ':id') }}`.replace(':id',
                                id), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            });
                            // 🔥 UPDATE COUNTS AUTOMATICALLY
                            updateAllCounts();
                        } catch (err) {
                            console.error('Gagal update status:', err);
                        }
                    }
                });
            });

            // 🔥 Initialize counts on load
            updateAllCounts();
        });
    </script>

    <script>
        document.getElementById('markAllRead').addEventListener('click', function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menghapus semua notifikasi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE via fetch atau axios
                    fetch("{{ route('notifikasi.hapus-semua') }}", {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    // Reload halaman atau update UI tanpa reload
                                    location
                                .reload(); // opsional, bisa juga update DOM langsung
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus notifikasi.',
                            'error');
                        });
                }
            });
        });
    </script>
@endsection
