@extends('layouts.app')

@section('title', 'Repositori - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/repositori/repo.css') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="header-content">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                                <li class="breadcrumb-item active">Repositori</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="fas fa-folder-open me-3"></i>Repositori
                        </h1>
                        <p class="page-subtitle">
                            Tempat terbaik untuk menyimpan dan berbagi file pengetahuan. Jelajahi repositori dan
                            temukan inspirasi baru setiap hari.
                        </p>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="header-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $totalRepo }}</div>
                            <div class="stat-label" style="margin-left: 10px">Total Repositori</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $totalPengguna }}</div>
                            <div class="stat-label" style="margin-left: 10px">Kontributor</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="search-filter-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="search-box-main">
                        <form method="GET" action="{{ route('repository') }}">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control"
                                    placeholder="Cari Repositori berdasarkan judul dan konten ..." name="search"
                                    value="{{ $search ?? '' }}" required>
                                <button class="btn btn-primary-custom" type="submit">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                            </div>
                            {{-- Supaya filter sort tetap kepake waktu search --}}
                            <input type="hidden" name="sort" value="">
                        </form>
                    </div>
                </div>
                @if (Auth::check())
                    <div class="col-lg-3">
                        <div class="view-toggle">
                            <a class="btn btn-primary-custom add ms-2" href="{{ route('repo.create') }}"
                                style="height:58px; display:flex; align-items:center; justify-content:center; border-radius:10px;">
                                <i class="fas fa-pen-clip me-1"></i> Buat Repository
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="view-toggle">
                            <select class="form-select ms-2" id="sortArticles" name="sort"
                                style="height:58px; border-radius:10px;" onchange="this.form.submit()">
                                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Judul A-Z</option>
                                <option value="author" {{ $sort == 'author' ? 'selected' : '' }}>Pembuat</option>
                            </select>
                        </div>
                    </div>
                @else
                    <div class="col-lg-6">
                        <div class="view-toggle">
                            <select class="form-select ms-2" id="sortArticles" name="sort"
                                style="height:58px; border-radius:10px;" onchange="this.form.submit()">
                                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Judul A-Z</option>
                                <option value="author" {{ $sort == 'author' ? 'selected' : '' }}>Pembuat</option>
                            </select>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>



    <!-- Main Content -->
    <section class="repositories-content">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="sidebar">
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-fire me-2"></i>Repositori Terpopuler
                            </h3>
                            <div class="popular-repositories">
                                @forelse ($popularRepos as $popular)
                                    <div class="popular-item">
                                        <div class="popular-icon">
                                            <i class="fa-regular fa-folder-open"></i>
                                        </div>
                                        <div class="popular-content">
                                            <h4 class="popular-title">
                                                <a
                                                    href="{{ Route('repo.detail', $popular->id) }}">{{ $popular->judul_repo }}</a>
                                            </h4>
                                            <div class="popular-meta">
                                                <span class="stars">
                                                    <i class="fas fa-download me-1"></i>{{ $popular->downloads_count }}
                                                    File Terunduh
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Belum ada repositori populer</p>
                                @endforelse
                            </div>
                        </div>
                        <!-- Top Contributors -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-users me-2"></i>Top Kontributor
                            </h3>
                            <div class="contributors-list">
                                @foreach ($topContributors as $contributor)
                                    <div class="contributor-item">
                                        <div class="contributor-avatar">
                                            @if (!$contributor->foto)
                                                <i class="fa fa-user"></i>
                                            @else
                                                <img src="{{ asset('storage/user-img/' . $contributor->foto) }}"
                                                    alt="">
                                            @endif
                                        </div>
                                        <div class="contributor-info">
                                            <div class="contributor-name"><a
                                                    href="{{ Route('profile', $contributor->id) }}">{{ $contributor->nama }}</a>
                                            </div>
                                            <div class="contributor-repos">{{ $contributor->repositori_count }} repositori
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repositories Grid -->
                <div class="col-lg-9">
                    <div class="repositories-header">
                        <div class="results-info">
                            <span class="results-count">
                                Menampilkan <strong>{{ $repo->firstItem() }}-{{ $repo->lastItem() }}</strong>
                                dari <strong>{{ $repo->total() }}</strong> Repository
                            </span>

                        </div>
                    </div>

                    <div class="repositories-grid" id="repositoriesGrid">
                        @forelse ($repo as $rp)
                            <!-- Repository Card 2 -->
                            <div class="repo-card" data-language="javascript">
                                <div class="repo-header">
                                    <div class="repo-icon">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <div class="repo-title">
                                        <h3><a href="{{ route('repo.detail', $rp->id) }}">{{ $rp->judul_repo }}</a></h3>
                                        <span class="repo-visibility">
                                            @if ($rp->status === 'publik')
                                                <i class="fas fa-lock-open me-1"></i>Public
                                            @else
                                                <i class="fas fa-lock me-1"></i>Private
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <p class="repo-description">
                                    {!! Str::limit($rp->deskripsi, 200) !!}
                                </p>

                                <div class="repo-author" style="display: flex">
                                    <div class="author-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name"
                                            style="margin-left:10px">{{ $rp->user->nama ?? 'Anonim' }}</span>
                                    </div>
                                </div>

                                <div class="repo-meta mt-3">
                                    <div class="meta-left">
                                        <span class="repo-size">
                                            <i class="fas fa-upload me-1"></i>{{ $rp->file_repo_count }} file
                                        </span>
                                        <span class="repo-size">
                                            <i class="fas fa-download me-1"></i>{{ $rp->downloads_count }}
                                        </span>

                                    </div>
                                    <div class="meta-right">
                                        <span class="updated-time">
                                            <i class="fas fa-clock me-1"></i>{{ $rp->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                <div class="repo-actions">
                                    <a href="{{ route('repo.detail', $rp->id) }}" class="btn btn-primary-custom">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p>Repositori Tidak Di Temukan</p>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $repo->links('pagination::bootstrap-5') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Search functionality
        document.querySelector('#searchRepository').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const repositories = document.querySelectorAll('.repo-card');

            repositories.forEach(repo => {
                const title = repo.querySelector('.repo-title h3').textContent.toLowerCase();
                const description = repo.querySelector('.repo-description').textContent.toLowerCase();
                const language = repo.querySelector('.language-indicator').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm) || language.includes(
                        searchTerm)) {
                    repo.style.display = 'block';
                    repo.style.animation = 'fadeIn 0.3s ease-in';
                } else {
                    repo.style.display = 'none';
                }
            });

            updateResultsCount();
        });

        // Language filter
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const language = this.getAttribute('data-language');
                const repositories = document.querySelectorAll('.repo-card');

                repositories.forEach(repo => {
                    if (language === 'all' || repo.getAttribute('data-language') === language) {
                        repo.style.display = 'block';
                        repo.style.animation = 'fadeIn 0.3s ease-in';
                    } else {
                        repo.style.display = 'none';
                    }
                });

                updateResultsCount();
            });
        });

        // View toggle
        document.querySelectorAll('[data-view]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('[data-view]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const view = this.getAttribute('data-view');
                const repositoriesGrid = document.getElementById('repositoriesGrid');

                if (view === 'list') {
                    repositoriesGrid.classList.add('list-view');
                } else {
                    repositoriesGrid.classList.remove('list-view');
                }
            });
        });

        // Sort functionality
        document.getElementById('sortRepositories').addEventListener('change', function() {
            const sortBy = this.value;
            const repositoriesGrid = document.getElementById('repositoriesGrid');
            const repositories = Array.from(repositoriesGrid.children);

            repositories.sort((a, b) => {
                switch (sortBy) {
                    case 'name':
                        const nameA = a.querySelector('.repo-title h3').textContent.toLowerCase();
                        const nameB = b.querySelector('.repo-title h3').textContent.toLowerCase();
                        return nameA.localeCompare(nameB);

                    case 'stars':
                        const starsA = parseInt(a.querySelector('.stat-item').textContent.replace(/[^\d]/g,
                            ''));
                        const starsB = parseInt(b.querySelector('.stat-item').textContent.replace(/[^\d]/g,
                            ''));
                        return starsB - starsA;

                    case 'forks':
                        const forksA = parseInt(a.querySelectorAll('.stat-item')[1].textContent.replace(
                            /[^\d]/g, ''));
                        const forksB = parseInt(b.querySelectorAll('.stat-item')[1].textContent.replace(
                            /[^\d]/g, ''));
                        return forksB - forksA;

                    case 'popular':
                        const downloadsA = parseInt(a.querySelectorAll('.stat-item')[2].textContent.replace(
                            /[^\d]/g, ''));
                        const downloadsB = parseInt(b.querySelectorAll('.stat-item')[2].textContent.replace(
                            /[^\d]/g, ''));
                        return downloadsB - downloadsA;

                    default: // latest
                        return 0;
                }
            });

            repositories.forEach(repo => repositoriesGrid.appendChild(repo));
        });

        // Star functionality
        document.querySelectorAll('.star-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.classList.add('starred');

                    // Update star count in stats
                    const starStat = this.closest('.repo-card').querySelector('.stat-item');
                    const currentStars = parseInt(starStat.textContent.replace(/[^\d]/g, ''));
                    starStat.innerHTML =
                        `<i class="fas fa-star me-1"></i>${(currentStars + 1).toLocaleString()} stars`;
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.classList.remove('starred');

                    // Update star count in stats
                    const starStat = this.closest('.repo-card').querySelector('.stat-item');
                    const currentStars = parseInt(starStat.textContent.replace(/[^\d]/g, ''));
                    starStat.innerHTML =
                        `<i class="fas fa-star me-1"></i>${(currentStars - 1).toLocaleString()} stars`;
                }
            });
        });

        // Fork functionality
        document.querySelectorAll('.fork-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const repoTitle = this.closest('.repo-card').querySelector('.repo-title h3').textContent;

                if (confirm(`Fork repositori "${repoTitle}"?`)) {
                    // Simulate fork action
                    alert('Repositori berhasil di-fork ke akun Anda!');

                    // Update fork count in stats
                    const forkStat = this.closest('.repo-card').querySelectorAll('.stat-item')[1];
                    const currentForks = parseInt(forkStat.textContent.replace(/[^\d]/g, ''));
                    forkStat.innerHTML =
                        `<i class="fas fa-code-branch me-1"></i>${(currentForks + 1).toLocaleString()} forks`;
                }
            });
        });

        // Download functionality
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const repoTitle = this.closest('.repo-card').querySelector('.repo-title h3').textContent;

                // Simulate download
                alert(`Mengunduh repositori "${repoTitle}"...`);

                // Update download count in stats
                const downloadStat = this.closest('.repo-card').querySelectorAll('.stat-item')[2];
                const currentDownloads = parseInt(downloadStat.textContent.replace(/[^\d]/g, ''));
                downloadStat.innerHTML =
                    `<i class="fas fa-download me-1"></i>${(currentDownloads + 1).toLocaleString()} downloads`;
            });
        });

        // Update results count
        function updateResultsCount() {
            const visibleRepos = document.querySelectorAll(
                '.repo-card[style*="display: block"], .repo-card:not([style*="display: none"])');
            const total = document.querySelectorAll('.repo-card').length;
            const showing = visibleRepos.length;

            document.querySelector('.results-count').innerHTML =
                `Menampilkan <strong>1-${showing}</strong> dari <strong>${showing}</strong> repositori`;
        }

        // Lazy loading animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.repo-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Search button click
        document.querySelector('.search-box-main .btn').addEventListener('click', function() {
            const searchInput = document.querySelector('#searchRepository');
            const event = new Event('keyup');
            searchInput.dispatchEvent(event);
        });

        // Language filter from sidebar
        document.querySelectorAll('.language-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const languageName = this.querySelector('.language-name').textContent.toLowerCase();

                // Find and click corresponding category button
                document.querySelectorAll('.category-btn').forEach(btn => {
                    if (btn.getAttribute('data-language') === languageName) {
                        btn.click();
                    }
                });
            });
        });

        // Add hover effects to repository cards
        document.querySelectorAll('.repo-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 15px 40px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', function() {
                if (!this.style.opacity || this.style.opacity === '1') {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.05)';
                }
            });
        });

        // Keyboard navigation for accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
                document.querySelector('#searchRepository').focus();
            }
        });

        // Show tooltip on first visit
        if (!localStorage.getItem('repo-tips-shown')) {
            setTimeout(() => {
                alert(
                    'Tips: Tekan "/" untuk fokus ke search box, atau gunakan filter bahasa di sidebar untuk pencarian yang lebih spesifik!'
                );
                localStorage.setItem('repo-tips-shown', 'true');
            }, 2000);
        }
    </script>

    <script>
        document.getElementById('sortArticles').addEventListener('change', function() {
            let sort = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('sort', sort); // tambahin ?sort=...
            window.location.href = url; // reload halaman
        });
    </script>
@endsection
