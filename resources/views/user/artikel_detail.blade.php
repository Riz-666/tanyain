@extends('layouts.app')

@section('title', 'Detail Artikel - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/artikel/detail.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github.min.css">
    <style>
        /* Pastikan code block tidak bisa execute script */
        .ql-syntax,
        .ql-syntax * {
            pointer-events: none !important;
        }

        .ql-syntax-wrapper .copy-btn {
            /* Re-enable untuk tombol copy */
            pointer-events: auto !important;
        }

        /* Styling yang lebih baik */
        .ql-syntax-wrapper {
            position: relative;
            margin: 1.5rem 0;
            background: #f8f9fa;
            border-radius: 8px;
            overflow-y: auto;
            /* scroll kalo melebihi max-height */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-height: 900px;
            /* batas maksimal tinggi */
        }


        .ql-syntax {
            margin: 0 !important;
            padding: 16px !important;
            background: #f5f5f5 !important;
            border: none !important;
            border-radius: 0 !important;
            overflow-x: auto;
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            white-space: pre;
            word-wrap: normal;
        }

        .ql-syntax code {
            background: transparent !important;
            padding: 0 !important;
            border: none !important;
            font-family: inherit;
            font-size: inherit;
            color: inherit;
            white-space: pre;
        }

        .copy-btn {
            position: sticky;
            top: 8px;
            right: 8px;
            background: #ff6b35;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s ease;
            pointer-events: auto !important;
        }

        .copy-btn:hover {
            background: #e55a2b;
            transform: translateY(-1px);
        }

        .copy-btn:active {
            transform: translateY(0);
        }

        /* Tambahan untuk mencegah HTML injection */
        .quill-content pre.ql-syntax::before {
            content: '';
            display: block;
            height: 0;
            width: 0;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <!-- Article Content -->
    <section class="article-detail">
        <div class="container">
            <div class="row">
                <!-- Main Article Content -->
                <div class="col-lg-8">
                    <article class="article-main">
                        <!-- Article Header -->
                        <header class="article-header">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                                    <li class="breadcrumb-item"><a href="{{ Route('article') }}">Artikel</a></li>
                                    <li class="breadcrumb-item active">{{ $artikel->slug }}</li>
                                </ol>
                            </nav>
                            <img src="{{ asset('storage/artikel/' . $artikel->id . '/cover/' . $artikel->cover) }}"
                                alt="cover.jpg" style="width: 100%; height:300px; object-fit:cover;">
                            <h1 class="article-title">
                                {{ $artikel->judul }}
                            </h1>
                            <div class="article-meta">
                                <div class="author-info">
                                    <div class="author-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="author-details">
                                        @if ($artikel->user)
                                            <a href="{{ route('profile', $artikel->user->id) }}"
                                                style="text-decoration: none;">
                                                <div class="author-name">{{ $artikel->user->nama }}</div>
                                            </a>
                                        @else
                                            <div class="author-name">Pengguna Terhapus</div>
                                        @endif

                                    </div>
                                </div>
                                <div class="article-stats">
                                    <span class="publish-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="reading-time">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($artikel->created_at)->diffForHumans() }}
                                    </span>

                                    <span class="views">
                                        <i class="fas fa-eye me-1"></i>
                                        {{ number_format($artikel->viewArtikel->count()) }} views
                                    </span>
                                </div>
                            </div>
                            <div class="article-actions">
                                <button class="action-btn share-btn" title="Share artikel">
                                    <i class="fas fa-share-alt"></i>
                                    <span>Share</span>
                                </button>
                            </div>
                        </header>
                        <div class="article-body">
                            <div class="quill-content">
                                {!! preg_replace_callback(
                                    '/<pre\s+class\s*=\s*["\']ql-syntax["\'][^>]*>(.*?)<\/pre>/s',
                                    function ($matches) {
                                        $fullMatch = $matches[0];
                                        $content = $matches[1];

                                        // Cek apakah ada data-code attribute (dari sanitasi)
                                        if (preg_match('/data-code\s*=\s*["\']([^"\']+)["\']/', $fullMatch, $dataMatch)) {
                                            $originalContent = base64_decode($dataMatch[1]);
                                            $escapedContent = htmlspecialchars($originalContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                        } else {
                                            // Fallback untuk data lama
                                            if (preg_match('/<code[^>]*>(.*?)<\/code>/s', $content, $codeMatch)) {
                                                $content = $codeMatch[1];
                                            }
                                            $escapedContent = htmlspecialchars(
                                                html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                                                ENT_QUOTES | ENT_HTML5,
                                                'UTF-8',
                                            );
                                        }

                                        // Return HTML yang aman
                                        return '<div class="ql-syntax-wrapper">
                                                                                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                                                                                        <pre class="ql-syntax"><code>' .
                                            $escapedContent .
                                            '</code></pre>
                                                                                    </div>';
                                    },
                                    $artikel->isi,
                                ) !!}
                            </div>
                            @if ($artikel->file)
                                <hr>
                                <embed
                                    src="{{ asset('storage/artikel/' . $artikel->id . '/files/' . $artikel->file) }}#toolbar=0"
                                    type="application/pdf" style="height: 1000px; width:100%">
                            @endif
                        </div>
                        <!-- Article Footer -->
                        <footer class="article-footer">
                            <div class="article-engagement">
                                <div class="engagement-stats">
                                </div>
                            </div>
                        </footer>
                    </article>
                </div>
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <aside class="article-sidebar">
                        <!-- Related Articles -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fa-regular fa-folder-open me-2"></i>Lihat File Lainnya
                            </h3>
                            <div class="related-articles">
                                @if (!$artikel->repositori)
                                    <article class="related-item">
                                        <div class="related-image">
                                            <div class="related-thumb">
                                                <i class="fa-regular fa-folder-open"></i>
                                            </div>
                                        </div>
                                        <div class="related-content">
                                            <h4 class="related-title mt-3">
                                                Tidak Ada File Lainnya
                                            </h4>
                                        </div>
                                    </article>
                                @else
                                    <a href="{{ route('repo.detail', $artikel->repositori->id) }}" class="repo-button">
                                        <article class="related-item">
                                            <div class="related-image">
                                                <div class="related-thumb">
                                                    <i class="fa-regular fa-folder-open"></i>
                                                </div>
                                            </div>
                                            <div class="related-content">
                                                <h4 class="related-title mt-3">
                                                    {{ $artikel->repositori->judul_repo }}
                                                </h4>
                                            </div>
                                        </article>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Popular Tags -->
                        <div class="sidebar-widget">
                            <h3 class="widget-title">
                                <i class="fas fa-tags me-2"></i>Tag Terkait
                            </h3>
                            <div class="popular-tags">
                                @forelse (($artikel->tag ?? collect()) as $tag)
                                    <a href="{{ route('article', ['tag' => $tag->slug]) }}"
                                        class="popular-tag {{ request('tag') == $tag->slug ? 'active' : '' }}">{{ $tag->nama_tag }}</a>
                                @empty
                                    <span class="badge bg-info text-dark">Tidak Ada Tag</span>
                                @endforelse
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <!-- Comments Section -->
    <section class="comments-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="comments-container">
                        <h3 class="comments-title">
                            <i class="fas fa-comments me-2"></i>Diskusi ({{ $artikel->komentar()->count() }})
                        </h3>

                        <!-- ERROR FLASH MESSAGE -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @auth
                            <!-- Comment Form -->
                            <div class="comment-form-container">
                                <form class="comment-form" method="POST" action="{{ route('komentar.store', $artikel->id) }}">
                                    @csrf
                                    <input type="hidden" name="parent_id" id="parent_id" value="">
                                    <div class="form-group mb-3">
                                        <label for="comment-text" class="form-label">Tulis komentar Anda</label>
                                        <textarea class="form-control" name="isi" rows="4" placeholder="Bagikan pendapat atau pertanyaan Anda..."
                                            required></textarea>
                                    </div>
                                    <div class="comment-form-actions">
                                        <button type="submit" class="btn btn-primary-custom">
                                            <i class="fas fa-paper-plane me-1"></i>Kirim Komentar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endauth


                        <!-- COMMENTS UTAMA (SEMUA, TAPI HANYA 10 YG DITAMPILKAN AWALNYA) -->
                        <div class="comments-list" id="mainCommentsList">
                            @php
                                $allKomentarUtama = $artikel
                                    ->komentar()
                                    ->whereNull('parent_id')
                                    ->with(['user', 'votes'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @foreach ($allKomentarUtama as $index => $comment)
                                @if ($index < 10)
                                    @include('partials.comment', ['comment' => $comment])
                                @endif
                            @endforeach
                        </div>

                        <!-- SEMUA KOMENTAR UTAMA LAINNYA (TERSEMBUNYI) -->
                        <div id="hiddenKomentarUtama" style="display: none;">
                            @foreach ($allKomentarUtama as $index => $comment)
                                @if ($index >= 10)
                                    @include('partials.comment', ['comment' => $comment])
                                @endif
                            @endforeach
                        </div>

                        <!-- TOMBOL LIHAT KOMENTAR LAINNYA -->
                        @if ($allKomentarUtama->count() > 10)
                            <div class="load-more-comments text-center mt-3">
                                <button class="btn btn-outline-primary load-more-comments-btn">
                                    <i class="fas fa-chevron-down me-1"></i>
                                    Lihat Komentar Lainnya
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer placeholder -->
    <!-- Include your footer here -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>
    <script>
        document.querySelectorAll('.ql-syntax').forEach(block => hljs.highlightElement(block));
    </script>
    <script>
        window.CURRENT_USER_ID = @json(Auth::check() ? Auth::id() : null);
    </script>

    <script>
        // Table of Contents scroll spy
        document.addEventListener('DOMContentLoaded', function() {
            const tocLinks = document.querySelectorAll('.toc-link');
            const sections = document.querySelectorAll('section[id]');

            function updateTOC() {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    if (window.scrollY >= sectionTop - 100) {
                        current = section.getAttribute('id');
                    }
                });

                tocLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');
                    }
                });
            }

            window.addEventListener('scroll', updateTOC);
            updateTOC();
        });

        // Copy code functionality
        document.querySelectorAll('.copy-code-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const codeBlock = this.closest('.code-block').querySelector('code');
                const text = codeBlock.textContent;

                navigator.clipboard.writeText(text).then(() => {
                    const originalIcon = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.classList.add('copied');

                    setTimeout(() => {
                        this.innerHTML = originalIcon;
                        this.classList.remove('copied');
                    }, 2000);
                });
            });
        });

        // Smooth scrolling for TOC links
        document.querySelectorAll('.toc-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);

                if (targetSection) {
                    const offsetTop = targetSection.offsetTop - 100;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Bookmark functionality
        document.querySelectorAll('.bookmark-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const text = this.querySelector('span');

                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.classList.add('bookmarked');
                    if (text) text.textContent = 'Bookmarked';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.classList.remove('bookmarked');
                    if (text) text.textContent = 'Bookmark';
                }
            });
        });

        // Share functionality
        document.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const articleTitle = document.querySelector('.article-title').textContent;
                const articleUrl = window.location.href;

                if (navigator.share) {
                    navigator.share({
                        title: articleTitle,
                        url: articleUrl
                    });
                } else {
                    navigator.clipboard.writeText(articleUrl).then(() => {
                        showToast('Link artikel berhasil disalin ke clipboard!');
                    });
                }
            });
        });

        // Helper escape HTML
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '<',
                '>': '>',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // Fungsi untuk menampilkan lebih banyak komentar utama (TANPA AJAX)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('load-more-comments-btn')) {
                e.preventDefault();

                const btn = e.target;
                const hiddenContainer = document.getElementById('hiddenKomentarUtama');
                const visibleContainer = document.getElementById('mainCommentsList');

                // Ambil maksimal 10 komentar tersembunyi
                const hiddenItems = Array.from(hiddenContainer.querySelectorAll('.comment-item')).slice(0, 10);

                if (hiddenItems.length === 0) {
                    btn.style.display = 'none';
                    return;
                }

                // Pindahkan ke container utama
                hiddenItems.forEach(item => {
                    hiddenContainer.removeChild(item);
                    visibleContainer.appendChild(item);
                });

                // Jika tidak ada lagi, sembunyikan tombol
                if (hiddenContainer.querySelectorAll('.comment-item').length === 0) {
                    btn.style.display = 'none';
                }
            }
        });

        // Fungsi toggle balasan: buka/tutup + muat 10 lagi
        document.addEventListener('click', function(e) {
            // Handle "Lihat Balasan Lainnya"
            if (e.target.classList.contains('show-replies-btn')) {
                e.preventDefault();
                const commentId = e.target.dataset.commentId;
                const repliesList = document.querySelector(`.replies-list[data-comment-id="${commentId}"]`);
                const hiddenReplies = document.querySelector(`.hidden-replies[data-comment-id="${commentId}"]`);
                const loadMoreBtn = document.querySelector(`.load-more-replies[data-comment-id="${commentId}"]`);
                const showBtn = e.target;
                const hideBtn = document.querySelector(`.hide-replies-btn[data-comment-id="${commentId}"]`);

                repliesList.style.display = 'block';
                showBtn.classList.add('d-none');
                hideBtn.classList.remove('d-none');

                if (hiddenReplies.children.length > 0) {
                    loadMoreBtn.classList.remove('d-none');
                }
            }

            // Handle "Tutup Balasan"
            if (e.target.classList.contains('hide-replies-btn')) {
                e.preventDefault();
                const commentId = e.target.dataset.commentId;
                const repliesList = document.querySelector(`.replies-list[data-comment-id="${commentId}"]`);
                const loadMoreBtn = document.querySelector(`.load-more-replies[data-comment-id="${commentId}"]`);
                const showBtn = document.querySelector(`.show-replies-btn[data-comment-id="${commentId}"]`);
                const hideBtn = e.target;

                repliesList.style.display = 'none';
                loadMoreBtn.classList.add('d-none');
                showBtn.classList.remove('d-none');
                hideBtn.classList.add('d-none');
            }

            // Handle "Lihat Balasan Lainnya" (load 10 lagi)
            if (e.target.classList.contains('load-more-replies-btn')) {
                e.preventDefault();
                const commentId = e.target.dataset.commentId;
                const repliesList = document.querySelector(`.replies-list[data-comment-id="${commentId}"]`);
                const hiddenReplies = document.querySelector(`.hidden-replies[data-comment-id="${commentId}"]`);
                const loadMoreBtn = document.querySelector(`.load-more-replies[data-comment-id="${commentId}"]`);

                const itemsToShow = Array.from(hiddenReplies.querySelectorAll('.comment-item')).slice(0, 10);

                if (itemsToShow.length === 0) {
                    loadMoreBtn.style.display = 'none';
                    return;
                }

                itemsToShow.forEach(item => {
                    hiddenReplies.removeChild(item);
                    repliesList.appendChild(item);
                });

                if (hiddenReplies.children.length === 0) {
                    loadMoreBtn.style.display = 'none';
                }
            }
        });

        // Fungsi untuk membuat form balasan (di bawah komentar utama)
        function createReplyForm(commentId, parentUserId, parentUserName, rootCommentId) {
            const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const form = document.createElement('form');
            form.className = 'reply-form mt-2';
            form.method = 'POST';
            form.action = `/komentar/${rootCommentId}/reply`; // gunakan rootCommentId juga untuk action

            let mention = '';
            let hiddenTagInput = '';

            if (parentUserId && parentUserId !== window.CURRENT_USER_ID) {
                mention = `@${parentUserName} `;
                hiddenTagInput = `<input type="hidden" name="tagged_user_id" value="${parentUserId}">`;
            }

            form.innerHTML = `
        <input type="hidden" name="_token" value="${CSRF}">
        <input type="hidden" name="parent_id" value="${rootCommentId}">
        ${hiddenTagInput}
        <div class="mb-2">
            <textarea name="isi" class="form-control" rows="2" placeholder="Tulis balasan...">${mention}</textarea>
        </div>
        <div>
            <button type="submit" class="btn btn-sm btn-primary-custom">
                <i class="fas fa-paper-plane me-1"></i>Kirim
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary cancel-reply">Batal</button>
        </div>
    `;

            form.querySelector('.cancel-reply').addEventListener('click', function(e) {
                e.preventDefault();
                form.remove();
            });

            const targetCard = document.querySelector(`.comment-item[data-comment-id="${commentId}"]`);
            if (targetCard) {
                const container = targetCard.querySelector('.comment-content');
                container.appendChild(form);
            } else {
                document.querySelector('.comments-list').appendChild(form);
            }

            setTimeout(() => {
                const ta = form.querySelector('textarea');
                ta.focus();
                ta.setSelectionRange(ta.value.length, ta.value.length);
            }, 0);

            return form;
        }


        // Event listener untuk tombol "Balas"
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('reply-btn')) {
                e.preventDefault();

                // Tutup semua form reply lain
                document.querySelectorAll('.reply-form').forEach(f => f.remove());

                const btn = e.target;
                const commentId = btn.dataset.id; // id komentar yang diklik (bisa balasan)
                const parentUserId = parseInt(btn.dataset.userid || '0', 10);
                const parentUserName = btn.dataset.username || '';
                const rootCommentId = parseInt(btn.dataset.rootId, 10); // ini id komentar utama

                // rootCommentId dijadikan parent_id di form
                createReplyForm(commentId, parentUserId, parentUserName, rootCommentId);
            }
        });

        // Newsletter form
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            showToast('Berhasil subscribe newsletter! Terima kasih.');
            this.reset();
        });

        // Comment form
        document.querySelector('.comment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const commentText = this.querySelector('textarea').value.trim();
            if (commentText) {
                showToast('Komentar berhasil dikirim! Terima kasih atas partisipasi Anda.');
                this.reset();
            }
        });

        // Toast notification helper
        function showToast(message) {
            let toast = document.querySelector('.toast-notification');
            if (!toast) {
                toast = document.createElement('div');
                toast.className = 'toast-notification';
                document.body.appendChild(toast);
            }
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Progress bar for reading
        function updateReadingProgress() {
            const article = document.querySelector('.article-body');
            const articleTop = article.offsetTop;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrolled = window.scrollY - articleTop + windowHeight / 2;
            const progress = Math.min(100, Math.max(0, (scrolled / articleHeight) * 100));
            const progressBar = document.querySelector('.reading-progress-bar');
            if (progressBar) progressBar.style.width = progress + '%';
        }

        function createReadingProgressBar() {
            const progressContainer = document.createElement('div');
            progressContainer.className = 'reading-progress-container';
            progressContainer.innerHTML = '<div class="reading-progress-bar"></div>';
            document.body.appendChild(progressContainer);
        }

        createReadingProgressBar();
        window.addEventListener('scroll', updateReadingProgress);
        updateReadingProgress();
    </script>

    <script>
        document.querySelectorAll('.ql-syntax').forEach((block) => {
            hljs.highlightElement(block);
        });
    </script>

    <!-- NOTIFIKASI -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (!notificationDropdown) return;

            function updateNotificationBadge() {
                fetch('{{ route('notifikasi.jumlah') }}')
                    .then(res => res.json())
                    .then(data => {
                        const badge = notificationDropdown.querySelector('.badge');
                        if (data.count > 0) {
                            if (!badge) {
                                const newBadge = document.createElement('span');
                                newBadge.className =
                                    'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                                newBadge.style.fontSize = '0.7rem';
                                newBadge.style.padding = '0.2rem 0.4rem';
                                newBadge.textContent = data.count;
                                notificationDropdown.appendChild(newBadge);
                            } else {
                                badge.textContent = data.count;
                            }
                        } else {
                            const badge = notificationDropdown.querySelector('.badge');
                            if (badge) badge.remove();
                        }
                    })
                    .catch(err => console.error('Gagal ambil jumlah notifikasi:', err));
            }

            notificationDropdown.addEventListener('show.bs.dropdown', function() {
                document.querySelectorAll('.dropdown-item[data-notif-id]').forEach(item => {
                    const notifId = item.dataset.notifId;
                    if (item.classList.contains('bg-light')) {
                        fetch(`/notifikasi/${notifId}/baca`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(() => {
                            item.classList.remove('bg-light');
                            item.querySelector('.badge').remove();
                        });
                    }
                });
                updateNotificationBadge();
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.mark-all-read')) {
                    e.preventDefault();
                    fetch('{{ route('notifikasi.baca-semua') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        document.querySelectorAll('.dropdown-item').forEach(item => {
                            item.classList.remove('bg-light');
                            const badge = item.querySelector('.badge');
                            if (badge) badge.remove();
                        });
                        updateNotificationBadge();
                    });
                }
            });

            setInterval(updateNotificationBadge, 30000);
            updateNotificationBadge();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Definisi global copyCode function
            window.copyCode = function(button) {
                try {
                    // Ambil wrapper dan pre element
                    const wrapper = button.closest('.ql-syntax-wrapper');
                    if (!wrapper) return;

                    const codeElement = wrapper.querySelector('.ql-syntax code');
                    const preElement = wrapper.querySelector('.ql-syntax');

                    // Ambil text content (yang sudah di-escape akan otomatis di-decode saat di-copy)
                    const textToCopy = codeElement ? codeElement.textContent : preElement.textContent;

                    if (!textToCopy) return;

                    // Copy ke clipboard
                    navigator.clipboard.writeText(textToCopy)
                        .then(() => {
                            const originalText = button.textContent;
                            button.textContent = 'Copied!';
                            button.style.backgroundColor = '#28a745';

                            setTimeout(() => {
                                button.textContent = originalText;
                                button.style.backgroundColor = '#ff6b35';
                            }, 1500);
                        })
                        .catch(err => {
                            console.error('Gagal menyalin: ', err);

                            // Fallback untuk browser yang tidak support clipboard API
                            try {
                                const textarea = document.createElement('textarea');
                                textarea.value = textToCopy;
                                textarea.style.position = 'fixed';
                                textarea.style.left = '-9999px';
                                document.body.appendChild(textarea);
                                textarea.select();
                                document.execCommand('copy');
                                document.body.removeChild(textarea);

                                button.textContent = 'Copied!';
                                setTimeout(() => button.textContent = 'Copy', 1500);
                            } catch (fallbackErr) {
                                alert('Gagal menyalin kode. Coba gunakan Ctrl+C manual.');
                            }
                        });
                } catch (error) {
                    console.error('Error in copyCode:', error);
                }
            };

            // Highlight syntax jika menggunakan highlight.js
            if (typeof hljs !== 'undefined') {
                document.querySelectorAll('.ql-syntax code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            }
        });
    </script>
@endsection
