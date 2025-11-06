@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/repositori/detail.css') }}">
    @endpush
    <div class="container" :class="darkMode ? 'dark' : 'light'">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <!-- Repository Header -->
        <div class="repo-header">
            <div class="breadcrumb">
                <a href="#">Dashboard</a>
                <span>›</span>
                <a href="#">Repositori</a>
                <span>›</span>
                <span>Detail</span>
            </div>
            <div class="action-buttons">
                <div class="dropdown">
                    <button class="btn">
                        <svg class="icon" viewBox="0 0 16 16" width="14" height="14">
                            <path
                                d="M8 9a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM8 0a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm0 13a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                        </svg>
                        Opsi
                    </button>
                    <form action="{{ Route('admin.repo.destroy', $repo->id) }}" method="POST"
                        id="form-delete-{{ $repo->id }}">
                        @csrf
                        <div class="dropdown-content">
                            <button class="btn btn-delete danger" data-id="{{ $repo->id }}"
                                style="background: transparent; border:none">
                                <svg class="icon" viewBox="0 0 16 16" width="14" height="14">
                                    <path
                                        d="M6.5 1.75a.25.25 0 01.25-.25h2.5a.25.25 0 01.25.25V3h-3V1.75zm4.5 0V3h2.25a.75.75 0 010 1.5H2.75a.75.75 0 010-1.5H5V1.75C5 .784 5.784 0 6.75 0h2.5C10.216 0 11 .784 11 1.75zM4.496 6.675a.75.75 0 10-1.492.15l.66 6.6A1.75 1.75 0 005.405 15h5.19c.9 0 1.65-.681 1.741-1.576l.66-6.6a.75.75 0 00-1.492-.149l-.66 6.6a.25.25 0 01-.249.225h-5.19a.25.25 0 01-.249-.225l-.66-6.6z" />
                                </svg>
                                Hapus Repositori
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <h1 class="repo-title">
                <div class="repo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                    </svg>

                </div>
                {{ $repo->judul_repo }}
            </h1>

            <div class="repo-meta">
                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 110 16A8 8 0 018 0zM1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0z" />
                    </svg>
                    Dibuat {{ $repo->created_at->translatedFormat('d F Y') }}
                </div>

                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 110 16A8 8 0 018 0zM1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0z" />
                    </svg>
                    By {{ $repo->user->nama ?? 'Pengguna Di Non-Aktifkan' }}
                </div>
                @if ($repo->status == 'publik')
                    <span class="status-badge status-public">Public</span>
                @else
                    <span class="status-badge status-public">Private</span>
                @endif
            </div>

            <p class="repo-description">
                {!! $repo->deskripsi !!}
            </p>
        </div>

        <!-- Files Section -->
        <div class="files-section">
            <h2 class="section-title">
                <svg class="icon" viewBox="0 0 16 16">
                    <path
                        d="M1.75 1A1.75 1.75 0 000 2.75v10.5C0 14.216.784 15 1.75 15h12.5A1.75 1.75 0 0016 13.25V4.664a1.75 1.75 0 00-.45-1.172L13.172.878A1.75 1.75 0 0011.828 0H1.75zM1.5 2.75a.25.25 0 01.25-.25h10.078a.25.25 0 01.172.069l2.378 2.014a.25.25 0 01.122.207v8.185a.25.25 0 01-.25.25H1.75a.25.25 0 01-.25-.25V2.75z" />
                </svg>
                File Repositori
            </h2>

            <!-- Desktop Table View -->
            <div class="table-container" style="border: 0px">
                <table id="repo-files" class="data-table">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Tipe</th>
                            <th>Ukuran</th>
                            <th>Di Upload</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($repo->fileRepo as $fr)
                            <tr>
                                <td>
                                    <div class="file-info">
                                        <div class="file-icon">
                                            <svg viewBox="0 0 16 16">
                                                <path
                                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zm4 18H6V4h7v5h5v11z" />
                                            </svg>
                                        </div>
                                        <div class="file-details">
                                            <div class="file-name">{{ $fr->nama_file }}</div>
                                            <div class="file-path">{{ $fr->path }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="file-type-badge type-doc">{{ $fr->ekstensi ?? '-' }}</span></td>
                                <td class="file-size">{{ $fr->ukuran ?? '-' }}</td>
                                <td class="file-date">{{ $fr->created_at->translatedFormat('d F Y') }}</td>
                                <td>
                                    <div class="table-actions">
                                        @php
                                            $allowedImageExt = ['png', 'jpg', 'jpeg'];
                                            $allowedVideoExt = ['mp4', 'webm', 'ogg'];
                                            $isPdf = strtolower($fr->ekstensi) === 'pdf';
                                            $isImage = in_array(strtolower($fr->ekstensi), $allowedImageExt);
                                            $isVideo = in_array(strtolower($fr->ekstensi), $allowedVideoExt);
                                        @endphp

                                        @if ($isPdf || $isImage || $isVideo)
                                            <a href="#" class="action-btn view-file"
                                                data-file-id="{{ $fr->id }}"
                                                data-file-ext="{{ strtolower($fr->ekstensi) }}"
                                                data-file-url="{{ route('admin.file.showPdf', $fr->id) }}">
                                                View
                                            </a>
                                        @else
                                            <button class="action-btn" disabled>View</button>
                                        @endif

                                        <a href="{{ route('admin.file.download', $fr->id) }}"
                                            class="action-btn">Download</a>

                                        <form action="{{ route('admin.file.destroy', $fr->id) }}" method="POST"
                                            id="form-delete-file-alert-{{ $fr->id }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn btn-delete-file-permanent"
                                                data-id-alert="{{ $fr->id }}">
                                                Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards">
                @foreach ($repo->fileRepo as $fr)
                    <div class="file-card">
                        <div class="file-card-header">
                            <div class="file-icon">
                                <svg viewBox="0 0 16 16">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zm4 18H6V4h7v5h5v11z" />
                                </svg>
                            </div>
                            <div class="file-main-info">
                                <div class="file-name">{{ $fr->nama_file }}</div>
                                <div class="file-path">{{ $fr->path }}</div>
                            </div>
                        </div>

                        <div class="file-card-meta">
                            <div class="meta-item">
                                <span class="meta-label">Tipe</span>
                                <span class="meta-value">
                                    <span class="file-type-badge type-doc">{{ $fr->ekstensi ?? '-' }}</span>
                                </span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Ukuran</span>
                                <span class="meta-value">{{ $fr->ukuran ?? '-' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Di Upload</span>
                                <span class="meta-value">{{ $fr->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>

                        <div class="file-card-actions">
                            @php
                                $allowedImageExt = ['png', 'jpg', 'jpeg'];
                                $allowedVideoExt = ['mp4', 'webm', 'ogg'];
                                $isPdf = strtolower($fr->ekstensi) === 'pdf';
                                $isImage = in_array(strtolower($fr->ekstensi), $allowedImageExt);
                                $isVideo = in_array(strtolower($fr->ekstensi), $allowedVideoExt);
                            @endphp

                            @if ($isPdf || $isImage || $isVideo)
                                <a href="#" class="action-btn view-file" data-file-id="{{ $fr->id }}"
                                    data-file-ext="{{ strtolower($fr->ekstensi) }}"
                                    data-file-url="{{ route('admin.file.showPdf', $fr->id) }}">
                                    View
                                </a>
                            @else
                                <button class="action-btn" disabled>View</button>
                            @endif

                            <a href="{{ route('admin.file.download', $fr->id) }}" class="action-btn">Download</a>

                            <form action="{{ route('admin.file.destroy', $fr->id) }}" method="POST"
                                id="form-delete-file-alert-mobile-{{ $fr->id }}" style="width: 100%;">
                                @csrf
                                <button type="submit" class="action-btn btn-delete-file-permanent"
                                    data-id-alert="{{ $fr->id }}">
                                    Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- Modal for Media Preview -->
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
    @push('script')
        <script src="{{ asset('admin/js/repositori/detail-repo.js') }}"></script>
    @endpush
@endsection
