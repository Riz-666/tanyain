<div id="komentar-{{ $komentar->id }}" class="comment-item mb-4 p-3 border rounded"
    style="{{ $loop->depth > 1 ? 'margin-left: 2rem; border-left: 3px solid #007bff;' : '' }}">
    <div class="comment-header d-flex justify-content-between align-items-start mb-2">
        <div class="comment-author d-flex align-items-center">
            <div class="author-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                style="width: 40px; height: 40px; font-weight: bold;">
                {{ strtoupper(substr($komentar->user->nama ?? '?', 0, 2)) }}
            </div>
            <div class="author-info">
                <div class="author-name fw-bold">{{ $komentar->user->nama ?? 'User tidak ditemukan' }}</div>
                <div class="comment-date text-muted small">
                    {{ $komentar->created_at ? $komentar->created_at->diffForHumans() : 'Tanggal tidak tersedia' }}
                </div>
            </div>
        </div>
        <div class="comment-actions">
            <button type="button" class="btn btn-sm btn-outline-secondary reply-trigger me-2"
                data-comment-id="{{ $komentar->id }}" onclick="toggleReplyForm({{ $komentar->id }})">
                <i class="fas fa-reply"></i> Balas
            </button>
            <form action="{{ route('admin.komentar.destroy', $komentar->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"
                    onclick="return confirmDelete(event, '{{ $komentar->id }}')">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    <div class="comment-content mb-2">
        {!! nl2br(e($komentar->isi)) !!}
    </div>
    <div class="comment-footer d-flex justify-content-between align-items-center">
        <div class="like-section">
            @auth
                <form action="{{ route('admin.komentar.vote', $komentar->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit"
                        class="btn btn-sm {{ $komentar->votes->where('user_id', auth()->id())->first() ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-thumbs-up"></i>
                        {{ $komentar->votes->count() }}
                    </button>
                </form>
            @else
                <span class="text-muted">Login untuk like</span>
            @endauth
        </div>
    </div>

    <!-- Reply Form -->
    <div class="reply-form mt-3" id="reply-form-{{ $komentar->id }}" style="display:none;">
        <form action="{{ route('admin.komentar.reply', $komentar->id) }}" method="POST" class="border-top pt-3"
            onsubmit="handleFormSubmit(this)">
            @csrf
            <div class="mb-2">
                <textarea name="isi" class="form-control" rows="2" placeholder="Tulis balasan..." required></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Balasan
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="cancelReply({{ $komentar->id }})">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <!-- Nested Replies -->
    @if ($komentar->children->count())
        <button class="btn btn-sm btn-outline-secondary show-replies-btn mt-2"
            onclick="toggleReplies({{ $komentar->id }})" id="toggle-btn-{{ $komentar->id }}">
            Lihat balasan ({{ $komentar->children->count() }})
        </button>

        <div class="reply-comments mt-3 pt-3 border-top d-none" id="reply-comments-{{ $komentar->id }}">
            @foreach ($komentar->children as $balasan)
                @include('admin.components.comment-item', ['komentar' => $balasan, 'artikel' => $artikel])
            @endforeach
        </div>
    @endif
</div>
<script>
    // Comments JavaScript Functions
    console.log('Comments script loaded');

    // Toggle reply form
    function toggleReplyForm(commentId) {
        console.log('Toggling reply form for comment:', commentId);

        // Hide all other reply forms first
        document.querySelectorAll('.reply-form').forEach(form => {
            if (form.id !== 'reply-form-' + commentId) {
                form.style.display = 'none';
            }
        });

        const replyForm = document.getElementById('reply-form-' + commentId);
        if (replyForm) {
            if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                replyForm.style.display = 'block';
                // Focus on textarea
                const textarea = replyForm.querySelector('textarea');
                if (textarea) {
                    textarea.focus();
                }
            } else {
                replyForm.style.display = 'none';
            }
        } else {
            console.error('Reply form not found for comment:', commentId);
        }
    }

    // Cancel reply
    function cancelReply(commentId) {
        console.log('Cancelling reply for comment:', commentId);

        const replyForm = document.getElementById('reply-form-' + commentId);
        if (replyForm) {
            replyForm.style.display = 'none';
            // Clear textarea
            const textarea = replyForm.querySelector('textarea');
            if (textarea) {
                textarea.value = '';
            }
        }
    }

    // Toggle nested replies
    function toggleReplies(commentId) {
        console.log('Toggling replies for comment:', commentId);

        const repliesContainer = document.getElementById('reply-comments-' + commentId);
        const toggleBtn = document.getElementById('toggle-btn-' + commentId);

        if (repliesContainer && toggleBtn) {
            repliesContainer.classList.toggle('d-none');
            const replyCount = repliesContainer.querySelectorAll('.comment-item').length;

            if (repliesContainer.classList.contains('d-none')) {
                toggleBtn.textContent = `Lihat balasan (${replyCount})`;
            } else {
                toggleBtn.textContent = 'Sembunyikan balasan';
            }
        }
    }

    // Confirm delete comment
    // Confirm delete dengan SweetAlert
    function confirmDelete(event, commentId) {
        event.preventDefault(); // Stop form submission

        Swal.fire({
            title: 'Hapus Komentar?',
            text: "Komentar ini dan semua balasannya akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form if confirmed
                event.target.closest('form').submit();
            }
        });

        return false; // Prevent default form submission
    }

    // Delete comment
    function deleteComment(commentId) {
        console.log('Deleting comment:', commentId);

        // ✅ VALIDASI LAGI
        if (!commentId || isNaN(commentId) || commentId <= 0) {
            alert('ID komentar tidak valid.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/komentar/${commentId}`;
        form.style.display = 'none';

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken.getAttribute('content')}">
                <input type="hidden" name="_method" value="DELETE">
            `;
        } else {
            console.error('CSRF token not found');
            alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        document.body.appendChild(form);
        form.submit();
    }

    // Handle form submission
    function handleFormSubmit(form) {
        console.log('Form submitted');

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

            // Re-enable button after 3 seconds as fallback
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Balasan';
            }, 3000);
        }

        return true; // Allow form submission to continue
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Comments DOM loaded');

        // Handle page refresh - close all reply forms
        document.querySelectorAll('.reply-form').forEach(form => {
            form.style.display = 'none';
        });

        // ✅ EVENT DELEGATION UNTUK TOMBOL HAPUS — AMBIL ID DARI DATA ATTRIBUTE
        document.body.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-comment-btn');
            if (deleteBtn) {
                const commentId = deleteBtn.getAttribute('data-comment-id');

                if (!commentId || isNaN(commentId) || parseInt(commentId) <= 0) {
                    console.error('Invalid comment ID:', commentId);
                    alert('ID komentar tidak valid. Silakan refresh halaman.');
                    return;
                }

                confirmDeleteComment(parseInt(commentId));
            }
        });
    });
</script>
