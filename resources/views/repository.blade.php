@extends('components.app')

@section('body')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="article-header">
                <h2 class="mb-0">Repository</h2>
            </div>
            <p class="article-count">24,214,773 repository</p>
        </div>
    </div>

    <!-- Repository List -->
    <div class="row">
        <div class="col-12">
            @for ($i = 0; $i < 10; $i++)
            <a href="{{ route('article.detail', ['id' => $i+1]) }}" class="text-decoration-none text-dark">
                <div class="article-card card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="view-count"><i class="bi bi-eye"></i> 124</span>
                                    <h5 class="card-title mb-0">Cara Handle Laravel Error 500</h5>
                                </div>
                                <p class="card-text text-muted small">
                                    Disini akan dijelaskan bagaimana cara handle error 500<br>
                                    pada Laravel dengan mudah<br>
                                    dan cara handle nya
                                </p>
                            </div>
                            <div class="col-md-3">
                                <div class="article-meta">
                                    <p class="article-date">Senin/Jul 20, 2025 at 22:27</p>
                                    <div class="article-author">
                                        <img src="{{ asset('storage/user-img/default-user.jpg') }}" class="author-avatar" alt="Profile">
                                        <div class="author-info">
                                            <p class="author-name">Mas Agung</p>
                                            <p class="author-stats">15.3k</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endfor
        </div>
    </div>

    <!-- Pagination if needed -->
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection