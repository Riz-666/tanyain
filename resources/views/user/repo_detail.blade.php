@extends('layouts.app')

@section('title', 'Detail Repositori - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css-new/repositori/detail.css') }}">
@endsection
@section('content')
    <!-- Repository Detail Content -->
    <section class="repo-detail">
        <div class="container">
            <div class="row">
                <!-- Main Repository Content -->
                <div class="col-lg-8">
                    <div class="repo-main">
                        <!-- Repository Header -->
                        <header class="repo-header">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                                    <li class="breadcrumb-item"><a href="{{ Route('repository') }}">Repositori</a></li>
                                    <li class="breadcrumb-item active">{{ $repo->judul_repo }}</li>
                                </ol>
                            </nav>

                            <div class="repo-title-section">
                                <div class="repo-icon-large">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <div class="repo-info">
                                    <h1 class="repo-title">{{ $repo->judul_repo }}</h1>
                                    <div class="repo-subtitle">
                                        @if ($repo->user)
                                            <a href="{{ route('profile', $repo->user->id) }}"
                                                style="text-decoration: none;">
                                                <span class="author-link">
                                                    <i class="fas fa-user me-1"></i>{{ $repo->user->nama }}
                                                </span>
                                            </a>
                                        @else
                                            <span class="author-link">
                                                <i class="fas fa-user me-1"></i>Pengguna Terhapus
                                            </span>
                                        @endif

                                        <span class="repo-visibility">
                                            @if ($repo->status == 'publik')
                                                <i class="fas fa-lock-open me-1"></i> {{ $repo->status }} Repository
                                            @else
                                                <i class="fas fa-lock me-1"></i> {{ $repo->status }} Repository
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="repo-stats-header">
                                <div class="article-actions">
                                    <button class="action-btn share-btn" title="Share artikel">
                                        <i class="fas fa-share-alt"></i>
                                        <span>Share</span>
                                    </button>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-download me-1"></i>
                                    <span class="stat-number">{{ $repo->downloads_count }}</span>
                                    <span class="stat-label">downloads</span>
                                </div>
                            </div>
                        </header>
                        <!-- Repository Description -->
                        <div class="repo-description-section">
                            <h2><i class="fas fa-book-open me-2"></i> Deskripsi</h2>
                            <p class="repo-description-text">
                                {!! $repo->deskripsi !!}
                            </p>
                        </div>
                        <!-- Repository Info Table -->
                        <div class="repo-info-section">
                            <h2><i class="fas fa-circle-info me-2"></i> Informasi Repository</h2>
                            <div class="info-table-container">
                                <table class="table info-table">
                                    <tbody>
                                        <tr>
                                            <td class="info-label">
                                                <i class="fas fa-database me-2"></i>Total Ukuran
                                            </td>
                                            <td class="info-value">{{ $formatFileSize($repo->file_repo_sum_ukuran) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">
                                                <i class="fas fa-calendar-alt me-2"></i>Dibuat
                                            </td>
                                            <td class="info-value">
                                                {{ \Carbon\Carbon::parse($repo->created_at)->translatedFormat('d F Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">
                                                <i class="fas fa-sync-alt me-2"></i>Terakhir Update
                                            </td>
                                            <td class="info-value">
                                                {{ \Carbon\Carbon::parse($repo->updated_at)->diffForHumans() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Repository Files Table -->
                        <div class="files-section">
                            <h2>
                                <i class="fas fa-folder-open me-2"></i>File Repository
                            </h2>
                            <div class="table-container">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table id="filesTable" class="table table-striped table-hover">
                                        <thead class="th-table">
                                            <tr>
                                                <th>Nama File</th>
                                                <th>Ukuran</th>
                                                <th>Ekstensi</th>
                                                <th>Di Upload</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="td-body">
                                            @foreach ($repo->fileRepo as $r)
                                                <tr>
                                                    <td>
                                                        <div class="file-icon php">
                                                            <i class="fas {{ $getFileIcon($r->ekstensi) }}"></i>
                                                        </div>
                                                        {{ Str::after($r->nama_file, '_') }}
                                                    </td>
                                                    <td>{{ $formatFileSize($r->ukuran) }}</td>
                                                    <td><span class="badge bg-primary">{{ $r->ekstensi ?? '-' }}</span>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($r->created_at)->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                            @php
                                                                $allowedImageExt = ['png', 'jpg', 'jpeg'];
                                                                $allowedVideoExt = ['mp4', 'webm', 'ogg'];
                                                                $isPdf = strtolower($r->ekstensi) === 'pdf';
                                                                $isImage = in_array(
                                                                    strtolower($r->ekstensi),
                                                                    $allowedImageExt,
                                                                );
                                                                $isVideo = in_array(
                                                                    strtolower($r->ekstensi),
                                                                    $allowedVideoExt,
                                                                );
                                                            @endphp

                                                            @if ($isPdf || $isImage || $isVideo)
                                                                <a href="#"
                                                                    class="btn btn-outline-primary btn-sm-action view-file"
                                                                    data-file-id="{{ $r->id }}"
                                                                    data-file-ext="{{ strtolower($r->ekstensi) }}"
                                                                    data-file-url="{{ route('file.pdf', $r->id) }}">
                                                                    <i class="fas fa-eye"></i> Lihat
                                                                </a>
                                                            @else
                                                                <button
                                                                    class="btn btn-outline-secondary btn-sm-action disabled"
                                                                    disabled>
                                                                    <i class="fas fa-eye-slash"></i> Lihat
                                                                </button>
                                                            @endif
                                                            <a href="{{ route('file.show', $r->id) }}"
                                                                class="btn btn-success btn-sm-action download-file">
                                                                <i class="fas fa-download"></i> Unduh
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <aside class="repo-sidebar">
                        <!-- Repository Quick Info -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-info-circle me-2"></i>Quick Info
                            </h3>
                            <div class="quick-info">
                                <div class="info-item">
                                    <strong>Total Ukuran:</strong> {{ $formatFileSize($repo->file_repo_sum_ukuran) }}
                                </div>
                                <div class="info-item">
                                    <strong>Di Buat:</strong>
                                    {{ \Carbon\Carbon::parse($repo->created_at)->translatedFormat('d F Y') }}
                                </div>
                                <div class="info-item">
                                    <strong>Update Terakhir:</strong>
                                    {{ \Carbon\Carbon::parse($repo->updated_at)->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <!-- Related Articles -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-newspaper me-2"></i>Artikel Terkait
                            </h3>
                            <div class="related-articles">
                                @forelse ($repo->artikel as $ra)
                                    <article class="related-item">
                                        <div class="related-image">
                                            <div class="related-thumb">
                                                <i class="fa-regular fa-newspaper"></i>
                                            </div>
                                        </div>
                                        <div class="related-content">
                                            <h4 class="related-title">
                                                <a href="{{ Route('article.detail', $ra->id) }}">{{ $ra->judul }}</a>
                                            </h4>
                                            <div class="related-meta">
                                                <span
                                                    class="related-date">{{ \Carbon\Carbon::parse($ra->created_at)->translatedFormat('d F Y') }}</span>
                                            </div>
                                        </div>
                                    </article>
                                @empty
                                    <p>Tidak Ada Artikel Terkait</p>
                                @endforelse
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        <!-- CSV Files Section -->
        @if ($repo->fileRepo()->csv()->count())
            <div class="files-section-csv">
                <h2>
                    <i class="fas fa-table me-2"></i>Visualisasi Data CSV
                </h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Select File CSV -->
                        <div class="mb-4">
                            <label for="csvSelector" class="form-label fw-bold">
                                <i class="fas fa-file-csv me-2"></i>Pilih File CSV untuk ditampilkan:
                            </label>
                            <select id="csvSelector" class="form-select form-select-lg">
                                <option value="">-- Pilih File CSV --</option>
                                @foreach ($repo->fileRepo()->where('ekstensi', 'csv')->get() as $csv)
                                    <option value="{{ $csv->id }}" data-filename="{{ $csv->nama_file }}">
                                        {{ Str::after($csv->nama_file, '_') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Visualization Tabs -->
                        <div id="visualizationTabs" style="display: none;" class="mb-4">
                            <ul class="nav nav-tabs nav-pills" id="csvVisualizationTabs" role="tablist"
                                style="border: none;">
                                <li class="nav-item me-1" role="presentation">
                                    <button class="nav-link px-4 py-2 border-0 rounded-pill text-muted"
                                        style="background: #f8f9fa;" id="table-tab" data-bs-toggle="tab"
                                        data-bs-target="#table-pane" type="button" role="tab">
                                        Tabel
                                    </button>
                                </li>
                                <li class="nav-item me-1" role="presentation">
                                    <button class="nav-link px-4 py-2 border-0 rounded-pill text-white active"
                                        style="background: #2563eb;" id="chart-tab" data-bs-toggle="tab"
                                        data-bs-target="#chart-pane" type="button" role="tab">
                                        Grafik
                                    </button>
                                </li>
                                <li class="nav-item me-1" role="presentation">
                                    <button class="nav-link px-4 py-2 border-0 rounded-pill text-muted"
                                        style="background: #f8f9fa;" id="pivot-tab" data-bs-toggle="tab"
                                        data-bs-target="#pivot-pane" type="button" role="tab">
                                        Peta
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-4 py-2 border-0 rounded-pill text-muted"
                                        style="background: #f8f9fa;" id="dynamic-tab" data-bs-toggle="tab"
                                        data-bs-target="#dynamic-pane" type="button" role="tab">
                                        Tabel Dinamis
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab Content -->
                        <div id="csvVisualizationContent" style="display: none;">
                            <div class="tab-content" id="csvVisualizationTabContent">
                                <!-- Table Tab -->
                                <div class="tab-pane fade" id="table-pane" role="tabpanel">
                                    <div id="csvTableContainer">
                                        <div class="alert alert-info d-flex align-items-center mb-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <div>
                                                <strong id="csvFileName">-</strong><br>
                                                <small id="csvRowCount" class="text-muted">-</small>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="csvTable" class="table table-striped table-hover"
                                                style="width:100%;">
                                                <thead class="th-table">
                                                    <!-- Header akan diisi otomatis JS -->
                                                </thead>
                                                <tbody>
                                                    <!-- Data akan diisi otomatis JS -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chart Tab -->
                                <div class="tab-pane fade show active" id="chart-pane" role="tabpanel">
                                    <div id="chartContainer">
                                        <div class="row mb-4">
                                            <div class="col-12 mb-3">
                                                <h5 class="mb-3">Sesuaikan tampilan grafik</h5>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold">Gaya Grafik</label>
                                                <select id="chartType" class="form-select">
                                                    <option value="bar">Bar Chart</option>
                                                    <option value="line">Line Chart</option>
                                                    <option value="pie">Pie Chart</option>
                                                    <option value="doughnut">Doughnut Chart</option>
                                                    <option value="radar">Radar Chart</option>
                                                    <option value="polarArea">Polar Area Chart</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold">Axis A</label>
                                                <select id="chartXAxis" class="form-select">
                                                    <!-- Options akan diisi JS -->
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold">Axis B</label>
                                                <select id="chartYAxis" class="form-select">
                                                    <!-- Options akan diisi JS -->
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold">Group Kolom</label>
                                                <select id="chartGroup" class="form-select">
                                                    <option value="">Tahun</option>
                                                    <!-- Options akan diisi JS -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-center mb-4">
                                            <button id="generateChart"
                                                class="btn px-4 py-2 text-white border-0 rounded-pill"
                                                style="background: #2563eb;">
                                                Pratinjau
                                            </button>
                                        </div>
                                        <div class="chart-wrapper bg-white p-4 rounded shadow-sm"
                                            style="position: relative; height: 450px;">
                                            <canvas id="csvChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Tab (placeholder) -->
                                <div class="tab-pane fade" id="pivot-pane" role="tabpanel">
                                    <div class="text-center py-5">
                                        <i class="fas fa-map text-muted" style="font-size: 4rem;"></i>
                                        <h4 class="text-muted mt-3">Fitur Peta</h4>
                                        <p class="text-muted">Visualisasi peta akan tersedia dalam update selanjutnya</p>
                                    </div>
                                </div>

                                <!-- Dynamic Table Tab -->
                                <div class="tab-pane fade" id="dynamic-pane" role="tabpanel">
                                    <div id="dynamicContainer">
                                        <div class="alert alert-info d-flex align-items-center mb-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <div>
                                                <small>Sistem gagal menemukan atribut untuk tabel dinamis secara otomatis.
                                                    Silahkan pilih opsi atribut secara manual.</small>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-semibold">Visualisasi</label>
                                                <select id="pivotVisualization" class="form-select">
                                                    <option value="heatmap">Heatmap</option>
                                                    <option value="table">Table</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-semibold">Aggregation</label>
                                                <select id="pivotAggregation" class="form-select">
                                                    <option value="sum">Integer Sum</option>
                                                    <option value="count">Count</option>
                                                    <option value="average">Average</option>
                                                    <option value="min">Min</option>
                                                    <option value="max">Max</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <label class="form-label fw-semibold">Rows</label>
                                                        <div class="border rounded p-2"
                                                            style="min-height: 100px; background: #f8f9fa;">
                                                            <div id="rowsContainer" class="d-flex flex-wrap gap-1">
                                                                <!-- Row items akan ditambahkan di sini -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label fw-semibold">Columns</label>
                                                        <div class="border rounded p-2"
                                                            style="min-height: 100px; background: #f8f9fa;">
                                                            <div id="colsContainer" class="d-flex flex-wrap gap-1">
                                                                <!-- Column items akan ditambahkan di sini -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label fw-semibold">Values</label>
                                                        <div class="border rounded p-2"
                                                            style="min-height: 100px; background: #f8f9fa;">
                                                            <div id="valuesContainer" class="d-flex flex-wrap gap-1">
                                                                <!-- Value items akan ditambahkan di sini -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Available Fields -->
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Available Fields</label>
                                            <div class="border rounded p-3" style="background: #f8f9fa;">
                                                <div id="availableFields" class="d-flex flex-wrap gap-2">
                                                    <!-- Available fields akan ditambahkan di sini -->
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pivot Table Result -->
                                        <div id="pivotResult" style="display: none;">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Hasil Tabel Dinamis</h6>
                                                <div>
                                                    <span class="badge bg-primary me-2" id="pivotRowCount">0 rows</span>
                                                    <span class="badge bg-info" id="pivotColCount">0 columns</span>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="pivotTable" class="table table-sm table-bordered">
                                                    <thead class="table-light"></thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </section>

    <!-- Modal for Image/Video Preview -->
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

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==============================
            // PREVIEW FILE (PDF, IMAGE & VIDEO)
            // ==============================
            const viewButtons = document.querySelectorAll('.view-file[data-file-id]');
            const mediaModal = new bootstrap.Modal(document.getElementById('mediaPreviewModal'));
            const previewImage = document.getElementById('previewImage');
            const previewVideo = document.getElementById('previewVideo');
            const videoSource = document.getElementById('videoSource');

            viewButtons.forEach(button => {
                button.addEventListener('click', function(e) {
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
                        previewVideo.pause(); // Pause video if it was playing
                        mediaModal.show();
                    }
                    // Handle Video
                    else if (['mp4', 'webm', 'ogg'].includes(ext.toLowerCase())) {
                        videoSource.src = fileUrl;
                        videoSource.type = `video/${ext.toLowerCase()}`;
                        previewVideo.load(); // Reload video with new source
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

            // ==============================
            // COPY CODE BUTTON
            // ==============================
            document.querySelectorAll('.copy-code-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const codeBlock = this.closest('.code-block')?.querySelector('code');
                    if (!codeBlock) return;

                    const text = codeBlock.textContent;

                    navigator.clipboard.writeText(text).then(() => {
                        const originalIcon = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-check"></i>';
                        this.classList.add('copied');

                        setTimeout(() => {
                            this.innerHTML = originalIcon;
                            this.classList.remove('copied');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy text: ', err);
                    });
                });
            });

            // ==============================
            // STAR BUTTON
            // ==============================
            const starBtn = document.querySelector('.star-btn');
            if (starBtn) {
                starBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    const text = this.querySelector('span');
                    const statNumber = document.querySelector('.repo-stats-header .stat-item .stat-number');

                    if (!icon || !text || !statNumber) return;

                    if (icon.classList.contains('far')) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        this.classList.add('starred');
                        text.textContent = 'Starred';

                        const currentStars = parseInt(statNumber.textContent.replace(/[^\d]/g, '')) || 0;
                        statNumber.textContent = (currentStars + 1).toLocaleString();
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        this.classList.remove('starred');
                        text.textContent = 'Star';

                        const currentStars = parseInt(statNumber.textContent.replace(/[^\d]/g, '')) || 0;
                        statNumber.textContent = (currentStars - 1).toLocaleString();
                    }
                });
            }

            // ==============================
            // FORK BUTTON
            // ==============================
            const forkBtn = document.querySelector('.fork-btn');
            if (forkBtn) {
                forkBtn.addEventListener('click', function() {
                    const repoTitle = document.querySelector('.repo-title')?.textContent;
                    if (!repoTitle) return;

                    if (confirm(`Fork repository "${repoTitle}"?`)) {
                        alert('Repository berhasil di-fork ke akun Anda!');

                        const forkStat = document.querySelectorAll('.repo-stats-header .stat-item')[1]
                            ?.querySelector('.stat-number');
                        if (forkStat) {
                            const currentForks = parseInt(forkStat.textContent.replace(/[^\d]/g, '')) || 0;
                            forkStat.textContent = (currentForks + 1).toLocaleString();
                        }
                    }
                });
            }

            // ==============================
            // SHARE BUTTON
            // ==============================
            const shareBtn = document.querySelector('.share-btn');
            if (shareBtn) {
                shareBtn.addEventListener('click', function() {
                    const repoTitle = document.querySelector('.repo-title')?.textContent;
                    const repoUrl = window.location.href;

                    if (!repoTitle || !repoUrl) return;

                    if (navigator.share) {
                        navigator.share({
                            title: repoTitle,
                            url: repoUrl
                        }).catch(err => {
                            console.error('Share failed:', err);
                        });
                    } else {
                        navigator.clipboard.writeText(repoUrl).then(() => {
                            showToast('Link repository berhasil disalin ke clipboard!');
                        }).catch(err => {
                            console.error('Copy to clipboard failed:', err);
                        });
                    }
                });
            }

            // ==============================
            // DOWNLOAD BUTTON
            // ==============================
            const downloadBtn = document.querySelector('.download-btn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const repoTitle = document.querySelector('.repo-title')?.textContent;
                    if (!repoTitle) return;

                    alert(`Mengunduh repository "${repoTitle}"...`);

                    const downloadStat = document.querySelectorAll('.repo-stats-header .stat-item')[2]
                        ?.querySelector('.stat-number');
                    if (downloadStat) {
                        const currentDownloads = parseFloat(downloadStat.textContent.replace(/[^\d.]/g,
                            '')) || 0;
                        downloadStat.textContent = (currentDownloads + 0.1).toFixed(1) + 'k';
                    }
                });
            }

            // ==============================
            // TOAST NOTIFICATION
            // ==============================
            function showToast(message) {
                let toast = document.querySelector('.toast-notification');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.className = 'toast-notification';
                    document.body.appendChild(toast);
                }

                toast.textContent = message;
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }

            // ==============================
            // SMOOTH SCROLL FOR ANCHOR LINKS
            // ==============================
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (!targetId || targetId === '#') return;

                    const target = document.querySelector(targetId);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // ==============================
            // ANIMATION ON SCROLL (FEATURE ITEMS, ETC)
            // ==============================
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

            document.querySelectorAll('.feature-item, .installation-step, .contributor-item').forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'all 0.6s ease';
                observer.observe(item);
            });

            // ==============================
            // AUTO-EXPAND CODE BLOCKS ON MOBILE
            // ==============================
            if (window.innerWidth <= 768) {
                document.querySelectorAll('.code-block pre').forEach(pre => {
                    pre.style.fontSize = '0.8rem';
                    pre.style.overflowX = 'auto';
                });
            }

            // ==============================
            // UPDATE VIEW COUNT (SIMULATE)
            // ==============================
            setTimeout(() => {
                const statItems = document.querySelectorAll('.repo-stats-header .stat-item');
                if (statItems.length > 3) {
                    const viewStat = statItems[3].querySelector('.stat-number');
                    if (viewStat) {
                        const currentViews = parseFloat(viewStat.textContent.replace(/[^\d.]/g, '')) || 0;
                        viewStat.textContent = (currentViews + 0.1).toFixed(1) + 'k';
                    }
                }
            }, 2000);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#filesTable').DataTable({
                "paging": true,
                "responsive": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "columnDefs": [{
                    "orderable": false,
                    "targets": 4
                }],
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],

                // Bahasa Indonesia
                "language": {
                    "decimal": ",",
                    "thousands": ".",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                    "aria": {
                        "sortAscending": ": aktifkan untuk mengurutkan kolom naik",
                        "sortDescending": ": aktifkan untuk mengurutkan kolom turun"
                    }
                }
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <!-- Leaflet.js CDN for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


    <script>
        // ==========================================
        // GLOBAL VARIABLES
        // ==========================================
        let csvData = {
            headers: [],
            records: [],
            currentChart: null,
            draggedElement: null
        };

        let currentMap = null;

        // ==========================================
        // EVENT LISTENERS & INITIALIZATION
        // ==========================================
        document.getElementById('csvSelector').addEventListener('change', function() {
            const fileId = this.value;
            const visualizationTabs = document.getElementById('visualizationTabs');
            const visualizationContent = document.getElementById('csvVisualizationContent');
            const csvFileName = document.getElementById('csvFileName');
            const csvRowCount = document.getElementById('csvRowCount');
            const thead = document.querySelector('#csvTable thead');
            const tbody = document.querySelector('#csvTable tbody');

            if (!fileId) {
                visualizationTabs.style.display = 'none';
                visualizationContent.style.display = 'none';
                return;
            }

            fetch(`/csv/${fileId}`)
                .then(res => res.json())
                .then(res => {
                    if (res.error) {
                        console.error('Error:', res.error);
                        return;
                    }
                    let headers = res.headers;
                    let records = res.records;

                    if (!headers || !Array.isArray(headers) || !records || !Array.isArray(records)) {
                        console.error('Data CSV tidak valid');
                        return;
                    }

                    // Simpan data ke global variable
                    csvData.headers = headers;
                    csvData.records = records;

                    // Hapus kolom id kalau ada
                    const idIndex = headers.indexOf('id');
                    let visibleHeaders = [...headers];
                    let visibleRecords = records.map(r => [...r]);

                    if (idIndex !== -1) {
                        visibleHeaders.splice(idIndex, 1);
                        visibleRecords = visibleRecords.map(r => {
                            r.splice(idIndex, 1);
                            return r;
                        });
                    }

                    // Update table
                    updateTable(visibleHeaders, visibleRecords);

                    // Setup chart and pivot options
                    setupChartOptions(visibleHeaders);
                    setupDynamicTableOptions(visibleHeaders);

                    // Check data compatibility and enable/disable tabs
                    checkDataCompatibility(visibleHeaders, visibleRecords);

                    csvFileName.textContent =
                        `File: ${this.options[this.selectedIndex].getAttribute('data-filename') || 'CSV'}`;
                    csvRowCount.textContent = `Total ${visibleRecords.length} baris data`;

                    // Show visualization options
                    visualizationTabs.style.display = 'block';
                    visualizationContent.style.display = 'block';

                    // Generate default chart
                    generateDefaultChart(visibleHeaders);
                })
                .catch(err => console.error("Gagal load CSV:", err));
        });

        // Tab styling event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('#csvVisualizationTabs button');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.disabled) return false;
                    tabButtons.forEach(btn => {
                        if (!btn.disabled) {
                            btn.className =
                                'nav-link px-4 py-2 border-0 rounded-pill text-muted';
                            btn.style.background = '#f8f9fa';
                        }
                    });
                    this.className = 'nav-link px-4 py-2 border-0 rounded-pill text-white active';
                    this.style.background = '#2563eb';
                });
            });
        });

        // ==========================================
        // TABLE & CHART FUNCTIONS (UNCHANGED)
        // ==========================================
        function updateTable(headers, records) {
            const thead = document.querySelector('#csvTable thead');
            const tbody = document.querySelector('#csvTable tbody');

            if ($.fn.DataTable.isDataTable('#csvTable')) {
                $('#csvTable').DataTable().clear().destroy();
            }

            thead.innerHTML = '';
            tbody.innerHTML = '';

            let headerRow = '<tr>';
            headers.forEach(h => headerRow += `<th>${h}</th>`);
            headerRow += '</tr>';
            thead.innerHTML = headerRow;

            let filterRow = '<tr>';
            headers.forEach(() => filterRow += `<th></th>`);
            filterRow += '</tr>';
            thead.innerHTML += filterRow;

            records.forEach(r => {
                let tr = '<tr>';
                r.forEach(c => tr += `<td>${c ?? ''}</td>`);
                tr += '</tr>';
                tbody.innerHTML += tr;
            });

            const table = $('#csvTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    decimal: ",",
                    thousands: ".",
                    sEmptyTable: "Tidak ada data yang tersedia",
                    sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    sInfoFiltered: "(disaring dari _MAX_ total entri)",
                    sLengthMenu: "Tampilkan _MENU_ entri",
                    sLoadingRecords: "Memuat...",
                    sProcessing: "Memproses...",
                    sSearch: "Cari Global:",
                    sZeroRecords: "Tidak ditemukan data yang sesuai",
                    oPaginate: {
                        sFirst: "Pertama",
                        sLast: "Terakhir",
                        sNext: "Berikutnya",
                        sPrevious: "Sebelumnya"
                    }
                },
                initComplete: function() {
                    this.api().columns().every(function(index) {
                        var column = this;
                        var title = $(column.header()).text();
                        var uniqueValues = [];
                        column.data().each(function(d) {
                            if (d !== null && d !== undefined && !uniqueValues.includes(d)) {
                                uniqueValues.push(d);
                            }
                        });

                        if (title === 'tahun') uniqueValues.sort((a, b) => b - a);
                        else uniqueValues.sort();

                        var select = $(
                            `<select class="form-select form-select-sm" style="border-radius:10px;border:none;background-color:#e2e8f0;padding:0.4rem 0.8rem;font-size:0.9rem;color:#333;transition:0.3s all;box-shadow: inset 0 0 0 1px #e2e8f0;"><option value="">Semua ${title}</option></select>`
                        ).on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                        uniqueValues.forEach(v => select.append(`<option value="${v}">${v}</option>`));
                        $(thead).find('tr:eq(1) th').eq(index).append(select);
                    });
                }
            });
        }

        function setupChartOptions(headers) {
            const chartXAxis = document.getElementById('chartXAxis');
            const chartYAxis = document.getElementById('chartYAxis');
            const chartGroup = document.getElementById('chartGroup');

            chartXAxis.innerHTML = '';
            chartYAxis.innerHTML = '';
            chartGroup.innerHTML = '<option value="">Tahun</option>';

            headers.forEach(header => {
                chartXAxis.innerHTML += `<option value="${header}">${header}</option>`;
                chartYAxis.innerHTML += `<option value="${header}">${header}</option>`;
                chartGroup.innerHTML += `<option value="${header}">${header}</option>`;
            });

            if (headers.includes('tahun')) {
                chartXAxis.value = 'tahun';
            }

            const numericColumns = findNumericColumns(headers);
            if (numericColumns.length > 0) {
                chartYAxis.value = numericColumns[0];
            }
        }

        function setupDynamicTableOptions(headers) {
            const availableFields = document.getElementById('availableFields');
            availableFields.innerHTML = '';

            const pivotSupport = checkPivotSupport(headers, csvData.records);

            if (pivotSupport.supported) {
                headers.forEach(header => {
                    const field = document.createElement('div');
                    field.className = 'badge bg-secondary';
                    field.style.cursor = 'move';
                    field.style.padding = '8px 12px';

                    const isNumeric = pivotSupport.numericColumns.includes(header);
                    const icon = isNumeric ? '<i class="fas fa-hashtag me-1"></i>' :
                        '<i class="fas fa-tag me-1"></i>';
                    field.innerHTML = icon + header;
                    field.draggable = true;
                    field.dataset.field = header;
                    field.title = isNumeric ? 'Numeric column (good for Values)' :
                        'Categorical column (good for Rows/Columns)';

                    field.addEventListener('dragstart', function(e) {
                        csvData.draggedElement = this;
                        e.dataTransfer.effectAllowed = 'move';
                    });

                    field.addEventListener('dragend', function(e) {
                        csvData.draggedElement = null;
                    });

                    availableFields.appendChild(field);
                });

                setTimeout(() => {
                    autoPopulatePivotFields(pivotSupport);
                }, 100);
            } else {
                availableFields.innerHTML = `
            <div class="text-muted">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${pivotSupport.reason}
            </div>
        `;
            }

            setupDropZones();
        }

        function autoPopulatePivotFields(pivotSupport) {
            if (pivotSupport.categoricalColumns.length > 0) {
                addFieldToContainer('rowsContainer', pivotSupport.categoricalColumns[0]);
            }
            if (pivotSupport.categoricalColumns.length > 1) {
                addFieldToContainer('colsContainer', pivotSupport.categoricalColumns[1]);
            }
            if (pivotSupport.numericColumns.length > 0) {
                addFieldToContainer('valuesContainer', pivotSupport.numericColumns[0]);
            }
            setTimeout(() => {
                updatePivotTable();
            }, 200);
        }

        function addFieldToContainer(containerId, fieldName) {
            const container = document.getElementById(containerId);
            const field = document.createElement('div');
            field.className = 'badge bg-primary me-1 mb-1';
            field.style.cursor = 'pointer';
            field.textContent = fieldName;
            field.dataset.field = fieldName;
            field.draggable = false;

            const removeBtn = document.createElement('span');
            removeBtn.innerHTML = '&times;';
            removeBtn.style.marginLeft = '5px';
            removeBtn.style.cursor = 'pointer';
            removeBtn.onclick = function() {
                field.remove();
                updatePivotTable();
            };
            field.appendChild(removeBtn);

            if (containerId === 'valuesContainer') {
                container.innerHTML = '';
            }
            container.appendChild(field);
        }

        function setupDropZones() {
            const dropZones = ['rowsContainer', 'colsContainer', 'valuesContainer'];
            dropZones.forEach(zoneId => {
                const zone = document.getElementById(zoneId);
                zone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    this.style.backgroundColor = '#e3f2fd';
                });
                zone.addEventListener('dragleave', function(e) {
                    this.style.backgroundColor = '#f8f9fa';
                });
                zone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.style.backgroundColor = '#f8f9fa';
                    if (csvData.draggedElement) {
                        const field = csvData.draggedElement.cloneNode(true);
                        field.className = 'badge bg-primary me-1 mb-1';
                        field.style.cursor = 'pointer';
                        field.draggable = false;

                        const removeBtn = document.createElement('span');
                        removeBtn.innerHTML = '&times;';
                        removeBtn.style.marginLeft = '5px';
                        removeBtn.style.cursor = 'pointer';
                        removeBtn.onclick = function() {
                            field.remove();
                            updatePivotTable();
                        };
                        field.appendChild(removeBtn);

                        if (zoneId === 'valuesContainer') {
                            this.innerHTML = '';
                        }
                        this.appendChild(field);
                        updatePivotTable();
                    }
                });
            });
        }

        function findNumericColumns(headers) {
            const numericColumns = [];
            headers.forEach((header, index) => {
                const sampleValues = csvData.records.slice(0, 10).map(record => record[index]);
                const numericValues = sampleValues.filter(val => !isNaN(parseFloat(val)) && isFinite(val));
                if (numericValues.length > sampleValues.length * 0.7) {
                    numericColumns.push(header);
                }
            });
            return numericColumns;
        }

        function checkDataCompatibility(headers, records) {
            const mapSupported = checkMapSupport(headers, records);
            const pivotSupported = checkPivotSupport(headers, records);

            const mapTab = document.getElementById('pivot-tab');
            const dynamicTab = document.getElementById('dynamic-tab');

            if (mapSupported.supported) {
                mapTab.disabled = false;
                mapTab.style.opacity = '1';
                mapTab.style.cursor = 'pointer';
                updateMapContent(mapSupported);
            } else {
                mapTab.disabled = true;
                mapTab.style.opacity = '0.5';
                mapTab.style.cursor = 'not-allowed';
                mapTab.title = mapSupported.reason;
            }

            if (pivotSupported.supported) {
                dynamicTab.disabled = false;
                dynamicTab.style.opacity = '1';
                dynamicTab.style.cursor = 'pointer';
                const alertBox = document.querySelector('#dynamic-pane .alert');
                if (alertBox) alertBox.style.display = 'none';
            } else {
                dynamicTab.disabled = true;
                dynamicTab.style.opacity = '0.5';
                dynamicTab.style.cursor = 'not-allowed';
                dynamicTab.title = pivotSupported.reason;
                const alertBox = document.querySelector('#dynamic-pane .alert div small');
                if (alertBox) alertBox.textContent = pivotSupported.reason;
            }
        }

        function checkMapSupport(headers, records) {
            const geoColumns = [
                'latitude', 'longitude', 'lat', 'lng', 'provinsi', 'kabupaten', 'kota', 'kecamatan', 'kelurahan',
                'alamat', 'lokasi', 'wilayah', 'spasial', 'kode_provinsi', 'kode_kabupaten', 'kode_kota',
                'kode_kecamatan'
            ];
            const hasGeoData = headers.some(header =>
                geoColumns.some(geo => header.toLowerCase().includes(geo.toLowerCase()))
            );

            if (!hasGeoData) {
                return {
                    supported: false,
                    reason: 'Data tidak mengandung informasi geografis (latitude, longitude, provinsi, kota, kode wilayah, dll)'
                };
            }

            const hasCoordinates = headers.some(header => ['latitude', 'lat'].some(coord => header.toLowerCase().includes(
                coord))) && headers.some(header => ['longitude', 'lng', 'lon'].some(coord => header.toLowerCase()
                .includes(coord)));

            const hasRegions = headers.some(header => ['provinsi', 'kabupaten', 'kota', 'kecamatan', 'wilayah', 'spasial',
                'kode_provinsi', 'kode_kabupaten', 'kode_kota', 'kode_kecamatan'
            ].some(region => header.toLowerCase().includes(region)));

            if (hasCoordinates || hasRegions) {
                return {
                    supported: true,
                    type: hasCoordinates ? 'coordinate' : 'region',
                    geoColumns: headers.filter(header =>
                        geoColumns.some(geo => header.toLowerCase().includes(geo.toLowerCase()))
                    )
                };
            }

            return {
                supported: false,
                reason: 'Data geografis tidak lengkap. Diperlukan koordinat (lat/lng) atau data wilayah'
            };
        }

        function checkPivotSupport(headers, records) {
            if (headers.length < 2) {
                return {
                    supported: false,
                    reason: 'Minimal diperlukan 2 kolom untuk membuat tabel dinamis'
                };
            }
            if (records.length < 2) {
                return {
                    supported: false,
                    reason: 'Minimal diperlukan 2 baris data untuk membuat tabel dinamis'
                };
            }
            const numericColumns = findNumericColumns(headers);
            if (numericColumns.length === 0) {
                return {
                    supported: false,
                    reason: 'Tidak ditemukan kolom numerik untuk aggregasi data'
                };
            }
            const categoricalColumns = headers.filter(header => !numericColumns.includes(header));
            if (categoricalColumns.length === 0) {
                return {
                    supported: false,
                    reason: 'Tidak ditemukan kolom kategorikal untuk pengelompokan data'
                };
            }
            return {
                supported: true,
                numericColumns: numericColumns,
                categoricalColumns: categoricalColumns
            };
        }

        // ==========================================
        // MAP FUNCTIONS — FULLY OPTIMIZED FOR JABODETABEK & CODE SUPPORT
        // ==========================================
        function updateMapContent(mapSupport) {
            const mapPane = document.getElementById('pivot-pane');

            if (mapSupport.type === 'coordinate') {
                mapPane.innerHTML = `
            <div class="mb-4">
                <h5 class="mb-3">Visualisasi Peta</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Latitude Column</label>
                        <select id="mapLatColumn" class="form-select">
                            ${mapSupport.geoColumns.filter(col => ['latitude', 'lat'].some(lat => col.toLowerCase().includes(lat))).map(col => `<option value="${col}">${col}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Longitude Column</label>
                        <select id="mapLngColumn" class="form-select">
                            ${mapSupport.geoColumns.filter(col => ['longitude', 'lng', 'lon'].some(lng => col.toLowerCase().includes(lng))).map(col => `<option value="${col}">${col}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Data Column</label>
                        <select id="mapDataColumn" class="form-select">
                            ${csvData.headers.map(header => `<option value="${header}">${header}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Label Column</label>
                        <select id="mapLabelColumn" class="form-select">
                            ${csvData.headers.map(header => `<option value="${header}">${header}</option>`).join('')}
                        </select>
                    </div>
                </div>
                <button id="generateMap" class="btn btn-primary mb-3">
                    <i class="fas fa-map me-2"></i>Generate Map
                </button>
            </div>
            <div id="mapContainer" style="height: 500px; border-radius: 8px; border: 1px solid #ddd;"></div>
        `;
                document.getElementById('generateMap').addEventListener('click', generateCoordinateMap);
            } else if (mapSupport.type === 'region') {
                mapPane.innerHTML = `
            <div class="mb-4">
                <h5 class="mb-3">Visualisasi Peta Wilayah Indonesia</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Region Column (Spasial/Wilayah/Kode)</label>
                        <select id="mapRegionColumn" class="form-select">
                            ${mapSupport.geoColumns.map(col => `<option value="${col}">${col}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Data Column (Value)</label>
                        <select id="mapDataColumn" class="form-select">
                            ${csvData.headers.filter(h => findNumericColumns([h]).length > 0).map(header => `<option value="${header}">${header}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Aggregation</label>
                        <select id="mapAggregation" class="form-select">
                            <option value="sum">Sum</option>
                            <option value="average">Average</option>
                            <option value="count">Count</option>
                            <option value="max">Maximum</option>
                            <option value="min">Minimum</option>
                        </select>
                    </div>
                </div>
                <button id="generateRegionMap" class="btn btn-primary mb-3">
                    <i class="fas fa-map me-2"></i>Generate Region Map
                </button>
            </div>
            <div id="mapContainer" style="height: 500px; border-radius: 8px; border: 1px solid #ddd;"></div>
            <div id="mapLegend" class="mt-3" style="display: none;">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">Rendah</span>
                    <div class="legend-gradient" style="width: 200px; height: 20px; background: linear-gradient(to right, #ffffcc, #a1dab4, #41b6c4, #2c7fb8, #253494);"></div>
                    <span class="ms-2">Tinggi</span>
                </div>
            </div>
        `;
                document.getElementById('generateRegionMap').addEventListener('click', generateRegionMap);
            }
        }

        function generateCoordinateMap() {
            const latColumn = document.getElementById('mapLatColumn').value;
            const lngColumn = document.getElementById('mapLngColumn').value;
            const dataColumn = document.getElementById('mapDataColumn').value;
            const labelColumn = document.getElementById('mapLabelColumn').value;

            const latIndex = csvData.headers.indexOf(latColumn);
            const lngIndex = csvData.headers.indexOf(lngColumn);
            const dataIndex = csvData.headers.indexOf(dataColumn);
            const labelIndex = csvData.headers.indexOf(labelColumn);

            if (currentMap) currentMap.remove();

            currentMap = L.map('mapContainer').setView([-2.5, 118], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(currentMap);

            const validPoints = csvData.records.filter(record => {
                const lat = parseFloat(record[latIndex]);
                const lng = parseFloat(record[lngIndex]);
                return !isNaN(lat) && !isNaN(lng);
            });

            if (validPoints.length === 0) {
                alert('Tidak ada data koordinat yang valid ditemukan');
                return;
            }

            const dataValues = validPoints.map(record => parseFloat(record[dataIndex]) || 0);
            const minValue = Math.min(...dataValues);
            const maxValue = Math.max(...dataValues);

            validPoints.forEach(record => {
                const lat = parseFloat(record[latIndex]);
                const lng = parseFloat(record[lngIndex]);
                const dataValue = parseFloat(record[dataIndex]) || 0;
                const label = record[labelIndex] || 'Unknown';

                const intensity = maxValue > minValue ? (dataValue - minValue) / (maxValue - minValue) : 0;
                const color = getColorFromIntensity(intensity);

                const marker = L.circleMarker([lat, lng], {
                    radius: 8,
                    fillColor: color,
                    color: '#000',
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(currentMap);

                marker.bindPopup(`
            <strong>${label}</strong><br>
            ${dataColumn}: ${dataValue.toLocaleString()}<br>
            Koordinat: ${lat}, ${lng}
        `);
            });

            const group = new L.featureGroup(currentMap._layers);
            if (Object.keys(currentMap._layers).length > 1) {
                currentMap.fitBounds(group.getBounds().pad(0.1));
            }

            const mapContainer = document.getElementById('mapContainer');
            const summary = document.createElement('div');
            summary.className = 'position-absolute top-0 end-0 bg-white p-2 m-2 rounded shadow-sm';
            summary.style.zIndex = '1000';
            summary.innerHTML = `
        <small>
            <strong>${validPoints.length}</strong> titik lokasi<br>
            Range: ${minValue.toLocaleString()} - ${maxValue.toLocaleString()}
        </small>
    `;
            mapContainer.style.position = 'relative';
            mapContainer.appendChild(summary);
        }

        function generateRegionMap() {
            const regionColumn = document.getElementById('mapRegionColumn').value;
            const dataColumn = document.getElementById('mapDataColumn').value;
            const aggregation = document.getElementById('mapAggregation').value;

            const regionIndex = csvData.headers.indexOf(regionColumn);
            const dataIndex = csvData.headers.indexOf(dataColumn);

            // Aggregate data by region
            const regionData = {};
            csvData.records.forEach(record => {
                const region = record[regionIndex];
                const value = parseFloat(record[dataIndex]) || 0;
                if (!regionData[region]) regionData[region] = [];
                regionData[region].push(value);
            });

            // Apply aggregation
            Object.keys(regionData).forEach(region => {
                const values = regionData[region];
                switch (aggregation) {
                    case 'sum':
                        regionData[region] = values.reduce((a, b) => a + b, 0);
                        break;
                    case 'average':
                        regionData[region] = values.reduce((a, b) => a + b, 0) / values.length;
                        break;
                    case 'count':
                        regionData[region] = values.length;
                        break;
                    case 'max':
                        regionData[region] = Math.max(...values);
                        break;
                    case 'min':
                        regionData[region] = Math.min(...values);
                        break;
                }
            });

            // Initialize map
            if (currentMap) currentMap.remove();

            // Smart zoom berdasarkan wilayah yang ada di data
            const availableRegions = Object.keys(regionData);
            const mapCenter = determineMapCenter(availableRegions);
            currentMap = L.map('mapContainer').setView(mapCenter.center, mapCenter.zoom);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(currentMap);

            // Get data range for color coding
            const allValues = Object.values(regionData);
            const minValue = Math.min(...allValues);
            const maxValue = Math.max(...allValues);

            // Get sample regions database
            const sampleRegions = createSampleIndonesiaRegions();
            const markers = [];

            // Add regions to map with smart positioning
            Object.keys(regionData).forEach(regionName => {
                const regionValue = regionData[regionName];
                const intensity = maxValue > minValue ? (regionValue - minValue) / (maxValue - minValue) : 0;
                const color = getColorFromIntensity(intensity);

                // Find matching region coordinates
                const matchedRegion = findRegionCoordinates(regionName, sampleRegions);
                if (matchedRegion) {
                    // Create polygon/circle for the region
                    const regionShape = L.circle(matchedRegion.center, {
                        radius: calculateRegionRadius(regionValue, minValue, maxValue),
                        fillColor: color,
                        color: '#000',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.7
                    }).addTo(currentMap);

                    // Add popup with data
                    regionShape.bindPopup(`
                <div style="font-family: Arial;">
                    <strong style="font-size: 14px;">${regionName}</strong><br>
                    <hr style="margin: 5px 0;">
                    <span style="color: #666;">${dataColumn}</span><br>
                    <strong style="font-size: 16px; color: #2563eb;">${regionValue.toLocaleString()}</strong><br>
                    <small style="color: #888;">Agregasi: ${aggregation}</small>
                </div>
            `);
                    markers.push(regionShape);
                }
            });

            // Auto-fit map to show all regions
            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                currentMap.fitBounds(group.getBounds().pad(0.1));
            }

            // Show legend
            document.getElementById('mapLegend').style.display = 'block';

            // Show summary
            const mapContainer = document.getElementById('mapContainer');
            const existingSummary = mapContainer.querySelector('.position-absolute');
            if (existingSummary) existingSummary.remove();

            const summary = document.createElement('div');
            summary.className = 'position-absolute top-0 end-0 bg-white p-3 m-2 rounded shadow';
            summary.style.zIndex = '1000';
            summary.innerHTML = `
        <div style="font-size: 12px;">
            <strong style="color: #2563eb;">${Object.keys(regionData).length}</strong> wilayah<br>
            <strong>${dataColumn}</strong><br>
            <span style="color: #666;">Min:</span> ${minValue.toLocaleString()}<br>
            <span style="color: #666;">Max:</span> ${maxValue.toLocaleString()}<br>
            <span style="color: #666;">Agregasi:</span> ${aggregation}
        </div>
    `;
            mapContainer.style.position = 'relative';
            mapContainer.appendChild(summary);
        }

        // Helper function untuk determine map center berdasarkan data
        function determineMapCenter(regions) {
            // Cek apakah ada kode wilayah di data
            const hasCode = regions.some(r => /^\d+$/.test(r.trim()));
            if (hasCode) {
                // Ambil kode pertama yang valid
                const firstValidCode = regions.find(r => /^\d+$/.test(r.trim()));
                if (firstValidCode) {
                    const codeMapping = findRegionCoordinates(firstValidCode, []);
                    if (codeMapping) {
                        // Jika kode kecamatan (6 digit), zoom lebih dekat
                        if (firstValidCode.length >= 6) {
                            return {
                                center: codeMapping.center,
                                zoom: 13 // Zoom level untuk kecamatan
                            };
                        }
                        // Jika kode kota/kabupaten (4 digit), zoom sedang
                        else {
                            return {
                                center: codeMapping.center,
                                zoom: 11 // Zoom level untuk kota/kabupaten
                            };
                        }
                    }
                }
            }

            // Jika ada "bogor" di data, fokus ke Bogor area
            const hasBogor = regions.some(r => r.toLowerCase().includes('bogor'));
            if (hasBogor) {
                // Cek apakah "kabupaten bogor"
                const hasKabBogor = regions.some(r => r.toLowerCase().includes('kabupaten bogor') || r.toLowerCase()
                    .includes('kab. bogor'));
                if (hasKabBogor) {
                    return {
                        center: [-6.8233, 107.1473], // Kabupaten Bogor
                        zoom: 10
                    };
                }
                return {
                    center: [-6.5944, 106.7892], // Kota Bogor
                    zoom: 11
                };
            }

            // Jika ada wilayah Jabodetabek
            const hasJabodetabek = regions.some(r => ['jakarta', 'tangerang', 'bekasi', 'depok', 'bogor', 'cibinong',
                'cikarang', 'serpong'
            ].some(city =>
                r.toLowerCase().includes(city)
            ));
            if (hasJabodetabek) {
                return {
                    center: [-6.2, 106.8], // Jakarta sebagai pusat
                    zoom: 9
                };
            }

            // Jika ada wilayah Jawa Barat
            const hasJabar = regions.some(r => ['bandung', 'cirebon', 'sukabumi', 'garut', 'tasikmalaya'].some(city =>
                r.toLowerCase().includes(city)
            ));
            if (hasJabar) {
                return {
                    center: [-6.9, 107.6], // Jawa Barat tengah
                    zoom: 8
                };
            }

            // Default Indonesia
            return {
                center: [-2.5, 118],
                zoom: 5
            };
        }

        // Helper function untuk find region coordinates
        function findRegionCoordinates(regionName, sampleRegions) {
            // Cek apakah regionName adalah kode wilayah (angka)
            if (/^\d+$/.test(regionName.trim())) {
                const code = regionName.trim();
                if (regionCodeMapping[code]) {
                    return regionCodeMapping[code];
                }
                // Coba cari kode yang lebih pendek (misal: dari kecamatan ke kota)
                for (let len = code.length - 1; len >= 3; len--) {
                    const parentCode = code.substring(0, len);
                    if (regionCodeMapping[parentCode]) {
                        return regionCodeMapping[parentCode];
                    }
                }
            }

            // Direct match
            let match = sampleRegions.find(r =>
                r.name.toLowerCase() === regionName.toLowerCase()
            );
            if (!match) {
                // Partial match
                match = sampleRegions.find(r =>
                    r.name.toLowerCase().includes(regionName.toLowerCase()) ||
                    regionName.toLowerCase().includes(r.name.toLowerCase())
                );
            }
            if (!match && regionName.toLowerCase().includes('bogor')) {
                // Special case untuk wilayah Bogor
                if (regionName.toLowerCase().includes('kabupaten') || regionName.toLowerCase().includes('kab.')) {
                    return {
                        center: [-6.8233, 107.1473],
                        name: 'Kabupaten Bogor'
                    };
                }
                return {
                    center: [-6.5944, 106.7892],
                    name: 'Kota Bogor'
                };
            }
            return match;
        }

        // Helper function untuk calculate region radius berdasarkan nilai data
        function calculateRegionRadius(value, minValue, maxValue) {
            const baseRadius = 15000; // 15km base
            const maxRadius = 50000; // 50km max
            if (maxValue === minValue) return baseRadius;
            const ratio = (value - minValue) / (maxValue - minValue);
            return baseRadius + (ratio * (maxRadius - baseRadius));
        }

        function getColorFromIntensity(intensity) {
            const colors = [
                '#ffffcc', // 0.0 - light yellow
                '#c2e699', // 0.2 - light green
                '#78c679', // 0.4 - medium green
                '#41b6c4', // 0.6 - light blue
                '#2c7fb8', // 0.8 - medium blue
                '#253494' // 1.0 - dark blue
            ];
            const index = Math.floor(intensity * (colors.length - 1));
            return colors[Math.min(index, colors.length - 1)];
        }

        // Database kode wilayah Jabodetabek
        const regionCodeMapping = {
            // Provinsi Aceh (11)
            '11': {
                center: [4.6951, 96.7494],
                name: 'Aceh'
            },
            '1171': {
                center: [5.5577, 95.3222],
                name: 'Kota Banda Aceh'
            },
            '1172': {
                center: [5.8947, 95.3213],
                name: 'Kota Sabang'
            },
            '1173': {
                center: [5.1801, 97.1507],
                name: 'Kota Lhokseumawe'
            },
            '1174': {
                center: [4.4683, 97.9681],
                name: 'Kota Langsa'
            },
            '1175': {
                center: [2.6667, 97.9667],
                name: 'Kota Subulussalam'
            },
            '1101': {
                center: [3.2017, 97.1382],
                name: 'Kab. Aceh Selatan'
            },
            '1102': {
                center: [3.3258, 97.7956],
                name: 'Kab. Aceh Tenggara'
            },
            '1103': {
                center: [4.6351, 97.6253],
                name: 'Kab. Aceh Timur'
            },
            '1104': {
                center: [4.6271, 96.8324],
                name: 'Kab. Aceh Tengah'
            },
            '1105': {
                center: [4.4500, 96.1667],
                name: 'Kab. Aceh Barat'
            },
            '1106': {
                center: [5.4500, 95.4167],
                name: 'Kab. Aceh Besar'
            },
            '1107': {
                center: [5.1333, 96.1333],
                name: 'Kab. Pidie'
            },
            '1108': {
                center: [5.1719, 97.1361],
                name: 'Kab. Aceh Utara'
            },
            '1109': {
                center: [2.6167, 96.0833],
                name: 'Kab. Simeulue'
            },
            '1110': {
                center: [2.4167, 97.7833],
                name: 'Kab. Aceh Singkil'
            },
            '1111': {
                center: [5.2033, 96.7017],
                name: 'Kab. Bireun'
            },
            '1112': {
                center: [3.9167, 96.8500],
                name: 'Kab. Aceh Barat Daya'
            },
            '1113': {
                center: [4.0167, 97.4167],
                name: 'Kab. Gayo Lues'
            },
            '1114': {
                center: [4.8333, 95.6500],
                name: 'Kab. Aceh Jaya'
            },
            '1115': {
                center: [4.1381, 96.5631],
                name: 'Kab. Nagan Raya'
            },
            '1116': {
                center: [4.2500, 98.0167],
                name: 'Kab. Aceh Tamiang'
            },
            '1117': {
                center: [4.7833, 96.8167],
                name: 'Kab. Bener Meriah'
            },
            '1118': {
                center: [5.1500, 96.1833],
                name: 'Kab. Pidie Jaya'
            },

            // Provinsi Sumatera Utara (12)
            '12': {
                center: [2.1154, 99.5451],
                name: 'Sumatera Utara'
            },
            '1271': {
                center: [3.5952, 98.6722],
                name: 'Kota Medan'
            },
            '1272': {
                center: [2.9597, 99.0687],
                name: 'Kota Pematang Siantar'
            },
            '1273': {
                center: [1.7427, 98.7792],
                name: 'Kota Sibolga'
            },
            '1274': {
                center: [2.9675, 99.7983],
                name: 'Kota Tanjung Balai'
            },
            '1275': {
                center: [3.6000, 98.4667],
                name: 'Kota Binjai'
            },
            '1276': {
                center: [3.3281, 99.1625],
                name: 'Kota Tebing Tinggi'
            },
            '1277': {
                center: [1.3800, 99.2700],
                name: 'Kota Padang Sidempuan'
            },
            '1278': {
                center: [1.2877, 97.6144],
                name: 'Kota Gunung Sitoli'
            },
            '1201': {
                center: [3.3667, 98.9333],
                name: 'Kab. Serdang Bedagai'
            },
            '1202': {
                center: [2.6500, 98.7000],
                name: 'Kab. Samosir'
            },
            '1203': {
                center: [2.2667, 98.5000],
                name: 'Kab. Humbang Hasundutan'
            },
            '1204': {
                center: [2.6167, 98.2500],
                name: 'Kab. Pakpak Bharat'
            },
            '1205': {
                center: [0.5833, 97.8333],
                name: 'Kab. Nias Selatan'
            },
            '1206': {
                center: [0.7667, 99.3167],
                name: 'Kab. Mandailing Natal'
            },
            '1207': {
                center: [2.6500, 99.0833],
                name: 'Kab. Toba Samosir'
            },
            '1208': {
                center: [2.7000, 98.2167],
                name: 'Kab. Dairi'
            },
            '1209': {
                center: [2.1167, 100.0833],
                name: 'Kab. Labuhan Batu'
            },
            '1210': {
                center: [2.9833, 99.6167],
                name: 'Kab. Asahan'
            },
            '1211': {
                center: [2.9667, 99.0167],
                name: 'Kab. Simalungun'
            },
            '1212': {
                center: [3.4333, 98.6833],
                name: 'Kab. Deli Serdang'
            },
            '1213': {
                center: [3.1000, 98.3167],
                name: 'Kab. Karo'
            },
            '1214': {
                center: [3.7833, 98.0000],
                name: 'Kab. Langkat'
            },
            '1215': {
                center: [1.0833, 97.5833],
                name: 'Kab. Nias'
            },
            '1216': {
                center: [1.5500, 99.2333],
                name: 'Kab. Tapanuli Selatan'
            },
            '1217': {
                center: [2.0167, 99.0833],
                name: 'Kab. Tapanuli Utara'
            },
            '1218': {
                center: [1.9167, 98.6667],
                name: 'Kab. Tapanuli Tengah'
            },
            '1219': {
                center: [3.2167, 99.4667],
                name: 'Kab. Batu Bara'
            },
            '1220': {
                center: [1.6833, 99.4333],
                name: 'Kab. Padang Lawas Utara'
            },
            '1221': {
                center: [1.1667, 99.7500],
                name: 'Kab. Padang Lawas'
            },
            '1222': {
                center: [1.8500, 100.1333],
                name: 'Kab. Labuhanbatu Selatan'
            },
            '1223': {
                center: [2.3667, 100.0000],
                name: 'Kab. Labuhanbatu Utara'
            },
            '1224': {
                center: [1.4167, 97.5000],
                name: 'Kab. Nias Utara'
            },
            '1225': {
                center: [1.1167, 97.5167],
                name: 'Kab. Nias Barat'
            },

            // Provinsi Sumatera Barat (13)
            '13': {
                center: [-0.7399, 100.8000],
                name: 'Sumatera Barat'
            },
            '1371': {
                center: [-0.9471, 100.4172],
                name: 'Kota Padang'
            },
            '1372': {
                center: [-0.7917, 100.6583],
                name: 'Kota Solok'
            },
            '1373': {
                center: [-0.6833, 100.7833],
                name: 'Kota Sawhlunto'
            },
            '1374': {
                center: [-0.4667, 100.4000],
                name: 'Kota Padang Panjang'
            },
            '1375': {
                center: [-0.3067, 100.3692],
                name: 'Kota Bukittinggi'
            },
            '1376': {
                center: [-0.2167, 100.6333],
                name: 'Kota Payakumbuh'
            },
            '1377': {
                center: [-0.6167, 100.1167],
                name: 'Kota Pariaman'
            },
            '1301': {
                center: [0.1833, 99.9167],
                name: 'Kab. Pasaman Barat'
            },
            '1302': {
                center: [-1.4333, 101.2667],
                name: 'Kab. Solok Selatan'
            },
            '1303': {
                center: [-1.0833, 101.4500],
                name: 'Kab. Dharmasraya'
            },
            '1304': {
                center: [-2.0833, 99.6500],
                name: 'Kab. Kepulauan Mentawai'
            },
            '1305': {
                center: [0.2500, 100.0833],
                name: 'Kab. Pasaman'
            },
            '1306': {
                center: [-0.0167, 100.5167],
                name: 'Kab. Lima Puluh Kota'
            },
            '1307': {
                center: [-0.2500, 100.1500],
                name: 'Kab. Agam'
            },
            '1308': {
                center: [-0.5500, 100.2167],
                name: 'Kab. Padang Pariaman'
            },
            '1309': {
                center: [-0.4833, 100.5333],
                name: 'Kab. Tanah Datar'
            },
            '1310': {
                center: [-0.6833, 101.0167],
                name: 'Kab. Sijunjung'
            },
            '1311': {
                center: [-1.0167, 100.7833],
                name: 'Kab. Solok'
            },
            '1312': {
                center: [-1.7833, 100.7833],
                name: 'Kab. Pesisir Selatan'
            },

            // Provinsi Riau (14)
            '14': {
                center: [0.2933, 101.7068],
                name: 'Riau'
            },
            '1471': {
                center: [0.5071, 101.4478],
                name: 'Kota Pekan Baru'
            },
            '1473': {
                center: [1.6667, 101.4500],
                name: 'Kota Dumai'
            },
            '1401': {
                center: [1.2333, 103.3000],
                name: 'Kab. Kepulauan Meranti'
            },
            '1402': {
                center: [-0.4833, 101.4667],
                name: 'Kab. Kuantan Singingi'
            },
            '1403': {
                center: [1.1167, 102.0000],
                name: 'Kab. Siak'
            },
            '1404': {
                center: [2.0833, 100.8833],
                name: 'Kab. Rokan Hilir'
            },
            '1405': {
                center: [1.1167, 100.4833],
                name: 'Kab. Rokan Hulu'
            },
            '1406': {
                center: [0.3333, 102.2333],
                name: 'Kab. Pelalawan'
            },
            '1407': {
                center: [-0.3333, 103.2500],
                name: 'Kab. Indragiri Hilir'
            },
            '1408': {
                center: [1.4667, 101.4333],
                name: 'Kab. Bengkalis'
            },
            '1409': {
                center: [-0.3667, 102.6000],
                name: 'Kab. Indragiri Hulu'
            },
            '1410': {
                center: [0.3167, 101.1500],
                name: 'Kab. Kampar'
            },

            // Provinsi Jambi (15)
            '15': {
                center: [-1.4852, 103.6151],
                name: 'Jambi'
            },
            '1571': {
                center: [-1.4852, 103.6151],
                name: 'Kota Jambi'
            },
            '1572': {
                center: [-2.0667, 101.3833],
                name: 'Kota Sungai Penuh'
            },
            '1501': {
                center: [-1.4167, 102.4500],
                name: 'Kab. Tebo'
            },
            '1502': {
                center: [-1.4833, 101.9000],
                name: 'Kab. Bungo'
            },
            '1503': {
                center: [-1.0500, 103.8667],
                name: 'Kab. Tanjung Jabung Timur'
            },
            '1504': {
                center: [-1.2167, 103.4000],
                name: 'Kab. Tanjung Jabung Barat'
            },
            '1505': {
                center: [-1.4500, 103.8833],
                name: 'Kab. Muaro Jambi'
            },
            '1506': {
                center: [-1.6833, 103.1167],
                name: 'Kab. Batanghari'
            },
            '1507': {
                center: [-2.2833, 102.6833],
                name: 'Kab. Sarolangun'
            },
            '1508': {
                center: [-2.1333, 101.9833],
                name: 'Kab. Merangin'
            },
            '1509': {
                center: [-2.0667, 101.5000],
                name: 'Kab. Kerinci'
            },

            // Provinsi Sumatera Selatan (16)
            '16': {
                center: [-3.3194, 103.9140],
                name: 'Sumatera Selatan'
            },
            '1671': {
                center: [-2.9761, 104.7754],
                name: 'Kota Palembang'
            },
            '1672': {
                center: [-4.0067, 103.2400],
                name: 'Kota Pagar Alam'
            },
            '1673': {
                center: [-3.2917, 102.8583],
                name: 'Kota Lubuk Linggau'
            },
            '1674': {
                center: [-3.4500, 104.2333],
                name: 'Kota Prabumulih'
            },
            '1601': {
                center: [-2.8333, 102.5000],
                name: 'Kab. Musi Rawas Utara'
            },
            '1602': {
                center: [-3.2833, 104.0833],
                name: 'Kab. Penukal Abab Lematang Ilir'
            },
            '1603': {
                center: [-3.6833, 103.1167],
                name: 'Kab. Empat Lawang'
            },
            '1604': {
                center: [-3.0500, 104.6000],
                name: 'Kab. Ogan Ilir'
            },
            '1605': {
                center: [-4.2833, 103.7667],
                name: 'Kab. Ogan Komering Ulu Selatan'
            },
            '1606': {
                center: [-3.8333, 104.6167],
                name: 'Kab. Ogan Komering Ulu Timur'
            },
            '1607': {
                center: [-2.8000, 104.8833],
                name: 'Kab. Banyuasin'
            },
            '1608': {
                center: [-2.5333, 104.6167],
                name: 'Kab. Musi Banyuasin'
            },
            '1609': {
                center: [-3.0000, 102.9167],
                name: 'Kab. Musi Rawas'
            },
            '1610': {
                center: [-3.7833, 103.5333],
                name: 'Kab. Lahat'
            },
            '1611': {
                center: [-3.6000, 103.9333],
                name: 'Kab. Muara Enim'
            },
            '1612': {
                center: [-3.2167, 105.0833],
                name: 'Kab. Ogan Komering Ilir'
            },
            '1613': {
                center: [-4.1167, 104.1667],
                name: 'Kab. Ogan Komering Ulu'
            },

            // Provinsi Bengkulu (17)
            '17': {
                center: [-3.5778, 102.3463],
                name: 'Bengkulu'
            },
            '1771': {
                center: [-3.8004, 102.2655],
                name: 'Kota Bengkulu'
            },
            '1701': {
                center: [-3.6000, 102.2333],
                name: 'Kab. Bengkulu Tengah'
            },
            '1702': {
                center: [-3.6167, 102.5833],
                name: 'Kab. Kepahiang'
            },
            '1703': {
                center: [-3.2000, 102.1833],
                name: 'Kab. Lebong'
            },
            '1704': {
                center: [-2.5833, 101.1667],
                name: 'Kab. Muko Muko'
            },
            '1705': {
                center: [-4.0667, 102.5167],
                name: 'Kab. Seluma'
            },
            '1706': {
                center: [-4.6000, 103.4500],
                name: 'Kab. Kaur'
            },
            '1707': {
                center: [-3.3000, 101.9833],
                name: 'Kab. Bengkulu Utara'
            },
            '1708': {
                center: [-3.4500, 102.1167],
                name: 'Kab. Rejang Lebong'
            },
            '1709': {
                center: [-4.3833, 103.0167],
                name: 'Kab. Bengkulu Selatan'
            },

            // Provinsi Lampung (18)
            '18': {
                center: [-4.5585, 105.4068],
                name: 'Lampung'
            },
            '1871': {
                center: [-5.3971, 105.2668],
                name: 'Kota Bandar Lampung'
            },
            '1872': {
                center: [-5.1133, 105.3067],
                name: 'Kota Metro'
            },
            '1801': {
                center: [-5.4333, 104.1500],
                name: 'Kab. Pesisir Barat'
            },
            '1802': {
                center: [-4.0167, 105.8000],
                name: 'Kab. Tulangbawang Barat'
            },
            '1803': {
                center: [-3.4333, 105.8333],
                name: 'Kab. Mesuji'
            },
            '1804': {
                center: [-5.3667, 104.9833],
                name: 'Kab. Pringsewu'
            },
            '1805': {
                center: [-5.4833, 105.0833],
                name: 'Kab. Pesawaran'
            },
            '1806': {
                center: [-4.2333, 104.5833],
                name: 'Kab. Way Kanan'
            },
            '1807': {
                center: [-4.8333, 105.6167],
                name: 'Kab. Lampung Timur'
            },
            '1808': {
                center: [-5.4667, 104.6333],
                name: 'Kab. Tanggamus'
            },
            '1809': {
                center: [-3.8333, 105.6333],
                name: 'Kab. Tulang Bawang'
            },
            '1810': {
                center: [-4.8667, 104.2833],
                name: 'Kab. Lampung Barat'
            },
            '1811': {
                center: [-4.1833, 104.7833],
                name: 'Kab. Lampung Utara'
            },
            '1812': {
                center: [-4.8667, 105.2667],
                name: 'Kab. Lampung Tengah'
            },
            '1813': {
                center: [-5.6333, 105.4833],
                name: 'Kab. Lampung Selatan'
            },

            // Provinsi Kepulauan Bangka Belitung (19)
            '19': {
                center: [-2.7410, 106.4406],
                name: 'Kepulauan Bangka Belitung'
            },
            '1971': {
                center: [-2.1316, 106.1168],
                name: 'Kota Pangkal Pinang'
            },
            '1901': {
                center: [-2.8333, 108.2500],
                name: 'Kab. Belitung Timur'
            },
            '1902': {
                center: [-1.8667, 105.9000],
                name: 'Kab. Bangka Barat'
            },
            '1903': {
                center: [-2.2000, 106.2667],
                name: 'Kab. Bangka Tengah'
            },
            '1904': {
                center: [-2.8500, 106.7167],
                name: 'Kab. Bangka Selatan'
            },
            '1905': {
                center: [-2.7667, 107.6333],
                name: 'Kab. Belitung'
            },
            '1906': {
                center: [-2.1167, 106.1000],
                name: 'Kab. Bangka'
            },

            // Provinsi Kepulauan Riau (21)
            '21': {
                center: [3.9456, 108.1429],
                name: 'Kepulauan Riau'
            },
            '2171': {
                center: [1.0456, 104.0305],
                name: 'Kota Batam'
            },
            '2172': {
                center: [0.9167, 104.4500],
                name: 'Kota Tanjung Pinang'
            },
            '2101': {
                center: [3.0167, 106.0833],
                name: 'Kab. Kepulauan Anambas'
            },
            '2102': {
                center: [0.2000, 104.6167],
                name: 'Kab. Lingga'
            },
            '2103': {
                center: [4.0000, 108.2000],
                name: 'Kab. Natuna'
            },
            '2104': {
                center: [1.0500, 103.4833],
                name: 'Kab. Karimun'
            },
            '2105': {
                center: [1.1333, 104.3500],
                name: 'Kab. Bintan'
            },

            // Provinsi DKI Jakarta (31)
            '31': {
                center: [-6.2088, 106.8456],
                name: 'DKI Jakarta'
            },
            '3171': {
                center: [-6.1751, 106.8275],
                name: 'Kota Jakarta Pusat'
            },
            '3172': {
                center: [-6.2615, 106.8106],
                name: 'Kota Jakarta Selatan'
            },
            '3173': {
                center: [-6.1352, 106.7549],
                name: 'Kota Jakarta Barat'
            },
            '3174': {
                center: [-6.1388, 106.8827],
                name: 'Kota Jakarta Utara'
            },
            '3175': {
                center: [-6.2251, 106.9004],
                name: 'Kota Jakarta Timur'
            },
            '3101': {
                center: [-5.6167, 106.5833],
                name: 'Kab. Kepulauan Seribu'
            },

            // Provinsi Jawa Barat (32)
            '32': {
                center: [-6.9, 107.6],
                name: 'Jawa Barat'
            },
            '3271': {
                center: [-6.5944, 106.7892],
                name: 'Kota Bogor'
            },
            '3272': {
                center: [-6.9185, 106.9274],
                name: 'Kota Sukabumi'
            },
            '3273': {
                center: [-6.9175, 107.6191],
                name: 'Kota Bandung'
            },
            '3274': {
                center: [-6.7063, 108.5570],
                name: 'Kota Cirebon'
            },
            '3275': {
                center: [-6.2383, 106.9756],
                name: 'Kota Bekasi'
            },
            '3276': {
                center: [-6.4025, 106.7942],
                name: 'Kota Depok'
            },
            '3277': {
                center: [-6.8721, 107.5420],
                name: 'Kota Cimahi'
            },
            '3278': {
                center: [-7.3274, 108.2207],
                name: 'Kota Tasikmalaya'
            },
            '3279': {
                center: [-7.3500, 108.5333],
                name: 'Kota Banjar'
            },
            '3201': {
                center: [-6.5944, 106.7892],
                name: 'Kab. Bogor'
            },
            '3202': {
                center: [-6.9167, 106.6000],
                name: 'Kab. Sukabumi'
            },
            '3203': {
                center: [-6.8167, 107.1333],
                name: 'Kab. Cianjur'
            },
            '3204': {
                center: [-7.0500, 107.5333],
                name: 'Kab. Bandung'
            },
            '3205': {
                center: [-7.2167, 107.9000],
                name: 'Kab. Garut'
            },
            '3206': {
                center: [-7.6833, 108.0500],
                name: 'Kab. Tasikmalaya'
            },
            '3207': {
                center: [-7.3333, 108.3500],
                name: 'Kab. Ciamis'
            },
            '3208': {
                center: [-6.9833, 108.4833],
                name: 'Kab. Kuningan'
            },
            '3209': {
                center: [-6.7000, 108.4667],
                name: 'Kab. Cirebon'
            },
            '3210': {
                center: [-6.8333, 108.2333],
                name: 'Kab. Majalengka'
            },
            '3211': {
                center: [-6.8500, 107.9167],
                name: 'Kab. Sumedang'
            },
            '3212': {
                center: [-6.3167, 108.3167],
                name: 'Kab. Indramayu'
            },
            '3213': {
                center: [-6.5667, 107.7667],
                name: 'Kab. Subang'
            },
            '3214': {
                center: [-6.5667, 107.4333],
                name: 'Kab. Purwakarta'
            },
            '3215': {
                center: [-6.3000, 107.3000],
                name: 'Kab. Karawang'
            },
            '3216': {
                center: [-6.2500, 107.1500],
                name: 'Kab. Bekasi'
            },
            '3217': {
                center: [-6.8500, 107.4833],
                name: 'Kab. Bandung Barat'
            },
            '3218': {
                center: [-7.6833, 108.6500],
                name: 'Kab. Pangandaran'
            },

            // Provinsi Jawa Tengah (33)
            '33': {
                center: [-7.150975, 110.1402594],
                name: 'Jawa Tengah'
            },
            '3371': {
                center: [-6.9932, 110.4203],
                name: 'Kota Semarang'
            },
            '3372': {
                center: [-7.3317, 110.5069],
                name: 'Kota Salatiga'
            },
            '3373': {
                center: [-7.5755, 110.8243],
                name: 'Kota Surakarta'
            },
            '3374': {
                center: [-7.4814, 110.2181],
                name: 'Kota Magelang'
            },
            '3375': {
                center: [-6.8886, 109.6753],
                name: 'Kota Pekalongan'
            },
            '3376': {
                center: [-6.8694, 109.1402],
                name: 'Kota Tegal'
            },
            '3301': {
                center: [-7.7167, 109.0167],
                name: 'Kab. Cilacap'
            },
            '3302': {
                center: [-7.5167, 109.2833],
                name: 'Kab. Banyumas'
            },
            '3303': {
                center: [-7.3833, 109.3667],
                name: 'Kab. Purbalingga'
            },
            '3304': {
                center: [-7.3000, 109.6833],
                name: 'Kab. Banjarnegara'
            },
            '3305': {
                center: [-7.6667, 109.6500],
                name: 'Kab. Kebumen'
            },
            '3306': {
                center: [-7.7167, 110.0167],
                name: 'Kab. Purworejo'
            },
            '3307': {
                center: [-7.3667, 109.9000],
                name: 'Kab. Wonosobo'
            },
            '3308': {
                center: [-7.4833, 110.2167],
                name: 'Kab. Magelang'
            },
            '3309': {
                center: [-7.5333, 110.5833],
                name: 'Kab. Boyolali'
            },
            '3310': {
                center: [-7.7167, 110.6000],
                name: 'Kab. Klaten'
            },
            '3311': {
                center: [-7.6833, 110.8333],
                name: 'Kab. Sukoharjo'
            },
            '3312': {
                center: [-7.8167, 110.9167],
                name: 'Kab. Wonogiri'
            },
            '3313': {
                center: [-7.6000, 110.9500],
                name: 'Kab. Karanganyar'
            },
            '3314': {
                center: [-7.4167, 111.0167],
                name: 'Kab. Sragen'
            },
            '3315': {
                center: [-7.0500, 110.9167],
                name: 'Kab. Grobogan'
            },
            '3316': {
                center: [-6.9667, 111.4167],
                name: 'Kab. Blora'
            },
            '3317': {
                center: [-6.7000, 111.3500],
                name: 'Kab. Rembang'
            },
            '3318': {
                center: [-6.7500, 111.0500],
                name: 'Kab. Pati'
            },
            '3319': {
                center: [-6.8167, 110.8333],
                name: 'Kab. Kudus'
            },
            '3320': {
                center: [-6.5833, 110.6667],
                name: 'Kab. Jepara'
            },
            '3321': {
                center: [-6.8833, 110.6333],
                name: 'Kab. Demak'
            },
            '3322': {
                center: [-7.1500, 110.4833],
                name: 'Kab. Semarang'
            },
            '3323': {
                center: [-7.3167, 110.1667],
                name: 'Kab. Temanggung'
            },
            '3324': {
                center: [-7.0167, 110.2000],
                name: 'Kab. Kendal'
            },
            '3325': {
                center: [-6.9167, 109.7333],
                name: 'Kab. Batang'
            },
            '3326': {
                center: [-7.0167, 109.6833],
                name: 'Kab. Pekalongan'
            },
            '3327': {
                center: [-6.8833, 109.3833],
                name: 'Kab. Pemalang'
            },
            '3328': {
                center: [-6.9000, 109.1333],
                name: 'Kab. Tegal'
            },
            '3329': {
                center: [-6.8833, 109.0333],
                name: 'Kab. Brebes'
            },

            // Provinsi DI Yogyakarta (34)
            '34': {
                center: [-7.8753849, 110.4262088],
                name: 'DI Yogyakarta'
            },
            '3471': {
                center: [-7.7956, 110.3695],
                name: 'Kota Yogyakarta'
            },
            '3401': {
                center: [-8.1667, 110.1667],
                name: 'Kab. Kulon Progo'
            },
            '3402': {
                center: [-7.8833, 110.3333],
                name: 'Kab. Bantul'
            },
            '3403': {
                center: [-7.9833, 110.5833],
                name: 'Kab. Gunung Kidul'
            },
            '3404': {
                center: [-7.7167, 110.3500],
                name: 'Kab. Sleman'
            },

            // Provinsi Jawa Timur (35)
            '35': {
                center: [-7.5360639, 112.2384017],
                name: 'Jawa Timur'
            },
            '3571': {
                center: [-8.2000, 111.0833],
                name: 'Kota Pacitan'
            },
            '3572': {
                center: [-7.8667, 111.4667],
                name: 'Kota Ponorogo'
            },
            '3573': {
                center: [-8.0500, 111.7167],
                name: 'Kota Trenggalek'
            },
            '3574': {
                center: [-8.0667, 111.9000],
                name: 'Kota Tulungagung'
            },
            '3575': {
                center: [-8.1000, 112.1667],
                name: 'Kota Blitar'
            },
            '3576': {
                center: [-7.8481, 112.0178],
                name: 'Kota Kediri'
            },
            '3577': {
                center: [-7.9666, 112.6326],
                name: 'Kota Malang'
            },
            '3578': {
                center: [-8.0956, 112.1609],
                name: 'Kota Blitar'
            },
            '3579': {
                center: [-7.7543, 113.2159],
                name: 'Kota Probolinggo'
            },
            '3580': {
                center: [-7.6453, 112.9075],
                name: 'Kota Pasuruan'
            },
            '3581': {
                center: [-7.4664, 112.4336],
                name: 'Kota Mojokerto'
            },
            '3582': {
                center: [-7.6298, 111.5239],
                name: 'Kota Madiun'
            },
            '3583': {
                center: [-7.2575, 112.7521],
                name: 'Kota Surabaya'
            },
            '3584': {
                center: [-7.8700, 112.5281],
                name: 'Kota Batu'
            },
            '3501': {
                center: [-8.2000, 111.0833],
                name: 'Kab. Pacitan'
            },
            '3502': {
                center: [-7.8667, 111.4667],
                name: 'Kab. Ponorogo'
            },
            '3503': {
                center: [-8.0500, 111.7167],
                name: 'Kab. Trenggalek'
            },
            '3504': {
                center: [-8.0667, 111.9000],
                name: 'Kab. Tulungagung'
            },
            '3505': {
                center: [-8.1000, 112.1667],
                name: 'Kab. Blitar'
            },
            '3506': {
                center: [-7.8167, 112.0167],
                name: 'Kab. Kediri'
            },
            '3507': {
                center: [-8.1000, 112.6333],
                name: 'Kab. Malang'
            },
            '3508': {
                center: [-8.1333, 113.2167],
                name: 'Kab. Lumajang'
            },
            '3509': {
                center: [-8.1667, 113.7000],
                name: 'Kab. Jember'
            },
            '3510': {
                center: [-8.2167, 114.3667],
                name: 'Kab. Banyuwangi'
            },
            '3511': {
                center: [-7.9167, 113.8167],
                name: 'Kab. Bondowoso'
            },
            '3512': {
                center: [-7.7000, 114.0167],
                name: 'Kab. Situbondo'
            },
            '3513': {
                center: [-7.8833, 113.2000],
                name: 'Kab. Probolinggo'
            },
            '3514': {
                center: [-7.7333, 112.9000],
                name: 'Kab. Pasuruan'
            },
            '3515': {
                center: [-7.4500, 112.7167],
                name: 'Kab. Sidoarjo'
            },
            '3516': {
                center: [-7.4667, 112.4333],
                name: 'Kab. Mojokerto'
            },
            '3517': {
                center: [-7.5500, 112.2333],
                name: 'Kab. Jombang'
            },
            '3518': {
                center: [-7.6000, 111.9167],
                name: 'Kab. Nganjuk'
            },
            '3519': {
                center: [-7.6167, 111.5167],
                name: 'Kab. Madiun'
            },
            '3520': {
                center: [-7.6333, 111.3500],
                name: 'Kab. Magetan'
            },
            '3521': {
                center: [-7.4000, 111.4500],
                name: 'Kab. Ngawi'
            },
            '3522': {
                center: [-7.1500, 111.8833],
                name: 'Kab. Bojonegoro'
            },
            '3523': {
                center: [-6.9000, 111.9333],
                name: 'Kab. Tuban'
            },
            '3524': {
                center: [-7.1167, 112.4167],
                name: 'Kab. Lamongan'
            },
            '3525': {
                center: [-7.1500, 112.6500],
                name: 'Kab. Gresik'
            },
            '3526': {
                center: [-7.0333, 112.7500],
                name: 'Kab. Bangkalan'
            },
            '3527': {
                center: [-7.1833, 113.2333],
                name: 'Kab. Sampang'
            },
            '3528': {
                center: [-7.1667, 113.4833],
                name: 'Kab. Pamekasan'
            },
            '3529': {
                center: [-7.0167, 113.8667],
                name: 'Kab. Sumenep'
            },

            // Provinsi Banten (36)
            '36': {
                center: [-6.4058172, 106.0640179],
                name: 'Banten'
            },
            '3671': {
                center: [-6.1783, 106.6319],
                name: 'Kota Tangerang'
            },
            '3672': {
                center: [-6.0167, 106.0167],
                name: 'Kota Cilegon'
            },
            '3673': {
                center: [-6.1200, 106.1502],
                name: 'Kota Serang'
            },
            '3674': {
                center: [-6.2800, 106.7100],
                name: 'Kota Tangerang Selatan'
            },
            '3601': {
                center: [-6.3000, 105.9167],
                name: 'Kab. Pandeglang'
            },
            '3602': {
                center: [-6.5667, 106.2500],
                name: 'Kab. Lebak'
            },
            '3603': {
                center: [-6.1833, 106.6333],
                name: 'Kab. Tangerang'
            },
            '3604': {
                center: [-6.3000, 106.2500],
                name: 'Kab. Serang'
            },

            // Provinsi Bali (51)
            '51': {
                center: [-8.4095178, 115.188916],
                name: 'Bali'
            },
            '5171': {
                center: [-8.6705, 115.2126],
                name: 'Kota Denpasar'
            },
            '5101': {
                center: [-8.3833, 114.6500],
                name: 'Kab. Jembrana'
            },
            '5102': {
                center: [-8.5333, 115.1167],
                name: 'Kab. Tabanan'
            },
            '5103': {
                center: [-8.5500, 115.1667],
                name: 'Kab. Badung'
            },
            '5104': {
                center: [-8.5333, 115.3333],
                name: 'Kab. Gianyar'
            },
            '5105': {
                center: [-8.5333, 115.4000],
                name: 'Kab. Klungkung'
            },
            '5106': {
                center: [-8.2833, 115.3500],
                name: 'Kab. Bangli'
            },
            '5107': {
                center: [-8.4167, 115.6167],
                name: 'Kab. Karangasem'
            },
            '5108': {
                center: [-8.1167, 115.0833],
                name: 'Kab. Buleleng'
            },

            // Provinsi Nusa Tenggara Barat (52)
            '52': {
                center: [-8.6529334, 117.3616476],
                name: 'Nusa Tenggara Barat'
            },
            '5271': {
                center: [-8.5833, 116.1167],
                name: 'Kota Mataram'
            },
            '5272': {
                center: [-8.4667, 118.7167],
                name: 'Kota Bima'
            },
            '5201': {
                center: [-8.6500, 116.1167],
                name: 'Kab. Lombok Barat'
            },
            '5202': {
                center: [-8.7000, 116.2833],
                name: 'Kab. Lombok Tengah'
            },
            '5203': {
                center: [-8.5333, 116.5500],
                name: 'Kab. Lombok Timur'
            },
            '5204': {
                center: [-8.4333, 117.4167],
                name: 'Kab. Sumbawa'
            },
            '5205': {
                center: [-8.5333, 118.4667],
                name: 'Kab. Dompu'
            },
            '5206': {
                center: [-8.6167, 118.8833],
                name: 'Kab. Bima'
            },
            '5207': {
                center: [-8.6833, 117.1000],
                name: 'Kab. Sumbawa Barat'
            },
            '5208': {
                center: [-8.3333, 116.3333],
                name: 'Kab. Lombok Utara'
            },

            // Provinsi Nusa Tenggara Timur (53)
            '53': {
                center: [-8.6573819, 121.0793705],
                name: 'Nusa Tenggara Timur'
            },
            '5371': {
                center: [-10.1718, 123.6044],
                name: 'Kota Kupang'
            },
            '5301': {
                center: [-10.0167, 123.6167],
                name: 'Kab. Kupang'
            },
            '5302': {
                center: [-9.8667, 124.0833],
                name: 'Kab. Timor Tengah Selatan'
            },
            '5303': {
                center: [-9.4333, 124.0333],
                name: 'Kab. Timor Tengah Utara'
            },
            '5304': {
                center: [-9.4167, 124.9000],
                name: 'Kab. Belu'
            },
            '5305': {
                center: [-8.2000, 124.5667],
                name: 'Kab. Alor'
            },
            '5306': {
                center: [-8.2167, 122.9667],
                name: 'Kab. Flores Timur'
            },
            '5307': {
                center: [-8.6833, 122.2333],
                name: 'Kab. Sikka'
            },
            '5308': {
                center: [-8.8333, 121.6667],
                name: 'Kab. Ende'
            },
            '5309': {
                center: [-8.6500, 120.9833],
                name: 'Kab. Ngada'
            },
            '5310': {
                center: [-8.5333, 120.4667],
                name: 'Kab. Manggarai'
            },
            '5311': {
                center: [-9.8167, 120.2833],
                name: 'Kab. Sumba Timur'
            },
            '5312': {
                center: [-9.4500, 119.4000],
                name: 'Kab. Sumba Barat'
            },
            '5313': {
                center: [-8.3833, 123.5000],
                name: 'Kab. Lembata'
            },
            '5314': {
                center: [-10.7333, 123.1167],
                name: 'Kab. Rote Ndao'
            },
            '5315': {
                center: [-8.6500, 120.1833],
                name: 'Kab. Manggarai Barat'
            },
            '5316': {
                center: [-9.4500, 119.7500],
                name: 'Kab. Sumba Tengah'
            },
            '5317': {
                center: [-9.6833, 119.2167],
                name: 'Kab. Sumba Barat Daya'
            },
            '5318': {
                center: [-8.7500, 121.2333],
                name: 'Kab. Nagekeo'
            },
            '5319': {
                center: [-8.6167, 120.4500],
                name: 'Kab. Manggarai Timur'
            },
            '5320': {
                center: [-10.5000, 121.8333],
                name: 'Kab. Sabu Raijua'
            },
            '5321': {
                center: [-9.5667, 124.9000],
                name: 'Kab. Malaka'
            },

            // Provinsi Kalimantan Barat (61)
            '61': {
                center: [-0.2787808, 111.4752851],
                name: 'Kalimantan Barat'
            },
            '6171': {
                center: [-0.0263, 109.3425],
                name: 'Kota Pontianak'
            },
            '6172': {
                center: [0.9067, 108.9939],
                name: 'Kota Singkawang'
            },
            '6101': {
                center: [1.3833, 109.3000],
                name: 'Kab. Sambas'
            },
            '6102': {
                center: [1.0500, 109.4167],
                name: 'Kab. Bengkayang'
            },
            '6103': {
                center: [-0.9167, 109.8333],
                name: 'Kab. Landak'
            },
            '6104': {
                center: [-0.3333, 109.1833],
                name: 'Kab. Mempawah'
            },
            '6105': {
                center: [0.1667, 110.6000],
                name: 'Kab. Sanggau'
            },
            '6106': {
                center: [-1.8333, 109.9833],
                name: 'Kab. Ketapang'
            },
            '6107': {
                center: [0.1500, 111.4833],
                name: 'Kab. Sintang'
            },
            '6108': {
                center: [0.8333, 112.0000],
                name: 'Kab. Kapuas Hulu'
            },
            '6109': {
                center: [-0.1333, 109.4667],
                name: 'Kab. Kubu Raya'
            },
            '6110': {
                center: [-1.0833, 110.2167],
                name: 'Kab. Kayong Utara'
            },
            '6111': {
                center: [0.0333, 110.9167],
                name: 'Kab. Sekadau'
            },
            '6112': {
                center: [-0.8333, 111.6833],
                name: 'Kab. Melawi'
            },

            // Provinsi Kalimantan Tengah (62)
            '62': {
                center: [-1.6814878, 113.3823545],
                name: 'Kalimantan Tengah'
            },
            '6271': {
                center: [-2.2067, 113.9117],
                name: 'Kota Palangkaraya'
            },
            '6201': {
                center: [-2.6833, 111.6167],
                name: 'Kab. Kotawaringin Barat'
            },
            '6202': {
                center: [-2.0833, 112.9500],
                name: 'Kab. Kotawaringin Timur'
            },
            '6203': {
                center: [-3.0167, 114.3833],
                name: 'Kab. Kapuas'
            },
            '6204': {
                center: [-2.1167, 114.7667],
                name: 'Kab. Barito Selatan'
            },
            '6205': {
                center: [-0.7833, 114.9000],
                name: 'Kab. Barito Utara'
            },
            '6206': {
                center: [-2.2500, 112.1333],
                name: 'Kab. Seruyan'
            },
            '6207': {
                center: [-1.6667, 113.0333],
                name: 'Kab. Katingan'
            },
            '6208': {
                center: [-2.6667, 111.2500],
                name: 'Kab. Sukamara'
            },
            '6209': {
                center: [-2.5167, 111.3500],
                name: 'Kab. Lamandau'
            },
            '6210': {
                center: [-1.0833, 113.4333],
                name: 'Kab. Gunung Mas'
            },
            '6211': {
                center: [-2.7333, 114.0667],
                name: 'Kab. Pulang Pisau'
            },
            '6212': {
                center: [-1.2667, 114.8000],
                name: 'Kab. Murung Raya'
            },
            '6213': {
                center: [-1.9333, 114.8833],
                name: 'Kab. Barito Timur'
            },

            // Provinsi Kalimantan Selatan (63)
            '63': {
                center: [-2.7804112, 115.6146659],
                name: 'Kalimantan Selatan'
            },
            '6371': {
                center: [-3.3194, 114.5906],
                name: 'Kota Banjarmasin'
            },
            '6372': {
                center: [-3.4500, 114.8333],
                name: 'Kota Banjarbaru'
            },
            '6301': {
                center: [-3.8167, 114.8667],
                name: 'Kab. Tanah Laut'
            },
            '6302': {
                center: [-3.2833, 116.1667],
                name: 'Kab. Kotabaru'
            },
            '6303': {
                center: [-3.3333, 114.8333],
                name: 'Kab. Banjar'
            },
            '6304': {
                center: [-3.2667, 114.6500],
                name: 'Kab. Barito Kuala'
            },
            '6305': {
                center: [-2.8833, 115.1333],
                name: 'Kab. Tapin'
            },
            '6306': {
                center: [-2.8333, 115.2333],
                name: 'Kab. Hulu Sungai Selatan'
            },
            '6307': {
                center: [-2.6000, 115.4000],
                name: 'Kab. Hulu Sungai Tengah'
            },
            '6308': {
                center: [-2.6167, 115.1500],
                name: 'Kab. Hulu Sungai Utara'
            },
            '6309': {
                center: [-1.8667, 115.4333],
                name: 'Kab. Tabalong'
            },
            '6310': {
                center: [-3.4167, 115.3833],
                name: 'Kab. Tanah Bambu'
            },
            '6311': {
                center: [-2.2833, 115.6167],
                name: 'Kab. Balangan'
            },

            // Provinsi Kalimantan Timur (64)
            '64': {
                center: [0.7893212, 116.2422857],
                name: 'Kalimantan Timur'
            },
            '6471': {
                center: [-0.4985, 117.1436],
                name: 'Kota Samarinda'
            },
            '6472': {
                center: [-1.2379, 116.8529],
                name: 'Kota Balikpapan'
            },
            '6474': {
                center: [0.1333, 117.4833],
                name: 'Kota Bontang'
            },
            '6401': {
                center: [-1.7167, 116.2333],
                name: 'Kab. Paser'
            },
            '6402': {
                center: [-0.5000, 116.3333],
                name: 'Kab. Kutai Kertanegara'
            },
            '6403': {
                center: [1.8333, 117.3667],
                name: 'Kab. Berau'
            },
            '6407': {
                center: [0.0000, 115.8333],
                name: 'Kab. Kutai Barat'
            },
            '6408': {
                center: [0.5333, 117.6333],
                name: 'Kab. Kutai Timur'
            },
            '6409': {
                center: [-1.4333, 116.5000],
                name: 'Kab. Penajam Paser Utara'
            },
            '6411': {
                center: [0.4000, 115.6000],
                name: 'Kab. Mahakam Ulu'
            },

            // Provinsi Kalimantan Utara (65)
            '65': {
                center: [3.0730929, 116.0413889],
                name: 'Kalimantan Utara'
            },
            '6571': {
                center: [3.3, 117.6333],
                name: 'Kota Tarakan'
            },
            '6501': {
                center: [2.9333, 117.0833],
                name: 'Kab. Bulungan'
            },
            '6502': {
                center: [3.5833, 116.0833],
                name: 'Kab. Malinau'
            },
            '6503': {
                center: [4.0833, 116.6667],
                name: 'Kab. Nunukan'
            },
            '6504': {
                center: [3.5500, 117.2333],
                name: 'Kab. Tana Tidung'
            },

            // Provinsi Sulawesi Utara (71)
            '71': {
                center: [0.6246932, 123.9750018],
                name: 'Sulawesi Utara'
            },
            '7171': {
                center: [1.4748, 124.8421],
                name: 'Kota Manado'
            },
            '7172': {
                center: [1.4500, 125.1833],
                name: 'Kota Bitung'
            },
            '7173': {
                center: [1.3333, 124.8333],
                name: 'Kota Tomohon'
            },
            '7174': {
                center: [0.7333, 124.3167],
                name: 'Kota Kotamobagu'
            },
            '7101': {
                center: [0.7167, 124.2833],
                name: 'Kab. Bolaang Mangondow'
            },
            '7102': {
                center: [1.2500, 124.9167],
                name: 'Kab. Minahasa'
            },
            '7103': {
                center: [3.5833, 125.5000],
                name: 'Kab. Kepulauan Sangihe'
            },
            '7104': {
                center: [4.2667, 126.7833],
                name: 'Kab. Kepulauan Talaud'
            },
            '7105': {
                center: [1.2000, 124.4500],
                name: 'Kab. Minahasa Selatan'
            },
            '7106': {
                center: [1.4833, 124.9500],
                name: 'Kab. Minahasa Utara'
            },
            '7107': {
                center: [0.8500, 124.0833],
                name: 'Kab. Bolaang Mangondow Utara'
            },
            '7108': {
                center: [2.7833, 125.4833],
                name: 'Kab. Kepulauan Siau Tagulandang Biaro'
            },
            '7109': {
                center: [1.1000, 124.9000],
                name: 'Kab. Minahasa Tenggara'
            },
            '7110': {
                center: [0.8833, 124.0000],
                name: 'Kab. Bolaang Mangondow Timur'
            },
            '7111': {
                center: [0.4167, 123.8667],
                name: 'Kab. Bolaang Mangondow Selatan'
            },

            // Provinsi Sulawesi Tengah (72)
            '72': {
                center: [-1.4300254, 121.4456179],
                name: 'Sulawesi Tengah'
            },
            '7271': {
                center: [-0.8917, 119.8707],
                name: 'Kota Palu'
            },
            '7201': {
                center: [-1.5500, 122.8833],
                name: 'Kab. Banggai'
            },
            '7202': {
                center: [-1.3833, 120.7500],
                name: 'Kab. Poso'
            },
            '7203': {
                center: [-0.4167, 119.7500],
                name: 'Kab. Donggala'
            },
            '7204': {
                center: [1.0500, 120.7833],
                name: 'Kab. Toli-Toli'
            },
            '7205': {
                center: [1.1000, 121.4000],
                name: 'Kab. Buol'
            },
            '7206': {
                center: [-2.3167, 121.8833],
                name: 'Kab. Morowali'
            },
            '7207': {
                center: [-1.6167, 123.4167],
                name: 'Kab. Banggai Kepulauan'
            },
            '7208': {
                center: [-0.6167, 120.4333],
                name: 'Kab. Parigi Moutong'
            },
            '7209': {
                center: [-1.3833, 121.5000],
                name: 'Kab. Tojo Una-Una'
            },
            '7210': {
                center: [-1.3167, 119.9667],
                name: 'Kab. Sigi'
            },
            '7211': {
                center: [-1.3833, 123.3667],
                name: 'Kab. Banggai Laut'
            },
            '7212': {
                center: [-1.7500, 121.8833],
                name: 'Kab. Morowali Utara'
            },

            // Provinsi Sulawesi Selatan (73)
            '73': {
                center: [-3.6687994, 119.9740534],
                name: 'Sulawesi Selatan'
            },
            '7371': {
                center: [-5.1477, 119.4327],
                name: 'Kota Makassar'
            },
            '7372': {
                center: [-4.0167, 119.6333],
                name: 'Kota Parepare'
            },
            '7373': {
                center: [-2.9917, 120.1917],
                name: 'Kota Palopo'
            },
            '7301': {
                center: [-6.1167, 120.4667],
                name: 'Kab. Kepulauan Selayar'
            },
            '7302': {
                center: [-5.5500, 120.1833],
                name: 'Kab. Bulukumba'
            },
            '7303': {
                center: [-5.5167, 120.0167],
                name: 'Kab. Bantaeng'
            },
            '7304': {
                center: [-5.6500, 119.7333],
                name: 'Kab. Jeneponto'
            },
            '7305': {
                center: [-5.4000, 119.4667],
                name: 'Kab. Takalar'
            },
            '7306': {
                center: [-5.3167, 119.6667],
                name: 'Kab. Gowa'
            },
            '7307': {
                center: [-5.1500, 120.2500],
                name: 'Kab. Sinjai'
            },
            '7308': {
                center: [-4.9833, 119.5833],
                name: 'Kab. Maros'
            },
            '7309': {
                center: [-4.7833, 119.5500],
                name: 'Kab. Pangkajene Kepulauan'
            },
            '7310': {
                center: [-4.4167, 119.6500],
                name: 'Kab. Barru'
            },
            '7311': {
                center: [-4.3500, 119.8833],
                name: 'Kab. Soppeng'
            },
            '7312': {
                center: [-4.0000, 120.2833],
                name: 'Kab. Wajo'
            },
            '7313': {
                center: [-3.8833, 120.0667],
                name: 'Kab. Sidenreng Rappang'
            },
            '7314': {
                center: [-3.6167, 119.6333],
                name: 'Kab. Pinrang'
            },
            '7315': {
                center: [-3.5500, 119.7833],
                name: 'Kab. Enrekang'
            },
            '7316': {
                center: [-3.4000, 120.2500],
                name: 'Kab. Luwu'
            },
            '7317': {
                center: [-3.0833, 119.8333],
                name: 'Kab. Tana Toraja'
            },
            '7322': {
                center: [-2.6833, 120.1833],
                name: 'Kab. Luwu Utara'
            },
            '7325': {
                center: [-2.5500, 121.0000],
                name: 'Kab. Luwu Timur'
            },
            '7326': {
                center: [-2.9167, 119.8833],
                name: 'Kab. Toraja Utara'
            },
            '7318': {
                center: [-4.5500, 120.0833],
                name: 'Kab. Bone'
            },

            // Provinsi Sulawesi Tenggara (74)
            '74': {
                center: [-4.14491, 122.174605],
                name: 'Sulawesi Tenggara'
            },
            '7471': {
                center: [-3.9450, 122.5986],
                name: 'Kota Kendari'
            },
            '7472': {
                center: [-5.4667, 122.6000],
                name: 'Kota Baubau'
            },
            '7401': {
                center: [-4.0333, 121.3167],
                name: 'Kab. Kolaka'
            },
            '7402': {
                center: [-3.9500, 122.1500],
                name: 'Kab. Konawe'
            },
            '7403': {
                center: [-4.8833, 122.6833],
                name: 'Kab. Muna'
            },
            '7404': {
                center: [-5.2167, 122.9833],
                name: 'Kab. Buton'
            },
            '7405': {
                center: [-4.2000, 122.4167],
                name: 'Kab. Konawe Selatan'
            },
            '7406': {
                center: [-4.5833, 121.8333],
                name: 'Kab. Bombana'
            },
            '7407': {
                center: [-5.2500, 123.6000],
                name: 'Kab. Wakatobi'
            },
            '7408': {
                center: [-3.4833, 121.1667],
                name: 'Kab. Kolaka Utara'
            },
            '7409': {
                center: [-3.6833, 121.9000],
                name: 'Kab. Konawe Utara'
            },
            '7410': {
                center: [-4.4333, 123.0833],
                name: 'Kab. Buton Utara'
            },
            '7411': {
                center: [-4.0000, 121.5833],
                name: 'Kab. Kolaka Timur'
            },
            '7412': {
                center: [-4.1833, 123.1833],
                name: 'Kab. Konawe Kepulauan'
            },
            '7413': {
                center: [-4.6667, 122.5833],
                name: 'Kab. Muna Barat'
            },
            '7414': {
                center: [-4.8167, 122.9167],
                name: 'Kab. Buton Tengah'
            },
            '7415': {
                center: [-5.2833, 122.8833],
                name: 'Kab. Buton Selatan'
            },

            // Provinsi Gorontalo (75)
            '75': {
                center: [0.6999372, 122.4467238],
                name: 'Gorontalo'
            },
            '7571': {
                center: [0.5412, 123.0681],
                name: 'Kota Gorontalo'
            },
            '7501': {
                center: [0.6333, 122.4500],
                name: 'Kab. Gorontalo'
            },
            '7502': {
                center: [0.8333, 122.2333],
                name: 'Kab. Boalemo'
            },
            '7503': {
                center: [0.5667, 123.0833],
                name: 'Kab. Bone Bolango'
            },
            '7504': {
                center: [0.7000, 121.6167],
                name: 'Kab. Pohuwato'
            },
            '7505': {
                center: [0.8167, 122.6333],
                name: 'Kab. Gorontalo Utara'
            },

            // Provinsi Sulawesi Barat (76)
            '76': {
                center: [-2.8441371, 119.2320784],
                name: 'Sulawesi Barat'
            },
            '7601': {
                center: [-3.5400, 118.9700],
                name: 'Kab. Majene'
            },
            '7602': {
                center: [-3.4333, 119.3333],
                name: 'Kab. Polewali Mandar'
            },
            '7603': {
                center: [-2.9000, 119.3000],
                name: 'Kab. Mamasa'
            },
            '7604': {
                center: [-2.6833, 118.8833],
                name: 'Kab. Mamuju'
            },
            '7605': {
                center: [-1.3833, 119.4167],
                name: 'Kab. Mamuju Utara'
            },
            '7606': {
                center: [-2.0833, 119.2500],
                name: 'Kab. Mamuju Tengah'
            },

            // Provinsi Maluku (81)
            '81': {
                center: [-3.2384616, 130.1452734],
                name: 'Maluku'
            },
            '8171': {
                center: [-3.6954, 128.1814],
                name: 'Kota Ambon'
            },
            '8172': {
                center: [-5.6333, 132.7500],
                name: 'Kota Tual'
            },
            '8101': {
                center: [-3.2167, 128.9000],
                name: 'Kab. Maluku Tengah'
            },
            '8102': {
                center: [-5.7000, 132.7333],
                name: 'Kab. Maluku Tenggara'
            },
            '8103': {
                center: [-7.9833, 131.3000],
                name: 'Kab. Maluku Tenggara Barat'
            },
            '8104': {
                center: [-3.3833, 126.6833],
                name: 'Kab. Buru'
            },
            '8105': {
                center: [-3.1833, 128.3667],
                name: 'Kab. Seram Bagian Barat'
            },
            '8106': {
                center: [-3.0833, 129.8333],
                name: 'Kab. Seram Bagian Timur'
            },
            '8107': {
                center: [-6.1833, 134.5333],
                name: 'Kab. Kepulauan Aru'
            },
            '8108': {
                center: [-7.5833, 126.5833],
                name: 'Kab. Maluku Barat Daya'
            },
            '8109': {
                center: [-3.6167, 126.5833],
                name: 'Kab. Buru Selatan'
            },

            // Provinsi Maluku Utara (82)
            '82': {
                center: [1.5709993, 127.8087693],
                name: 'Maluku Utara'
            },
            '8271': {
                center: [0.7833, 127.3667],
                name: 'Kota Ternate'
            },
            '8272': {
                center: [0.6833, 127.4000],
                name: 'Kota Tidore Kepulauan'
            },
            '8201': {
                center: [1.4167, 127.5500],
                name: 'Kab. Halmahera Barat'
            },
            '8202': {
                center: [0.5500, 128.0333],
                name: 'Kab. Halmahera Tengah'
            },
            '8203': {
                center: [1.5333, 127.8167],
                name: 'Kab. Halmahera Utara'
            },
            '8204': {
                center: [1.0500, 128.3667],
                name: 'Kab. Halmahera Timur'
            },
            '8205': {
                center: [-1.8167, 127.5167],
                name: 'Kab. Halmahera Selatan'
            },
            '8206': {
                center: [-1.5833, 125.9167],
                name: 'Kab. Kepulauan Sula'
            },
            '8207': {
                center: [2.3167, 128.4000],
                name: 'Kab. Pulau Morotai'
            },
            '8208': {
                center: [-1.8333, 124.7833],
                name: 'Kab. Pulau Taliabu'
            },

            // Provinsi Papua (91)
            '91': {
                center: [-4.269928, 138.0803529],
                name: 'Papua'
            },
            '9171': {
                center: [-2.5316, 140.7186],
                name: 'Kota Jayapura'
            },
            '9101': {
                center: [-8.4833, 140.4000],
                name: 'Kab. Merauke'
            },
            '9102': {
                center: [-4.0833, 138.9167],
                name: 'Kab. Jayawijaya'
            },
            '9103': {
                center: [-2.8833, 140.4500],
                name: 'Kab. Jayapura'
            },
            '9104': {
                center: [-3.3667, 135.4833],
                name: 'Kab. Nabire'
            },
            '9105': {
                center: [-1.8167, 136.2333],
                name: 'Kab. Kepulauan Yapen'
            },
            '9106': {
                center: [-1.1667, 136.1000],
                name: 'Kab. Biak Numfor'
            },
            '9107': {
                center: [-3.8833, 136.3500],
                name: 'Kab. Paniai'
            },
            '9108': {
                center: [-4.5333, 136.5500],
                name: 'Kab. Mimika'
            },
            '9109': {
                center: [-3.4500, 137.1833],
                name: 'Kab. Puncak Jaya'
            },
            '9110': {
                center: [-1.8667, 138.7667],
                name: 'Kab. Sarmi'
            },
            '9111': {
                center: [-3.1833, 140.4833],
                name: 'Kab. Keerom'
            },
            '9112': {
                center: [-4.0833, 140.4000],
                name: 'Kab. Pegunungan Bintang'
            },
            '9113': {
                center: [-4.4667, 139.4167],
                name: 'Kab. Yahukimo'
            },
            '9114': {
                center: [-3.4833, 138.0500],
                name: 'Kab. Tolikara'
            },
            '9115': {
                center: [-1.8333, 136.6333],
                name: 'Kab. Waropen'
            },
            '9116': {
                center: [-5.8333, 140.3667],
                name: 'Kab. Boven Digoel'
            },
            '9117': {
                center: [-6.7833, 140.2500],
                name: 'Kab. Mappi'
            },
            '9118': {
                center: [-5.0500, 138.4667],
                name: 'Kab. Asmat'
            },
            '9119': {
                center: [-4.4000, 138.0333],
                name: 'Kab. Yahukimo'
            },
            '9120': {
                center: [-1.3167, 135.5333],
                name: 'Kab. Supiori'
            },
            '9121': {
                center: [-2.3833, 138.5333],
                name: 'Kab. Mamberamo Raya'
            },
            '9122': {
                center: [-2.2500, 137.1833],
                name: 'Kab. Mamberamo Tengah'
            },
            '9123': {
                center: [-3.6167, 138.7833],
                name: 'Kab. Yalimo'
            },
            '9124': {
                center: [-3.9000, 138.3167],
                name: 'Kab. Lanny Jaya'
            },
            '9125': {
                center: [-4.4000, 138.0333],
                name: 'Kab. Nduga'
            },
            '9126': {
                center: [-3.8333, 137.1167],
                name: 'Kab. Puncak'
            },
            '9127': {
                center: [-3.9167, 135.7167],
                name: 'Kab. Dogiyai'
            },
            '9128': {
                center: [-3.4167, 136.7833],
                name: 'Kab. Intan Jaya'
            },
            '9129': {
                center: [-4.1833, 136.2167],
                name: 'Kab. Deiyai'
            },

            // Provinsi Papua Barat (92)
            '92': {
                center: [-1.3361154, 133.1747162],
                name: 'Papua Barat'
            },
            '9271': {
                center: [-0.8833, 131.2667],
                name: 'Kota Sorong'
            },
            '9201': {
                center: [-0.9500, 131.2833],
                name: 'Kab. Sorong'
            },
            '9202': {
                center: [-0.8667, 134.0667],
                name: 'Kab. Manokwari'
            },
            '9203': {
                center: [-2.9167, 132.3000],
                name: 'Kab. Fakfak'
            },
            '9204': {
                center: [-1.8667, 132.0333],
                name: 'Kab. Sorong Selatan'
            },
            '9205': {
                center: [-0.2333, 130.5167],
                name: 'Kab. Raja Ampat'
            },
            '9206': {
                center: [-1.8833, 133.5167],
                name: 'Kab. Teluk Bintuni'
            },
            '9207': {
                center: [-2.7167, 134.3833],
                name: 'Kab. Teluk Wondama'
            },
            '9208': {
                center: [-3.6500, 133.6833],
                name: 'Kab. Kaimana'
            },
            '9209': {
                center: [-0.6667, 132.0833],
                name: 'Kab. Tambrauw'
            },
            '9210': {
                center: [-1.2667, 132.3000],
                name: 'Kab. Maybrat'
            },
            '9211': {
                center: [-2.0833, 134.0667],
                name: 'Kab. Manokwari Selatan'
            },
            '9212': {
                center: [-1.3500, 133.7833],
                name: 'Kab. Pegunungan Arfak'
            }
        };

        function createSampleIndonesiaRegions() {
            return [
                // Aceh
                {
                    name: 'Kota Banda Aceh',
                    center: [5.5577, 95.3222]
                },
                {
                    name: 'Kota Sabang',
                    center: [5.8947, 95.3213]
                },
                {
                    name: 'Kota Lhokseumawe',
                    center: [5.1801, 97.1507]
                },
                {
                    name: 'Kota Langsa',
                    center: [4.4683, 97.9681]
                },
                {
                    name: 'Kota Subulussalam',
                    center: [2.6667, 97.9667]
                },
                {
                    name: 'Kab. Aceh Selatan',
                    center: [3.2017, 97.1382]
                },
                {
                    name: 'Kab. Aceh Tenggara',
                    center: [3.3258, 97.7956]
                },
                {
                    name: 'Kab. Aceh Timur',
                    center: [4.6351, 97.6253]
                },
                {
                    name: 'Kab. Aceh Tengah',
                    center: [4.6271, 96.8324]
                },
                {
                    name: 'Kab. Aceh Barat',
                    center: [4.4500, 96.1667]
                },
                {
                    name: 'Kab. Aceh Besar',
                    center: [5.4500, 95.4167]
                },
                {
                    name: 'Kab. Pidie',
                    center: [5.1333, 96.1333]
                },
                {
                    name: 'Kab. Aceh Utara',
                    center: [5.1719, 97.1361]
                },
                {
                    name: 'Kab. Simeulue',
                    center: [2.6167, 96.0833]
                },
                {
                    name: 'Kab. Aceh Singkil',
                    center: [2.4167, 97.7833]
                },
                {
                    name: 'Kab. Bireun',
                    center: [5.2033, 96.7017]
                },
                {
                    name: 'Kab. Aceh Barat Daya',
                    center: [3.9167, 96.8500]
                },
                {
                    name: 'Kab. Gayo Lues',
                    center: [4.0167, 97.4167]
                },
                {
                    name: 'Kab. Aceh Jaya',
                    center: [4.8333, 95.6500]
                },
                {
                    name: 'Kab. Nagan Raya',
                    center: [4.1381, 96.5631]
                },
                {
                    name: 'Kab. Aceh Tamiang',
                    center: [4.2500, 98.0167]
                },
                {
                    name: 'Kab. Bener Meriah',
                    center: [4.7833, 96.8167]
                },
                {
                    name: 'Kab. Pidie Jaya',
                    center: [5.1500, 96.1833]
                },

                // Sumatera Utara
                {
                    name: 'Kota Medan',
                    center: [3.5952, 98.6722]
                },
                {
                    name: 'Kota Pematang Siantar',
                    center: [2.9597, 99.0687]
                },
                {
                    name: 'Kota Sibolga',
                    center: [1.7427, 98.7792]
                },
                {
                    name: 'Kota Tanjung Balai',
                    center: [2.9675, 99.7983]
                },
                {
                    name: 'Kota Binjai',
                    center: [3.6000, 98.4667]
                },
                {
                    name: 'Kota Tebing Tinggi',
                    center: [3.3281, 99.1625]
                },
                {
                    name: 'Kota Padang Sidempuan',
                    center: [1.3800, 99.2700]
                },
                {
                    name: 'Kota Gunung Sitoli',
                    center: [1.2877, 97.6144]
                },
                {
                    name: 'Kab. Serdang Bedagai',
                    center: [3.3667, 98.9333]
                },
                {
                    name: 'Kab. Samosir',
                    center: [2.6500, 98.7000]
                },
                {
                    name: 'Kab. Humbang Hasundutan',
                    center: [2.2667, 98.5000]
                },
                {
                    name: 'Kab. Pakpak Bharat',
                    center: [2.6167, 98.2500]
                },
                {
                    name: 'Kab. Nias Selatan',
                    center: [0.5833, 97.8333]
                },
                {
                    name: 'Kab. Mandailing Natal',
                    center: [0.7667, 99.3167]
                },
                {
                    name: 'Kab. Toba Samosir',
                    center: [2.6500, 99.0833]
                },
                {
                    name: 'Kab. Dairi',
                    center: [2.7000, 98.2167]
                },
                {
                    name: 'Kab. Labuhan Batu',
                    center: [2.1167, 100.0833]
                },
                {
                    name: 'Kab. Asahan',
                    center: [2.9833, 99.6167]
                },
                {
                    name: 'Kab. Simalungun',
                    center: [2.9667, 99.0167]
                },
                {
                    name: 'Kab. Deli Serdang',
                    center: [3.4333, 98.6833]
                },
                {
                    name: 'Kab. Karo',
                    center: [3.1000, 98.3167]
                },
                {
                    name: 'Kab. Langkat',
                    center: [3.7833, 98.0000]
                },
                {
                    name: 'Kab. Nias',
                    center: [1.0833, 97.5833]
                },
                {
                    name: 'Kab. Tapanuli Selatan',
                    center: [1.5500, 99.2333]
                },
                {
                    name: 'Kab. Tapanuli Utara',
                    center: [2.0167, 99.0833]
                },
                {
                    name: 'Kab. Tapanuli Tengah',
                    center: [1.9167, 98.6667]
                },
                {
                    name: 'Kab. Batu Bara',
                    center: [3.2167, 99.4667]
                },
                {
                    name: 'Kab. Padang Lawas Utara',
                    center: [1.6833, 99.4333]
                },
                {
                    name: 'Kab. Padang Lawas',
                    center: [1.1667, 99.7500]
                },
                {
                    name: 'Kab. Labuhanbatu Selatan',
                    center: [1.8500, 100.1333]
                },
                {
                    name: 'Kab. Labuhanbatu Utara',
                    center: [2.3667, 100.0000]
                },
                {
                    name: 'Kab. Nias Utara',
                    center: [1.4167, 97.5000]
                },
                {
                    name: 'Kab. Nias Barat',
                    center: [1.1167, 97.5167]
                },

                // Sumatera Barat
                {
                    name: 'Kota Padang',
                    center: [-0.9471, 100.4172]
                },
                {
                    name: 'Kota Solok',
                    center: [-0.7917, 100.6583]
                },
                {
                    name: 'Kota Sawhlunto',
                    center: [-0.6833, 100.7833]
                },
                {
                    name: 'Kota Padang Panjang',
                    center: [-0.4667, 100.4000]
                },
                {
                    name: 'Kota Bukittinggi',
                    center: [-0.3067, 100.3692]
                },
                {
                    name: 'Kota Payakumbuh',
                    center: [-0.2167, 100.6333]
                },
                {
                    name: 'Kota Pariaman',
                    center: [-0.6167, 100.1167]
                },
                {
                    name: 'Kab. Pasaman Barat',
                    center: [0.1833, 99.9167]
                },
                {
                    name: 'Kab. Solok Selatan',
                    center: [-1.4333, 101.2667]
                },
                {
                    name: 'Kab. Dharmasraya',
                    center: [-1.0833, 101.4500]
                },
                {
                    name: 'Kab. Kepulauan Mentawai',
                    center: [-2.0833, 99.6500]
                },
                {
                    name: 'Kab. Pasaman',
                    center: [0.2500, 100.0833]
                },
                {
                    name: 'Kab. Lima Puluh Kota',
                    center: [-0.0167, 100.5167]
                },
                {
                    name: 'Kab. Agam',
                    center: [-0.2500, 100.1500]
                },
                {
                    name: 'Kab. Padang Pariaman',
                    center: [-0.5500, 100.2167]
                },
                {
                    name: 'Kab. Tanah Datar',
                    center: [-0.4833, 100.5333]
                },
                {
                    name: 'Kab. Sijunjung',
                    center: [-0.6833, 101.0167]
                },
                {
                    name: 'Kab. Solok',
                    center: [-1.0167, 100.7833]
                },
                {
                    name: 'Kab. Pesisir Selatan',
                    center: [-1.7833, 100.7833]
                },

                // Riau
                {
                    name: 'Kota Pekan Baru',
                    center: [0.5071, 101.4478]
                },
                {
                    name: 'Kota Dumai',
                    center: [1.6667, 101.4500]
                },
                {
                    name: 'Kab. Kepulauan Meranti',
                    center: [1.2333, 103.3000]
                },
                {
                    name: 'Kab. Kuantan Singingi',
                    center: [-0.4833, 101.4667]
                },
                {
                    name: 'Kab. Siak',
                    center: [1.1167, 102.0000]
                },
                {
                    name: 'Kab. Rokan Hilir',
                    center: [2.0833, 100.8833]
                },
                {
                    name: 'Kab. Rokan Hulu',
                    center: [1.1167, 100.4833]
                },
                {
                    name: 'Kab. Pelalawan',
                    center: [0.3333, 102.2333]
                },
                {
                    name: 'Kab. Indragiri Hilir',
                    center: [-0.3333, 103.2500]
                },
                {
                    name: 'Kab. Bengkalis',
                    center: [1.4667, 101.4333]
                },
                {
                    name: 'Kab. Indragiri Hulu',
                    center: [-0.3667, 102.6000]
                },
                {
                    name: 'Kab. Kampar',
                    center: [0.3167, 101.1500]
                },

                // Jambi
                {
                    name: 'Kota Jambi',
                    center: [-1.4852, 103.6151]
                },
                {
                    name: 'Kota Sungai Penuh',
                    center: [-2.0667, 101.3833]
                },
                {
                    name: 'Kab. Tebo',
                    center: [-1.4167, 102.4500]
                },
                {
                    name: 'Kab. Bungo',
                    center: [-1.4833, 101.9000]
                },
                {
                    name: 'Kab. Tanjung Jabung Timur',
                    center: [-1.0500, 103.8667]
                },
                {
                    name: 'Kab. Tanjung Jabung Barat',
                    center: [-1.2167, 103.4000]
                },
                {
                    name: 'Kab. Muaro Jambi',
                    center: [-1.4500, 103.8833]
                },
                {
                    name: 'Kab. Batanghari',
                    center: [-1.6833, 103.1167]
                },
                {
                    name: 'Kab. Sarolangun',
                    center: [-2.2833, 102.6833]
                },
                {
                    name: 'Kab. Merangin',
                    center: [-2.1333, 101.9833]
                },
                {
                    name: 'Kab. Kerinci',
                    center: [-2.0667, 101.5000]
                },

                // Sumatera Selatan
                {
                    name: 'Kota Palembang',
                    center: [-2.9761, 104.7754]
                },
                {
                    name: 'Kota Pagar Alam',
                    center: [-4.0067, 103.2400]
                },
                {
                    name: 'Kota Lubuk Linggau',
                    center: [-3.2917, 102.8583]
                },
                {
                    name: 'Kota Prabumulih',
                    center: [-3.4500, 104.2333]
                },
                {
                    name: 'Kab. Musi Rawas Utara',
                    center: [-2.8333, 102.5000]
                },
                {
                    name: 'Kab. Penukal Abab Lematang Ilir',
                    center: [-3.2833, 104.0833]
                },
                {
                    name: 'Kab. Empat Lawang',
                    center: [-3.6833, 103.1167]
                },
                {
                    name: 'Kab. Ogan Ilir',
                    center: [-3.0500, 104.6000]
                },
                {
                    name: 'Kab. Ogan Komering Ulu Selatan',
                    center: [-4.2833, 103.7667]
                },
                {
                    name: 'Kab. Ogan Komering Ulu Timur',
                    center: [-3.8333, 104.6167]
                },
                {
                    name: 'Kab. Banyuasin',
                    center: [-2.8000, 104.8833]
                },
                {
                    name: 'Kab. Musi Banyuasin',
                    center: [-2.5333, 104.6167]
                },
                {
                    name: 'Kab. Musi Rawas',
                    center: [-3.0000, 102.9167]
                },
                {
                    name: 'Kab. Lahat',
                    center: [-3.7833, 103.5333]
                },
                {
                    name: 'Kab. Muara Enim',
                    center: [-3.6000, 103.9333]
                },
                {
                    name: 'Kab. Ogan Komering Ilir',
                    center: [-3.2167, 105.0833]
                },
                {
                    name: 'Kab. Ogan Komering Ulu',
                    center: [-4.1167, 104.1667]
                },

                // Bengkulu
                {
                    name: 'Kota Bengkulu',
                    center: [-3.8004, 102.2655]
                },
                {
                    name: 'Kab. Bengkulu Tengah',
                    center: [-3.6000, 102.2333]
                },
                {
                    name: 'Kab. Kepahiang',
                    center: [-3.6167, 102.5833]
                },
                {
                    name: 'Kab. Lebong',
                    center: [-3.2000, 102.1833]
                },
                {
                    name: 'Kab. Muko Muko',
                    center: [-2.5833, 101.1667]
                },
                {
                    name: 'Kab. Seluma',
                    center: [-4.0667, 102.5167]
                },
                {
                    name: 'Kab. Kaur',
                    center: [-4.6000, 103.4500]
                },
                {
                    name: 'Kab. Bengkulu Utara',
                    center: [-3.3000, 101.9833]
                },
                {
                    name: 'Kab. Rejang Lebong',
                    center: [-3.4500, 102.1167]
                },
                {
                    name: 'Kab. Bengkulu Selatan',
                    center: [-4.3833, 103.0167]
                },

                // Lampung
                {
                    name: 'Kota Bandar Lampung',
                    center: [-5.3971, 105.2668]
                },
                {
                    name: 'Kota Metro',
                    center: [-5.1133, 105.3067]
                },
                {
                    name: 'Kab. Pesisir Barat',
                    center: [-5.4333, 104.1500]
                },
                {
                    name: 'Kab. Tulangbawang Barat',
                    center: [-4.0167, 105.8000]
                },
                {
                    name: 'Kab. Mesuji',
                    center: [-3.4333, 105.8333]
                },
                {
                    name: 'Kab. Pringsewu',
                    center: [-5.3667, 104.9833]
                },
                {
                    name: 'Kab. Pesawaran',
                    center: [-5.4833, 105.0833]
                },
                {
                    name: 'Kab. Way Kanan',
                    center: [-4.2333, 104.5833]
                },
                {
                    name: 'Kab. Lampung Timur',
                    center: [-4.8333, 105.6167]
                },
                {
                    name: 'Kab. Tanggamus',
                    center: [-5.4667, 104.6333]
                },
                {
                    name: 'Kab. Tulang Bawang',
                    center: [-3.8333, 105.6333]
                },
                {
                    name: 'Kab. Lampung Barat',
                    center: [-4.8667, 104.2833]
                },
                {
                    name: 'Kab. Lampung Utara',
                    center: [-4.1833, 104.7833]
                },
                {
                    name: 'Kab. Lampung Tengah',
                    center: [-4.8667, 105.2667]
                },
                {
                    name: 'Kab. Lampung Selatan',
                    center: [-5.6333, 105.4833]
                },

                // Kepulauan Bangka Belitung
                {
                    name: 'Kota Pangkal Pinang',
                    center: [-2.1316, 106.1168]
                },
                {
                    name: 'Kab. Belitung Timur',
                    center: [-2.8333, 108.2500]
                },
                {
                    name: 'Kab. Bangka Barat',
                    center: [-1.8667, 105.9000]
                },
                {
                    name: 'Kab. Bangka Tengah',
                    center: [-2.2000, 106.2667]
                },
                {
                    name: 'Kab. Bangka Selatan',
                    center: [-2.8500, 106.7167]
                },
                {
                    name: 'Kab. Belitung',
                    center: [-2.7667, 107.6333]
                },
                {
                    name: 'Kab. Bangka',
                    center: [-2.1167, 106.1000]
                },

                // Kepulauan Riau
                {
                    name: 'Kota Batam',
                    center: [1.0456, 104.0305]
                },
                {
                    name: 'Kota Tanjung Pinang',
                    center: [0.9167, 104.4500]
                },
                {
                    name: 'Kab. Kepulauan Anambas',
                    center: [3.0167, 106.0833]
                },
                {
                    name: 'Kab. Lingga',
                    center: [0.2000, 104.6167]
                },
                {
                    name: 'Kab. Natuna',
                    center: [4.0000, 108.2000]
                },
                {
                    name: 'Kab. Karimun',
                    center: [1.0500, 103.4833]
                },
                {
                    name: 'Kab. Bintan',
                    center: [1.1333, 104.3500]
                },

                // DKI Jakarta
                {
                    name: 'Kota Jakarta Timur',
                    center: [-6.2251, 106.9004]
                },
                {
                    name: 'Kota Jakarta Selatan',
                    center: [-6.2615, 106.8106]
                },
                {
                    name: 'Kota Jakarta Barat',
                    center: [-6.1352, 106.7549]
                },
                {
                    name: 'Kota Jakarta Utara',
                    center: [-6.1388, 106.8827]
                },
                {
                    name: 'Kota Jakarta Pusat',
                    center: [-6.1751, 106.8275]
                },
                {
                    name: 'Kab. Kepulauan Seribu',
                    center: [-5.6167, 106.5833]
                },

                // Jawa Barat
                {
                    name: 'Kota Bandung',
                    center: [-6.9175, 107.6191]
                },
                {
                    name: 'Kota Banjar',
                    center: [-7.3500, 108.5333]
                },
                {
                    name: 'Kota Tasikmalaya',
                    center: [-7.3274, 108.2207]
                },
                {
                    name: 'Kota Cimahi',
                    center: [-6.8721, 107.5420]
                },
                {
                    name: 'Kota Depok',
                    center: [-6.4025, 106.7942]
                },
                {
                    name: 'Kota Bekasi',
                    center: [-6.2383, 106.9756]
                },
                {
                    name: 'Kota Cirebon',
                    center: [-6.7063, 108.5570]
                },
                {
                    name: 'Kota Sukabumi',
                    center: [-6.9185, 106.9274]
                },
                {
                    name: 'Kota Bogor',
                    center: [-6.5944, 106.7892]
                },
                {
                    name: 'Kab. Pangandaran',
                    center: [-7.6833, 108.6500]
                },
                {
                    name: 'Kab. Bandung Barat',
                    center: [-6.8500, 107.4833]
                },
                {
                    name: 'Kab. Bekasi',
                    center: [-6.2500, 107.1500]
                },
                {
                    name: 'Kab. Karawang',
                    center: [-6.3000, 107.3000]
                },
                {
                    name: 'Kab. Purwakarta',
                    center: [-6.5667, 107.4333]
                },
                {
                    name: 'Kab. Subang',
                    center: [-6.5667, 107.7667]
                },
                {
                    name: 'Kab. Indramayu',
                    center: [-6.3167, 108.3167]
                },
                {
                    name: 'Kab. Sumedang',
                    center: [-6.8500, 107.9167]
                },
                {
                    name: 'Kab. Majalengka',
                    center: [-6.8333, 108.2333]
                },
                {
                    name: 'Kab. Cirebon',
                    center: [-6.7000, 108.4667]
                },
                {
                    name: 'Kab. Kuningan',
                    center: [-6.9833, 108.4833]
                },
                {
                    name: 'Kab. Ciamis',
                    center: [-7.3333, 108.3500]
                },
                {
                    name: 'Kab. Tasikmalaya',
                    center: [-7.6833, 108.0500]
                },
                {
                    name: 'Kab. Garut',
                    center: [-7.2167, 107.9000]
                },
                {
                    name: 'Kab. Bandung',
                    center: [-7.0500, 107.5333]
                },
                {
                    name: 'Kab. Cianjur',
                    center: [-6.8167, 107.1333]
                },
                {
                    name: 'Kab. Sukabumi',
                    center: [-6.9167, 106.6000]
                },
                {
                    name: 'Kab. Bogor',
                    center: [-6.5944, 106.7892]
                },

                // Jawa Tengah
                {
                    name: 'Kota Semarang',
                    center: [-6.9932, 110.4203]
                },
                {
                    name: 'Kota Tegal',
                    center: [-6.8694, 109.1402]
                },
                {
                    name: 'Kota Pekalongan',
                    center: [-6.8886, 109.6753]
                },
                {
                    name: 'Kota Salatiga',
                    center: [-7.3317, 110.5069]
                },
                {
                    name: 'Kota Surakarta',
                    center: [-7.5755, 110.8243]
                },
                {
                    name: 'Kota Magelang',
                    center: [-7.4814, 110.2181]
                },
                {
                    name: 'Kab. Brebes',
                    center: [-6.8833, 109.0333]
                },
                {
                    name: 'Kab. Tegal',
                    center: [-6.9000, 109.1333]
                },
                {
                    name: 'Kab. Pemalang',
                    center: [-6.8833, 109.3833]
                },
                {
                    name: 'Kab. Pekalongan',
                    center: [-7.0167, 109.6833]
                },
                {
                    name: 'Kab. Batang',
                    center: [-6.9167, 109.7333]
                },
                {
                    name: 'Kab. Kendal',
                    center: [-7.0167, 110.2000]
                },
                {
                    name: 'Kab. Temanggung',
                    center: [-7.3167, 110.1667]
                },
                {
                    name: 'Kab. Semarang',
                    center: [-7.1500, 110.4833]
                },
                {
                    name: 'Kab. Demak',
                    center: [-6.8833, 110.6333]
                },
                {
                    name: 'Kab. Jepara',
                    center: [-6.5833, 110.6667]
                },
                {
                    name: 'Kab. Kudus',
                    center: [-6.8167, 110.8333]
                },
                {
                    name: 'Kab. Pati',
                    center: [-6.7500, 111.0500]
                },
                {
                    name: 'Kab. Rembang',
                    center: [-6.7000, 111.3500]
                },
                {
                    name: 'Kab. Blora',
                    center: [-6.9667, 111.4167]
                },
                {
                    name: 'Kab. Grobogan',
                    center: [-7.0500, 110.9167]
                },
                {
                    name: 'Kab. Sragen',
                    center: [-7.4167, 111.0167]
                },
                {
                    name: 'Kab. Karanganyar',
                    center: [-7.6000, 110.9500]
                },
                {
                    name: 'Kab. Wonogiri',
                    center: [-7.8167, 110.9167]
                },
                {
                    name: 'Kab. Sukoharjo',
                    center: [-7.6833, 110.8333]
                },
                {
                    name: 'Kab. Klaten',
                    center: [-7.7167, 110.6000]
                },
                {
                    name: 'Kab. Boyolali',
                    center: [-7.5333, 110.5833]
                },
                {
                    name: 'Kab. Magelang',
                    center: [-7.4833, 110.2167]
                },
                {
                    name: 'Kab. Wonosobo',
                    center: [-7.3667, 109.9000]
                },
                {
                    name: 'Kab. Purworejo',
                    center: [-7.7167, 110.0167]
                },
                {
                    name: 'Kab. Kebumen',
                    center: [-7.6667, 109.6500]
                },
                {
                    name: 'Kab. Banjarnegara',
                    center: [-7.3000, 109.6833]
                },
                {
                    name: 'Kab. Purbalingga',
                    center: [-7.3833, 109.3667]
                },
                {
                    name: 'Kab. Banyumas',
                    center: [-7.5167, 109.2833]
                },
                {
                    name: 'Kab. Cilacap',
                    center: [-7.7167, 109.0167]
                },

                // DI Yogyakarta
                {
                    name: 'Kota Yogyakarta',
                    center: [-7.7956, 110.3695]
                },
                {
                    name: 'Kab. Sleman',
                    center: [-7.7167, 110.3500]
                },
                {
                    name: 'Kab. Gunung Kidul',
                    center: [-7.9833, 110.5833]
                },
                {
                    name: 'Kab. Bantul',
                    center: [-7.8833, 110.3333]
                },
                {
                    name: 'Kab. Kulon Progo',
                    center: [-7.8333, 110.1667]
                },

                // Jawa Timur
                {
                    name: 'Kota Surabaya',
                    center: [-7.2575, 112.7521]
                },
                {
                    name: 'Kota Batu',
                    center: [-7.8700, 112.5281]
                },
                {
                    name: 'Kota Madiun',
                    center: [-7.6298, 111.5239]
                },
                {
                    name: 'Kota Mojokerto',
                    center: [-7.4664, 112.4336]
                },
                {
                    name: 'Kota Pasuruan',
                    center: [-7.6453, 112.9075]
                },
                {
                    name: 'Kota Probolinggo',
                    center: [-7.7543, 113.2159]
                },
                {
                    name: 'Kota Malang',
                    center: [-7.9666, 112.6326]
                },
                {
                    name: 'Kota Blitar',
                    center: [-8.0956, 112.1609]
                },
                {
                    name: 'Kota Kediri',
                    center: [-7.8481, 112.0178]
                },
                {
                    name: 'Kab. Sumenep',
                    center: [-7.0167, 113.8667]
                },
                {
                    name: 'Kab. Pamekasan',
                    center: [-7.1667, 113.4833]
                },
                {
                    name: 'Kab. Sampang',
                    center: [-7.1833, 113.2333]
                },
                {
                    name: 'Kab. Bangkalan',
                    center: [-7.0333, 112.7500]
                },
                {
                    name: 'Kab. Gresik',
                    center: [-7.1500, 112.6500]
                },
                {
                    name: 'Kab. Lamongan',
                    center: [-7.1167, 112.4167]
                },
                {
                    name: 'Kab. Tuban',
                    center: [-6.9000, 111.9333]
                },
                {
                    name: 'Kab. Bojonegoro',
                    center: [-7.1500, 111.8833]
                },
                {
                    name: 'Kab. Ngawi',
                    center: [-7.4000, 111.4500]
                },
                {
                    name: 'Kab. Magetan',
                    center: [-7.6333, 111.3500]
                },
                {
                    name: 'Kab. Madiun',
                    center: [-7.6167, 111.5167]
                },
                {
                    name: 'Kab. Nganjuk',
                    center: [-7.6000, 111.9167]
                },
                {
                    name: 'Kab. Jombang',
                    center: [-7.5500, 112.2333]
                },
                {
                    name: 'Kab. Mojokerto',
                    center: [-7.4667, 112.4333]
                },
                {
                    name: 'Kab. Sidoarjo',
                    center: [-7.4500, 112.7167]
                },
                {
                    name: 'Kab. Pasuruan',
                    center: [-7.7333, 112.9000]
                },
                {
                    name: 'Kab. Probolinggo',
                    center: [-7.8833, 113.2000]
                },
                {
                    name: 'Kab. Situbondo',
                    center: [-7.7000, 114.0167]
                },
                {
                    name: 'Kab. Bondowoso',
                    center: [-7.9167, 113.8167]
                },
                {
                    name: 'Kab. Banyuwangi',
                    center: [-8.2167, 114.3667]
                },
                {
                    name: 'Kab. Jember',
                    center: [-8.1667, 113.7000]
                },
                {
                    name: 'Kab. Lumajang',
                    center: [-8.1333, 113.2167]
                },
                {
                    name: 'Kab. Malang',
                    center: [-8.1000, 112.6333]
                },
                {
                    name: 'Kab. Kediri',
                    center: [-7.8167, 112.0167]
                },
                {
                    name: 'Kab. Blitar',
                    center: [-8.1000, 112.1667]
                },
                {
                    name: 'Kab. Tulungagung',
                    center: [-8.0667, 111.9000]
                },
                {
                    name: 'Kab. Trenggalek',
                    center: [-8.0500, 111.7167]
                },
                {
                    name: 'Kab. Ponorogo',
                    center: [-7.8667, 111.4667]
                },
                {
                    name: 'Kab. Pacitan',
                    center: [-8.2000, 111.0833]
                },

                // Banten
                {
                    name: 'Kota Serang',
                    center: [-6.1200, 106.1502]
                },
                {
                    name: 'Kota Cilegon',
                    center: [-6.0167, 106.0167]
                },
                {
                    name: 'Kota Tangerang',
                    center: [-6.1783, 106.6319]
                },
                {
                    name: 'Kota Tangerang Selatan',
                    center: [-6.2800, 106.7100]
                },
                {
                    name: 'Kab. Serang',
                    center: [-6.3000, 106.2500]
                },
                {
                    name: 'Kab. Tangerang',
                    center: [-6.1833, 106.6333]
                },
                {
                    name: 'Kab. Lebak',
                    center: [-6.5667, 106.2500]
                },
                {
                    name: 'Kab. Pandeglang',
                    center: [-6.3000, 105.9167]
                },

                // Bali
                {
                    name: 'Kota Denpasar',
                    center: [-8.6705, 115.2126]
                },
                {
                    name: 'Kab. Buleleng',
                    center: [-8.1167, 115.0833]
                },
                {
                    name: 'Kab. Karangasem',
                    center: [-8.4167, 115.6167]
                },
                {
                    name: 'Kab. Bangli',
                    center: [-8.2833, 115.3500]
                },
                {
                    name: 'Kab. Klungkung',
                    center: [-8.5333, 115.4000]
                },
                {
                    name: 'Kab. Gianyar',
                    center: [-8.5333, 115.3333]
                },
                {
                    name: 'Kab. Badung',
                    center: [-8.5500, 115.1667]
                },
                {
                    name: 'Kab. Tabanan',
                    center: [-8.5333, 115.1167]
                },
                {
                    name: 'Kab. Jembrana',
                    center: [-8.3833, 114.6500]
                },

                // Nusa Tenggara Barat
                {
                    name: 'Kota Mataram',
                    center: [-8.5833, 116.1167]
                },
                {
                    name: 'Kota Bima',
                    center: [-8.4667, 118.7167]
                },
                {
                    name: 'Kab. Lombok Utara',
                    center: [-8.3333, 116.3333]
                },
                {
                    name: 'Kab. Sumbawa Barat',
                    center: [-8.6833, 117.1000]
                },
                {
                    name: 'Kab. Bima',
                    center: [-8.6167, 118.8833]
                },
                {
                    name: 'Kab. Dompu',
                    center: [-8.5333, 118.4667]
                },
                {
                    name: 'Kab. Sumbawa',
                    center: [-8.4333, 117.4167]
                },
                {
                    name: 'Kab. Lombok Timur',
                    center: [-8.5333, 116.5500]
                },
                {
                    name: 'Kab. Lombok Tengah',
                    center: [-8.7000, 116.2833]
                },
                {
                    name: 'Kab. Lombok Barat',
                    center: [-8.6500, 116.1167]
                },

                // Nusa Tenggara Timur
                {
                    name: 'Kota Kupang',
                    center: [-10.1718, 123.6044]
                },
                {
                    name: 'Kab. Malaka',
                    center: [-9.5667, 124.9000]
                },
                {
                    name: 'Kab. Sabu Raijua',
                    center: [-10.5000, 121.8333]
                },
                {
                    name: 'Kab. Manggarai Timur',
                    center: [-8.6167, 120.4500]
                },
                {
                    name: 'Kab. Sumba Barat Daya',
                    center: [-9.6833, 119.2167]
                },
                {
                    name: 'Kab. Sumba Tengah',
                    center: [-9.4500, 119.7500]
                },
                {
                    name: 'Kab. Nagekeo',
                    center: [-8.7500, 121.2333]
                },
                {
                    name: 'Kab. Manggarai Barat',
                    center: [-8.6500, 120.1833]
                },
                {
                    name: 'Kab. Rote Ndao',
                    center: [-10.7333, 123.1167]
                },
                {
                    name: 'Kab. Lembata',
                    center: [-8.3833, 123.5000]
                },
                {
                    name: 'Kab. Sumba Barat',
                    center: [-9.4500, 119.4000]
                },
                {
                    name: 'Kab. Sumba Timur',
                    center: [-9.8167, 120.2833]
                },
                {
                    name: 'Kab. Manggarai',
                    center: [-8.5333, 120.4667]
                },
                {
                    name: 'Kab. Ngada',
                    center: [-8.6500, 120.9833]
                },
                {
                    name: 'Kab. Ende',
                    center: [-8.8333, 121.6667]
                },
                {
                    name: 'Kab. Sikka',
                    center: [-8.6833, 122.2333]
                },
                {
                    name: 'Kab. Flores Timur',
                    center: [-8.2167, 122.9667]
                },
                {
                    name: 'Kab. Alor',
                    center: [-8.2000, 124.5667]
                },
                {
                    name: 'Kab. Belu',
                    center: [-9.4167, 124.9000]
                },
                {
                    name: 'Kab. Timor Tengah Utara',
                    center: [-9.4333, 124.0333]
                },
                {
                    name: 'Kab. Timor Tengah Selatan',
                    center: [-9.8667, 124.0833]
                },
                {
                    name: 'Kab. Kupang',
                    center: [-10.0167, 123.6167]
                },

                // Kalimantan Barat
                {
                    name: 'Kota Pontianak',
                    center: [-0.0263, 109.3425]
                },
                {
                    name: 'Kota Singkawang',
                    center: [0.9067, 108.9939]
                },
                {
                    name: 'Kab. Kubu Raya',
                    center: [-0.1333, 109.4667]
                },
                {
                    name: 'Kab. Kayong Utara',
                    center: [-1.0833, 110.2167]
                },
                {
                    name: 'Kab. Sekadau',
                    center: [0.0333, 110.9167]
                },
                {
                    name: 'Kab. Melawi',
                    center: [-0.8333, 111.6833]
                },
                {
                    name: 'Kab. Landak',
                    center: [-0.9167, 109.8333]
                },
                {
                    name: 'Kab. Bengkayang',
                    center: [1.0500, 109.4167]
                },
                {
                    name: 'Kab. Kapuas Hulu',
                    center: [0.8333, 112.0000]
                },
                {
                    name: 'Kab. Sintang',
                    center: [0.1500, 111.4833]
                },
                {
                    name: 'Kab. Ketapang',
                    center: [-1.8333, 109.9833]
                },
                {
                    name: 'Kab. Sanggau',
                    center: [0.1667, 110.6000]
                },
                {
                    name: 'Kab. Mempawah',
                    center: [-0.3333, 109.1833]
                },
                {
                    name: 'Kab. Sambas',
                    center: [1.3833, 109.3000]
                },

                // Kalimantan Tengah
                {
                    name: 'Kota Palangkaraya',
                    center: [-2.2067, 113.9117]
                },
                {
                    name: 'Kab. Barito Timur',
                    center: [-1.9333, 114.8833]
                },
                {
                    name: 'Kab. Murung Raya',
                    center: [-1.2667, 114.8000]
                },
                {
                    name: 'Kab. Pulang Pisau',
                    center: [-2.7333, 114.0667]
                },
                {
                    name: 'Kab. Gunung Mas',
                    center: [-1.0833, 113.4333]
                },
                {
                    name: 'Kab. Lamandau',
                    center: [-2.5167, 111.3500]
                },
                {
                    name: 'Kab. Sukamara',
                    center: [-2.6667, 111.2500]
                },
                {
                    name: 'Kab. Seruyan',
                    center: [-2.2500, 112.1333]
                },
                {
                    name: 'Kab. Katingan',
                    center: [-1.6667, 113.0333]
                },
                {
                    name: 'Kab. Barito Utara',
                    center: [-0.7833, 114.9000]
                },
                {
                    name: 'Kab. Barito Selatan',
                    center: [-2.1167, 114.7667]
                },
                {
                    name: 'Kab. Kapuas',
                    center: [-3.0167, 114.3833]
                },
                {
                    name: 'Kab. Kotawaringin Timur',
                    center: [-2.0833, 112.9500]
                },
                {
                    name: 'Kab. Kotawaringin Barat',
                    center: [-2.6833, 111.6167]
                },

                // Kalimantan Selatan
                {
                    name: 'Kota Banjarmasin',
                    center: [-3.3194, 114.5906]
                },
                {
                    name: 'Kota Banjarbaru',
                    center: [-3.4500, 114.8333]
                },
                {
                    name: 'Kab. Balangan',
                    center: [-2.2833, 115.6167]
                },
                {
                    name: 'Kab. Tanah Bambu',
                    center: [-3.4167, 115.3833]
                },
                {
                    name: 'Kab. Tabalong',
                    center: [-1.8667, 115.4333]
                },
                {
                    name: 'Kab. Hulu Sungai Utara',
                    center: [-2.6167, 115.1500]
                },
                {
                    name: 'Kab. Hulu Sungai Tengah',
                    center: [-2.6000, 115.4000]
                },
                {
                    name: 'Kab. Hulu Sungai Selatan',
                    center: [-2.8333, 115.2333]
                },
                {
                    name: 'Kab. Tapin',
                    center: [-2.8833, 115.1333]
                },
                {
                    name: 'Kab. Barito Kuala',
                    center: [-3.2667, 114.6500]
                },
                {
                    name: 'Kab. Banjar',
                    center: [-3.3333, 114.8333]
                },
                {
                    name: 'Kab. Kotabaru',
                    center: [-3.2833, 116.1667]
                },
                {
                    name: 'Kab. Tanah Laut',
                    center: [-3.8167, 114.8667]
                },

                // Kalimantan Timur
                {
                    name: 'Kota Samarinda',
                    center: [-0.4985, 117.1436]
                },
                {
                    name: 'Kota Bontang',
                    center: [0.1333, 117.4833]
                },
                {
                    name: 'Kota Balikpapan',
                    center: [-1.2379, 116.8529]
                },
                {
                    name: 'Kab. Mahakam Ulu',
                    center: [0.4000, 115.6000]
                },
                {
                    name: 'Kab. Penajam Paser Utara',
                    center: [-1.4333, 116.5000]
                },
                {
                    name: 'Kab. Kutai Timur',
                    center: [0.5333, 117.6333]
                },
                {
                    name: 'Kab. Kutai Barat',
                    center: [0.0000, 115.8333]
                },
                {
                    name: 'Kab. Berau',
                    center: [1.8333, 117.3667]
                },
                {
                    name: 'Kab. Kutai Kertanegara',
                    center: [-0.5000, 116.3333]
                },
                {
                    name: 'Kab. Paser',
                    center: [-1.7167, 116.2333]
                },

                // Kalimantan Utara
                {
                    name: 'Kota Tarakan',
                    center: [3.3, 117.6333]
                },
                {
                    name: 'Kab. Tana Tidung',
                    center: [3.5500, 117.2333]
                },
                {
                    name: 'Kab. Nunukan',
                    center: [4.0833, 116.6667]
                },
                {
                    name: 'Kab. Malinau',
                    center: [3.5833, 116.0833]
                },
                {
                    name: 'Kab. Bulungan',
                    center: [2.9333, 117.0833]
                },

                // Sulawesi Utara
                {
                    name: 'Kota Manado',
                    center: [1.4748, 124.8421]
                },
                {
                    name: 'Kota Tomohon',
                    center: [1.3333, 124.8333]
                },
                {
                    name: 'Kota Bitung',
                    center: [1.4500, 125.1833]
                },
                {
                    name: 'Kota Kotamobagu',
                    center: [0.7333, 124.3167]
                },
                {
                    name: 'Kab. Bolaang Mangondow Selatan',
                    center: [0.4167, 123.8667]
                },
                {
                    name: 'Kab. Bolaang Mangondow Timur',
                    center: [0.8833, 124.0000]
                },
                {
                    name: 'Kab. Kepulauan Siau Tagulandang Biaro',
                    center: [2.7833, 125.4833]
                },
                {
                    name: 'Kab. Bolaang Mangondow Utara',
                    center: [0.8500, 124.0833]
                },
                {
                    name: 'Kab. Minahasa Tenggara',
                    center: [1.1000, 124.9000]
                },
                {
                    name: 'Kab. Minahasa Utara',
                    center: [1.4833, 124.9500]
                },
                {
                    name: 'Kab. Minahasa Selatan',
                    center: [1.2000, 124.4500]
                },
                {
                    name: 'Kab. Kepulauan Talaud',
                    center: [4.2667, 126.7833]
                },
                {
                    name: 'Kab. Kepulauan Sangihe',
                    center: [3.5833, 125.5000]
                },
                {
                    name: 'Kab. Minahasa',
                    center: [1.2500, 124.9167]
                },
                {
                    name: 'Kab. Bolaang Mangondow',
                    center: [0.7167, 124.2833]
                },

                // Sulawesi Tengah
                {
                    name: 'Kota Palu',
                    center: [-0.8917, 119.8707]
                },
                {
                    name: 'Kab. Morowali Utara',
                    center: [-1.7500, 121.8833]
                },
                {
                    name: 'Kab. Banggai Laut',
                    center: [-1.3833, 123.3667]
                },
                {
                    name: 'Kab. Sigi',
                    center: [-1.3167, 119.9667]
                },
                {
                    name: 'Kab. Tojo Una-Una',
                    center: [-1.3833, 121.5000]
                },
                {
                    name: 'Kab. Parigi Moutong',
                    center: [-0.6167, 120.4333]
                },
                {
                    name: 'Kab. Banggai Kepulauan',
                    center: [-1.6167, 123.4167]
                },
                {
                    name: 'Kab. Morowali',
                    center: [-2.3167, 121.8833]
                },
                {
                    name: 'Kab. Buol',
                    center: [1.1000, 121.4000]
                },
                {
                    name: 'Kab. Toli-Toli',
                    center: [1.0500, 120.7833]
                },
                {
                    name: 'Kab. Donggala',
                    center: [-0.4167, 119.7500]
                },
                {
                    name: 'Kab. Poso',
                    center: [-1.3833, 120.7500]
                },
                {
                    name: 'Kab. Banggai',
                    center: [-1.5500, 122.8833]
                },

                // Sulawesi Selatan
                {
                    name: 'Kota Makasar',
                    center: [-5.1477, 119.4327]
                },
                {
                    name: 'Kota Palopo',
                    center: [-2.9917, 120.1917]
                },
                {
                    name: 'Kota Pare Pare',
                    center: [-4.0167, 119.6333]
                },
                {
                    name: 'Kab. Toraja Utara',
                    center: [-2.9167, 119.8833]
                },
                {
                    name: 'Kab. Luwu Timur',
                    center: [-2.5500, 121.0000]
                },
                {
                    name: 'Kab. Luwu Utara',
                    center: [-2.6833, 120.1833]
                },
                {
                    name: 'Kab. Tana Toraja',
                    center: [-3.0833, 119.8333]
                },
                {
                    name: 'Kab. Luwu',
                    center: [-3.4000, 120.2500]
                },
                {
                    name: 'Kab. Enrekang',
                    center: [-3.5500, 119.7833]
                },
                {
                    name: 'Kab. Pinrang',
                    center: [-3.6167, 119.6333]
                },
                {
                    name: 'Kab. Sidenreng Rappang',
                    center: [-3.8833, 120.0667]
                },
                {
                    name: 'Kab. Wajo',
                    center: [-4.0000, 120.2833]
                },
                {
                    name: 'Kab. Soppeng',
                    center: [-4.3500, 119.8833]
                },
                {
                    name: 'Kab. Barru',
                    center: [-4.4167, 119.6500]
                },
                {
                    name: 'Kab. Pangkajene Kepulauan',
                    center: [-4.7833, 119.5500]
                },
                {
                    name: 'Kab. Maros',
                    center: [-4.9833, 119.5833]
                },
                {
                    name: 'Kab. Bone',
                    center: [-4.5500, 120.0833]
                },
                {
                    name: 'Kab. Sinjai',
                    center: [-5.1500, 120.2500]
                },
                {
                    name: 'Kab. Gowa',
                    center: [-5.3167, 119.6667]
                },
                {
                    name: 'Kab. Takalar',
                    center: [-5.4000, 119.4667]
                },
                {
                    name: 'Kab. Jeneponto',
                    center: [-5.6500, 119.7333]
                },
                {
                    name: 'Kab. Bantaeng',
                    center: [-5.5167, 120.0167]
                },
                {
                    name: 'Kab. Bulukumba',
                    center: [-5.5500, 120.1833]
                },
                {
                    name: 'Kab. Kepulauan Selayar',
                    center: [-6.1167, 120.4667]
                },

                // Sulawesi Tenggara
                {
                    name: 'Kota Kendari',
                    center: [-3.9450, 122.5986]
                },
                {
                    name: 'Kota Bau Bau',
                    center: [-5.4667, 122.6000]
                },
                {
                    name: 'Kab. Buton Selatan',
                    center: [-5.2833, 122.8833]
                },
                {
                    name: 'Kab. Buton Tengah',
                    center: [-4.8167, 122.9167]
                },
                {
                    name: 'Kab. Muna Barat',
                    center: [-4.6667, 122.5833]
                },
                {
                    name: 'Kab. Konawe Kepulauan',
                    center: [-4.1833, 123.1833]
                },
                {
                    name: 'Kab. Kolaka Timur',
                    center: [-4.0000, 121.5833]
                },
                {
                    name: 'Kab. Buton Utara',
                    center: [-4.4333, 123.0833]
                },
                {
                    name: 'Kab. Konawe Utara',
                    center: [-3.6833, 121.9000]
                },
                {
                    name: 'Kab. Kolaka Utara',
                    center: [-3.4833, 121.1667]
                },
                {
                    name: 'Kab. Wakatobi',
                    center: [-5.2500, 123.6000]
                },
                {
                    name: 'Kab. Bombana',
                    center: [-4.5833, 121.8333]
                },
                {
                    name: 'Kab. Konawe Selatan',
                    center: [-4.2000, 122.4167]
                },
                {
                    name: 'Kab. Buton',
                    center: [-5.2167, 122.9833]
                },
                {
                    name: 'Kab. Muna',
                    center: [-4.8833, 122.6833]
                },
                {
                    name: 'Kab. Konawe',
                    center: [-3.9500, 122.1500]
                },
                {
                    name: 'Kab. Kolaka',
                    center: [-4.0333, 121.3167]
                },

                // Gorontalo
                {
                    name: 'Kota Gorontalo',
                    center: [0.5412, 123.0681]
                },
                {
                    name: 'Kab. Pohuwato',
                    center: [0.7000, 121.6167]
                },
                {
                    name: 'Kab. Bone Bolango',
                    center: [0.5667, 123.0833]
                },
                {
                    name: 'Kab. Boalemo',
                    center: [0.8333, 122.2333]
                },
                {
                    name: 'Kab. Gorontalo',
                    center: [0.6333, 122.4500]
                },
                {
                    name: 'Kab. Gorontalo Utara',
                    center: [0.8167, 122.6333]
                },

                // Sulawesi Barat
                {
                    name: 'Kab. Majene',
                    center: [-3.5400, 118.9700]
                },
                {
                    name: 'Kab. Polowali Mandar',
                    center: [-3.4333, 119.3333]
                },
                {
                    name: 'Kab. Mamasa',
                    center: [-2.9000, 119.3000]
                },
                {
                    name: 'Kab. Mamuju',
                    center: [-2.6833, 118.8833]
                },
                {
                    name: 'Kab. Mamuju Utara',
                    center: [-1.3833, 119.4167]
                },
                {
                    name: 'Kab. Mamuju Tengah',
                    center: [-2.0833, 119.2500]
                },

                // Maluku
                {
                    name: 'Kota Ambon',
                    center: [-3.6954, 128.1814]
                },
                {
                    name: 'Kota Tual',
                    center: [-5.6333, 132.7500]
                },
                {
                    name: 'Kab. Buru Selatan',
                    center: [-3.6167, 126.5833]
                },
                {
                    name: 'Kab. Maluku Barat Daya',
                    center: [-7.5833, 126.5833]
                },
                {
                    name: 'Kab. Kepulauan Aru',
                    center: [-6.1833, 134.5333]
                },
                {
                    name: 'Kab. Seram Bagian Barat',
                    center: [-3.1833, 128.3667]
                },
                {
                    name: 'Kab. Seram Bagian Timur',
                    center: [-3.0833, 129.8333]
                },
                {
                    name: 'Kab. Buru',
                    center: [-3.3833, 126.6833]
                },
                {
                    name: 'Kab. Maluku Tenggara Barat',
                    center: [-7.9833, 131.3000]
                },
                {
                    name: 'Kab. Maluku Tenggara',
                    center: [-5.7000, 132.7333]
                },
                {
                    name: 'Kab. Maluku Tengah',
                    center: [-3.2167, 128.9000]
                },

                // Maluku Utara
                {
                    name: 'Kota Ternate',
                    center: [0.7833, 127.3667]
                },
                {
                    name: 'Kota Tidore Kepulauan',
                    center: [0.6833, 127.4000]
                },
                {
                    name: 'Kab. Pulau Taliabu',
                    center: [-1.8333, 124.7833]
                },
                {
                    name: 'Kab. Pulau Morotai',
                    center: [2.3167, 128.4000]
                },
                {
                    name: 'Kab. Halmahera Timur',
                    center: [1.0500, 128.3667]
                },
                {
                    name: 'Kab. Kepulauan Sula',
                    center: [-1.5833, 125.9167]
                },
                {
                    name: 'Kab. Halmahera Selatan',
                    center: [-1.8167, 127.5167]
                },
                {
                    name: 'Kab. Halmahera Utara',
                    center: [1.5333, 127.8167]
                },
                {
                    name: 'Kab. Halmahera Tengah',
                    center: [0.5500, 128.0333]
                },
                {
                    name: 'Kab. Halmahera Barat',
                    center: [1.4167, 127.5500]
                },

                // Papua
                {
                    name: 'Kota Jayapura',
                    center: [-2.5316, 140.7186]
                },
                {
                    name: 'Kab. Deiyai',
                    center: [-4.1833, 136.2167]
                },
                {
                    name: 'Kab. Intan Jaya',
                    center: [-3.4167, 136.7833]
                },
                {
                    name: 'Kab. Dogiyai',
                    center: [-3.9167, 135.7167]
                },
                {
                    name: 'Kab. Puncak',
                    center: [-3.8333, 137.1167]
                },
                {
                    name: 'Kab. Nduga',
                    center: [-4.4000, 138.0333]
                },
                {
                    name: 'Kab. Lanny Jaya',
                    center: [-3.9000, 138.3167]
                },
                {
                    name: 'Kab. Yalimo',
                    center: [-3.6167, 138.7833]
                },
                {
                    name: 'Kab. Mamberamo Tengah',
                    center: [-2.2500, 137.1833]
                },
                {
                    name: 'Kab. Mamberamo Raya',
                    center: [-2.3833, 138.5333]
                },
                {
                    name: 'Kab. Supiori',
                    center: [-1.3167, 135.5333]
                },
                {
                    name: 'Kab. Asmat',
                    center: [-5.0500, 138.4667]
                },
                {
                    name: 'Kab. Mappi',
                    center: [-6.7833, 140.2500]
                },
                {
                    name: 'Kab. Boven Digoel',
                    center: [-5.8333, 140.3667]
                },
                {
                    name: 'Kab. Waropen',
                    center: [-1.8333, 136.6333]
                },
                {
                    name: 'Kab. Tolikara',
                    center: [-3.4833, 138.0500]
                },
                {
                    name: 'Kab. Yahukimo',
                    center: [-4.4667, 139.4167]
                },
                {
                    name: 'Kab. Pegunungan Bintang',
                    center: [-4.0833, 140.4000]
                },
                {
                    name: 'Kab. Keerom',
                    center: [-3.1833, 140.4833]
                },
                {
                    name: 'Kab. Sarmi',
                    center: [-1.8667, 138.7667]
                },
                {
                    name: 'Kab. Mimika',
                    center: [-4.5333, 136.5500]
                },
                {
                    name: 'Kab. Paniai',
                    center: [-3.8833, 136.3500]
                },
                {
                    name: 'Kab. Puncak Jaya',
                    center: [-3.4500, 137.1833]
                },
                {
                    name: 'Kab. Biak Numfor',
                    center: [-1.1667, 136.1000]
                },
                {
                    name: 'Kab. Kepulauan Yapen',
                    center: [-1.8167, 136.2333]
                },
                {
                    name: 'Kab. Nabire',
                    center: [-3.3667, 135.4833]
                },
                {
                    name: 'Kab. Jayapura',
                    center: [-2.8833, 140.4500]
                },
                {
                    name: 'Kab. Jayawijaya',
                    center: [-4.0833, 138.9167]
                },
                {
                    name: 'Kab. Merauke',
                    center: [-8.4833, 140.4000]
                },

                // Papua Barat
                {
                    name: 'Kota Sorong',
                    center: [-0.8833, 131.2667]
                },
                {
                    name: 'Kab. Pegunungan Arfak',
                    center: [-1.3500, 133.7833]
                },
                {
                    name: 'Kab. Manokwari Selatan',
                    center: [-2.0833, 134.0667]
                },
                {
                    name: 'Kab. Maybrat',
                    center: [-1.2667, 132.3000]
                },
                {
                    name: 'Kab. Tambrauw',
                    center: [-0.6667, 132.0833]
                },
                {
                    name: 'Kab. Kaimana',
                    center: [-3.6500, 133.6833]
                },
                {
                    name: 'Kab. Teluk Wondama',
                    center: [-2.7167, 134.3833]
                },
                {
                    name: 'Kab. Teluk Bintuni',
                    center: [-1.8833, 133.5167]
                },
                {
                    name: 'Kab. Raja Ampat',
                    center: [-0.2333, 130.5167]
                },
                {
                    name: 'Kab. Sorong Selatan',
                    center: [-1.8667, 132.0333]
                },
                {
                    name: 'Kab. Fak Fak',
                    center: [-2.9167, 132.3000]
                },
                {
                    name: 'Kab. Manokwari',
                    center: [-0.8667, 134.0667]
                },
                {
                    name: 'Kab. Sorong',
                    center: [-0.9500, 131.2833]
                }
            ];
        }

        function generateDefaultChart(headers) {
            setTimeout(() => {
                const generateBtn = document.getElementById('generateChart');
                if (generateBtn) generateBtn.click();
            }, 500);
        }

        // Chart Generation
        document.getElementById('generateChart').addEventListener('click', function() {
            const chartType = document.getElementById('chartType').value;
            const xAxis = document.getElementById('chartXAxis').value;
            const yAxis = document.getElementById('chartYAxis').value;
            const groupBy = document.getElementById('chartGroup').value;

            if (!xAxis || !yAxis) {
                alert('Pilih Axis A dan Axis B untuk membuat chart');
                return;
            }

            generateChart(chartType, xAxis, yAxis, groupBy);
        });

        function generateChart(type, xAxis, yAxis, groupBy) {
            const canvas = document.getElementById('csvChart');
            const ctx = canvas.getContext('2d');

            if (csvData.currentChart) csvData.currentChart.destroy();

            const processedData = processDataForChart(xAxis, yAxis, groupBy);

            const colors = [
                '#9dc948',
                '#f4d03f',
                '#5dade2',
                '#f1948a',
                '#82ca9d'
            ];

            const config = {
                type: type,
                data: {
                    labels: processedData.labels,
                    datasets: processedData.datasets.map((dataset, index) => ({
                        label: dataset.label,
                        data: dataset.data,
                        backgroundColor: type === 'pie' || type === 'doughnut' ? colors.slice(0, dataset
                            .data.length) : colors[index % colors.length],
                        borderColor: type === 'pie' || type === 'doughnut' ? colors.slice(0, dataset.data
                            .length) : colors[index % colors.length],
                        borderWidth: type === 'line' ? 3 : 1,
                        tension: type === 'line' ? 0.4 : 0,
                        fill: type === 'line' ? false : true
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: type === 'pie' || type === 'doughnut' || processedData.datasets.length > 1,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: type === 'pie' || type === 'doughnut' || type === 'radar' || type === 'polarArea' ? {} : {
                        x: {
                            title: {
                                display: true,
                                text: 'Axis A',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Axis B',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: '#e0e0e0'
                            }
                        }
                    }
                }
            };

            csvData.currentChart = new Chart(ctx, config);
        }

        function processDataForChart(xAxis, yAxis, groupBy) {
            const xIndex = csvData.headers.indexOf(xAxis);
            const yIndex = csvData.headers.indexOf(yAxis);
            const groupIndex = groupBy ? csvData.headers.indexOf(groupBy) : -1;

            if (groupIndex === -1 || !groupBy) {
                const grouped = {};
                csvData.records.forEach(record => {
                    const xValue = record[xIndex];
                    const yValue = parseFloat(record[yIndex]) || 0;
                    if (grouped[xValue]) grouped[xValue] += yValue;
                    else grouped[xValue] = yValue;
                });
                return {
                    labels: Object.keys(grouped),
                    datasets: [{
                        label: yAxis,
                        data: Object.values(grouped)
                    }]
                };
            } else {
                const grouped = {};
                const groups = new Set();
                csvData.records.forEach(record => {
                    const xValue = record[xIndex];
                    const yValue = parseFloat(record[yIndex]) || 0;
                    const groupValue = record[groupIndex];
                    groups.add(groupValue);
                    if (!grouped[xValue]) grouped[xValue] = {};
                    if (grouped[xValue][groupValue]) grouped[xValue][groupValue] += yValue;
                    else grouped[xValue][groupValue] = yValue;
                });
                const labels = Object.keys(grouped).sort();
                const groupsArray = Array.from(groups).sort();
                const datasets = groupsArray.map(group => ({
                    label: group,
                    data: labels.map(label => grouped[label][group] || 0)
                }));
                return {
                    labels,
                    datasets
                };
            }
        }

        // Dynamic Table Functions
        function updatePivotTable() {
            const rows = Array.from(document.querySelectorAll('#rowsContainer .badge')).map(badge => badge.dataset.field);
            const cols = Array.from(document.querySelectorAll('#colsContainer .badge')).map(badge => badge.dataset.field);
            const values = Array.from(document.querySelectorAll('#valuesContainer .badge')).map(badge => badge.dataset
                .field);
            const aggregation = document.getElementById('pivotAggregation').value;
            const visualization = document.getElementById('pivotVisualization').value;

            if (values.length === 0) {
                document.getElementById('pivotResult').style.display = 'none';
                return;
            }

            const pivotData = createPivotData(rows, cols, values[0], aggregation);

            if (visualization === 'heatmap') renderHeatmapTable(pivotData, rows, cols);
            else renderPivotTable(pivotData, rows, cols);

            document.getElementById('pivotResult').style.display = 'block';
        }

        function createPivotData(rows, cols, values, aggregation) {
            const rowIndices = rows.map(r => csvData.headers.indexOf(r));
            const colIndices = cols.map(c => csvData.headers.indexOf(c));
            const valueIndex = csvData.headers.indexOf(values);

            const pivot = {};
            const allColKeys = new Set();

            csvData.records.forEach(record => {
                const rowKey = rowIndices.length > 0 ? rowIndices.map(i => record[i]).join(' | ') : 'Total';
                const colKey = colIndices.length > 0 ? colIndices.map(i => record[i]).join(' | ') : 'Total';
                allColKeys.add(colKey);
                if (!pivot[rowKey]) pivot[rowKey] = {};
                if (!pivot[rowKey][colKey]) pivot[rowKey][colKey] = [];
                const value = parseFloat(record[valueIndex]) || 0;
                pivot[rowKey][colKey].push(value);
            });

            Object.keys(pivot).forEach(rowKey => {
                Object.keys(pivot[rowKey]).forEach(colKey => {
                    const values = pivot[rowKey][colKey];
                    switch (aggregation) {
                        case 'sum':
                            pivot[rowKey][colKey] = values.reduce((a, b) => a + b, 0);
                            break;
                        case 'average':
                            pivot[rowKey][colKey] = values.length > 0 ? values.reduce((a, b) => a + b, 0) /
                                values.length : 0;
                            break;
                        case 'count':
                            pivot[rowKey][colKey] = values.length;
                            break;
                        case 'min':
                            pivot[rowKey][colKey] = values.length > 0 ? Math.min(...values) : 0;
                            break;
                        case 'max':
                            pivot[rowKey][colKey] = values.length > 0 ? Math.max(...values) : 0;
                            break;
                    }
                });
            });

            return {
                pivot,
                allColKeys: Array.from(allColKeys).sort()
            };
        }

        function renderHeatmapTable(data, rows, cols) {
            const thead = document.querySelector('#pivotTable thead');
            const tbody = document.querySelector('#pivotTable tbody');
            const {
                pivot,
                allColKeys
            } = data;

            thead.innerHTML = '';
            tbody.innerHTML = '';

            let allValues = [];
            Object.values(pivot).forEach(row => {
                Object.values(row).forEach(val => {
                    if (typeof val === 'number') allValues.push(val);
                });
            });
            const minVal = Math.min(...allValues);
            const maxVal = Math.max(...allValues);

            let headerRow1 =
                '<tr><th rowspan="2" class="align-middle text-center" style="background: #f8f9fa;">satuan</th>';
            headerRow1 += '<th rowspan="2" class="align-middle text-center" style="background: #f8f9fa;">tahun</th>';
            headerRow1 += `<th colspan="${allColKeys.length}" class="text-center" style="background: #e3f2fd;">INDEKS</th>`;
            headerRow1 += '<th rowspan="2" class="align-middle text-center" style="background: #f8f9fa;">Totals</th></tr>';

            let headerRow2 = '<tr>';
            allColKeys.forEach(colKey => {
                headerRow2 +=
                    `<th class="text-center" style="background: #e3f2fd; font-size: 0.9rem;">${colKey}</th>`;
            });
            headerRow2 += '</tr>';

            thead.innerHTML = headerRow1 + headerRow2;

            const rowKeys = Object.keys(pivot).sort();
            let totalRow = 0;

            rowKeys.forEach((rowKey, index) => {
                let row = '';
                if (index === 0) {
                    row +=
                        `<td rowspan="${rowKeys.length}" class="align-middle text-center fw-bold" style="background: #f8f9fa;">${rowKey}</td>`;
                }
                row += `<td class="text-center" style="background: #f8f9fa;">${rowKey}</td>`;
                let rowTotal = 0;

                allColKeys.forEach(colKey => {
                    const value = pivot[rowKey][colKey] || 0;
                    rowTotal += value;
                    const intensity = maxVal > minVal ? (value - minVal) / (maxVal - minVal) : 0;
                    const backgroundColor = value > 0 ? `rgba(37, 99, 235, ${0.1 + intensity * 0.6})` :
                        '#ffffff';
                    row +=
                        `<td class="text-center" style="background-color: ${backgroundColor}; padding: 8px;">${value.toLocaleString()}</td>`;
                });

                row +=
                    `<td class="text-center fw-bold" style="background: #f0f0f0;">${rowTotal.toLocaleString()}</td>`;
                totalRow += rowTotal;
                tbody.innerHTML += '<tr>' + row + '</tr>';
            });

            let totalsRow =
                `<tr style="background: #e9ecef;"><td class="fw-bold text-center">Totals</td><td class="fw-bold text-center">-</td>`;
            allColKeys.forEach(colKey => {
                let colTotal = 0;
                Object.keys(pivot).forEach(rowKey => {
                    colTotal += pivot[rowKey][colKey] || 0;
                });
                totalsRow += `<td class="fw-bold text-center">${colTotal.toLocaleString()}</td>`;
            });
            totalsRow += `<td class="fw-bold text-center">${totalRow.toLocaleString()}</td></tr>`;
            tbody.innerHTML += totalsRow;

            document.getElementById('pivotRowCount').textContent = `${rowKeys.length} rows`;
            document.getElementById('pivotColCount').textContent = `${allColKeys.length} columns`;
            document.getElementById('pivotTable').className = 'table table-sm table-bordered';
            document.getElementById('pivotTable').style.fontSize = '0.85rem';
        }

        function renderPivotTable(data, rows, cols) {
            const thead = document.querySelector('#pivotTable thead');
            const tbody = document.querySelector('#pivotTable tbody');
            const {
                pivot,
                allColKeys
            } = data;

            thead.innerHTML = '';
            tbody.innerHTML = '';

            let headerRow = '<tr><th>' + (rows.length > 0 ? rows.join(' / ') : 'Row') + '</th>';
            allColKeys.forEach(colKey => {
                headerRow += `<th class="text-center">${colKey}</th>`;
            });
            headerRow += '<th class="text-center">Total</th></tr>';
            thead.innerHTML = headerRow;

            Object.keys(pivot).forEach(rowKey => {
                let row = `<tr><td class="fw-bold">${rowKey}</td>`;
                let rowTotal = 0;
                allColKeys.forEach(colKey => {
                    const value = pivot[rowKey][colKey] || 0;
                    rowTotal += value;
                    row +=
                        `<td class="text-center">${typeof value === 'number' ? value.toLocaleString() : value}</td>`;
                });
                row += `<td class="text-center fw-bold">${rowTotal.toLocaleString()}</td></tr>`;
                tbody.innerHTML += row;
            });

            document.getElementById('pivotRowCount').textContent = `${Object.keys(pivot).length} rows`;
            document.getElementById('pivotColCount').textContent = `${allColKeys.length} columns`;
            document.getElementById('pivotTable').className = 'table table-striped table-hover table-bordered';
        }

        document.getElementById('pivotVisualization').addEventListener('change', updatePivotTable);
        document.getElementById('pivotAggregation').addEventListener('change', updatePivotTable);
    </script>
@endsection
