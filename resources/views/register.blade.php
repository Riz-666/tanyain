@extends('layouts.app')
@section('title', 'Register - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/login.css') }}">
    <style>
        /* Password Strength Bar - Enhanced */
        .password-strength-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            margin-top: 10px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .password-strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s ease;
            border-radius: 10px;
            position: relative;
        }

        .password-strength-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
                rgba(255, 255, 255, 0.3) 0%,
                rgba(255, 255, 255, 0) 50%,
                rgba(255, 255, 255, 0.3) 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .strength-weak .password-strength-fill {
            width: 33% !important;
            background: linear-gradient(90deg, #ef4444, #f87171) !important;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
        }

        .strength-medium .password-strength-fill {
            width: 66% !important;
            background: linear-gradient(90deg, #f59e0b, #fbbf24) !important;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
        }

        .strength-strong .password-strength-fill {
            width: 100% !important;
            background: linear-gradient(90deg, #10b981, #34d399) !important;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }

        .password-strength-text {
            font-size: 13px;
            margin-top: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .text-weak {
            color: #ef4444;
            animation: fadeIn 0.3s ease;
        }
        .text-medium {
            color: #f59e0b;
            animation: fadeIn 0.3s ease;
        }
        .text-strong {
            color: #10b981;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Password Match Indicator */
        .password-match-indicator {
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .match-success {
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .match-error {
            color: #ef4444;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Animated Icons */
        @keyframes checkmark {
            0% { transform: scale(0) rotate(0deg); }
            50% { transform: scale(1.2) rotate(180deg); }
            100% { transform: scale(1) rotate(360deg); }
        }

        @keyframes crossmark {
            0% { transform: scale(0) rotate(0deg); }
            100% { transform: scale(1) rotate(90deg); }
        }

        .icon-check {
            animation: checkmark 0.3s ease;
        }

        .icon-cross {
            animation: crossmark 0.3s ease;
        }

        /* Username availability */
        .username-feedback {
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
        }

        .feedback-success { color: #10b981; }
        .feedback-error { color: #ef4444; }
        .feedback-info { color: #3b82f6; }

        /* Enhanced form input focus */
        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-input:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            transform: translateY(-2px);
            background: #ffffff;
        }

        .form-input:hover {
            border-color: #cbd5e1;
        }

        /* Form label animation */
        .form-label {
            transition: all 0.3s ease;
        }

        .form-input:focus + .form-label,
        .form-input:not(:placeholder-shown) + .form-label {
            color: var(--primary-orange);
        }

        /* Smooth checkbox animation */
        .checkbox:checked + .checkbox-custom {
            animation: checkboxPop 0.3s ease;
        }

        @keyframes checkboxPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Loading dots animation */
        @keyframes loadingDots {
            0%, 20% { content: '.'; }
            40% { content: '..'; }
            60%, 100% { content: '...'; }
        }

        .loading-text::after {
            content: '';
            animation: loadingDots 1.5s infinite;
        }

        /* Password requirements - Enhanced */
        .password-requirements {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 12px;
            font-size: 13px;
            color: var(--text-light);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 6px 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 4px 0;
        }

        .requirement-met {
            color: #10b981;
            font-weight: 500;
        }

        .requirement-icon {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        .requirement-icon.met {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
            transform: scale(1);
        }

        .requirement-icon.unmet {
            background: #e2e8f0;
            color: #94a3b8;
            border: 2px solid #cbd5e1;
        }
    </style>
@endsection

@section('content')
    <center>
        <div class="login-container">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="login-header">
                <h1 class="login-title">Daftar Akun Baru</h1>
                <p class="login-subtitle">Buat akun untuk mulai berbagi pengetahuan</p>
            </div>

            <form id="registerForm" action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-input"
                        placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-input"
                        placeholder="Masukkan username" value="{{ old('username') }}" required>
                    <div id="usernameFeedback" class="username-feedback" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input"
                        placeholder="Masukkan email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Masukkan password (min. 6 karakter)" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength-bar">
                        <div class="password-strength-fill" id="strengthBar"></div>
                    </div>
                    <div class="password-strength-text" id="strengthText"></div>

                    <div class="password-requirements" id="passwordRequirements" style="display: none;">
                        <div class="requirement-item" id="req-length">
                            <span class="requirement-icon unmet"></span>
                            <span>Minimal 6 karakter</span>
                        </div>
                        <div class="requirement-item" id="req-number">
                            <span class="requirement-icon unmet"></span>
                            <span>Mengandung angka</span>
                        </div>
                        <div class="requirement-item" id="req-letter">
                            <span class="requirement-icon unmet"></span>
                            <span>Mengandung huruf</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            placeholder="Masukkan ulang password" required>
                        <button type="button" class="toggle-password" id="togglePasswordConfirm">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div id="passwordMatchIndicator" class="password-match-indicator" style="display: none;"></div>
                </div>

                <div class="remember-me">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="terms" class="checkbox" name="terms" required>
                        <label for="terms" class="checkbox-custom"></label>
                    </div>
                    <label for="terms" class="remember-label">Saya setuju dengan syarat dan ketentuan</label>
                </div>

                <button type="submit" class="login-button" id="registerBtn">
                    <span class="button-text">Daftar Sekarang</span>
                    <div class="loading"></div>
                </button>

                <div style="text-align: center; margin-top: 20px;">
                    <span style="color: var(--text-light); font-size: 14px;">Sudah punya akun? </span>
                    <a href="{{ route('login') }}" style="color: var(--primary-orange); text-decoration: none; font-weight: 600;">Login di sini</a>
                </div>
            </form>
        </div>
    </center>

    <script>
        // Toggle password visibility with smooth animation
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
        });

        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirmInput = document.getElementById('password_confirmation');

        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
        });

        // Username validation with feedback
        const usernameInput = document.getElementById('username');
        const usernameFeedback = document.getElementById('usernameFeedback');
        let usernameTimeout;

        usernameInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, '');

            clearTimeout(usernameTimeout);

            if (this.value.length === 0) {
                usernameFeedback.style.display = 'none';
                return;
            }

            if (this.value.length < 3) {
                usernameFeedback.style.display = 'flex';
                usernameFeedback.className = 'username-feedback feedback-error';
                usernameFeedback.innerHTML = '<span class="icon-cross">✕</span> Username minimal 3 karakter';
                return;
            }

            usernameFeedback.style.display = 'flex';
            usernameFeedback.className = 'username-feedback feedback-info';
            usernameFeedback.innerHTML = '<span>⏳</span> Mengecek ketersediaan...';

            usernameTimeout = setTimeout(() => {
                usernameFeedback.className = 'username-feedback feedback-success';
                usernameFeedback.innerHTML = '<span class="icon-check">✓</span> Username tersedia';
            }, 800);
        });

        // Enhanced password strength checker
        const passwordField = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const passwordRequirements = document.getElementById('passwordRequirements');

        passwordField.addEventListener('focus', function() {
            passwordRequirements.style.display = 'block';
        });

        passwordField.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Check requirements
            const hasLength = password.length >= 6;
            const hasNumber = /\d/.test(password);
            const hasLetter = /[a-zA-Z]/.test(password);

            // Update requirement indicators
            updateRequirement('req-length', hasLength);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-letter', hasLetter);

            // Calculate strength
            if (hasLength) strength++;
            if (hasNumber) strength++;
            if (hasLetter) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[!@#$%^&*]/.test(password)) strength++;

            // Reset classes first
            strengthBar.parentElement.className = 'password-strength-bar';

            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.style.background = 'transparent';
                strengthText.textContent = '';
                strengthText.className = 'password-strength-text';
            } else if (strength <= 2) {
                strengthBar.parentElement.classList.add('strength-weak');
                strengthText.innerHTML = '<span style="font-size: 16px;">🔒</span> Lemah';
                strengthText.className = 'password-strength-text text-weak';
            } else if (strength <= 4) {
                strengthBar.parentElement.classList.add('strength-medium');
                strengthText.innerHTML = '<span style="font-size: 16px;">🔐</span> Sedang';
                strengthText.className = 'password-strength-text text-medium';
            } else {
                strengthBar.parentElement.classList.add('strength-strong');
                strengthText.innerHTML = '<span style="font-size: 16px;">🔐</span> Kuat';
                strengthText.className = 'password-strength-text text-strong';
            }
        });

        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            const icon = element.querySelector('.requirement-icon');

            if (met) {
                element.classList.add('requirement-met');
                icon.classList.remove('unmet');
                icon.classList.add('met');
                icon.innerHTML = '✓';
                element.style.transform = 'translateX(0)';
            } else {
                element.classList.remove('requirement-met');
                icon.classList.remove('met');
                icon.classList.add('unmet');
                icon.innerHTML = '';
                element.style.transform = 'translateX(0)';
            }
        }

        // Password confirmation match checker
        const passwordMatchIndicator = document.getElementById('passwordMatchIndicator');

        passwordConfirmInput.addEventListener('input', function() {
            const password = passwordField.value;
            const confirm = this.value;

            if (confirm.length === 0) {
                passwordMatchIndicator.style.display = 'none';
                return;
            }

            passwordMatchIndicator.style.display = 'flex';

            if (password === confirm) {
                passwordMatchIndicator.className = 'password-match-indicator match-success';
                passwordMatchIndicator.innerHTML = '<span class="icon-check">✓</span> Password cocok';
            } else {
                passwordMatchIndicator.className = 'password-match-indicator match-error';
                passwordMatchIndicator.innerHTML = '<span class="icon-cross">✕</span> Password tidak cocok';
            }
        });

        // Form submission with loading state
        const registerForm = document.getElementById('registerForm');
        const registerBtn = document.getElementById('registerBtn');

        registerForm.addEventListener('submit', function(e) {
            registerBtn.classList.add('loading');
            registerBtn.disabled = true;
        });
    </script>
@endsection
