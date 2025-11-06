@extends('layouts.app')

@section('title', 'Buat Artikel - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')

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
                Buat Artikel Baru
            </h1>
            <p class="form-subtitle">Tulis dan publikasikan artikel profesional dengan mudah</p>

            <form id="articleForm" method="POST" action="{{ route('add.artikel') }}" enctype="multipart/form-data">
                @csrf
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
                            placeholder="Masukkan judul artikel yang menarik..." value="{{ old('judul') }}" required>
                        <div class="form-text">Judul yang baik akan menarik perhatian pembaca</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="articleTags" class="form-label">
                                <i class="fas fa-tags me-2"></i>Tag Artikel
                            </label>
                            <select class="form-select form-control" id="articleTags" name="tag[]" multiple="multiple">
                                @foreach ($tag as $t)
                                    <option value="{{ $t->id }}"
                                        {{ collect(old('tag'))->contains($t->id) ? 'selected' : '' }}>{{ $t->nama_tag }}
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
                        <div class="upload-content" id="coverPlaceholder">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">Klik atau seret gambar cover di sini</div>
                            <div class="upload-subtext">Format: JPG, PNG, WEBP (Max: 5MB)</div>
                        </div>

                        <div id="coverImagePreview" style="display:none;"></div>

                        <input type="file" name="cover" id="coverImage" accept="image/*" style="display:none;">
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
                            <input type="hidden" name="isi" id="articleContent">
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
                                <input name="file" type="file" id="supportFiles" accept=".pdf"
                                    style="display: none;">
                            </div>
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
                                        {{ old('repositori_id') == $r->id ? 'selected' : '' }}>{{ $r->judul_repo }}</option>
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
                        <i class="fas fa-paper-plane me-2"></i>Publikasikan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast for notifications (Pure jQuery Version) -->
    <div id="successToast" class="position-fixed top-0 end-0 m-3 p-3 rounded shadow text-white bg-success"
        style="z-index: 9999; min-width: 300px; display: none;">
        <div class="d-flex align-items-center">
            <div class="toast-body flex-grow-1">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage">Pesan akan muncul di sini</span>
            </div>
            <button type="button" class="btn-close btn-close-white ms-2"
                onclick="$('#successToast').fadeOut(300);"></button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>

    <script>
        // ===== GLOBAL VARIABLES =====
        let quill;
        let currentImages = new Set();
        window.uploadedImages = []; // ✅ PENTING: Simpan data gambar yang diupload

        // ===== GLOBAL FUNCTIONS =====
        function getImagesFromEditor() {
            if (!quill || !quill.root) return [];
            return Array.from(quill.root.querySelectorAll('img')).map(img => img.getAttribute('src'));
        }

        function updateCurrentImages() {
            currentImages = new Set(getImagesFromEditor());
        }

        function saveDraftSilent() {
            let draftData = {
                artikel_id: document.querySelector("#artikelId")?.value || null,
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
            let artikelId = document.querySelector("#artikelId")?.value || '';
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
                            // Set konten ke editor
                            quill.root.innerHTML = data.draft.content;

                            // ✅ PERBAIKAN UTAMA - Rebuild uploadedImages array dari gambar yang ada di editor
                            let imagesInContent = getImagesFromEditor();
                            window.uploadedImages = [];

                            // Cek setiap gambar di konten
                            imagesInContent.forEach(imgUrl => {
                                // Jika ini gambar dari temp (belum dipindah ke artikel folder)
                                if (imgUrl.includes('/artikel/temp/')) {
                                    // ✅ SOLUSI: Ekstrak path relatif dari URL dengan cara yang lebih aman
                                    // Cari substring setelah '/storage/'
                                    const storageIndex = imgUrl.indexOf('/storage/');
                                    if (storageIndex !== -1) {
                                        let tempPath = imgUrl.substring(storageIndex + '/storage/'
                                        .length); // Hasil: "artikel/temp/abc123.jpg"
                                        window.uploadedImages.push({
                                            url: imgUrl,
                                            temp_path: tempPath
                                        });
                                        console.log(`✅ Gambar TEMP ditemukan: ${imgUrl} -> Path: ${tempPath}`);
                                    } else {
                                        // Jika format URL tidak dikenali, tetap push dengan temp_path null agar tidak crash
                                        console.warn('⚠️ Format URL gambar TEMP tidak dikenali:', imgUrl);
                                        window.uploadedImages.push({
                                            url: imgUrl,
                                            temp_path: null
                                        });
                                    }
                                }
                                // Jika gambar sudah di folder artikel (dari draft yang sudah pernah di-submit)
                                else if (imgUrl.includes('/artikel/') && !imgUrl.includes('/temp/')) {
                                    // Untuk gambar yang sudah di folder artikel, tidak perlu dipindah lagi
                                    window.uploadedImages.push({
                                        url: imgUrl,
                                        temp_path: null // null karena tidak perlu dipindah
                                    });
                                    console.log(`📁 Gambar PERMANEN ditemukan: ${imgUrl}`);
                                }
                                // Jika bukan gambar dari sistem kita (misalnya, gambar eksternal), abaikan temp_path
                                else {
                                    window.uploadedImages.push({
                                        url: imgUrl,
                                        temp_path: null
                                    });
                                    console.log(`🌐 Gambar EKSTERNAL ditemukan: ${imgUrl}`);
                                }
                            });

                            console.log('✅ Rebuilt uploadedImages array:', window.uploadedImages);

                            updateCurrentImages();

                            // Load data form lainnya
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
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    $('#coverPlaceholder').hide();
                    $('#coverImagePreview').html(`
                    <img src="${evt.target.result}" alt="Cover Preview" class="preview-image">
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCover()">
                            <i class="fas fa-trash me-1"></i>Hapus
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

            toast.removeClass('bg-success bg-danger bg-warning bg-info text-white text-dark');

            if (type === 'error') {
                toast.addClass('bg-danger text-white');
                toastBody.html(`<i class="fas fa-exclamation-circle me-2"></i>${message}`);
            } else if (type === 'warning') {
                toast.addClass('bg-warning text-dark');
                toastBody.html(`<i class="fas fa-exclamation-triangle me-2"></i>${message}`);
            } else if (type === 'info') {
                toast.addClass('bg-info text-white');
                toastBody.html(`<i class="fas fa-info-circle me-2"></i>${message}`);
            } else {
                toast.addClass('bg-success text-white');
                toastBody.html(`<i class="fas fa-check-circle me-2"></i>${message}`);
            }

            toast.fadeIn(300);
            setTimeout(() => {
                toast.fadeOut(300);
            }, 3000);
        }

        function setupEventHandlers() {
            // Cover image upload
            document.getElementById('coverPreview').addEventListener('click', function() {
                document.getElementById('coverImage').click();
            });

            document.getElementById('coverImage').addEventListener('change', handleCoverUpload);

            // File upload
            document.getElementById('fileUploadArea').addEventListener('click', function() {
                document.getElementById('supportFiles').click();
            });

            $('#supportFiles').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size > 10 * 1024 * 1024) {
                    showToast('File maksimal 10MB', 'error');
                    return;
                }

                const fileList = $('#fileList');
                fileList.html(`
                <div id="fileItem">
                    ${file.name} <button type="button" class="btn btn-danger btn-sm" id="deleteFileBtn"><i class="fa fa-trash"></i> Hapus</button>
                </div>
            `);

                $('#deleteFileBtn').on('click', function() {
                    $('#supportFiles').val('');
                    $('#fileList').html('');
                });
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
                width: '100%',
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

            // ✅ TIDAK ADA AUTO-LOAD DRAFT untuk create (halaman kosong)

            // Autosave tiap 30 detik
            setInterval(() => {
                saveDraftSilent();
            }, 30000);

            // ✅ CUSTOM IMAGE UPLOAD HANDLER - INI YANG PENTING!
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

                            // ✅ SIMPAN INFO GAMBAR - INI YANG KURANG!
                            window.uploadedImages.push({
                                url: result.url,
                                temp_path: result.temp_path
                            });

                            console.log('Image uploaded:', result.url);
                            console.log('Uploaded images array:', window.uploadedImages);

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

            // Deteksi jika gambar dihapus dari editor
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
                            // Hapus dari uploadedImages
                            window.uploadedImages = window.uploadedImages.filter(img => img
                                .url !== imgUrl);
                            console.log('Image deleted:', imgUrl);
                        })
                        .catch(err => console.error('Gagal hapus gambar:', err));
                });

                currentImages = newImages;
            });

            // ✅ YANG PALING PENTING - FORM SUBMIT HANDLER
            document.querySelector('#articleForm').addEventListener('submit', function(e) {
                console.log('Form submitting...');

                let content = quill.root.innerHTML;
                content = content.replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, '');
                document.querySelector('#articleContent').value = content;

                // ✅ Filter gambar yang masih ada di editor
                let imagesInEditor = getImagesFromEditor();
                let imagesToKeep = window.uploadedImages.filter(img => imagesInEditor.includes(img.url));

                console.log('Images in editor:', imagesInEditor);
                console.log('Images to keep:', imagesToKeep);

                // ✅ BUAT HIDDEN INPUT UNTUK DATA GAMBAR
                let input = document.querySelector('#uploadedImagesInput');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'uploaded_images';
                    input.id = 'uploadedImagesInput';
                    this.appendChild(input);
                }
                input.value = JSON.stringify(imagesToKeep);

                console.log('Hidden input value:', input.value);

                // ✅ Cleanup gambar yang tidak dipakai dari temp
                let imagesToDelete = window.uploadedImages.filter(img => !imagesInEditor.includes(img.url));
                imagesToDelete.forEach(img => {
                    fetch('{{ route('artikel.deleteImage') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            url: img.url
                        })
                    }).catch(err => console.error('Gagal hapus cleanup:', err));
                });

                // Form akan submit normal setelah ini
            });

            // Setup event handlers
            setupEventHandlers();
        });
    </script>
@endsection
