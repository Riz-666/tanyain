@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/artikel/artikel.css') }}">

    @endpush

    <div class="container" :class="darkMode ? 'dark' : 'light'">
        <!-- Article Header -->
        <div class="article-header">
            <div class="breadcrumb">
                <a href="#">Dashboard</a>
                <span>›</span>
                <a href="#">Artikel</a>
                <span>›</span>
                <span>Detail</span>
            </div>

            <div class="action-buttons">
                <div class="dropdown">
                    <button class="btn delete-btn">
                        <svg class="icon" viewBox="0 0 16 16" width="14" height="14">
                            <path
                                d="M8 9a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM8 0a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm0 13a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                        </svg>
                        Opsi
                    </button>
                    <form action="{{ Route('admin.artikel.destroy', $artikel->id) }}" method="POST"
                        id="form-delete-{{ $artikel->id }}">
                        @csrf
                        <div class="dropdown-content">
                            <button class="btn btn-delete danger" data-id="{{ $artikel->id }}"
                                style="background: transparent; border:none">
                                <svg class="icon" viewBox="0 0 16 16" width="14" height="14">
                                    <path
                                        d="M6.5 1.75a.25.25 0 01.25-.25h2.5a.25.25 0 01.25.25V3h-3V1.75zm4.5 0V3h2.25a.75.75 0 010 1.5H2.75a.75.75 0 010-1.5H5V1.75C5 .784 5.784 0 6.75 0h2.5C10.216 0 11 .784 11 1.75zM4.496 6.675a.75.75 0 10-1.492.15l.66 6.6A1.75 1.75 0 005.405 15h5.19c.9 0 1.65-.681 1.741-1.576l.66-6.6a.75.75 0 00-1.492-.149l-.66 6.6a.25.25 0 01-.249.225h-5.19a.25.25 0 01-.249-.225l-.66-6.6z" />
                                </svg>
                                Hapus Artikel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <h1 class="article-title">{{ $artikel->judul }}</h1>

            <div class="article-meta">
                <div class="meta-item">
                    <i class="fa fa-globe"></i>
                    Dipublikasikan {{ $artikel->created_at->translatedFormat('d F Y') }}
                </div>

                <div class="meta-item">
                    <i class="fa fa-eye"></i>
                    {{ $artikel->views }}
                </div>

                <div class="meta-item">
                    <i class="fa fa-user"></i>
                    By {{ $artikel->user->nama ?? 'Pengguna Di Non-Aktifkan' }}
                </div>

                @if ($artikel->status == 'publik')
                    <span class="visibility-badge visibility-public">Public</span>
                @else
                    <span class="visibility-badge visibility-public">Private</span>
                @endif
            </div>

            <div class="tags-section">
                <div class="tags-label">Tags:</div>
                <div class="tags-container">
                    @foreach ($artikel->tag as $tag)
                        <span class="tag">{{ $tag->nama_tag }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Main Content -->
            <div class="article-content">
                <div class="article-text">
                    {!! $artikel->isi !!}

                    @if ($artikel->file)
                        <hr>
                        <embed src="{{ asset('storage/artikel-file/' . $artikel->file) }}" type="application/pdf"
                            style="height: 1000px; width:100%">
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar-artikel">
                <!-- Repository Link -->
                <div class="sidebar-card">
                    <h3 class="sidebar-title">
                        <i class="icon fa fa-folder-open" style="color: #f48223"></i>
                        Repositori Terkait
                    </h3>
                    @if (!$artikel->repositori)
                        <a href="#" class="repo-link">
                            <div class="repo-icon">
                                <i class="icon fa fa-folder-open" style="color: #ffffff"></i>
                            </div>
                            <div class="repo-info">
                                Tidak Ada Repositori Terkait
                            </div>
                        </a>
                    @else
                        <a href="{{ Route('admin.repo.detail', $artikel->repositori->id) }}" class="repo-link">
                            <div class="repo-icon">
                                <i class="icon fa fa-folder-open" style="color: #ffffff"></i>
                            </div>
                            <div class="repo-info">
                                <div class="repo-name">{{ $artikel->repositori->judul_repo }}</div>
                            </div>
                        </a>
                    @endif

                </div>


                <!-- Article Info -->
                <div class="sidebar-card">
                    <h3 class="sidebar-title">
                        <svg class="icon" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 118 1a7 7 0 010 14zm0 1A8 8 0 108 0a8 8 0 000 16z" />
                            <path
                                d="M5.255 5.786a.237.237 0 00.241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 00.25.246h.811a.25.25 0 00.25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                        </svg>
                        Informasi Artikel
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #7f8c8d;">Dibuat:</span>
                            <span>{{ $artikel->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #7f8c8d;">Diperbarui:</span>
                            <span>{{ $artikel->updated_at?->translatedFormat('d F Y') ?? '-' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #7f8c8d;">Jumlah Kata:</span>
                            <span>{{ str_word_count(strip_tags($artikel->isi)) }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

            <!-- Comments Section -->
        <div class="comments-section mt-5">
            <!-- Header -->
            <div class="comments-header d-flex justify-content-between align-items-center mb-4">
                <h3 class="comments-title m-0">
                    <i class="fas fa-comments icon"></i>
                    Komentar
                </h3>
                <span class="comments-count">{{ $artikel->komentar->count() }} Komentar</span>
            </div>

            <!-- Comment Form -->
            @auth
                <form class="comment-form mb-4 p-3 border rounded" action="{{ route('admin.komentar.store', $artikel->id) }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label fw-bold">Tulis Komentar</label>
                        <textarea name="isi" class="form-control" rows="3" placeholder="Tulis komentar Anda di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fas fa-paper-plane"></i> Kirim Komentar
                    </button>
                </form>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Silakan <a href="{{ route('login') }}">login</a> untuk berkomentar.
                </div>
            @endauth

            <div class="comments-list">
                @php
                    $komentarUtama = $artikel->komentar->whereNull('parent_id');
                    $showLimit = 10; // jumlah komentar utama awal yang muncul
                @endphp

                @forelse ($komentarUtama as $index => $komentar)
                    <div class="{{ $index >= $showLimit ? 'd-none more-comments' : '' }}">
                        @include('admin.components.comment-item', [
                            'komentar' => $komentar,
                            'artikel' => $artikel,
                        ])
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-comment-slash fa-2x d-block mb-2"></i>
                        Belum ada komentar. Jadilah yang pertama!
                    </div>
                @endforelse

                @if ($komentarUtama->count() > $showLimit)
                    <div class="text-center mt-3">
                        <button id="show-more-comments" class="btn btn-sm btn-outline-primary">
                            Lihat komentar lainnya
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('show-more-comments');
    if (btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.more-comments').forEach(el => el.classList.remove('d-none'));
            btn.style.display = 'none';
        });
    }
});

        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Event Delegation: handle klik pada .reply-trigger, meskipun ditambahkan dinamis
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('reply-trigger') || e.target.closest('.reply-trigger')) {
                    e.preventDefault();

                    // Cari tombol/anchor yang diklik
                    const trigger = e.target.closest('.reply-trigger');
                    const id = trigger.dataset.id;
                    const form = document.getElementById('reply-form-' + id);

                    if (form) {
                        form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    }
                }
            });

            // Handle SweetAlert delete (tetap pakai querySelectorAll karena tombol hapus tidak dinamis setelah load)
            document.querySelectorAll('.delete-comment-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const komentarId = this.dataset.id;

                    Swal.fire({
                        title: 'Hapus Komentar?',
                        text: "Komentar ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/komentar/${komentarId}`;

                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';
                            form.appendChild(csrf);

                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';
                            form.appendChild(method);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
