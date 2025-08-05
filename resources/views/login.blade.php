@extends('components.app')

@section('body')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow" style="width: 400px;">
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
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="text-orange">Login</h3>
                </div>

                <form method="POST" action="{{ route('auth') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label">Password</label>

                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password">

                        </div>
                    </div>

                    <div class="button d-grid gap-2 mt-4">
                        <button class="btn btn-login btn-lg">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
@endsection
