    <div class="comment-item {{ $comment->parent_id ? 'reply' : '' }}" data-comment-id="{{ $comment->id }}">
        <div class="comment-avatar">
            @if ($comment->user->foto)
                <img src="{{ asset('storage/user-img/' . $comment->user->foto) }}" alt="{{ $comment->user->nama }}"
                    class="rounded-circle" width="36" height="36" style="object-fit: cover;">
            @else
                <i class="fas fa-user"></i>
            @endif
        </div>
        <div class="comment-content">
            <div class="comment-header">
                <h5 class="comment-author">{{ $comment->user->nama }}</h5>
                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
            </div>

            <div class="comment-text">
                <p>{!! nl2br(e($comment->isi)) !!}</p>
            </div>
            @auth
                <div class="comment-actions d-flex gap-2 mt-2">
                <form method="POST" action="{{ route('komentar.vote', $comment->id) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="comment-action-btn like-btn {{ $comment->userVote ? 'liked' : '' }}">
                        <i class="fas fa-heart {{ $comment->userVote ? 'text-danger' : 'text-muted' }}"></i>
                        <span>{{ $comment->votes->where('vote_type', 'like')->count() }}</span>
                    </button>
                </form>

                <button class="comment-action-btn reply-btn" data-id="{{ $comment->id }}"
                    data-userid="{{ $comment->user->id }}"
                    data-username="{{ htmlspecialchars($comment->user->nama, ENT_QUOTES) }}"
                    data-root-id="{{ $comment->parent_id ?? $comment->id }}">
                    <i class="fas fa-reply me-1"></i> Balas
                </button>
            </div>

            @endauth

            <!-- FORM BALASAN -->
            <div class="reply-form-container mt-2 d-none" data-parent-id="{{ $comment->id }}">
                <form action="/komentar/{{ $comment->id }}/reply" method="POST" class="p-2 bg-light rounded">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="mb-2">
                        <textarea name="isi" class="form-control" rows="2" placeholder="Tulis balasan Anda..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary-custom">Kirim Balasan</button>
                    </div>
                </form>
            </div>

            <!-- TANDAI LEVEL NESTED DENGAN MARGIN -->
            @if ($comment->parent_id)
                <style>
                    .comment-item[data-comment-id="{{ $comment->id }}"] {
                        margin-left: 40px;
                        border-left: 2px solid #e9ecef;
                        padding-left: 15px;
                    }
                </style>
            @endif

            @if (!$comment->parent_id)
                <!-- Tombol untuk menampilkan balasan -->
                <div class="replies-toggle mt-2">
                    <small class="text-muted">
                        <span class="replies-count">{{ $comment->children->count() }} balasan</span>
                        <button class="btn btn-link p-0 text-decoration-none show-replies-btn"
                            data-comment-id="{{ $comment->id }}">
                            Lihat Balasan Lainnya
                        </button>
                        <button class="btn btn-link p-0 text-decoration-none hide-replies-btn d-none"
                            data-comment-id="{{ $comment->id }}">
                            Tutup Balasan
                        </button>
                    </small>
                </div>

                <!-- Container untuk 10 balasan pertama -->
                <div class="replies-list mt-1" data-comment-id="{{ $comment->id }}" style="display: none;">
                    @php
                        $allReplies = $comment->children()->orderBy('created_at', 'asc')->get();
                    @endphp
                    @foreach ($allReplies as $index => $reply)
                        @if ($index < 10)
                            @include('partials.comment', ['comment' => $reply])
                        @endif
                    @endforeach
                </div>

                <!-- Container untuk balasan selanjutnya (tersembunyi) -->
                <div class="hidden-replies mt-1" data-comment-id="{{ $comment->id }}" style="display: none;">
                    @foreach ($allReplies as $index => $reply)
                        @if ($index >= 10)
                            @include('partials.comment', ['comment' => $reply])
                        @endif
                    @endforeach
                </div>

                <!-- Tombol "Lihat Balasan Lainnya" untuk load lebih banyak -->
                <div class="load-more-replies text-center mt-1 d-none" data-comment-id="{{ $comment->id }}">
                    <button class="btn btn-outline-primary btn-sm load-more-replies-btn"
                        data-comment-id="{{ $comment->id }}">
                        <i class="fas fa-chevron-down me-1"></i> Lihat Balasan Lainnya
                    </button>
                </div>
            @endif
        </div>
    </div>
