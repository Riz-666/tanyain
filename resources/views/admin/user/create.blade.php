@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/user/create.css') }}">
    @endpush
    <div class="content" :class="darkMode ? 'dark' : 'light'">

        <form action="{{ Route('admin.user.store') }}" method="post">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <div class="form-container">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-header">
                            <h2><i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru</h2>
                            <p>Lengkapi formulir di bawah untuk menambahkan Pengguna baru</p>
                        </div>

                        <div class="form-body">
                            <form id="userForm">
                                <div class="mb-4">
                                    <label for="nama" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="{{ old('nama') }}">
                                </div>

                                <div class="mb-4">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-at me-2"></i>Username
                                    </label>
                                    <input type="text" class="form-control" id="username" name="username" readonly
                                        style="background-color: rgba(107, 107, 107, 0.397)" {{ old('username') }}>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}">
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                            value="{{ old('password') }}">
                                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-eye"></i>
                                        </button>
                                    </div>

                                    <div class="password-strength">
                                        <div class="strength-label">Kekuatan Password:</div>
                                        <div class="strength-bar">
                                            <div class="strength-fill" id="strength-fill"></div>
                                        </div>
                                        <div class="strength-text" id="strength-text">Masukkan password</div>
                                    </div>

                                    <div class="requirements">
                                        <strong>Requirements:</strong>
                                        <ul>
                                            <li>Minimal 8 karakter</li>
                                            <li>Mengandung huruf besar dan kecil</li>
                                            <li>Mengandung angka</li>
                                            <li>Mengandung karakter khusus (!@#$%^&*)</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Konfirmasi Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password" value="{{ old('password') }}">
                                        <button type="button" class="password-toggle"
                                            onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye" id="confirm_password-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password-alert"
                                        style="color:red; font-size:0.85rem; margin-top:0.25rem; display:none;">
                                        Password dan konfirmasi password tidak cocok
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ Route('admin.user') }}" type="button" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan User
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    @push('script')
        <script src="{{ asset('admin/js/user/create.js') }}"></script>
    @endpush
@endsection
