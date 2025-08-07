@extends('components.app')

@section('body')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <!-- Article Title and Info -->
                <div class="article-detail-header">
                    <h2 class="article-title">{{ $artikel->judul }}</h2>
                    <div class="article-info">
                        <span class="article-date">Dibuat {{ $artikel->created_at }}</span>
                        <span class="article-views">Dilihat {{ $artikel->viewArtikel->count() }}</span>
                    </div>
                    <div class="article-author-info">
                        <div class="author-avatar-container">
                            @if($artikel->user->foto)
                                <img src="{{ asset('storage/user-img/'.$artikel->user->foto) }}" class="author-avatar"
                                alt="Profile">
                            @else
                                <img src="{{ asset('storage/user-img/default-user.jpg') }}" class="author-avatar"
                                alt="Profile">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="author-detail">
                                <a href="{{ Route('profile', $artikel->user->id) }}">
                                    <p class="badge bg-secondary text-center mt-1 author-name">{{ $artikel->user->nama }}
                                    </p>
                                </a>
                                <p class="author-stats mt-1">Status Konten : {{ $artikel->status }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="author-detail">
                                <a href="{{ Route('repo.detail', $artikel->repositori->id) }}" class="btn btn-orange"
                                    style="width: 100%">Lihat Repositori Terkait</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Article Content -->
                <div class="article-content mt-4">
                    <p>{!! $artikel->isi !!}</p>
                </div>
                <hr>
                <div class="file-artikel">
                    @if($artikel->file)
                        <embed src="{{ asset('storage/artikel-file/' . $artikel->file) }}" type="application/pdf"
                        style="height: 1000px; width:100%">
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
