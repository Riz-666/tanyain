@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/profile/profile.css') }}">
    @endpush
    <form action="{{ Route('admin.profile.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Profile Card -->
        <div class="profile-card form-container" :class="{ 'dark': darkMode }">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="{{ asset('storage/user-img/' . ($admin->foto ?? 'default-user.jpg')) }}" alt="Avatar"
                        style="width:100px; height:100px">
                </div>
                <h3 class="profile-name">{{ Auth::user()->nama }}</h3>
                @if (Auth::user()->role == 'super_admin')
                    <p class="profile-role">Administrator Sistem</p>
                @endif
            </div>

            <form id="profileForm">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="fas fa-user section-icon"></i>
                        Informasi Dasar
                    </h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fullName" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" id="fullName" value="{{ Auth::user()->nama }}"
                                    name="nama">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input type="text" class="form-control" id="username"
                                    value="{{ Auth::user()->username }}" name="username">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}"
                                name="email">
                        </div>
                    </div>
                </div>



                <!-- Change Password Section -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="fas fa-key section-icon"></i>
                        Ubah Password (Opsional)
                    </h4>

                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="newPassword" name="password"
                                placeholder="Masukkan password baru (kosongkan jika tidak ingin mengubah)"
                                autocomplete="new-password">

                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="strength-text" id="strengthText">Lemah</span>
                                <small class="text-muted">Kekuatan Password</small>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements" id="passwordRequirements" style="display: none;">
                            <div class="requirement" id="lengthReq">
                                <i class="fas fa-times-circle requirement-icon"></i>
                                Minimal 8 karakter
                            </div>
                            <div class="requirement" id="upperReq">
                                <i class="fas fa-times-circle requirement-icon"></i>
                                Minimal 1 huruf besar
                            </div>
                            <div class="requirement" id="lowerReq">
                                <i class="fas fa-times-circle requirement-icon"></i>
                                Minimal 1 huruf kecil
                            </div>
                            <div class="requirement" id="numberReq">
                                <i class="fas fa-times-circle requirement-icon"></i>
                                Minimal 1 angka
                            </div>
                            <div class="requirement" id="specialReq">
                                <i class="fas fa-times-circle requirement-icon"></i>
                                Minimal 1 karakter khusus (@, #, $, !, dll)
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Ulangi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="confirmPassword"
                                placeholder="Ulangi password baru" name="confirm_password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="form-text" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label for="profilePhoto" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" id="profilePhoto" name="foto">
                    </div>
                </div>


                <!-- Password Verification Section -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="fas fa-shield-alt section-icon"></i>
                        Verifikasi Keamanan
                    </h4>

                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="currentPassword"
                                placeholder="Masukkan password saat ini untuk verifikasi" name="currentPassword">
                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text text-muted">

                            <p><i class="fas fa-info-circle me-1"></i> Diperlukan untuk mengonfirmasi perubahan</p>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </form>
    @push('script')
        <script src="{{ asset('admin/js/profile.js') }}"></script>
    @endpush

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fungsi toggle mata
            function setupPasswordToggle(inputId, toggleId) {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);

                if (!input || !toggle) return;

                toggle.addEventListener("click", function() {
                    const type = input.getAttribute("type") === "password" ? "text" : "password";
                    input.setAttribute("type", type);

                    const icon = toggle.querySelector("i");
                    icon.className = type === "password" ? "fas fa-eye" : "fas fa-eye-slash";
                });
            }

            // aktifkan untuk semua field
            setupPasswordToggle("currentPassword", "toggleCurrentPassword");
            setupPasswordToggle("newPassword", "toggleNewPassword");
            setupPasswordToggle("confirmPassword", "toggleConfirmPassword");
        });
    </script>
@endsection
