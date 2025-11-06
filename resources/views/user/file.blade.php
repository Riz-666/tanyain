@extends('layouts.app')

@section('title', 'File Repository - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/file/file.css') }}">
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
                                <li class="breadcrumb-item active">File</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="fa-regular fa-file me-3"></i>File Repositori
                        </h1>
                        <p class="page-subtitle">
                            Kumpulan file dan dokumen yang dapat diakses untuk mendukung pembelajaran, riset, dan
                            pengelolaan pengetahuan secara terbuka.
                        </p>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="header-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $totalFile }}</div>
                            <div class="stat-label">Total File</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $totalPengguna }}</div>
                            <div class="stat-label">Kontributor</div>
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
                <div class="col-lg-8">
                    <div class="search-box-main">
                        <form method="GET" action="{{ route('file') }}">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control"
                                    placeholder="Cari file berdasarkan nama, ekstensi, atau ..." name="search"
                                    value="{{ $search ?? '' }}" required>
                                <button class="btn btn-primary-custom" type="submit">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                            </div>
                            {{-- Supaya filter ekstensi tetap kepake waktu search --}}
                            <input type="hidden" name="extension" value="{{ $selectedExtension ?? 'all' }}">
                        </form>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="view-toggle">
                        <select class="form-select ms-2" id="sortFiles" onchange="this.form.submit()" name="sort">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="size" {{ request('sort') == 'size' ? 'selected' : '' }}>Ukuran</option>
                            <option value="extension" {{ request('sort') == 'extension' ? 'selected' : '' }}>Ekstensi
                            </option>
                            <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Downloads
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Extensions -->
    <section class="filter-extensions-section">
        <div class="container">
            <div class="filter-extensions">
                <div class="extensions-label">
                    <i class="fas fa-tag me-2"></i>Filter Ekstensi:
                </div>
                <div class="extensions-list">
                    <button class="extension-btn {{ $selectedExtension == 'all' ? 'active' : '' }}"
                        onclick="window.location.href='?extension=all'">Semua</button>

                    @foreach ($extensions as $ext)
                        <button class="extension-btn {{ $selectedExtension == $ext ? 'active' : '' }}"
                            onclick="window.location.href='?extension={{ $ext }}'">
                            {{ $ext }}
                        </button>
                    @endforeach
                </div>
            </div>
    </section>

    <!-- Main Content -->
    <section class="files-content">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="sidebar">
                        <!-- Popular Files -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-fire me-2"></i>File Populer
                            </h3>
                            <div class="popular-files">
                                @forelse ($popularFiles->take(5) as $file)
                                    <div class="popular-item">
                                        <div class="popular-icon">
                                            @php
                                                $ext = strtolower($file->ekstensi);
                                                $iconClass = match ($ext) {
                                                    'pdf' => 'fa-regular fa-file-pdf',
                                                    'pptx' => 'fa-regular fa-file-powerpoint',
                                                    'jpg', 'jpeg', 'png' => 'fa-regular fa-image',
                                                    'mp4' => 'fa-regular fa-file-video',
                                                    default => 'fas fa-file',
                                                };
                                            @endphp
                                            <i class="{{ $iconClass }}"></i>
                                        </div>
                                        <div class="popular-content">
                                            <h4 class="popular-title" style="text-overflow:ellipsis">
                                                <a
                                                    href="{{ route('file.show', $file->id) }}">{{ Str::limit(Str::after($file->nama_file, '_'), 35) }}</a>
                                            </h4>
                                            <div class="popular-meta">
                                                <span class="extension">.{{ $file->ekstensi }}</span>
                                                <span class="downloads"><i
                                                        class="fas fa-download me-1"></i>{{ number_format($file->download_logs_count) }}
                                                    downloads</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    Tidak Ada File Popular
                                @endforelse
                            </div>
                        </div>

                        <!-- File Extensions -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-tag me-2"></i>Ekstensi File
                            </h3>
                            <div class="extensions-list-sidebar">
                                @forelse ($extensionsCount as $ext)
                                    <a href="{{ route('file', ['extension' => $ext->ekstensi]) }}" class="extension-item">
                                        <div class="extension-info">
                                            <div class="extension-color" style="background-color: #f1e05a;"></div>
                                            <span class="extension-name">.{{ $ext->ekstensi }}</span>
                                        </div>
                                        <span class="extension-count">{{ number_format($ext->total) }}</span>
                                    </a>
                                @empty
                                    Tidak Ada Ekstensi
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Files Grid -->
                <div class="col-lg-9">
                    <div class="files-header">
                        <div class="results-info">
                            <span class="results-count">
                                Menampilkan <strong>{{ $files->firstItem() }}-{{ $files->lastItem() }}</strong>
                                dari <strong>{{ $files->total() }}</strong> File Repository
                            </span>
                        </div>
                    </div>

                    <div class="files-grid" id="filesGrid">
                        @foreach ($files as $file)
                            <div class="file-card" data-extension=".{{ $file->ekstensi }}">
                                <div class="file-header">
                                    <div class="file-icon">
                                        @php
                                            // pilih icon sesuai ekstensi
                                            $ext = strtolower($file->ekstensi);
                                            $iconClass = match ($ext) {
                                                'zip', 'rar', 'tar' => 'fa-regular fa-file-zipper',
                                                'pdf' => 'fa-regular fa-file-pdf',
                                                'xlxs' => 'fa-regular fa-file-excel',
                                                'doc', 'docx' => 'fa-regular fa-file-word',
                                                'pptx' => 'fa-regular fa-file-powerpoint',
                                                'jpg', 'jpeg', 'png' => 'fa-regular fa-image',
                                                'mp4' => 'fa-regular fa-file-video',
                                                'sql' => 'fa-regular fa-file-code',
                                                default => 'fas fa-file',
                                            };
                                        @endphp
                                        <i class="{{ $iconClass }}"></i>
                                    </div>
                                    <div class="file-info">
                                        <h3 class="file-name">{{ Str::after($file->nama_file, '_') }}</h3>
                                        <div class="file-meta">
                                            <span class="file-extension">.{{ $file->ekstensi }}</span>
                                            <span class="file-size">{{ $formatFileSize($file->ukuran) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="file-repository">
                                    <div class="repo-link">
                                        <i class="fa-regular fa-folder-open me-1"></i>
                                        <a href="{{ route('repo.detail', $file->repositori->id) }}">
                                            {{ $file->repositori->judul_repo }}
                                        </a>
                                    </div>
                                    <div class="repo-author">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $file->repositori->user->nama ?? 'Unknown' }}
                                    </div>
                                </div>


                                <div class="file-stats">
                                    <span class="stat-item">
                                        <i class="fas fa-download me-1"></i>{{ $file->download_logs_count }}
                                        downloads
                                    </span>
                                </div>

                                <div class="file-actions">
                                    @php
                                        $ext = strtolower(pathinfo($file->nama_file, PATHINFO_EXTENSION));
                                        // Tambahkan mp4 ke list previewable
                                        $isPreviewable = in_array($ext, ['png', 'jpg', 'jpeg', 'pdf', 'mp4']);
                                        $iconClass = $isPreviewable ? 'fa-eye' : 'fa-eye-slash';
                                        $btnClass = $isPreviewable
                                            ? 'btn-outline-primary'
                                            : 'btn-outline-secondary disabled';
                                        $tooltipText = $isPreviewable
                                            ? 'Lihat Preview'
                                            : 'Preview tidak tersedia untuk file ini';
                                    @endphp

                                    <button type="button" class="btn btn-sm {{ $btnClass }} view-file-btn"
                                        data-file-id="{{ $file->id }}" data-file-ext="{{ $ext }}"
                                        data-file-url="{{ route('file.pdf', $file->id) }}"
                                        {{ !$isPreviewable ? 'disabled title="' . $tooltipText . '"' : '' }}
                                        aria-label="{{ $tooltipText }}">
                                        <i class="fas {{ $iconClass }} me-1"></i>Lihat File
                                    </button>
                                    <a href="{{ route('file.show', $file->id) }}"
                                        class="btn btn-sm btn-success download-file-btn"
                                        data-file="{{ $file->nama_file }}">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                    <a href="{{ route('repo.detail', $file->repositori_id) }}"
                                        class="btn btn-sm btn-primary-custom" style="width: 100%">
                                        <i class="fas fa-folder me-1"></i>Kunjungi Repositori
                                    </a>
                                </div>
                            </div>
                        @endforeach


                        <!-- More files will be loaded via pagination -->
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $files->links('pagination::bootstrap-5') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal for Media Preview (Image & Video) -->
    <div class="modal fade" id="mediaPreviewModal" tabindex="-1" aria-labelledby="mediaPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaPreviewModalLabel">Preview Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" alt="Preview" class="img-fluid rounded"
                        style="max-height: 70vh; object-fit: contain; display: none;">
                    <video id="previewVideo" controls class="w-100 rounded" style="max-height: 70vh; display: none;">
                        <source id="videoSource" src="" type="video/mp4">
                        Browser Anda tidak mendukung video tag.
                    </video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-file-btn[data-file-id]');
            const mediaModal = new bootstrap.Modal(document.getElementById('mediaPreviewModal'));
            const previewImage = document.getElementById('previewImage');
            const previewVideo = document.getElementById('previewVideo');
            const videoSource = document.getElementById('videoSource');

            viewButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.disabled) {
                        return;
                    }

                    e.preventDefault();

                    const fileId = this.getAttribute('data-file-id');
                    const ext = this.getAttribute('data-file-ext');
                    const fileUrl = this.getAttribute('data-file-url');

                    if (!fileId || !ext || !fileUrl) {
                        console.error('Missing required data attributes:', {
                            fileId,
                            ext,
                            fileUrl
                        });
                        return;
                    }

                    // Handle PDF
                    if (ext === 'pdf') {
                        window.open(fileUrl, '_blank');
                    }
                    // Handle Images
                    else if (['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'].includes(ext
                            .toLowerCase())) {
                        previewImage.src = fileUrl;
                        previewImage.style.display = 'block';
                        previewVideo.style.display = 'none';
                        previewVideo.pause();
                        mediaModal.show();
                    }
                    // Handle Video
                    else if (['mp4', 'webm', 'ogg'].includes(ext.toLowerCase())) {
                        videoSource.src = fileUrl;
                        videoSource.type = `video/${ext.toLowerCase()}`;
                        previewVideo.load();
                        previewVideo.style.display = 'block';
                        previewImage.style.display = 'none';
                        mediaModal.show();
                    } else {
                        console.warn('Unsupported file type for preview:', ext);
                    }
                });
            });

            // Reset media when modal is closed
            document.getElementById('mediaPreviewModal').addEventListener('hidden.bs.modal', function() {
                previewVideo.pause();
                previewVideo.currentTime = 0;
                videoSource.src = '';
                previewImage.src = '';
            });
        });
    </script>
    <script>
        // Search functionality
        document.querySelector('#searchFile').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const files = document.querySelectorAll('.file-card');

            files.forEach(file => {
                const fileName = file.querySelector('.file-name').textContent.toLowerCase();
                const fileDescription = file.querySelector('.file-description').textContent.toLowerCase();
                const fileExtension = file.querySelector('.file-extension').textContent.toLowerCase();
                const repoName = file.querySelector('.repo-link a').textContent.toLowerCase();

                if (fileName.includes(searchTerm) || fileDescription.includes(searchTerm) ||
                    fileExtension.includes(searchTerm) || repoName.includes(searchTerm)) {
                    file.style.display = 'block';
                    file.style.animation = 'fadeIn 0.3s ease-in';
                } else {
                    file.style.display = 'none';
                }
            });

            updateResultsCount();
        });

        // Extension filter
        document.querySelectorAll('.extension-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.extension-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const extension = this.getAttribute('data-extension');
                const files = document.querySelectorAll('.file-card');

                files.forEach(file => {
                    if (extension === 'all' || file.getAttribute('data-extension') === extension) {
                        file.style.display = 'block';
                        file.style.animation = 'fadeIn 0.3s ease-in';
                    } else {
                        file.style.display = 'none';
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
                const filesGrid = document.getElementById('filesGrid');

                if (view === 'list') {
                    filesGrid.classList.add('list-view');
                } else {
                    filesGrid.classList.remove('list-view');
                }
            });
        });

        // Sort functionality (Lanjutan)
        document.getElementById('sortFiles').addEventListener('change', function() {
            const sortBy = this.value;
            const filesGrid = document.getElementById('filesGrid');
            const files = Array.from(filesGrid.children);

            files.sort((a, b) => {
                switch (sortBy) {
                    case 'name':
                        const nameA = a.querySelector('.file-name').textContent.toLowerCase();
                        const nameB = b.querySelector('.file-name').textContent.toLowerCase();
                        return nameA.localeCompare(nameB);
                    case 'size':
                        const getSizeInBytes = (sizeStr) => {
                            const parts = sizeStr.split(' ');
                            const num = parseFloat(parts[0]);
                            const unit = parts[1];
                            switch (unit) {
                                case 'KB':
                                    return num * 1024;
                                case 'MB':
                                    return num * 1024 * 1024;
                                case 'GB':
                                    return num * 1024 * 1024 * 1024;
                                default:
                                    return num; // Assume Bytes
                            }
                        };
                        const sizeA = getSizeInBytes(a.querySelector('.file-size').textContent);
                        const sizeB = getSizeInBytes(b.querySelector('.file-size').textContent);
                        return sizeA - sizeB;
                    case 'extension':
                        const extA = a.querySelector('.file-extension').textContent.toLowerCase();
                        const extB = b.querySelector('.file-extension').textContent.toLowerCase();
                        return extA.localeCompare(extB);
                    case 'downloads':
                        const downloadsA = parseInt(a.querySelector('.stat-item').textContent.replace(
                            /[^\d]/g, ''));
                        const downloadsB = parseInt(b.querySelector('.stat-item').textContent.replace(
                            /[^\d]/g, ''));
                        return downloadsB - downloadsA; // Descending order
                    default: // 'latest' - Placeholder, as we don't have actual date data in static HTML
                        return 0;
                }
            });

            // Clear the grid and re-append sorted elements
            filesGrid.innerHTML = '';
            files.forEach(file => {
                filesGrid.appendChild(file);
            });
        });

        // Update results count function
        function updateResultsCount() {
            const visibleFiles = document.querySelectorAll(
                '.file-card[style*="display: block"], .file-card:not([style*="display: none"])'
            );
            const total = document.querySelectorAll('.file-card').length;
            const showing = visibleFiles.length;
            document.querySelector('.results-count').innerHTML =
                `Menampilkan <strong>1-${showing}</strong> dari <strong>${total}</strong> file`;
        }

        // Initialize: Call updateResultsCount on page load to set the initial count
        document.addEventListener('DOMContentLoaded', function() {
            updateResultsCount();
        });

        // Optional: Add lazy loading or other enhancements similar to the article page
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

        document.querySelectorAll('.file-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
    <script>
        document.querySelectorAll('.extension-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const ext = this.dataset.extension;

                document.querySelectorAll('.file-item').forEach(item => {
                    item.style.display = (ext === 'all' || item.dataset.extension === ext) ?
                        'block' : 'none';
                });

                document.querySelectorAll('.extension-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

    <script>
        document.getElementById('sortFiles').addEventListener('change', function() {
            let sort = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('sort', sort); // tambahin ?sort=...
            window.location.href = url; // reload halaman
        });
    </script>
@endsection
