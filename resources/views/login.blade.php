@extends('components.app')

@section('body')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow" style="width: 400px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h3 class="text-orange">Logo</h3>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label">Password</label>
                        <a href="#" class="text-decoration-none" style="color: #f48223; font-size: 0.9rem;">Forgot password?</a>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="input-group-text">
                            <i class="bi bi-eye-slash" id="togglePassword"></i>
                        </span>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-orange btn-lg">Log in</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
</script>
@endsection