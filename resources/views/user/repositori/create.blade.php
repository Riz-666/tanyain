@extends('layouts.app')

@section('title', 'Buat Repositori - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/repositori/create.css') }}">
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
                Tambah Repository Baru
            </h1>
            <p class="form-subtitle">Buat repository baru untuk project Anda</p>

            <form action="{{ route('add.repo') }}" method="POST" enctype="multipart/form-data" id="repositoryForm">
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
                            placeholder="contoh: my-awesome-project" value="{{ old('judul_repo') }}" required>
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
                            placeholder="Jelaskan singkat tentang project ini, teknologi yang digunakan, dan tujuan utamanya..." value=""
                            required>{{ old('deskripsi') }}</textarea>
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
                            <option value="publik">Public - Semua Orang Bisa Melihat</option>
                            <option value="private">Private - Hanya Pengguna Terdaftat Yang Bisa Melihat</option>
                        </select>
                        <div class="form-text">
                            <i class="fas fa-question-circle"></i>
                            Status dapat diubah nanti di pengaturan repository
                        </div>

                        <!-- Status Badge Preview -->
                        <div id="statusPreview"></div>
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
                                <span class="format-badge">PDF (20MB)</span>
                                <span class="format-badge">POWER POINT (20MB)</span>
                                <span class="format-badge">video (50MB)</span>
                                <span class="format-badge">Foto (5MB)</span>
                                <span class="format-badge">ZIP (150MB)</span>
                                <span class="format-badge">RAR (150MB)</span>
                            </div>
                        </div>
                        <input type="file" id="projectFiles" name="file_tambahan[]" multiple
                            accept=".pdf, .jpg, .jpeg, .png, .mp4, .pptx, .zip, .rar"
                            style="display: none;">
                    </div>

                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Maksimal 250MB Total File. Anda Bisa Upload Multiple File Sekaligus.
                    </div>

                    <div id="fileList" class="file-list"></div>
                </div>

                <!-- Repository Preview -->
                <div class="repo-preview" id="repoPreview">
                    <div class="preview-title">
                        <i class="fas fa-eye"></i>
                        Preview Repository
                    </div>
                    <div class="preview-content">
                        <h5 id="previewTitle">Nama Repository</h5>
                        <p id="previewDescription">Deskripsi repository akan muncul di sini...</p>
                        <div id="previewStatus"></div>
                        <div id="previewFiles"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline-primary" id="previewBtn">
                        <i class="fas fa-eye me-2"></i>Preview Repository
                    </button>
                    <button type="submit" class="btn btn-primary" id="createBtn">
                        <i class="fas fa-plus me-2"></i>Buat Repository
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast for notifications -->
    <div class="toast align-items-center text-bg-success border-0" role="alert" id="successToast"
        style="display: none;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <!-- Loading Overlay - Muncul saat submit form -->
    <div id="loadingOverlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); z-index: 9999; justify-content: center; align-items: center; flex-direction: column; color: white; font-family: system-ui;">
        <div class="spinner"
            style="border: 4px solid #333; border-top: 4px solid #FFA500; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin-bottom: 20px;">
        </div>
        <h4 style="margin: 0; font-weight: 600;">Sedang membuat repositori...</h4>
        <p style="margin-top: 8px; opacity: 0.8; font-size: 14px; text-align: center; max-width: 80%;">
            Mohon tunggu, jangan tutup halaman ini.<br>
            Proses ini bisa memakan waktu beberapa detik hingga menit tergantung ukuran file.
        </p>
    </div>

    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('repositoryForm');
            if (form) {
                form.addEventListener('submit', function() {
                    // Tampilkan loading overlay
                    document.getElementById('loadingOverlay').style.display = 'flex';

                    // Optional: disable tombol agar tidak diklik dua kali
                    const submitBtn = document.getElementById('createBtn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sedang Diproses...';
                    }
                });
            }
        });
    </script>

    <script>
        // Minimal JavaScript - mostly for file handling and form interactions
        let uploadedFiles = [];

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

            // Draft save
            document.getElementById('draftBtn').addEventListener('click', saveDraft);
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
                if (file.size > 250 * 1024 * 1024) {
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

        function getFileIcon(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const iconMap = {
                'jpg': 'fa-file-image',
                'jpeg': 'fa-file-image',
                'png': 'fa-file-image',
                'mp4': 'fa-file-video',
                'pptx': 'fa-file-powerpoint',
                'pdf': 'fa-file-pdf',
                'zip': 'fa-zipper',
                'rar': 'fa-zipper',
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

        function saveDraft() {
            const draftData = {
                title: document.getElementById('repoTitle').value,
                description: document.getElementById('repoDescription').value,
                status: document.getElementById('repoStatus').value,
                files: uploadedFiles.map(f => f.name),
                timestamp: new Date().toISOString()
            };

            localStorage.setItem('repositoryDraft', JSON.stringify(draftData));
            showToast('Draft berhasil disimpan!', 'success');
        }

        function handleSubmit(e) {
            const title = document.getElementById('repoTitle').value;
            const description = document.getElementById('repoDescription').value;
            const status = document.getElementById('repoStatus').value;

            if (!title || !description || !status) {
                showToast('Mohon lengkapi semua field yang wajib!', 'error');
                e.preventDefault();
                return;
            }

            // ✅ INI YANG BARU — SALIN SEMUA FILE DARI uploadedFiles KE INPUT HTML
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
    </script>

@endsection
