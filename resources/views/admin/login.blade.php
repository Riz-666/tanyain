@extends('layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/admin-login.css') }}">
    @endpush
    <center>
        @if ($errors->any())
            <div class="container" style="width:24%;">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <script>
                // Auto hide alert merah setelah 5 detik
                setTimeout(() => {
                    let alert = document.getElementById('error-alert');
                    if (alert) {
                        alert.classList.remove('show');
                        alert.classList.add('hide');
                    }
                }, 5000);
            </script>
        @endif
        <div class="login-container mt-6" style="margin-top: 60px">
            @if (session('lockout_seconds'))
                <div id="lockout-message">
                    Coba lagi dalam
                    <span id="countdown">{{ session('lockout_seconds') }}</span> detik.
                </div>

                <script>
                    let countdownEl = document.getElementById('countdown');
                    let timeLeft = parseInt(countdownEl.innerText);

                    let timer = setInterval(() => {
                        timeLeft--;
                        countdownEl.innerText = timeLeft;
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            document.getElementById('lockout-message').innerText = "Kamu bisa mencoba login lagi.";
                        }
                    }, 1000);
                </script>
            @endif


            <div class="logo">
                <h1>Admin Panel</h1>
            </div>

            <form action="{{ Route('admin.auth') }}" id="loginForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Username atau Email *</label>
                    <div class="input-focus-effect">
                        <input type="text" name="email" class="form-control"
                            placeholder="Masukkan username atau email">
                    </div>
                </div>

                <div class="form-group" style="position: relative;">
                    <label for="password">Password *</label>
                    <div class="input-focus-effect">
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                    </div>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" id="remember">
                        <span>Ingat saya</span>
                    </label>
                </div>
                <div class="form-group mt-3">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
                <button type="submit" class="login-btn" id="loginButton">
                    Masuk ke Dashboard
                </button>
            </form>
        </div>
    </center>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
