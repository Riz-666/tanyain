@extends('components.app')
@section('body')
    <header class="header">
        @if (Auth::check() && Auth::user()->role === 'user')
            <h1 class="text-wrapper">Wellcome, {{ Auth::user()->nama }}</h1>
            <p class="div">Tuntaskan Tugasmu Dengan Mudah di Platform TanyaIn</p>
            <a href="{{ Route('article') }}" class="button btn btn-light" role="button">
                <span class="text-wrapper-2">JELAJAHI ARTIKEL</span>
            </a>
        @else
            <h1 class="text-wrapper">TanyaIn</h1>
            <p class="div">PLATFORM BLOG DAN ARTIKEL AKADEMIK BERBASIS PENCARIAN</p>
            <a href="{{ Route('article') }}" class="button btn btn-light" role="button">
                <span class="text-wrapper-2">JELAJAHI ARTIKEL</span>
            </a>
        @endif
    </header>

    <main class="frame mt-5">
        <div class="mb-4">
            <form method="GET" action="{{ route('index') }}">
                <select name="filter" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
                    <option value="" {{ $filter == '' ? 'selected' : '' }}>Semua</option>
                    <option value="artikel" {{ $filter == 'artikel' ? 'selected' : '' }}>Artikel</option>
                    <option value="repositori" {{ $filter == 'repositori' ? 'selected' : '' }}>Repositori</option>
                </select>
            </form>
        </div>
        @if ($items->count() > 0)
            @foreach ($items as $item)
                <a href="{{ $item['type'] === 'artikel' ? route('article.detail', $item['id']) : '#' }}"
                    style="width: 100%">
                    <article class="background-border">
                        <h2 class="link-a-better">{{ $item['judul'] }}</h2>
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
                            <small>{!! strip_tags($item['isi']) !!}</small>
                        </div>
                        <div class="attachment">
                            @if ($item['type'] === 'repositori')
                                <i class="fa-regular fa-folder-open fa-2x vector"></i>
                                <span class="text-wrapper-2">Type : Repositori</span>
                            @else
                                <i class="fa-regular fa-file fa-2x vector"></i>
                                <span class="text-wrapper-2">Type : Artikel</span>
                            @endif
                        </div>
                        <footer class="background">
                            <p class="p">dibuat {{ $item['created_at'] }}</p>
                            <div class="author-info">
                                <img class="topchef-s-user"
                                    src="{{ asset($item['user']->foto ? 'storage/user-img/' . $item['user']->id : 'storage/user-img/default-user.jpg') }}"
                                    alt="{{ $item['user']->nama }}" />
                                <div class="author-details">
                                    <span class="text-wrapper-3">{{ $item['user']->nama }}</span>
                                    <span class="text-wrapper-4 ">{{ ucfirst($item['type']) }}</span>
                                </div>
                            </div>
                        </footer>
                    </article>
                </a>
            @endforeach
        @else
            <p class="text-muted">Belum ada konten tersedia.</p>
        @endif
    </main>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            {{ $items->links('pagination::bootstrap-5') }}
        </ul>
    </nav>
@endsection
