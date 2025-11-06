@extends('layouts.app')

@section('title', 'Edit Profile ' . $user->nama . ' - Manajemen Pengetahuan SPBE Kota Bogor')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/profile/edit-profile.css') }}">
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form -->
        <form id="profileForm" action="{{ route('profile.update', $user->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-user-edit me-3"></i>
                        Edit Profile
                    </h1>
                    <p class="page-subtitle">Kelola informasi akun dan pengaturan profil Anda</p>
                </div>
            </div>

            <!-- Photo Upload Section -->
            <div class="form-section">
                <h2 class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    Foto Profile
                </h2>

                <div class="photo-section">
                    <div class="current-photo">
                        <img src="{{ $user->foto ? asset('storage/user-img/' . $user->foto) : 'storage/user-img/default-user.jpg' }}"
                            alt="Profile Photo" class="profile-photo" id="profilePhoto" data-bs-toggle="modal"
                            data-bs-target="#photoPreviewModal">
                        <div class="photo-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>

                    <div class="photo-controls">
                        <h5>Foto Profile Anda</h5>
                        <p>Klik pada foto untuk melihat preview. Upload foto baru dengan format JPG, PNG, atau GIF. Maksimal
                            ukuran 5MB.</p>

                        <div class="upload-buttons">
                            <button type="button" class="btn btn-primary"
                                onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-upload me-2"></i>Upload Foto Baru
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="removePhotoBtn">
                                <i class="fas fa-trash me-2"></i>Hapus Foto
                            </button>
                            <input type="file" name="foto" id="photoInput" accept="image/*" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="form-section">
                <h2 class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    Informasi Dasar
                </h2>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="fullName" class="form-label">
                            <i class="fas fa-id-card"></i>
                            Nama Lengkap *
                        </label>
                        <input type="text" class="form-control" name="nama" id="fullName"
                            value="{{ old('nama', $user->nama) }}" required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="username" class="form-label">
                            <i class="fas fa-at"></i>
                            Username *
                        </label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="{{ old('username', $user->username) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address *
                    </label>
                    <input type="email" class="form-control" name="email" id="email"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-4">
                    <label for="bio" class="form-label">
                        <i class="fas fa-quote-left"></i>
                        Bio
                    </label>
                    <textarea class="form-control" name="bio" id="bio" rows="4"
                        placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                    <div class="form-text">Maksimal 500 karakter</div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="form-section">
                <h2 class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    Social Media
                </h2>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label for="instagram" class="form-label">
                            <i class="fab fa-instagram"></i>
                            Instagram
                        </label>
                        <div class="social-input">
                            <i class="fab fa-instagram social-icon"></i>
                            <input type="url" class="form-control" name="instagram" id="instagram"
                                placeholder="https://instagram.com/username"
                                value="{{ old('instagram', $user->instagram) }}" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="linkedin" class="form-label">
                            <i class="fab fa-linkedin"></i>
                            LinkedIn
                        </label>
                        <div class="social-input">
                            <i class="fab fa-linkedin social-icon"></i>
                            <input type="url" class="form-control" name="linkedin" id="linkedin"
                                placeholder="https://linkedin.com/in/username"
                                value="{{ old('linkedin', $user->linkedin) }}" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="github" class="form-label">
                            <i class="fab fa-github"></i>
                            GitHub
                        </label>
                        <div class="social-input">
                            <i class="fab fa-github social-icon"></i>
                            <input type="url" class="form-control" name="github" id="github"
                                placeholder="https://github.com/username" value="{{ old('github', $user->github) }}"
                                autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="info-box">
                <div class="info-header">
                    <i class="fas fa-info-circle"></i>
                    <strong>Info</strong>
                </div>
                <p>
                    Kolom Password Baru boleh dikosongkan apabila Anda tidak ingin melakukan perubahan password.
                </p>
            </div>
            <!-- Change Password -->
            <div class="form-section">
                <h2 class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    Ubah Password
                </h2>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-key"></i>
                            Password Baru
                        </label>
                        <input type="password" class="form-control" id="password" name="password"
                            autocomplete="new-password" placeholder="Masukkan password baru">

                        <!-- indikator strength -->
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-progress" id="strengthProgress"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Belum ada password</div>
                        </div>

                        <div class="password-requirements">
                            <div class="requirement" id="req-length">
                                <i class="fas fa-times"></i>
                                Minimal 8 karakter
                            </div>
                            <div class="requirement" id="req-upper">
                                <i class="fas fa-times"></i>
                                Mengandung huruf besar
                            </div>
                            <div class="requirement" id="req-lower">
                                <i class="fas fa-times"></i>
                                Mengandung huruf kecil
                            </div>
                            <div class="requirement" id="req-number">
                                <i class="fas fa-times"></i>
                                Mengandung angka
                            </div>
                            <div class="requirement" id="req-special">
                                <i class="fas fa-times"></i>
                                Mengandung karakter khusus
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-shield-alt"></i>
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            autocomplete="new-password" placeholder="Konfirmasi password baru">
                        <div class="invalid-feedback" id="passwordMatchFeedback"></div>
                    </div>
                </div>
            </div>

            <!-- Verification Section -->
            <div class="verification-section">
                <div class="verification-title">
                    <i class="fas fa-shield-alt"></i>
                    Verifikasi Password Lama
                </div>
                <p class="verification-text">
                    Untuk keamanan akun, masukkan password lama Anda sebelum menyimpan perubahan profile.
                </p>
                <div class="mb-3">
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                        autocomplete="current-password" placeholder="Masukkan password lama Anda" required>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-section" >
                <div>
                    <h5 class="mb-2">Simpan Perubahan</h5>
                    <p class="text-muted mb-0">Pastikan semua informasi sudah benar sebelum menyimpan.</p>
                </div>

                <div class="action-buttons">
                    <a href="{{ Route('profile', Auth::user()->id) }}" type="button" class="btn btn-outline-primary" id="cancelBtn">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
        <!-- /Form -->
    </div>

    <!-- Photo Preview Modal -->
    <div class="modal fade" id="photoPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-image me-2"></i>Preview Foto Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ $user->foto ? asset('storage/user-img/' . $user->foto) : 'storage/user-img/default-user.jpg' }}"
                        alt="Profile Preview" class="img-fluid rounded-circle"
                        style="max-width: 300px; border: 4px solid var(--primary-orange);">
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setupPhotoUpload();
            setupPasswordStrength();
            setupFormValidation();
            setupEventHandlers();
        });

        function setupPhotoUpload() {
            const photoInput = document.getElementById('photoInput');
            const profilePhoto = document.getElementById('profilePhoto');
            const removePhotoBtn = document.getElementById('removePhotoBtn');
            const userId = '{{ $user->id }}'; // Ambil ID user dari Blade

            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            title: 'Ukuran Terlalu Besar!',
                            text: 'Maksimal ukuran file 5MB.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                        this.value = ''; // Kosongkan input
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePhoto.src = e.target.result;
                        document.querySelector('#photoPreviewModal img').src = e.target.result;
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Foto berhasil diupload!',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            // 👇 HAPUS FOTO VIA AJAX + SWEETALERT2
            removePhotoBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi Hapus Foto',
                    text: 'Apakah Anda yakin ingin menghapus foto profil Anda? Ini tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        removePhotoBtn.disabled = true;
                        removePhotoBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...';

                        fetch('{{ route('profile.photo.destroy', ':id') }}'.replace(':id', userId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const defaultPhoto =
                                        '{{ asset('storage/user-img/default-user.jpg') }}';
                                    profilePhoto.src = defaultPhoto;
                                    document.querySelector('#photoPreviewModal img').src = defaultPhoto;
                                    photoInput.value = '';
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: data.message,
                                        icon: 'success',
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: data.message ||
                                            'Terjadi kesalahan saat menghapus foto.',
                                        icon: 'error',
                                        confirmButtonColor: '#dc3545'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545'
                                });
                            })
                            .finally(() => {
                                removePhotoBtn.disabled = false;
                                removePhotoBtn.innerHTML =
                                '<i class="fas fa-trash me-2"></i>Hapus Foto';
                            });
                    }
                });
            });
        }

        function setupPasswordStrength() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            const strengthProgress = document.getElementById('strengthProgress');
            const strengthText = document.getElementById('strengthText');

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = calculatePasswordStrength(password);
                updatePasswordUI(strength);
                checkPasswordRequirements(password);
            });

            confirmInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirm = this.value;
                const feedback = document.getElementById('passwordMatchFeedback');

                if (confirm && password !== confirm) {
                    this.classList.add('is-invalid');
                    feedback.textContent = 'Password tidak cocok';
                } else {
                    this.classList.remove('is-invalid');
                    feedback.textContent = '';
                }
            });
        }

        function calculatePasswordStrength(password) {
            let score = 0;
            if (password.length >= 8) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            return score;
        }

        function updatePasswordUI(strength) {
            const strengthProgress = document.getElementById('strengthProgress');
            const strengthText = document.getElementById('strengthText');

            strengthProgress.className = 'strength-progress';
            strengthText.className = 'strength-text';

            switch (strength) {
                case 0:
                case 1:
                    strengthProgress.classList.add('strength-weak');
                    strengthText.classList.add('text-weak');
                    strengthText.textContent = 'Sangat Lemah';
                    break;
                case 2:
                    strengthProgress.classList.add('strength-fair');
                    strengthText.classList.add('text-fair');
                    strengthText.textContent = 'Lemah';
                    break;
                case 3:
                    strengthProgress.classList.add('strength-good');
                    strengthText.classList.add('text-good');
                    strengthText.textContent = 'Cukup Kuat';
                    break;
                case 4:
                case 5:
                    strengthProgress.classList.add('strength-strong');
                    strengthText.classList.add('text-strong');
                    strengthText.textContent = 'Sangat Kuat';
                    break;
            }
        }

        function checkPasswordRequirements(password) {
            const requirements = [{
                    id: 'req-length',
                    test: password.length >= 8
                },
                {
                    id: 'req-upper',
                    test: /[A-Z]/.test(password)
                },
                {
                    id: 'req-lower',
                    test: /[a-z]/.test(password)
                },
                {
                    id: 'req-number',
                    test: /[0-9]/.test(password)
                },
                {
                    id: 'req-special',
                    test: /[^A-Za-z0-9]/.test(password)
                }
            ];

            requirements.forEach(req => {
                const element = document.getElementById(req.id);
                const icon = element.querySelector('i');

                if (req.test) {
                    element.classList.add('met');
                    icon.className = 'fas fa-check';
                } else {
                    element.classList.remove('met');
                    icon.className = 'fas fa-times';
                }
            });
        }

        function setupFormValidation() {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input[required]');

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid') && this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                } else if (this.value) {
                    this.classList.remove('is-invalid');
                }
            });

            const usernameInput = document.getElementById('username');
            usernameInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^\w\-]/g, '').toLowerCase();
            });
        }

        function setupEventHandlers() {
            // Hapus event handler manual submit, karena tombol sekarang type="submit"
            document.getElementById('previewBtn').addEventListener('click', function() {
                showToast('Fitur preview akan segera tersedia!', 'info');
            });

            document.getElementById('cancelBtn').addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin membatalkan perubahan?')) {
                    location.reload();
                }
            });
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
            } else if (type === 'info') {
                toast.classList.add('text-bg-info');
                toastBody.innerHTML = `<i class="fas fa-info-circle me-2"></i>${message}`;
            } else {
                toast.classList.add('text-bg-success');
                toastBody.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
            }

            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 4000);
        }

        // Add smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.form-section');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'all 0.6s ease';
                section.style.transitionDelay = `${index * 0.1}s`;

                setTimeout(() => {
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 100);
            });

            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                control.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.disabled) return;

                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;

                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            .form-control:focus {
                transform: translateY(-1px);
            }

            .btn:active {
                transform: translateY(1px);
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
