@extends('layouts.app')

@section('title', 'Profile ' . $user->nama . ' - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/profile/profile.css') }}">
@endsection

@section('content')
    <div class="container py-4" style="margin-top: 70px">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                    @if (Auth::check())
                        @if (Auth::user()->foto == null)
                            <img src="{{ asset('storage/user-img/default-user.jpg') }}" alt="Profile Avatar"
                                class="profile-avatar">
                        @else
                            <img src="{{ asset('storage/user-img/' . $user->foto) }}" alt="Profile Avatar"
                                class="profile-avatar">
                        @endif
                    @else
                        <img src="{{ asset('storage/user-img/' . $user->foto) }}" alt="Profile Avatar"
                            class="profile-avatar">
                    @endif
                </div>
                <div class="col-md-9">
                    <h1 class="profile-name">{{ $user->nama }} - ({{ $user->username }})</h1>
                    <p class="profile-bio">
                        {{ $user->bio ?? 'Belum Ada Bio' }}
                    </p>
                    <div class="social-links">
                        <a href="{{ $user->github }}" class="social-link">
                            <i class="fab fa-github"></i>
                            Github
                        </a>
                        <a href="{{ $user->linkedin }}" class="social-link">
                            <i class="fab fa-linkedin"></i>
                            LinkedIn
                        </a>
                        <a href="{{ $user->instagram }}" class="social-link">
                            <i class="fab fa-instagram"></i>
                            Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['artikel'] }}</span>
                        <div class="stat-label">Total Artikel</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['repositori'] }}</span>
                        <div class="stat-label">Total Repository</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($stats['views']) }}</span>
                        <div class="stat-label">Total Views</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['files'] }}</span>
                        <div class="stat-label">Total File</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Search Section -->
        <div class="search-section">
            <div class="row">
                @if (Auth::check())
                    <div class="col-md-8 mb-3 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" id="searchInput" name="q"
                                placeholder="Cari artikel atau repository..." value="{{ $query ?? '' }}">
                            <button class="btn search-btn" type="submit" id="searchBtn">
                                <i class="fas fa-search me-2"></i>Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="">
                            <select class="form-select search-input" id="filterSelect" name="filter"
                                onchange="this.form.submit()">
                                <option value="all" {{ ($filter ?? 'all') == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="publik" {{ ($filter ?? '') == 'publik' ? 'selected' : '' }}>Publik Saja
                                </option>
                                <option value="private" {{ ($filter ?? '') == 'private' ? 'selected' : '' }}>Private Saja
                                </option>
                            </select>
                        </form>
                    </div>
                @else
                    <div class="col-md-12 mb-3 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" id="searchInput" name="q"
                                placeholder="Cari artikel atau repository..." value="{{ $query ?? '' }}">
                            <button class="btn search-btn" type="submit" id="searchBtn">
                                <i class="fas fa-search me-2"></i>Cari
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Content Tabs -->
        <div class="content-section">
            <ul class="nav nav-tabs tab-nav" id="contentTabs" role="tablist">
                @php
                    $activeTab = request('tab', 'articles'); // default 'articles'
                @endphp

                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'articles' ? 'active' : '' }}" id="articles-tab"
                        data-bs-toggle="tab" data-bs-target="#articles" type="button" role="tab">
                        <i class="fas fa-newspaper me-2"></i>Artikel Saya
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'repos' ? 'active' : '' }}" id="repos-tab"
                        data-bs-toggle="tab" data-bs-target="#repos" type="button" role="tab">
                        <i class="fa fa-folder-open me-2"></i>Repository
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="contentTabsContent">
                <!-- Articles Tab -->
                <div class="tab-pane fade {{ $activeTab === 'articles' ? 'show active' : '' }}" id="articles"
                    role="tabpanel">
                    <div id="articlesContainer">
                        @forelse ($artikel as $ua)
                            <div class="article-card">
                                <a href="{{ route('article.detail', $ua->id) }}" class="article-title">
                                    <i class="fa fa-newspaper me-2"></i>{{ $ua->judul }}
                                </a>
                                <p class="article-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($ua->isi), 300) }}
                                </p>
                                <div class="mb-2 ">
                                    @foreach ($ua->tag->take(3) as $tag)
                                        <span class="tag">{{ $tag->nama_tag }}</span>
                                    @endforeach
                                </div>
                                <div
                                    class="article-meta d-flex justify-content-between align-items-end mt-3 pt-2 border-top">
                                    <div class="meta-info text-muted">
                                        @if ($ua->status == 'publik')
                                            <small class="badge bg-secondary"><i class="fa fa-lock-open"></i>
                                                {{ $ua->status }}</small>
                                        @else
                                            <small class="badge bg-secondary"><i class="fa fa-lock"></i>
                                                {{ $ua->status }}</small>
                                        @endif
                                        <span>
                                            <i class="fas fa-calendar me-1"></i>
                                            Di Buat {{ $ua->created_at ? $ua->created_at->diffForHumans() : 'Unknown' }}
                                        </span>
                                        <span>
                                            <i class="fas fa-edit me-1"></i>
                                            Di Perbarui
                                            {{ $ua->updated_at ? $ua->updated_at->diffForHumans() : 'Unknown' }}
                                        </span>
                                    </div>
                                    <div class="btn-group-custom">
                                        @if (auth()->check() && auth()->id() === $ua->user_id)
                                            <a href="{{ route('edit.artikel', $ua->id) }}"
                                                class="btn btn-edit btn-sm me-2">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $ua->id }}" data-type="artikel">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="fa fa-newspaper fa-2x d-block mb-2"></i>
                                <span>Belum ada Artikel</span>
                            </div>
                        @endforelse


                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $artikel->appends(['tab' => 'articles'])->links('pagination::bootstrap-5') }}
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Repositories Tab -->
                <div class="tab-pane fade {{ $activeTab === 'repos' ? 'show active' : '' }}" id="repos"
                    role="tabpanel">
                    <div id="reposContainer">
                        @forelse ($repositori as $repo)
                            <div class="repo-card">
                                <a href="{{ Route('repo.detail', $repo->id) }}" class="repo-title">
                                    <i class="fa fa-folder-open me-2"></i>{{ $repo->judul_repo }}
                                </a>
                                <p class="repo-description">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($repo->deskripsi), 300) }}
                                </p>
                                <div class="repo-meta d-flex justify-content-between align-items-end mt-3 pt-2 border-top">
                                    <div class="meta-info text-muted">
                                        @if ($repo->status == 'publik')
                                            <small class="badge bg-secondary"><i class="fa fa-lock-open"></i>
                                                {{ $repo->status }}</small>
                                        @else
                                            <small class="badge bg-secondary"><i class="fa fa-lock"></i>
                                                {{ $repo->status }}</small>
                                        @endif
                                        <span>
                                            <i class="fas fa-calendar me-1"></i>
                                            Di Buat
                                            {{ $repo->created_at ? $repo->created_at->diffForHumans() : 'Unknown' }}
                                        </span>
                                        <span>
                                            <i class="fas fa-edit me-1"></i>
                                            Di Perbarui
                                            {{ $repo->updated_at ? $repo->updated_at->diffForHumans() : 'Unknown' }}
                                        </span>
                                    </div>
                                    <div class="btn-group-custom">
                                        @if (auth()->check() && auth()->id() === $repo->user_id)
                                            <a href="{{ route('edit.repo', $repo->id) }}"
                                                class="btn btn-edit btn-sm me-2">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $repo->id }}" data-type="repo">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="fa fa-folder-open fa-2x d-block mb-2"></i>
                                <span>Belum ada Repositori</span>
                            </div>
                        @endforelse
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $repositori->appends(['tab' => 'repos'])->links('pagination::bootstrap-5') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast for notifications -->
    <div class="toast align-items-center text-bg-success border-0" role="alert" id="successToast"
        style="display: none;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

    <!-- SweetAlert2 (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Highcharts (HANYA SATU VERSION!) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.2.0/highcharts.min.js"></script>

    <script>
        // Minimal JavaScript for functionality
        document.getElementById('searchBtn').addEventListener('click', function() {
            const query = document.getElementById('searchInput').value.toLowerCase().trim();
            const filter = document.getElementById('filterSelect').value;

            if (!query) {
                showToast('Masukkan kata kunci pencarian', 'warning');
                return;
            }

            const articles = document.querySelectorAll('.article-card');
            const repos = document.querySelectorAll('.repo-card');
            let foundArticles = 0,
                foundRepos = 0;

            if (filter === 'all' || filter === 'articles') {
                articles.forEach(article => {
                    const title = article.querySelector('.article-title').textContent.toLowerCase();
                    const excerpt = article.querySelector('.article-excerpt').textContent.toLowerCase();
                    const match = title.includes(query) || excerpt.includes(query);
                    article.style.display = match ? 'block' : 'none';
                    if (match) foundArticles++;
                });
            } else {
                articles.forEach(article => article.style.display = 'none');
            }

            if (filter === 'all' || filter === 'repos') {
                repos.forEach(repo => {
                    const title = repo.querySelector('.repo-title').textContent.toLowerCase();
                    const desc = repo.querySelector('.repo-description').textContent.toLowerCase();
                    const match = title.includes(query) || desc.includes(query);
                    repo.style.display = match ? 'block' : 'none';
                    if (match) foundRepos++;
                });
            } else {
                repos.forEach(repo => repo.style.display = 'none');
            }

            if (foundArticles && !foundRepos) {
                new bootstrap.Tab(document.getElementById('articles-tab')).show();
            } else if (foundRepos && !foundArticles) {
                new bootstrap.Tab(document.getElementById('repos-tab')).show();
            } else if (foundArticles && foundRepos) {
                new bootstrap.Tab(document.getElementById('articles-tab')).show();
            }

            showToast(`Ditemukan ${foundArticles} artikel & ${foundRepos} repository untuk "${query}"`, 'success');
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') document.getElementById('searchBtn').click();
        });

        // Update statistics — AMAN DAN TIDAK AKAN CRASH
        function updateStats(type, change) {
            const statsMap = {
                'articles': 0,
                'repos': 1
            };
            const statIndex = statsMap[type];

            const statElements = document.querySelectorAll('.stat-number');
            if (!statElements || statIndex >= statElements.length) {
                console.warn(`Stat element for type "${type}" not found in DOM.`);
                return;
            }

            const statElement = statElements[statIndex];
            if (!statElement) {
                console.warn(`Stat element at index ${statIndex} is null.`);
                return;
            }

            const currentValue = parseInt(statElement.textContent) || 0;
            const newValue = Math.max(0, currentValue + change);

            animateNumber(statElement, currentValue, newValue, 500);
        }

        function animateNumber(element, start, end, duration) {
            const range = end - start;
            const increment = range / (duration / 16);
            let current = start;
            const timer = setInterval(() => {
                current += increment;
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                    current = end;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 16);
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('successToast');
            const toastBody = toast.querySelector('.toast-body');

            toast.className = 'toast align-items-center border-0';

            if (type === 'error') {
                toast.classList.add('text-bg-danger');
                toastBody.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${message}`;
            } else if (type === 'warning') {
                toast.classList.add('text-bg-warning');
                toastBody.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
            } else if (type === 'info') {
                toast.classList.add('text-bg-info');
                toastBody.innerHTML = `<i class="fas fa-info-circle me-2"></i>${message}`;
            } else {
                toast.classList.add('text-bg-success');
                toastBody.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
            }

            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        document.getElementById('filterSelect').addEventListener('change', function() {
            const filter = this.value;
            const articlesTab = document.getElementById('articles-tab');
            const reposTab = document.getElementById('repos-tab');

            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.article-card, .repo-card').forEach(card => card.style.display = 'block');

            if (filter === 'articles') articlesTab.click();
            else if (filter === 'repos') reposTab.click();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const socialLinks = document.querySelectorAll('.social-link');
            socialLinks.forEach(link => {
                link.addEventListener('mouseenter', () => link.style.transform =
                    'translateY(-2px) scale(1.05)');
                link.addEventListener('mouseleave', () => link.style.transform = 'translateY(0) scale(1)');
            });

            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    setTimeout(() => ripple.remove(), 600);
                });
            });

            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function() {
                    const target = this.getAttribute('data-bs-target');
                    const targetElement = document.querySelector(target);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                });
            });

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const cards = document.querySelectorAll('.article-card, .repo-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                card.style.transitionDelay = `${index * 0.1}s`;
                observer.observe(card);
            });

            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.toLowerCase();
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        const suggestions = ['React', 'Vue.js', 'Node.js', 'JavaScript',
                            'TypeScript', 'Performance', 'API', 'Frontend', 'Backend',
                            'Database'
                        ];
                        const matches = suggestions.filter(item => item.toLowerCase().includes(
                            query));
                        if (matches.length > 0) console.log('Suggestions:', matches);
                    }, 300);
                }
            });
        });

        // Delete with SweetAlert + AJAX — AMAN DAN TIDAK MENGGUNAKAN FORM
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (!deleteBtn) return;

            const id = deleteBtn.getAttribute('data-id');
            const type = deleteBtn.getAttribute('data-type');

            if (!id || !type) return;

            let url, messageSuccess;

            if (type === 'artikel') {
                url = '/artikel/hapus/' + id;
                messageSuccess = 'Artikel masuk trash (20 hari)';
            } else if (type === 'repo') {
                url = '/hapus-repo/' + id;
                messageSuccess = 'Repositori berhasil dihapus sementara. Bisa di-restore dalam 20 hari.';
            } else {
                return;
            }

            Swal.fire({
                title: 'Hapus ' + (type === 'artikel' ? 'Artikel' : 'Repository') + '?',
                text: "Data yang dihapus tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const originalIcon = deleteBtn.innerHTML;
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    deleteBtn.disabled = true;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                deleteBtn.closest('.article-card, .repo-card').remove();
                                updateStats(type, -1); // ← AMAN SEKARANG!
                                showToast(messageSuccess, 'success');
                            } else {
                                showToast('Gagal menghapus: ' + data.message, 'error');
                            }
                        })
                        .catch((error) => {
                            console.error('Fetch error:', error); // 👈 DEBUG
                            showToast('Terjadi kesalahan jaringan.', 'error');
                        })
                        .finally(() => {
                            deleteBtn.innerHTML = originalIcon;
                            deleteBtn.disabled = false;
                        });
                }
            });
        });

        // Add CSS animation for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to { transform: scale(2); opacity: 0; }
            }
            .card-enter { opacity: 0; transform: translateY(20px); }
            .card-enter-active { opacity: 1; transform: translateY(0); transition: all 0.6s ease; }
        `;
        document.head.appendChild(style);
    </script>
@endsection
