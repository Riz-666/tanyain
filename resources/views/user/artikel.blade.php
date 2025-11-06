@extends('layouts.app')

@section('title', 'Artikel - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/artikel/artikel.css') }}">
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
                                <li class="breadcrumb-item active">Artikel</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="fas fa-newspaper me-3"></i>Artikel
                        </h1>
                        <p class="page-subtitle">
                            Jelajahi koleksi artikel berkualitas yang ditulis oleh para ahli dan praktisi.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="header-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $totalArtikel }}</div>
                            <div class="stat-label">Total Artikel</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $totalUser }}</div>
                            <div class="stat-label">Penulis</div>
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
                        <form method="GET" action="{{ route('article') }}">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control"
                                    placeholder="Cari artikel berdasarkan judul, konten, atau tag..." name="search"
                                    value="{{ $search ?? '' }}" required>
                                <button class="btn btn-primary-custom" type="submit">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                            </div>
                            {{-- Supaya filter sort tetap kepake waktu search --}}
                            <input type="hidden" name="sort" value="{{ $sort ?? 'latest' }}">
                        </form>
                    </div>
                </div>
                @if (Auth::check())
                    <div class="col-lg-3">
                        <div class="view-toggle">
                            <a class="btn btn-primary-custom add ms-2" href="{{ route('article.create') }}"
                                style="height:58px; display:flex; align-items:center; justify-content:center; border-radius:10px;">
                                <i class="fas fa-pen-clip me-1"></i> Buat Artikel
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="view-toggle">
                            <select class="form-select ms-2" id="sortArticles" style="height:58px; border-radius:10px;">
                                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Judul A-Z</option>
                                <option value="author" {{ $sort == 'author' ? 'selected' : '' }}>Penulis</option>
                            </select>
                        </div>
                    </div>
                @else
                    <div class="col-lg-6">
                        <div class="view-toggle">
                            <select class="form-select ms-2" id="sortArticles" style="height:58px; border-radius:10px;">
                                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Judul A-Z</option>
                                <option value="author" {{ $sort == 'author' ? 'selected' : '' }}>Penulis</option>
                            </select>
                        </div>
                    </div>
                @endif



            </div>
        </div>
    </section>

    <!-- Filter Tags -->
    <section class="filter-tags-section">
        <div class="container">
            <div class="filter-tags">
                <div class="tags-label">
                    <i class="fas fa-tags me-2"></i>Filter Berdasar Tag:
                </div>
                <div class="tags-list">
                    <div class="container">
                        <div class="tag-filter-wrapper">
                            <div class="all">
                                <a href="{{ route('article', ['tag' => 'all']) }}"
                                    class="tag-btn text-decoration-none {{ request('tag') == 'all' || !request('tag') ? 'active' : '' }}">
                                    Semua
                                </a>
                            </div>
                            <div class="marquee">
                                <div class="marquee-track">
                                    <!-- copy pertama -->
                                    @foreach ($allTags as $tag)
                                        <a href="{{ route('article', ['tag' => $tag->slug]) }}"
                                            class="tag-btn text-decoration-none {{ request('tag') == $tag->slug ? 'active' : '' }}">
                                            {{ $tag->nama_tag }}
                                        </a>
                                    @endforeach
                                    @foreach ($allTags as $tag)
                                        <a href="{{ route('article', ['tag' => $tag->slug]) }}"
                                            class="tag-btn text-decoration-none {{ request('tag') == $tag->slug ? 'active' : '' }}">
                                            {{ $tag->nama_tag }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="articles-content">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="sidebar">
                        <!-- Popular Articles -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-fire me-2"></i>Artikel Populer
                            </h3>
                            <div class="popular-articles">
                                @forelse ($popularArticles as $pop)
                                    <div class="popular-item">
                                        <div class="popular-image">
                                            <div class="article-thumb">
                                                <i class="fas fa-newspaper"></i>
                                            </div>
                                        </div>
                                        <div class="popular-content">
                                            <h4 class="popular-title">
                                                <a href="{{ route('article.detail', $pop->id) }}">
                                                    {{ $pop->judul }}
                                                </a>
                                            </h4>
                                            <div class="popular-meta">
                                                <span class="views">
                                                    <i class="fas fa-eye me-1"></i>{{ $pop->view_artikel_count }}
                                                </span>
                                                <span class="date">
                                                    {{ $pop->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    Tidak Ada Artikel Popular
                                @endforelse
                            </div>

                        </div>

                        <!-- Categories -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-tag me-2"></i>Tag Populer
                            </h3>
                            <div class="categories-list">
                                @forelse ($popularTags as $tag)
                                    <a href="{{ route('article', ['tag' => $tag->slug]) }}" class="category-item">
                                        <span
                                            class="category-name {{ request('tag') == $tag->slug ? 'active' : '' }}">{{ $tag->nama_tag }}</span>
                                        <span class="category-count">{{ $tag->artikel_count }}</span>
                                    </a>
                                @empty
                                    Tidak Ada Tag Popular
                                @endforelse
                            </div>
                        </div>


                        <!-- Recent Authors -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-users me-2"></i>Top Penulis Aktif
                            </h3>
                            <div class="authors-list">
                                <div class="top-authors">
                                    @foreach ($topAuthors as $author)
                                        <div class="author-item mb-3">
                                            <div class="author-avatar">
                                                @if ($author->foto)
                                                    <img src="{{ asset('storage/user-img/' . $author->foto) }}"
                                                        alt="{{ $author->nama }}">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div class="author-info">
                                                <div class="author-name"><a
                                                        href="{{ Route('profile', $author->id) }}">{{ $author->nama }}</a>
                                                </div>
                                                <div class="author-articles">{{ $author->artikel_count }} artikel</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles Grid -->
                <div class="col-lg-9">
                    <div class="articles-header">
                        <div class="results-info">
                            <span class="results-count">
                                Menampilkan <strong>{{ $artikel->firstItem() }}-{{ $artikel->lastItem() }}</strong>
                                dari <strong>{{ $artikel->total() }}</strong> Artikel
                            </span>
                        </div>
                    </div>


                    <div class="articles-grid" id="articlesGrid">
                        @forelse($artikel as $art)
                            <!-- Article Card 1 -->
                            <div class="article-card" data-category="web-development">
                                @if (!$art->cover)
                                    <div class="article-image">
                                        <div class="article-thumb">
                                            <i class="fa fa-images"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="article-image">
                                        <div class="article-thumb">
                                            <img src="{{ asset('storage/artikel/' . $art->id . '/cover/' . $art->cover) }}"
                                                alt="{{ $art->judul }}">
                                        </div>
                                    </div>
                                @endif
                                <div class="article-content">
                                    <h3 class="article-title">
                                        <a href="{{ route('article.detail', $art->id) }}">{{ $art->judul }}</a>

                                    </h3>

                                    <p class="article-excerpt">
                                        {{ Str::limit(strip_tags($art->isi), 150, '...') }}
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-left">
                                            <span class="author">
                                                <i
                                                    class="fas fa-user me-1"></i>{{ $art->user->nama ?? 'Pengguna Di Non Aktifkan' }}
                                            </span>
                                            <span class="date">
                                                <i
                                                    class="fa-regular fa-calendar me-1"></i>{{ $art->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="meta-right">
                                            <span class="views">
                                                <i
                                                    class="fas fa-eye me-1"></i>{{ number_format($art->viewArtikel->count()) }}
                                            </span>
                                            <span class="reading-time">
                                                <i
                                                    class="fa-regular fa-clock me-1"></i>{{ round(str_word_count(strip_tags($art->isi)) / 200) ?: 1 }}
                                                min
                                            </span>
                                        </div>
                                    </div>
                                    <div class="article-tags">
                                        @forelse ($art->tag as $tag)
                                            <span class="tag">{{ $tag->nama_tag }}</span>
                                        @empty
                                            <span class="tag">Tidak Ada Tag</span>
                                        @endforelse
                                    </div>
                                    <div class="article-actions" style="display: flex; ">
                                        <a href="{{ route('article.detail', $art->id) }}" class="btn btn-primary-custom">
                                            <i class="fas fa-book-open me-1"></i>Baca Artikel
                                        </a>
                                        @if ($art->status == 'publik')
                                            <small class="badge bg-secondary"><i class="fa fa-lock-open"></i>
                                                {{ $art->status }}</small>
                                        @else
                                            <small class="badge bg-secondary"><i class="fa fa-lock"></i>
                                                {{ $art->status }}</small>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Tidak ada artikel ditemukan.</p>
                        @endforelse
                        <!-- More articles will be loaded via pagination -->
                    </div>
                    <!-- Pagination if needed -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{ $artikel->links('pagination::bootstrap-5') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Search functionality
        document.querySelector('#searchArticle').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const articles = document.querySelectorAll('.article-card');

            articles.forEach(article => {
                const title = article.querySelector('.article-title').textContent.toLowerCase();
                const excerpt = article.querySelector('.article-excerpt').textContent.toLowerCase();
                const tags = Array.from(article.querySelectorAll('.tag')).map(tag => tag.textContent
                    .toLowerCase());

                if (title.includes(searchTerm) || excerpt.includes(searchTerm) || tags.some(tag => tag
                        .includes(searchTerm))) {
                    article.style.display = 'block';
                    article.style.animation = 'fadeIn 0.3s ease-in';
                } else {
                    article.style.display = 'none';
                }
            });

            updateResultsCount();
        });

        // Category filter
        document.querySelectorAll('.tag-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tag-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const category = this.getAttribute('data-category');
                const articles = document.querySelectorAll('.article-card');

                articles.forEach(article => {
                    if (category === 'all' || article.getAttribute('data-category') === category) {
                        article.style.display = 'block';
                        article.style.animation = 'fadeIn 0.3s ease-in';
                    } else {
                        article.style.display = 'none';
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
                const articlesGrid = document.getElementById('articlesGrid');

                if (view === 'list') {
                    articlesGrid.classList.add('list-view');
                } else {
                    articlesGrid.classList.remove('list-view');
                }
            });
        });

        // Sort functionality
        document.getElementById('sortArticles').addEventListener('change', function() {
                    const sortBy = this.value;
                    const articlesGrid = document.getElementById('articlesGrid');
                    const articles = Array.from(articlesGrid.children);

                    // Sort functionality
                    document.getElementById('sortArticles').addEventListener('change', function() {
                        const sortBy = this.value;
                        const articlesGrid = document.getElementById('articlesGrid');
                        const articles = Array.from(articlesGrid.children);

                        articles.sort((a, b) => {
                            switch (sortBy) {
                                case 'title':
                                    const titleA = a.querySelector('.article-title').textContent
                                        .toLowerCase();
                                    const titleB = b.querySelector('.article-title').textContent
                                        .toLowerCase();
                                    return titleA.localeCompare(titleB);

                                case 'author':
                                    const authorA = a.querySelector('.author').textContent.toLowerCase();
                                    const authorB = b.querySelector('.author').textContent.toLowerCase();
                                    return authorA.localeCompare(authorB);

                                case 'popular':
                                    const viewsA = parseInt(a.querySelector('.views').textContent.replace(
                                        /[^\d]/g, ''));
                                    const viewsB = parseInt(b.querySelector('.views').textContent.replace(
                                        /[^\d]/g, ''));
                                    return viewsB - viewsA;

                                default: // latest
                                    return 0;
                            }
                        });

                        articles.forEach(article => articlesGrid.appendChild(article));
                    });

                    // Bookmark functionality
                    document.querySelectorAll('.bookmark-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const icon = this.querySelector('i');
                            if (icon.classList.contains('far')) {
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                                this.classList.add('bookmarked');
                            } else {
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                                this.classList.remove('bookmarked');
                            }
                        });
                    });

                    // Share functionality
                    document.querySelectorAll('.share-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const articleTitle = this.closest('.article-card').querySelector(
                                '.article-title a').textContent;
                            const articleUrl = this.closest('.article-card').querySelector(
                                '.article-title a').href;

                            if (navigator.share) {
                                navigator.share({
                                    title: articleTitle,
                                    url: articleUrl
                                });
                            } else {
                                // Fallback for browsers that don't support Web Share API
                                navigator.clipboard.writeText(articleUrl).then(() => {
                                    alert('Link artikel berhasil disalin ke clipboard!');
                                });
                            }
                        });
                    });

                    // Update results count
                    function updateResultsCount() {
                        const visibleArticles = document.querySelectorAll(
                            '.article-card[style*="display: block"], .article-card:not([style*="display: none"])');
                        const total = document.querySelectorAll('.article-card').length;
                        const showing = visibleArticles.length;

                        document.querySelector('.results-count').innerHTML =
                            `Menampilkan <strong>1-${showing}</strong> dari <strong>${showing}</strong> artikel`;
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

                    document.querySelectorAll('.article-card').forEach(card => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        card.style.transition = 'all 0.6s ease';
                        observer.observe(card);
                    });

                    // Search button click
                    document.querySelector('.search-box-main .btn').addEventListener('click', function() {
                        const searchInput = document.querySelector('#searchArticle');
                        const event = new Event('keyup');
                        searchInput.dispatchEvent(event);
                    });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const track = document.getElementById("marquee-track");
            const content = document.getElementById("marquee-content");

            // clone isi biar ada 2 copy
            const clone = content.cloneNode(true);
            track.appendChild(clone);
        });
    </script>


    <script>
        document.getElementById('sortArticles').addEventListener('change', function() {
            let sort = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('sort', sort); // tambahin ?sort=...
            window.location.href = url; // reload halaman
        });
    </script>
    <script>
        document.getElementById('sortArticles').addEventListener('change', function() {
            let sort = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('sort', sort);
            let searchInput = document.querySelector('input[name="search"]');
            if (searchInput && searchInput.value) {
                url.searchParams.set('search', searchInput.value);
            }
            window.location.href = url;
        });
    </script>

@endsection
