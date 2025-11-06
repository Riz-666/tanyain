@extends('layouts.app')
@section('title', 'Login - Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/login.css') }}">
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
            @if(session('login'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('login') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="login-header">
                <h1 class="login-title">Selamat Datang</h1>
                <p class="login-subtitle">Harap Login Terlebih Dahulu</p>
            </div>

            <form id="loginForm" action="{{ route('auth') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="userInput" class="form-label">Username Atau Email</label>
                    <input type="text" id="userInput" name="email" class="form-input"
                        placeholder="Masukan username Atau email" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Masukan password" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-me">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" class="checkbox" name="remember">
                        <label for="remember" class="checkbox-custom"></label>
                    </div>
                    <label for="remember" class="remember-label">Remember me</label>
                </div>

                <button type="submit" class="login-button" id="loginBtn">
                    <span class="button-text">Sign In</span>
                    <div class="loading"></div>
                </button>
            </form>
        </div>
    </center>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.innerHTML = type === 'password' ?
                '<i class="fa fa-eye"></i>' :
                '<i class="fa fa-eye-slash"></i>';
        });

        // Form submission (JS hanya untuk loading state, tidak mencegah submit default)
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');

        loginForm.addEventListener('submit', function(e) {
            // Show loading state
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;

            // Get form data
            const formData = new FormData(loginForm);
            const email = formData.get('email'); // ← SUDAH BENAR: name="email"
            const password = formData.get('password');
            const remember = formData.get('remember');

            // Simulate login process (untuk UX saja, real login di-handle oleh Laravel)
            setTimeout(() => {
                loginBtn.classList.remove('loading');
                loginBtn.disabled = false;

                console.log('Login attempt:', { email, password, remember });
                // Jangan pakai alert() di produksi — ini hanya simulasi
            }, 2000);
        });

        // Forgot password
        document.getElementById('forgotPassword').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Forgot password functionality would be implemented here!');
        });

        // Social login buttons
        document.getElementById('googleLogin').addEventListener('click', function() {
            alert('Google login would be implemented here!');
        });

        document.getElementById('facebookLogin').addEventListener('click', function() {
            alert('Facebook login would be implemented here!');
        });

        // Auto-detect email vs username (UI only)
        const userInputField = document.getElementById('userInput');
        userInputField.addEventListener('input', function() {
            const value = this.value;
            const label = document.querySelector('label[for="userInput"]');

            if (value.includes('@')) {
                label.textContent = 'Email Address';
                this.setAttribute('type', 'email');
            } else {
                label.textContent = 'Username or Email';
                this.setAttribute('type', 'text');
            }
        });
    </script>
@endsection
