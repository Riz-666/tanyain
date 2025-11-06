<!-- Header/Hero Section -->
<section class="hero-section" id="beranda">
    <div class="container" id="pencarian">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">Manajemen Pengetahuan SPBE  Kota Bogor</h1>
                    <p class="hero-subtitle">
                        Layanan data terbuka berbasis elektronik memberikan layanan data Pemerintah Daerah tanpa biaya, mudah, cepat, tepat dan akurat
                    </p>
                    <form action="{{ route('search.all') }}" method="GET" role="search">
                        <div class="search-box">
                            <div class="input-group">
                                <input class="form-control" aria-label="Search" id="input-cari" type="search"
                                    name="search" placeholder="Cari repositori, artikel, atau topik..."
                                    value="{{ request('search') }}" required>
                                <button class="btn search-btn" type="submit">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex gap-3 flex-wrap">
                        <a class="btn btn-primary-custom {{ request()->is('repository') ? 'active' : '' }}"
                            href="{{ route('repository') }}">
                            <i class="fas fa-folder me-2"></i>Jelajahi Repositori
                        </a>
                        <a class="btn btn-outline-primary  {{ request()->is('article') ? 'active' : '' }}"
                            href="{{ route('article') }}">
                            <i class="fa-regular fa-newspaper me-2"></i>Baca Artikel
                        </a>
                    </div>

                    <!-- Quick Features -->
                    <div class="hero-features mt-4">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="hero-feature-item">
                                    <i class="fas fa-bolt text-warning me-2"></i>
                                    <span>Akses Cepat</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="hero-feature-item">
                                    <i class="fas fa-circle-check text-success me-2"></i>
                                    <span>Data Terverifikasi</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="hero-feature-item">
                                    <i class="fas fa-users text-info me-2"></i>
                                    <span>Pengguna Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <div class="hero-illustration-container">
                        <div class="hero-main-card">
                            <div class="hero-icon-main">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h3>Manajemen Pengetahuan SPBE Kota Bogor</h3>

                            <!-- Floating Stats -->
                            <div class="hero-stat-1">
                                <i class="fas fa-brain"></i>
                                <span>Pengetahuan</span>
                            </div>
                            <div class="hero-stat-2">
                                <i class="fas fa-newspaper"></i>
                                <span>Artikel</span>
                            </div>
                            <div class="hero-stat-3">
                                <i class="fas fa-folder-open"></i>
                                <span>Repository</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
