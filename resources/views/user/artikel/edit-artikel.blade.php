@extends('layouts.app')

@section('title', 'Edit Artikel - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/artikel/create.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
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
                <i class="fas fa-pen-fancy text-primary me-2"></i>
                Edit Artikel
            </h1>
            <p class="form-subtitle">Perbarui artikel Anda dengan mudah</p>

            <form id="articleForm" method="POST" action="{{ route('update.artikel', $artikel->id) }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="artikel_id" id="artikelId" value="{{ $artikel->id }}">

                <!-- Basic Info Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi Dasar
                    </h3>

                    <div class="mb-4">
                        <label for="articleTitle" class="form-label">
                            <i class="fas fa-heading me-2"></i>Judul Artikel *
                        </label>
                        <input type="text" name="judul" class="form-control" id="articleTitle"
                            placeholder="Masukkan judul artikel yang menarik..." value="{{ old('judul', $artikel->judul) }}"
                            required>
                        <div class="form-text">Judul yang baik akan menarik perhatian pembaca</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="articleTags" class="form-label">
                                <i class="fas fa-tags me-2"></i>Tag Artikel
                            </label>
                            <select class="form-select" id="articleTags" name="tag[]" multiple="multiple">
                                @foreach ($tag as $t)
                                    <option value="{{ $t->id }}"
                                        {{ collect(old('tag', $artikel->tag->pluck('id')))->contains($t->id) ? 'selected' : '' }}>
                                        {{ $t->nama_tag }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="newTag" class="form-label">
                                <i class="fas fa-plus me-2"></i>Tambah Tag Baru
                            </label>
                            <div class="input-group">
                                <input type="text" name="new_tag[]" class="form-control" id="newTag"
                                    placeholder="Nama tag baru">
                                <button class="btn btn-outline-primary" type="button" id="addTagBtn">
                                    <i class="fas fa-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cover Image Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-image"></i>
                        Gambar Cover
                    </h3>

                    <div class="cover-preview" id="coverPreview">
                        @if ($artikel->cover)
                            <div id="coverImagePreview">
                                <img src="{{ asset('storage/artikel/' . $artikel->id . '/cover/' . $artikel->cover) }}"
                                    alt="Cover Preview" class="preview-image">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCover()">
                                        <i class="fas fa-trash me-1"></i>Hapus Cover
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changeCover()">
                                        <i class="fas fa-edit me-1"></i>Ganti Cover
                                    </button>
                                </div>
                            </div>
                            <div id="coverPlaceholder" style="display:none;">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <div class="upload-text">Klik atau seret gambar cover di sini</div>
                                <div class="upload-subtext">Format: JPG, PNG, WEBP (Max: 5MB)</div>
                            </div>
                        @else
                            <div id="coverPlaceholder">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <div class="upload-text">Klik atau seret gambar cover di sini</div>
                                <div class="upload-subtext">Format: JPG, PNG, WEBP (Max: 5MB)</div>
                            </div>
                            <div id="coverImagePreview" style="display:none;"></div>
                        @endif

                        <input type="file" name="cover" id="coverImage" accept="image/*" style="display:none;">
                        <input type="hidden" name="remove_cover" id="removeCoverInput" value="0">
                    </div>
                </div>

                <!-- Content Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-edit"></i>
                        Konten Artikel
                    </h3>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-align-left me-2"></i>Isi Artikel *
                        </label>
                        <div class="editor-container">
                            <div id="articleEditor" style="height: 400px;"></div>
                            <input type="hidden" name="isi" id="articleContent"
                                value="{{ old('isi', $artikel->isi) }}">
                        </div>
                    </div>
                </div>

                <!-- Files & Repository Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-paperclip"></i>
                        File & Repository
                    </h3>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="fas fa-file-upload me-2"></i>Upload File Pendukung
                            </label>
                            <div class="file-upload-area" id="fileUploadArea">
                                <i class="fas fa-file-alt"
                                    style="font-size: 2rem; color: var(--primary-orange); margin-bottom: 1rem;"></i>
                                <p class="mb-2">Seret file atau klik untuk upload</p>
                                <small class="text-muted">PDF (Max: 10MB)</small>
                                <input type="file" id="supportFiles" name="file" accept=".pdf"
                                    style="display: none;">
                            </div>

                            <!-- Tampilkan file yang sudah ada -->
                            @if ($artikel->file)
                                <div class="existing-file mt-3 p-2 bg-light rounded">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-file me-2 text-primary"></i>
                                            <span>{{ $artikel->file }}</span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="removeExistingFile()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="existing_file" value="{{ $artikel->file }}">
                                    <input type="hidden" name="remove_file" id="removeFileInput" value="0">
                                </div>
                            @endif

                            <div id="fileList" class="file-list"></div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="repository" class="form-label">
                                <i class="fab fa-github me-2"></i>Tautkan ke Repository
                            </label>
                            <select class="form-select" id="repository" name="repositori_id">
                                <option value="">Pilih Repository (Opsional)</option>
                                @foreach ($repositori as $r)
                                    <option value="{{ $r->id }}"
                                        {{ old('repositori_id', $artikel->repositori_id) == $r->id ? 'selected' : '' }}>
                                        {{ $r->judul_repo }}
                                    </option>
                                @endforeach
                            </select>


                            <div class="form-text">Repository yang terkait dengan artikel ini</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline-primary" id="saveDraftBtn">
                        <i class="fas fa-save me-2"></i>Simpan Draft
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="loadDraftBtn">
                        <i class="fas fa-folder-open me-2"></i>Load Draft
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="previewArticle()">
                        <i class="fas fa-eye me-2"></i>Preview
                    </button>
                    <button type="submit" class="btn btn-primary" id="publishBtn">
                        <i class="fas fa-paper-plane me-2"></i>Perbarui Artikel
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
    <script>
        // ===== GLOBAL VARIABLES =====
        let quill;
        let currentImages = new Set();
        let initialImages = new Set();
        window.uploadedImages = [];
        let uploadedFiles = [];

        // ===== GLOBAL FUNCTIONS =====
        function getImagesFromEditor() {
            if (!quill || !quill.root) return [];
            return Array.from(quill.root.querySelectorAll('img')).map(img => img.getAttribute('src'));
        }

        function updateCurrentImages() {
            currentImages = new Set(getImagesFromEditor());
        }

        function saveDraftSilent() {
            const artikelId = $('#artikelId').val();
            if (!artikelId) return;

            let draftData = {
                artikel_id: artikelId,
                judul: $('#articleTitle').val(),
                tags: $('#articleTags').val(),
                content: quill.root.innerHTML,
                coverImage: $('#coverImage')[0]?.files[0] ? $('#coverImage')[0].files[0].name : '',
                repository: $('#repository').val()
            };

            fetch('/drafts/save', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(draftData)
            }).catch(err => console.error("Autosave gagal:", err));
        }

        function saveDraft() {
            saveDraftSilent();
            showToast('Draft berhasil disimpan!', 'success');
        }

        function loadDraft() {
            let artikelId = $('#artikelId').val();
            if (!artikelId) {
                showToast('Artikel belum dibuat!', 'error');
                return;
            }

            fetch(`/drafts/load/${artikelId}`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Response data:', data);

                    if (data.success !== false && data.draft && data.draft !== null) {

                        if (data.draft.content && data.draft.content.trim() !== '') {
                            quill.root.innerHTML = data.draft.content;

                            if (data.draft.image_temp_paths && Array.isArray(data.draft.image_temp_paths)) {
                                window.uploadedImages = data.draft.image_temp_paths.map(path => {
                                    let url = `${window.location.origin}/storage/${path}`;
                                    return {
                                        url,
                                        temp_path: path
                                    };
                                });
                            } else {
                                window.uploadedImages = [];
                            }

                            updateCurrentImages();

                            if (data.draft.judul) $('#articleTitle').val(data.draft.judul);
                            if (data.draft.tags) {
                                $('#articleTags').val(data.draft.tags).trigger('change');
                            }
                            if (data.draft.repository) {
                                $('#repository').val(data.draft.repository).trigger('change');
                            }

                            showToast('Draft berhasil dimuat!', 'success');
                        } else {
                            showToast('Draft ditemukan, tapi konten kosong.', 'warning');
                        }
                    } else {
                        showToast('Tidak ada draft tersimpan untuk artikel ini.', 'info');
                    }
                })
                .catch(err => {
                    console.error("Load draft error:", err);
                    showToast('Gagal memuat draft. Periksa koneksi internet.', 'error');
                });
        }

        function handleCoverUpload(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    showToast('Ukuran cover maksimal 5MB', 'error');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    $('#coverPlaceholder').hide();
                    $('#coverImagePreview').html(`
                <img src="${evt.target.result}" alt="Cover Preview" class="preview-image">
                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCover()">
                        <i class="fas fa-trash me-1"></i>Hapus Cover
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changeCover()">
                        <i class="fas fa-edit me-1"></i>Ganti Cover
                    </button>
                </div>
            `).show();
                    showToast('Cover berhasil diupload!', 'success');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeCover() {
            $('#coverImage').val('');
            $('#coverImagePreview').hide().empty();
            $('#coverPlaceholder').show();
            document.getElementById('removeCoverInput').value = '1';
            showToast('Cover akan dihapus saat artikel disimpan.', 'success');
        }

        function changeCover() {
            document.getElementById('coverImage').click();
        }

        function addFileToList(file) {
            const fileItem = $(`
        <div class="file-item" data-file-name="${file.name}">
            <div class="d-flex align-items-center flex-grow-1">
                <i class="fas fa-file me-2 text-primary"></i>
                <span>${file.name}</span>
                <small class="text-muted ms-2">(${formatFileSize(file.size)})</small>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFile('${file.name}')">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `);

            $('#fileList').append(fileItem);
        }

        function removeFile(fileName) {
            uploadedFiles = uploadedFiles.filter(file => file.name !== fileName);
            $(`.file-item[data-file-name="${fileName}"]`).remove();
            showToast('File berhasil dihapus!', 'success');
        }

        function removeExistingFile() {
            document.getElementById('removeFileInput').value = '1';
            $('.existing-file').fadeOut(300, function() {
                $(this).remove();
            });
            showToast('File akan dihapus saat artikel disimpan.', 'success');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function addNewTag() {
            const newTag = $('#newTag').val().trim();
            if (newTag) {
                const existingOptions = $('#articleTags option').map(function() {
                    return $(this).text();
                }).get();

                if (!existingOptions.includes(newTag)) {
                    const option = new Option(newTag, newTag, true, true);
                    $('#articleTags').append(option).trigger('change');
                    $('#newTag').val('');
                    showToast('Tag baru berhasil ditambahkan!', 'success');
                } else {
                    showToast('Tag sudah ada!', 'error');
                }
            }
        }

        function previewArticle() {
            const content = {
                title: $('#articleTitle').val(),
                content: quill.root.innerHTML,
                tags: $('#articleTags').val(),
                coverImage: $('#coverPreview img').attr('src'),
                repository: $('#repository option:selected').text()
            };

            if (!content.title?.trim()) {
                showToast('Judul artikel harus diisi!', 'error');
                return;
            }

            if (quill.getLength() <= 1) {
                showToast('Isi artikel harus diisi!', 'error');
                return;
            }

            const preview = window.open('', '_blank', 'width=800,height=600');
            preview.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Preview: ${content.title}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 2rem; background: #f8f9fa; }
                .preview-container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                .cover-image { width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 1.5rem; }
                .article-title { font-size: 2rem; color: #333; margin-bottom: 1rem; }
                .content img { max-width: 100%; width: 100%; }
                .tag { background: #007bff; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; margin-right: 0.5rem; font-size: 0.875rem; }
                .content { line-height: 1.6; font-size: 1.1rem; }
                .repository-info { background: #e9ecef; padding: 1rem; border-radius: 8px; margin-top: 2rem; }
            </style>
        </head>
        <body>
            <div class="preview-container">
                ${content.coverImage ? `<img src="${content.coverImage}" class="cover-image">` : ''}
                <h1 class="article-title">${content.title}</h1>
                <div class="mb-3">
                    ${content.tags && content.tags.length > 0 ? content.tags.map(tag => `<span class="tag">${tag}</span>`).join('') : ''}
                </div>
                <div class="content">${content.content}</div>
                ${content.repository && content.repository !== 'Pilih repository (opsional)' ? `<div class="repository-info"><strong>Repository:</strong> ${content.repository}</div>` : ''}
            </div>
        </body>
        </html>
    `);
        }

        function showToast(message, type = 'success') {
            const toast = $('#successToast');
            const toastBody = toast.find('.toast-body');

            toast.removeClass('text-bg-success text-bg-danger text-bg-warning text-bg-info');

            if (type === 'error') {
                toast.addClass('text-bg-danger');
                toastBody.html(`<i class="fas fa-exclamation-circle me-2"></i>${message}`);
            } else if (type === 'warning') {
                toast.addClass('text-bg-warning');
                toastBody.html(`<i class="fas fa-exclamation-triangle me-2"></i>${message}`);
            } else if (type === 'info') {
                toast.addClass('text-bg-info');
                toastBody.html(`<i class="fas fa-info-circle me-2"></i>${message}`);
            } else {
                toast.addClass('text-bg-success');
                toastBody.html(`<i class="fas fa-check-circle me-2"></i>${message}`);
            }

            toast.show();
            setTimeout(() => {
                toast.hide();
            }, 3000);
        }

        function setupEventHandlers() {
            // Cover image upload
            document.getElementById('coverPreview').addEventListener('click', function(e) {
                if (e.target.closest('.btn')) return;
                document.getElementById('coverImage').click();
            });

            document.getElementById('coverImage').addEventListener('change', handleCoverUpload);

            // File upload
            document.getElementById('fileUploadArea').addEventListener('click', function(e) {
                if (e.target.closest('.btn')) return;
                document.getElementById('supportFiles').click();
            });

            document.getElementById('supportFiles').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size > 10 * 1024 * 1024) {
                    showToast('File maksimal 10MB', 'error');
                    this.value = '';
                    return;
                }

                if (file.type !== "application/pdf") {
                    showToast('Hanya boleh upload PDF!', 'error');
                    this.value = '';
                    return;
                }

                uploadedFiles = [file];
                $('#fileList').html('');
                addFileToList(file);
            });

            // Add new tag
            $('#addTagBtn').on('click', addNewTag);
            $('#newTag').on('keypress', function(e) {
                if (e.which === 13) {
                    addNewTag();
                }
            });

            // Draft actions
            $('#saveDraftBtn').on('click', saveDraft);
            $('#loadDraftBtn').on('click', loadDraft);
        }

        // ===== DOCUMENT READY =====
        $(document).ready(function() {
            // Initialize Select2
            $('#articleTags').select2({
                placeholder: "Pilih tag untuk artikel",
                allowClear: true,
                width: '100%'
            });

            $('#repository').select2({
                placeholder: "Pilih repository (opsional)",
                allowClear: true,
                width: '100%'
            });

            // Initialize Quill Editor
            quill = new Quill('#articleEditor', {
                theme: 'snow',
                placeholder: 'Mulai menulis artikel Anda di sini...',
                modules: {
                    toolbar: [
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        [{
                            'size': ['small', false, 'large', 'huge']
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'align': []
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['blockquote', 'code-block'],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });

            // Load isi artikel lama saat edit - FIXED!
            if ($('#articleContent').val()) {
                let content = $('#articleContent').val();
                let artikelId = $('#artikelId').val();

                // Fix path gambar yang relatif menjadi absolute URL
                if (artikelId) {
                    // Pattern: src="artikel/{id}/images/{filename}"
                    content = content.replace(
                        /src="artikel\/\d+\/images\/([^"]+)"/g,
                        `src="${window.location.origin}/storage/artikel/${artikelId}/images/$1"`
                    );

                    // Pattern: src="/storage/artikel/{id}/images/{filename}" (jika sudah ada /storage)
                    content = content.replace(
                        /src="\/storage\/artikel\/\d+\/images\/([^"]+)"/g,
                        `src="${window.location.origin}/storage/artikel/${artikelId}/images/$1"`
                    );

                    // Pattern: src="storage/artikel/{id}/images/{filename}" (tanpa slash depan)
                    content = content.replace(
                        /src="storage\/artikel\/\d+\/images\/([^"]+)"/g,
                        `src="${window.location.origin}/storage/artikel/${artikelId}/images/$1"`
                    );
                }

                quill.root.innerHTML = content;
                initialImages = new Set(getImagesFromEditor());
                currentImages = new Set(initialImages);
            }

            // Autosave tiap 30 detik
            setInterval(() => {
                saveDraftSilent();
            }, 30000);

            // Upload Image Function
            function uploadImage(file) {
                let formData = new FormData();
                formData.append('image', file);

                fetch('/upload-image', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            let range = quill.getSelection() || {
                                index: 0
                            };
                            quill.insertEmbed(range.index, 'image', result.url);

                            window.uploadedImages.push({
                                url: result.url,
                                temp_path: result.temp_path
                            });

                            showToast('Gambar berhasil diupload!', 'success');
                            updateCurrentImages();
                        } else {
                            showToast(result.message || "Upload gagal", 'error');
                        }
                    })
                    .catch(err => {
                        console.error('Upload error:', err);
                        showToast("Upload error", 'error');
                    });
            }

            // Override toolbar image button
            quill.getModule('toolbar').addHandler('image', () => {
                let input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = () => {
                    let file = input.files[0];
                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            showToast('Ukuran gambar maksimal 5MB', 'error');
                            return;
                        }
                        uploadImage(file);
                    }
                };
            });

            // Track perubahan gambar di editor
            quill.on('text-change', function() {
                let newImages = new Set(getImagesFromEditor());
                let deletedImages = [...currentImages].filter(img => !newImages.has(img));

                deletedImages.forEach(imgUrl => {
                    // Hapus dari server
                    fetch('{{ route('artikel.deleteImage') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                url: imgUrl
                            })
                        })
                        .then(() => {
                            // Hapus dari uploadedImages jika ada
                            window.uploadedImages = window.uploadedImages.filter(img => img
                                .url !== imgUrl);
                            console.log('Gambar berhasil dihapus:', imgUrl);
                        })
                        .catch(err => console.error('Gagal hapus gambar:', err));
                });

                currentImages = newImages;
            });

            // Simpan isi editor ke hidden input sebelum submit
            document.querySelector('#articleForm').addEventListener('submit', function(e) {
                let content = quill.root.innerHTML;
                content = content.replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, '');
                document.querySelector('#articleContent').value = content;

                // Kirim data gambar baru yang diupload
                let imagesInEditor = getImagesFromEditor();
                let imagesToKeep = window.uploadedImages.filter(img => imagesInEditor.includes(img.url));

                let input = document.querySelector('#uploadedImagesInput');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'uploaded_images';
                    input.id = 'uploadedImagesInput';
                    this.appendChild(input);
                }
                input.value = JSON.stringify(imagesToKeep);

                console.log('Images to keep:', imagesToKeep);
            });

            // Setup event handlers
            setupEventHandlers();
        });
    </script>

@endsection
