@extends('components.app')
@section('body')
    <header class="header">
        @if (Auth::check() && Auth::user()->role === 'user')
            <h1 class="text-wrapper">Wellcome, {{ Auth::user()->nama }}</h1>
            <p class="div">Tuntaskan Tugasmu Dengan Mudah di Platform TanyaIn</p>
            <a href="#" class="button btn btn-light" role="button">
                <span class="text-wrapper-2">JELAJAHI ARTIKEL</span>
            </a>
        @else
            <h1 class="text-wrapper">TanyaIn</h1>
            <p class="div">PLATFORM BLOG DAN ARTIKEL AKADEMIK BERBASIS PENCARIAN</p>
            <a href="#" class="button btn btn-light" role="button">
                <span class="text-wrapper-2">JELAJAHI ARTIKEL</span>
            </a>
        @endif
    </header>

    <main class="frame">
        <section class="listbox-choose-blog">
            <div class="overlap-group">
                <div class="border" aria-hidden="true"></div>
                <div class="option-all-blogs">
                    <div class="container">
                        <select class="text-wrapper" aria-label="Choose blog category">
                            <option selected>All blogs</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        @foreach ($artikel as $art)
            <a href="{{ route('article.detail', $art->id) }}" style="width: 100%">
                <article class="background-border">
                    <h2 class="link-a-better">{{ $art->judul }}</h2>
                    <div class="isi"
                        style="
                    width: 1022px;
                    height: 50px;
                    margin-top:50px;
                    color: #37474f;
                    font-size: 20px;
                    letter-spacing: 0.25px;
                    line-height: 21px;
                    width: 900px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    ">
                        <small>{!! strip_tags($art->isi) !!}</small>
                    </div>
                    <div class="attachment">
                        <i class="fa-regular fa-file fa-2x vector"></i>
                        @if ($art->repositori)
                            <span class="text-wrapper-2">{{ $art->repositori->first()->nama_file }}</span>
                        @else
                            <span class="text-wrapper-2">Tidak ada File</span>
                        @endif
                    </div>

                    <footer class="background">
                        <p class="p">dibuat {{ $art->created_at }}</p>
                        <div class="author-info">
                            <img class="topchef-s-user"
                                src="{{ asset($art->user->foto ? 'storage/user-img/' . $art->user_id : 'storage/user-img/default-user.jpg') }}"
                                alt="{{ $art->user->nama }}" />
                            <div class="author-details">
                                <span class="text-wrapper-3">{{ $art->user->nama }}</span>
                                <span class="text-wrapper-4 ">{{ $art->status }}</span>
                            </div>
                        </div>
                    </footer>
                </article>
            </a>
        @endforeach
    </main>
@endsection
