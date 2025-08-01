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
                        <span class="article-views">Dilihat {{ $artikel->views }}</span>
                    </div>
                    <div class="article-author-info">
                        <div class="author-avatar-container">
                            <img src="{{ asset('storage/user-img/default-user.jpg') }}" class="author-avatar"
                                alt="Profile">
                        </div>
                        <div class="col-md-9">
                            <div class="author-detail">
                                <p class="author-name">{{ $artikel->user->nama }}</p>
                                <p class="author-stats">15.3k</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="author-detail">
                                <a href="{{ Route('repo.detail',$artikel->repositori->id) }}" class="btn btn-primary" style="width: 100%">Lihat Repositori Artikel</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Article Content -->
                <div class="article-content mt-4">
                    <p>{!! $artikel->isi !!}</p>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection
