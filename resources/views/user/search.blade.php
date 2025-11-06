@extends('layouts.app')

@section('title', 'Hasil Pencarian - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/search.css') }}">
@endsection

@section('content')
    <div class="container-main">
        <!-- Search Header -->
        <div class="search-header">
            <h1 class="search-title">
                <i class="fas fa-search me-2"></i>
                Hasil Pencarian
            </h1>
            <p class="search-info">
                Menampilkan hasil untuk kata kunci: <span class="search-keyword">"{{ $keyword }}"</span>
                @if ($tab === 'artikel')
                    - Ditemukan
                    <strong>{{ is_object($artikels) && method_exists($artikels, 'total') ? $artikels->total() : 0 }}</strong>
                    artikel
                @elseif($tab === 'repositori')
                    - Ditemukan
                    <strong>{{ is_object($repos) && method_exists($repos, 'total') ? $repos->total() : 0 }}</strong>
                    repository
                @else
                    - Ditemukan
                    <strong>{{ is_object($artikels) && method_exists($artikels, 'total') ? $artikels->total() : 0 }}</strong>
                    artikel dan
                    <strong>{{ is_object($repos) && method_exists($repos, 'total') ? $repos->total() : 0 }}</strong>
                    repository
                @endif
            </p>

            <!-- Filter Tabs -->
            <ul class="nav nav-tabs filter-tabs" id="filterTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $tab === 'all' || $tab === '' ? 'active' : '' }}"
                        href="{{ route('search.all', ['search' => $keyword, 'tab' => 'all']) }}" id="all-tab"
                        data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>Semua
                        ({{ (is_object($artikels) && method_exists($artikels, 'total') ? $artikels->total() : 0) + (is_object($repos) && method_exists($repos, 'total') ? $repos->total() : 0) }})
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $tab === 'artikel' ? 'active' : '' }}"
                        href="{{ route('search.all', ['search' => $keyword, 'tab' => 'artikel']) }}" id="articles-tab"
                        data-bs-toggle="tab" data-bs-target="#articles" type="button" role="tab">
                        <i class="fas fa-file-alt me-2"></i>Artikel
                        ({{ is_object($artikels) && method_exists($artikels, 'total') ? $artikels->total() : 0 }})
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $tab === 'repositori' ? 'active' : '' }}"
                        href="{{ route('search.all', ['search' => $keyword, 'tab' => 'repositori']) }}"
                        id="repositories-tab" data-bs-toggle="tab" data-bs-target="#repositories" type="button"
                        role="tab">
                        <i class="fa-regular fa-folder-open me-2"></i>Repository
                        ({{ is_object($repos) && method_exists($repos, 'total') ? $repos->total() : 0 }})
                    </a>
                </li>
            </ul>
        </div>

        <!-- Results Content -->
        <div class="tab-content" id="filterTabsContent">
            <!-- All Results Tab -->
            <div class="tab-pane fade show {{ $tab === 'all' || $tab === '' ? 'active' : '' }}" id="all"
                role="tabpanel">
                <!-- Articles Section -->
                @if (is_object($artikels) && $artikels->isNotEmpty())
                    <div class="results-section">
                        <h2 class="section-title">
                            <i class="fas fa-file-alt"></i>
                            Artikel
                            <span class="result-count">{{ $artikels->total() }} hasil</span>
                        </h2>

                        @foreach ($artikels as $artikel)
                            <div class="article-card">
                                @if ($artikel->cover)
                                    <img src="{{ asset('storage/artikel/' . $artikel->id . '/cover/' . $artikel->cover) }}"
                                        alt="Article Cover" class="article-cover">
                                @else
                                    <div class="article-cover-placeholder">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                                <div class="article-content">
                                    <div class="article-author">
                                        <img src="{{ $artikel->user?->foto
                                            ? asset('storage/user-img/' . $artikel->user->foto)
                                            : asset('storage/user-img/default-user.jpg') }}"
                                            alt="Author" class="author-avatar">

                                        <div class="author-info">
                                            <p class="author-name">{{ $artikel->user->name ?? 'Pengguna Di Non-Aktifkan' }}</p>
                                            <p class="author-role">{{ $artikel->user->bio ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <a href="{{ route('article.detail', $artikel->id) }}"
                                        class="article-title">{{ $artikel->judul }}</a>

                                    <p class="article-description">
                                        {!! Str::limit(strip_tags($artikel->isi), 150) !!}
                                    </p>

                                    <div class="article-tags">
                                        @foreach ($artikel->tag as $tag)
                                            <span class="tag">{{ $tag->nama_tag }}</span>
                                        @endforeach
                                    </div>

                                    <div class="article-meta">
                                        <div class="meta-left">
                                            <div class="meta-item">
                                                <i class="fas fa-calendar"></i>
                                                <span>{{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d F Y') }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-eye"></i>
                                                <span
                                                    class="stat-number">{{ number_format($artikel->viewArtikel->count()) }}</span>
                                                Pengunjung
                                            </div>
                                        </div>
                                        <div
                                            class="status-badge {{ $artikel->status === 'publik' ? 'status-published' : 'status-private' }}">
                                            <i
                                                class="fas {{ $artikel->status === 'publik' ? 'fa-lock-open' : 'fa-lock' }} me-1"></i>
                                            {{ $artikel->status === 'publik' ? 'Publik' : 'Private' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination for Articles in 'all' tab -->
                        @if ($artikels->hasPages())
                            <div class="pagination-wrapper">
                                <nav aria-label="Articles pagination">
                                    {{ $artikels->appends(['search' => $keyword, 'tab' => $tab])->links('pagination::bootstrap-5', ['page_name' => 'page_artikel']) }}
                                </nav>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Repositories Section -->
                @if (is_object($repos) && $repos->isNotEmpty())
                    <div class="results-section">
                        <h2 class="section-title">
                            <i class="fa-regular fa-folder-open"></i>
                            Repository
                            <span class="result-count">{{ $repos->total() }} hasil</span>
                        </h2>

                        @foreach ($repos as $repo)
                            <div class="repo-card">
                                <div class="repo-header">
                                    <div class="repo-author">
                                        @if ($repo->user)
                                            <img src="{{ $repo->user->foto
                                                ? asset('storage/user-img/' . $repo->user->foto)
                                                : asset('storage/user-img/default-user.jpg') }}"
                                                alt="Author" class="author-avatar">
                                        @else
                                            <img src="{{ asset('storage/user-img/default-user.jpg') }}"
                                                alt="Pengguna Terhapus" class="author-avatar">
                                        @endif
                                        <div class="author-info">
                                            <p class="author-name">{{ $repo->user->name ?? 'Pengguna Di Non-Aktifkan' }}</p>
                                            <p class="author-role">{{ $repo->user->bio ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="visibility-badge {{ $repo->status === 'publik' ? 'visibility-public' : 'visibility-private' }}">
                                        <i class="fas {{ $repo->status === 'publik' ? 'fa-globe' : 'fa-lock' }}"></i>
                                        {{ $repo->status === 'publik' ? 'Public' : 'Private' }}
                                    </div>
                                </div>

                                <a href="{{ route('repo.detail', $repo->id) }}" class="repo-title">
                                    <i class="fa-regular fa-folder-open me-2"></i>
                                    {{ $repo->judul_repo }}
                                </a>

                                <p class="repo-description">
                                    {!! Str::limit($repo->deskripsi, 150) !!}
                                </p>

                                <div class="repo-stats">
                                    <div class="stats-left">
                                        <div class="stat-item">
                                            <i class="fas fa-file-alt"></i>
                                            <span class="stat-number">{{ $repo->jumlah_file ?? 0 }}</span> File Di Unggah
                                        </div>
                                        <div class="stat-item">
                                            <i class="fas fa-download"></i>
                                            <span class="stat-number">{{ $repo->download_count ?? 0 }}</span> File Di
                                            Unduh
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination for Repos in 'all' tab -->
                        @if ($repos->hasPages())
                            <div class="pagination-wrapper">
                                <nav aria-label="Repositories pagination">
                                    {{ $repos->appends(['search' => $keyword, 'tab' => $tab])->links('pagination::bootstrap-5', ['page_name' => 'page_repos']) }}
                                </nav>
                            </div>
                        @endif
                    </div>
                @endif

                @if (is_object($artikels) && $artikels->isEmpty() && (is_object($repos) && $repos->isEmpty()))
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>Tidak ada hasil ditemukan</h4>
                        <p>Coba gunakan kata kunci lain atau cek ejaan Anda.</p>
                    </div>
                @endif
            </div>

            <!-- Articles Only Tab -->
            <div class="tab-pane fade {{ $tab === 'artikel' ? 'active show' : '' }}" id="articles" role="tabpanel">
                <div class="results-section">
                    <h2 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Semua Artikel
                        <span
                            class="result-count">{{ is_object($artikels) && method_exists($artikels, 'total') ? $artikels->total() : 0 }}
                            hasil</span>
                    </h2>

                    <p class="text-muted mb-4">Menampilkan semua artikel yang mengandung kata kunci "{{ $keyword }}"
                    </p>

                    @if (is_object($artikels) && $artikels->isNotEmpty())
                        @foreach ($artikels as $artikel)
                            <div class="article-card">
                                @if ($artikel->cover)
                                    <img src="{{ asset('storage/artikel/' . $artikel->id . '/cover/' . $artikel->cover) }}"
                                        alt="Article Cover" class="article-cover">
                                @else
                                    <div class="article-cover-placeholder">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                                <div class="article-content">
                                    <div class="article-author">
                                        <img src="{{ $artikel->user?->foto
                                            ? asset('storage/user-img/' . $artikel->user->foto)
                                            : asset('storage/user-img/default-user.jpg') }}"
                                            alt="Author" class="author-avatar">

                                        <div class="author-info">
                                            <p class="author-name">{{ $artikel->user->name ?? 'Pengguna Di Non-Aktifkan' }}</p>
                                            <p class="author-role">{{ $artikel->user->bio ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <a href="{{ route('article.detail', $artikel->id) }}"
                                        class="article-title">{{ $artikel->judul }}</a>

                                    <p class="article-description">
                                        {!! Str::limit(strip_tags($artikel->isi), 150) !!}
                                    </p>

                                    <div class="article-tags">
                                        @foreach ($artikel->tag as $tag)
                                            <span class="tag">{{ $tag->nama_tag }}</span>
                                        @endforeach
                                    </div>

                                    <div class="article-meta">
                                        <div class="meta-left">
                                            <div class="meta-item">
                                                <i class="fas fa-calendar"></i>
                                                <span>{{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d F Y') }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-eye"></i>
                                                <span
                                                    class="stat-number">{{ number_format($artikel->viewArtikel->count()) }}</span>
                                                Pengunjung
                                            </div>
                                        </div>
                                        <div
                                            class="visibility-badge {{ $artikel->status === 'publik' ? 'visibility-public' : 'visibility-private' }}">
                                            <i
                                                class="fas {{ $artikel->status === 'publik' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                            {{ $artikel->status === 'publik' ? 'Public' : 'Private' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="pagination-wrapper">
                            <nav aria-label="Articles pagination">
                                {{ $artikels->appends(['search' => $keyword, 'tab' => $tab])->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-file-alt"></i>
                            <h4>Artikel Lainnya</h4>
                            <p>Belum ada artikel yang sesuai dengan pencarian Anda.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Repositories Only Tab -->
            <div class="tab-pane fade {{ $tab === 'repositori' ? 'active show' : '' }}" id="repositories"
                role="tabpanel">
                <div class="results-section">
                    <h2 class="section-title">
                        <i class="fa-regular fa-folder-open"></i>
                        Semua Repository
                        <span
                            class="result-count">{{ is_object($repos) && method_exists($repos, 'total') ? $repos->total() : 0 }}
                            hasil</span>
                    </h2>

                    <p class="text-muted mb-4">Menampilkan semua repository yang mengandung kata kunci
                        "{{ $keyword }}"</p>

                    @if (is_object($repos) && $repos->isNotEmpty())
                        @foreach ($repos as $repo)
                            <div class="repo-card">
                                <div class="repo-header">
                                    <div class="repo-author">
                                        @if ($repo->user)
                                            <img src="{{ $repo->user->foto
                                                ? asset('storage/user-img/' . $repo->user->foto)
                                                : asset('storage/user-img/default-user.jpg') }}"
                                                alt="Author" class="author-avatar">
                                        @else
                                            <img src="{{ asset('storage/user-img/default-user.jpg') }}"
                                                alt="Pengguna Terhapus" class="author-avatar">
                                        @endif
                                        <div class="author-info">
                                            <p class="author-name">{{ $repo->user->name ?? 'Pengguna Di Non-Aktifkan' }}
                                            </p>
                                            <p class="author-role">{{ $repo->user->bio ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="visibility-badge {{ $repo->status === 'publik' ? 'visibility-public' : 'visibility-private' }}">
                                        <i class="fas {{ $repo->status === 'publik' ? 'fa-globe' : 'fa-lock' }}"></i>
                                        {{ $repo->status === 'publik' ? 'Public' : 'Private' }}
                                    </div>
                                </div>

                                <a href="{{ route('repo.detail', $repo->id) }}" class="repo-title">
                                    <i class="fa-regular fa-folder-open me-2"></i>
                                    {{ $repo->judul_repo }}
                                </a>

                                <p class="repo-description">
                                    {!! Str::limit($repo->deskripsi, 150) !!}
                                </p>

                                <div class="repo-stats">
                                    <div class="stats-left">
                                        <div class="stat-item">
                                            <i class="fas fa-file-alt"></i>
                                            <span class="stat-number">{{ $repo->jumlah_file ?? 0 }}</span> File Di Unggah
                                        </div>
                                        <div class="stat-item">
                                            <i class="fas fa-download"></i>
                                            <span class="stat-number">{{ $repo->download_count ?? 0 }}</span> File Di
                                            Unduh
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="pagination-wrapper">
                            <nav aria-label="Repositories pagination">
                                {{ $repos->appends(['search' => $keyword, 'tab' => $tab])->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fab fa-github"></i>
                            <h4>Repository Lainnya</h4>
                            <p>Belum ada repository yang sesuai dengan pencarian Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <style>
        mark {
            padding: 0.1em 0.2em;
            border-radius: 0.2em;
            background: var(--light-orange);
            color: var(--primary-orange);
            font-weight: 600;
        }

        .article-cover-placeholder {
            width: 100%;
            height: 180px;
            background-color: #FFA500;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .article-cover-placeholder .fas.fa-image {
            font-size: 48px;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            opacity: 0.9;
        }
    </style>
@endsection
@section('scripts')
    <script>
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            let tab = urlParams.get('tab') || 'all';

            // Deteksi pagination khusus - override tab
            if (urlParams.has('page') && !urlParams.has('page_artikel') && !urlParams.has('page_repos')) {
                // page biasa tanpa suffix = tab artikel atau repositori
                if (tab === 'artikel') tab = 'artikel';
                else if (tab === 'repositori') tab = 'repositori';
            } else if (urlParams.has('page_repos')) {
                // Ada page_repos, pasti di tab repositori
                tab = 'repositori';
            } else if (urlParams.has('page_artikel')) {
                // Ada page_artikel, pasti di tab artikel
                tab = 'artikel';
            }

            // Force tab active berdasarkan URL
            const tabMap = {
                'all': '#all-tab',
                'artikel': '#articles-tab',
                'repositori': '#repositories-tab'
            };

            const contentMap = {
                'all': '#all',
                'artikel': '#articles',
                'repositori': '#repositories'
            };

            // Remove active from all
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(el => {
                el.classList.remove('show', 'active');
            });

            // Activate correct tab
            if (tabMap[tab]) {
                const nav = document.querySelector(tabMap[tab]);
                const content = document.querySelector(contentMap[tab]);
                if (nav) nav.classList.add('active');
                if (content) content.classList.add('show', 'active');
            }

            // Update URL tanpa reload biar tab parameter sync
            if (urlParams.get('tab') !== tab) {
                urlParams.set('tab', tab);
                const newUrl = window.location.pathname + '?' + urlParams.toString();
                window.history.replaceState({}, '', newUrl);
            }
        })();
    </script>
@endsection
