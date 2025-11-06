@extends('layouts.app')

@section('title', 'Edit Repositori - Open Data Diskominfo Kota Bogor')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/repositori/edit.css') }}">
@endsection

@section('content')
    <div class="container-main">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="form-card">
            <h1 class="form-title">
                <i class="fa fa-folder-open text-primary me-2"></i>
                Edit Repository
            </h1>
            <p class="form-subtitle">Perbarui informasi dan file repository Anda</p>

            <form action="{{ route('update.repo', $repo->id) }}" method="POST" enctype="multipart/form-data" id="repositoryForm">
                @csrf
                <!-- Repository Info Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi Repository
                    </h3>

                    <div class="mb-4">
                        <label for="repoTitle" class="form-label">
                            <i class="fas fa-tag"></i>
                            Judul Repository *
                        </label>
                        <input type="text" class="form-control" name="judul_repo" id="repoTitle"
                            value="{{ old('judul_repo', $repo->judul_repo) }}" placeholder="contoh: my-awesome-project" required>
                        <div class="form-text">
                            <i class="fas fa-lightbulb"></i>
                            Gunakan nama yang deskriptif dan mudah diingat
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="repoDescription" class="form-label">
                            <i class="fas fa-align-left"></i>
                            Deskripsi Singkat *
                        </label>
                        <textarea class="form-control" name="deskripsi" id="repoDescription" rows="4"
                            placeholder="Jelaskan singkat tentang project ini, teknologi yang digunakan, dan tujuan utamanya..." required>{{ old('deskripsi', $repo->deskripsi) }}</textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Deskripsi yang baik membantu orang memahami project Anda
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="repoStatus" class="form-label">
                            <i class="fas fa-shield-alt"></i>
                            Status Repository *
                        </label>
                        <select class="form-select" id="repoStatus" name="status" required>
                            <option value="">Pilih status repository</option>
                            <option value="publik" {{ old('status', $repo->status) == 'publik' ? 'selected' : '' }}>Public - Semua Orang Bisa Melihat</option>
                            <option value="private" {{ old('status', $repo->status) == 'private' ? 'selected' : '' }}>Private - Hanya Pengguna Terdaftar Yang Bisa Melihat</option>
                        </select>
                        <div class="form-text">
                            <i class="fas fa-question-circle"></i>
                            Status dapat diubah nanti di pengaturan repository
                        </div>

                        <!-- Status Badge Preview -->
                        <div id="statusPreview">
                            @if($repo->status)
                                @php
                                    $badge = $repo->status === 'publik' ?
                                        '<div class="status-badge status-public"><i class="fas fa-globe me-1"></i>Public Repository</div>' :
                                        '<div class="status-badge status-private"><i class="fas fa-lock me-1"></i>Private Repository</div>';
                                    echo $badge;
                                @endphp
                            @endif
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-upload"></i>
                        Upload File Project
                    </h3>

                    <div class="file-upload-area" id="fileUploadArea">
                        <div class="upload-content">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">Drag & Drop file project di sini</div>
                            <div class="upload-subtext">atau klik untuk memilih file dari komputer Anda</div>

                            <div class="supported-formats">
                                <span class="format-badge">PDF</span>
                                <span class="format-badge">RAR</span>
                                <span class="format-badge">TAR</span>
                                <span class="format-badge">ZIP</span>
                                <span class="format-badge">WORD</span>
                                <span class="format-badge">EXCEL</span>
                                <span class="format-badge">SQL</span>
                                <span class="format-badge">POWER POINT</span>
                                <span class="format-badge">video</span>
                            </div>
                        </div>
                        <input type="file" id="projectFiles" name="file_tambahan[]" multiple
                            accept=".pdf, .zip, .doc, .docx, .xlsx, .jpg, .jpeg, .png, .mp4, .rar, .sql, .pptx,.csv,.tar"
                            style="display: none;">
                    </div>

                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Maksimal 250MB Total File. Anda Bisa Upload Multiple File Sekaligus.
                    </div>

                    <div id="fileList" class="file-list">
                        @if($repo->fileRepo->isNotEmpty())
                            @foreach($repo->fileRepo as $file)
                                <div class="file-item">
                                    <div class="file-info">
                                        <div class="file-icon">
                                            <i class="fas {{ $getFileIcon($file->ekstensi) }}"></i>
                                        </div>
                                        <div class="file-details">
                                            <div class="file-name">{{ $file->nama_file }}</div>
                                            <div class="file-size">
                                                <div class="file-size">{{ $formatFileSize($file->ukuran) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-remove btn-sm btn-delete-permanent"
                                        data-file-id="{{ $file->id }}"
                                        title="Hapus file lama">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Repository Preview -->
                <div class="repo-preview" id="repoPreview">
                    <div class="preview-title">
                        <i class="fas fa-eye"></i>
                        Preview Repository
                    </div>
                    <div class="preview-content">
                        <h5 id="previewTitle">{{ $repo->judul_repo }}</h5>
                        <p id="previewDescription">{{ $repo->deskripsi }}</p>
                        <div id="previewStatus">
                            @if($repo->status === 'publik')
                                <span class="status-badge status-public"><i class="fas fa-globe me-1"></i>Public</span>
                            @else
                                <span class="status-badge status-private"><i class="fas fa-lock me-1"></i>Private</span>
                            @endif
                        </div>
                        <div id="previewFiles">
                            @if($repo->fileRepo->isNotEmpty())
                                <strong>{{ $repo->fileRepo->count() }} file(s) uploaded:</strong> {{ $repo->fileRepo->pluck('nama_file')->join(', ') }}
                            @else
                                <em>Belum ada file yang diupload</em>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline-primary" id="previewBtn">
                        <i class="fas fa-eye me-2"></i>Preview Repository
                    </button>
                    <button type="submit" class="btn btn-primary" id="createBtn">
                        <i class="fas fa-check me-2"></i>Perbarui Repository
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast for notifications -->
    <div class="toast align-items-center text-bg-success border-0" role="alert" id="successToast" style="display: none;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

    <!-- Loading Overlay - Muncul saat submit form -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); z-index: 9999; justify-content: center; align-items: center; flex-direction: column; color: white; font-family: system-ui;">
    <div class="spinner" style="border: 4px solid #444; border-top: 4px solid var(--primary-orange); border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin-bottom: 20px;"></div>
    <h4 style="margin: 0; font-weight: 600;">Memperbarui repositori...</h4>
    <p style="margin-top: 8px; opacity: 0.8; font-size: 14px; text-align: center; max-width: 80%;">
        Mohon tunggu, jangan tutup halaman ini.<br>
        Proses ini bisa memakan waktu beberapa detik hingga menit tergantung ukuran file.
    </p>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('repositoryForm');
    if (form) {
        form.addEventListener('submit', function() {
            // Tampilkan loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Disable tombol submit
            const submitBtn = document.getElementById('createBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sedang Diproses...';
            }
        });
    }
});
</script>

    <script>
    // Minimal JavaScript - mostly for file handling and form interactions
    let uploadedFiles = [];

        document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.btn-delete-permanent');
    if (!deleteBtn) return;

    const fileId = deleteBtn.getAttribute('data-file-id');
    if (!fileId) return;

    Swal.fire({
        title: 'Hapus File?',
        text: "File yang dihapus tidak bisa dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const originalIcon = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            deleteBtn.disabled = true;

            fetch(`/filerepo/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: new FormData().append('_method', 'DELETE') // 👈 INI BENAR!
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    deleteBtn.closest('.file-item').remove();
                    showToast('File berhasil dihapus!', 'success');
                } else {
                    showToast('Gagal menghapus file: ' + data.message, 'error');
                }
            })
            .catch(() => {
                showToast('Terjadi kesalahan jaringan.', 'error');
            })
            .finally(() => {
                deleteBtn.innerHTML = originalIcon;
                deleteBtn.disabled = false;
            });
        }
    });
});

        document.addEventListener('DOMContentLoaded', function() {
            setupEventHandlers();
        });

        function setupEventHandlers() {
            // File upload handlers
            const fileUploadArea = document.getElementById('fileUploadArea');
            const fileInput = document.getElementById('projectFiles');

            fileUploadArea.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', handleFileUpload);

            // Drag and drop
            fileUploadArea.addEventListener('dragover', handleDragOver);
            fileUploadArea.addEventListener('drop', handleDrop);
            fileUploadArea.addEventListener('dragleave', handleDragLeave);

            // Status change preview
            document.getElementById('repoStatus').addEventListener('change', updateStatusPreview);

            // Form preview
            document.getElementById('previewBtn').addEventListener('click', showPreview);

            // Form submission — INI YANG DIUBAH!
            document.getElementById('repositoryForm').addEventListener('submit', handleSubmit);

            // Draft save — dihapus karena tidak relevan di edit
            // document.getElementById('draftBtn').addEventListener('click', saveDraft);
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.currentTarget.style.borderColor = 'var(--secondary-orange)';
            e.currentTarget.style.background = '#fff';
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.currentTarget.style.borderColor = 'var(--primary-orange)';
            e.currentTarget.style.background = 'var(--light-orange)';
        }

        function handleDrop(e) {
            e.preventDefault();
            handleDragLeave(e);
            const files = Array.from(e.dataTransfer.files);
            processFiles(files);
        }

        function handleFileUpload(e) {
            const files = Array.from(e.target.files);
            processFiles(files);
        }

        function processFiles(files) {
            files.forEach(file => {
                if (file.size > 151 * 1024 * 1024) {
                    showToast(`File ${file.name} terlalu besar! Maksimal 50MB.`, 'error');
                    return;
                }

                uploadedFiles.push(file);
                addFileToList(file);
            });

            if (files.length > 0) {
                showToast(`${files.length} file berhasil ditambahkan!`, 'success');
            }
        }

        function addFileToList(file) {
            const fileList = document.getElementById('fileList');
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <div class="file-info">
                    <div class="file-icon">
                        <i class="fas ${getFileIcon(file.name)}"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                </div>
                <button type="button" class="btn-remove" onclick="removeFile('${file.name}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileList.appendChild(fileItem);
        }

        function removeFile(fileName) {
            uploadedFiles = uploadedFiles.filter(file => file.name !== fileName);

            // Remove from DOM
            const fileItems = document.querySelectorAll('.file-item');
            fileItems.forEach(item => {
                if (item.querySelector('.file-name').textContent === fileName) {
                    item.remove();
                }
            });

            showToast('File berhasil dihapus!', 'success');
        }

        // 👇 Fungsi baru: hapus file lama (server-side)
        function removeExistingFile(fileId) {
            if (!confirm('Apakah Anda yakin ingin menghapus file ini secara permanen?')) return;

            fetch(`/repositori/${{{ $repo->id }}}/delete-file/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hapus elemen dari DOM
                    const items = document.querySelectorAll('.file-item');
                    items.forEach(item => {
                        const removeBtn = item.querySelector('.btn-remove');
                        if (removeBtn && removeBtn.onclick.toString().includes(`removeExistingFile('${fileId}')`)) {
                            item.remove();
                        }
                    });
                    showToast('File lama berhasil dihapus!', 'success');
                } else {
                    showToast('Gagal menghapus file.', 'error');
                }
            })
            .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'));
        }

        function getFileIcon(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const iconMap = {
                'zip': 'fa-file-archive',
                'rar': 'fa-file-archive',
                'tar': 'fa-file-archive',
                'csv': 'fa-file-archive',
                'sql': 'fa-file-code',
                'jpg': 'fa-file-image',
                'jpeg': 'fa-file-image',
                'png': 'fa-file-image',
                'mp4': 'fa-file-video',
                'json': 'fa-file-code',
                'pptx': 'fa-file-powerpoint',
                'pdf': 'fa-file-pdf',
                'xlsx': 'fa-file-excel',
                'doc' : 'fa-file-word',
                'docx' : 'fa-file-word'
            };

            return iconMap[ext] || 'fa-file';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function updateStatusPreview() {
            const status = document.getElementById('repoStatus').value;
            const preview = document.getElementById('statusPreview');

            if (status) {
                const badge = status === 'publik' ?
                    '<div class="status-badge status-public"><i class="fas fa-globe me-1"></i>Public Repository</div>' :
                    '<div class="status-badge status-private"><i class="fas fa-lock me-1"></i>Private Repository</div>';
                preview.innerHTML = badge;
            } else {
                preview.innerHTML = '';
            }
        }

        function showPreview() {
            const title = document.getElementById('repoTitle').value;
            const description = document.getElementById('repoDescription').value;
            const status = document.getElementById('repoStatus').value;

            if (!title || !description || !status) {
                showToast('Mohon lengkapi semua field terlebih dahulu!', 'warning');
                return;
            }

            const preview = document.getElementById('repoPreview');
            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewDescription').textContent = description;

            const statusBadge = status === 'publik' ?
                '<span class="status-badge status-public"><i class="fas fa-globe me-1"></i>Public</span>' :
                '<span class="status-badge status-private"><i class="fas fa-lock me-1"></i>Private</span>';
            document.getElementById('previewStatus').innerHTML = statusBadge;

            const filesText = uploadedFiles.length > 0 ?
                `<strong>${uploadedFiles.length} file(s) uploaded:</strong> ${uploadedFiles.map(f => f.name).join(', ')}` :
                '<em>Belum ada file yang diupload</em>';
            document.getElementById('previewFiles').innerHTML = filesText;

            preview.classList.add('show');
        }

        // 👇 Fungsi baru: simpan draft di edit — opsional, bisa dihapus jika tidak perlu
        // function saveDraft() { ... }

        function handleSubmit(e) {
            const title = document.getElementById('repoTitle').value;
            const description = document.getElementById('repoDescription').value;
            const status = document.getElementById('repoStatus').value;

            if (!title || !description || !status) {
                showToast('Mohon lengkapi semua field yang wajib!', 'error');
                e.preventDefault();
                return;
            }

            // ✅ INI KUNCI UTAMANYA — SALIN SEMUA FILE DARI uploadedFiles KE INPUT HTML
            const input = document.getElementById('projectFiles');
            const dataTransfer = new DataTransfer();

            uploadedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files; // ← INI KUNCI UTAMANYA!

            // ✅ TIDAK ADA APA-APA LAIN — JANGAN ADA console.log, setTimeout, fetch, dll!
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('successToast');
            const toastBody = toast.querySelector('.toast-body');

            toast.className = 'toast align-items-center border-0';

            if (type === 'error') {
                toast.classList.add('text-bg-danger');
                toastBody.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${message}`;
            } else if (type === 'warning') {
                toast.classList.add('text-bg-warning');
                toastBody.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
            } else {
                toast.classList.add('text-bg-success');
                toastBody.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
            }

            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // 👇 Helper untuk fungsi getFileIcon di Blade (PHP)
        @push('scripts')
        <script>
            function getFileIcon(filename) {
                const ext = filename.split('.').pop().toLowerCase();
                const map = {
                    'zip': 'fa-file-archive',
                    'rar': 'fa-file-archive',
                    'tar': 'fa-file-archive',
                    'csv': 'fa-file-archive',
                    'sql': 'fa-file-code',
                    'jpg': 'fa-file-image',
                    'jpeg': 'fa-file-image',
                    'png': 'fa-file-image',
                    'mp4': 'fa-file-video',
                    'pptx': 'fa-file-powerpoint',
                    'pdf': 'fa-file-pdf',
                    'xlsx': 'fa-file-excel',
                    'doc': 'fa-file-word',
                    'docx': 'fa-file-word'
                };
                return map[ext] || 'fa-file';
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        </script>
        @endpush
    </script>
@endsection
