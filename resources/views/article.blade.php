@extends('components.app')

@section('body')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="article-header">
                    <h2 class="mb-0">List Article</h2>
                    @if (Auth::check() && auth()->user()->role == 'user')
                        <div class="show-all-btn">
                            <a href="{{ route('article.create') }}" class="btn btn-orange text-white">Tambah Article</a>
                        </div>
                    @endif
                </div>
                <p class="article-count">{{ $totalArtikel }} Articles</p>
            </div>
        </div>

        <!-- Article List -->
        <div class="row">
            <div class="col-12">
                @foreach ($artikel as $art)
                    <a href="{{ route('article.detail', $art->id) }}" class="text-decoration-none text-dark">
                        <div class="article-card card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="view-count"><i class="bi bi-eye"></i>
                                                {{ $art->viewArtikel->count() }}</span>
                                            <h5 class="card-title mb-0">{{ $art->judul }}</h5>
                                        </div>
                                        <p class="card-text text-muted small">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($art->isi), 1000) }}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="article-meta">
                                            <p class="article-date">{{ $art->created_at }}</p>
                                            <div class="article-author">

                                                @if($art->user->foto)
                                                    <img src="{{ asset('storage/user-img/'.$art->user->foto) }}" alt="{{ $art->user->nama }}" class="author-avatar">
                                                @else
                                                <img src="{{ asset('storage/user-img/default-user.jpg') }}"
                                                    class="author-avatar" alt="Profile">
                                                @endif
                                                <div class="author-info">
                                                    <p class="author-name">{{ $art->user->nama }}</p>
                                                    <span
                                                        class="badge bg-secondary text-white text-wrapper-4 ">{{ $art->status }}</span>
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
                {{ $artikel->links('pagination::bootstrap-5') }}
            </ul>
        </nav>
    </div>
@endsection
