@extends('components.app')

@section('body')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="article-header">
                    <h2 class="mb-0">List Repository</h2>
                    @if (Auth::check() && auth()->user()->role == 'user')
                        <div class="show-all-btn">
                            <a href="{{ route('repo.create') }}" class="btn btn-orange text-white">Tambah Repositori</a>
                        </div>
                    @endif
                </div>
                <p class="article-count">{{ $totalRepo }} Repository</p>
            </div>
        </div>
        <!-- Article List -->
        <div class="row">
            <div class="col-12">
                @foreach ($repo as $r)
                    <a href="{{ route('repo.detail', $r->id) }}" class="text-decoration-none text-dark">
                        <div class="article-card card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="d-flex align-items-center mb-2">
                                            <h5 class="card-title mb-0">{{ $r->judul_repo }}</h5>
                                        </div>
                                        <p class="card-text text-muted small">
                                            {!! $r->deskripsi !!}
                                        </p>
                                        <p class="card-text text-muted small mt-2">
                                            {{ $r->file_repo_count }} Files Uploaded
                                        </p>
                                        <p class="card-text text-muted small">
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="article-meta">
                                            <p class="article-date">{{ $r->created_at }}</p>
                                            <div class="article-author">
                                                @if($r->user->foto)
                                                    <img src="{{ asset('storage/user-img/'.$r->user->foto) }}" alt="{{ $r->user->nama }}" class="author-avatar">
                                                @else
                                                <img src="{{ asset('storage/user-img/default-user.jpg') }}"
                                                    class="author-avatar" alt="Profile">
                                                @endif
                                                <div class="author-info">
                                                    <p class="author-name">{{ $r->user->nama }}</p>
                                                    <span
                                                        class="badge bg-secondary text-white text-wrapper-4 ">{{ $r->status }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Pagination if needed -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $repo->links('pagination::bootstrap-5') }}
            </ul>
        </nav>
    </div>
@endsection
