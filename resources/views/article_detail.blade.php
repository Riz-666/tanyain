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
                        <div class="author-detail">
                            <p class="author-name">{{ $artikel->user->nama }}</p>
                            <p class="author-stats">15.3k</p>
                        </div>
                    </div>
                </div>
                <!-- Article Content -->
                <div class="article-content mt-4">
                    <p>{!! $artikel->isi !!}</p>

                    <!-- PDF Viewer -->

                    @if ($artikel->repositori->isNotEmpty())
                        <embed src="{{ asset('storage/repositori/' . $artikel->repositori->first()->nama_file) }}"
                            type="application/pdf" width="100%" height="1000px" />
                    @else
                        <p>Tidak ada file repositori untuk artikel ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
@endsection
