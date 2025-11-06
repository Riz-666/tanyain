@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/tag/create.css') }}">
    @endpush
    <form action="{{ Route('admin.tag.store') }}" method="POST">
        @csrf
        <div class="form-container">
            <!-- Header Section -->
            <div class="page-header" :class="{ 'dark': darkMode }">
                <div class="feature-badge">
                    <i class="fas fa-tags me-1"></i>
                    Tag Management
                </div>
            </div>

            <!-- Success Message -->
            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle me-2"></i>
                Tag berhasil ditambahkan!
            </div>

            <!-- Form Section -->
            <form id="tagForm">
                <div class="mb-4">
                    <label for="tagName" class="form-label">
                        <i class="fas fa-tag me-2" style="color: var(--primary-orange);"></i>
                        Nama Tag
                    </label>
                    <div class="input-group-custom">
                        <i class="form-icon fas fa-hashtag"></i>
                        <input type="text" class="form-control input-with-icon" id="tagName" name="nama_tag"
                            placeholder="Masukkan nama tag (contoh: Teknologi, Bisnis, Tutorial)" required maxlength="50">
                    </div>
                    <div class="help-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Gunakan nama yang deskriptif dan mudah dipahami. Maksimal 50 karakter.
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>
                        Simpan Tag
                    </button>
                    <a type="button" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </a>
                </div>
            </form>

            <!-- Additional Info -->
            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-lightbulb me-1" style="color: var(--primary-orange);"></i>
                    Tips: Gunakan tag yang konsisten untuk memudahkan pencarian dan kategorisasi konten
                </small>
            </div>
        </div>
    </form>
@endsection
