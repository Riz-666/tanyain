@extends('components.app')

@section('body')
    <div class="container mt-4">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <!-- Article Title and Info -->
                <div class="article-detail-header">
                    <h2 class="article-title">{{ $repo->judul_repo }}</h2>
                    <div class="article-info">
                        <span class="article-date">Dibuat {{ $repo->created_at }}</span>
                    </div>
                    <div class="article-author-info">
                        <div class="author-avatar-container">
                            <img src="{{ asset('storage/user-img/' . $repo->user->foto) }}" class="author-avatar"
                                alt="Profile">
                        </div>
                        <div class="author-detail">
                            <a href="{{ Route('profile', $repo->user->id) }}">
                                <p class="badge bg-secondary text-center mt-1 author-name">{{ $repo->user->nama }}</p>
                            </a>
                            <p class="author-stats">15.3k</p>
                        </div>
                    </div>
                </div>
                <!-- Article Content -->

                <div class="article-content mt-4">
                    <p>{!! $repo->deskripsi !!}</p>
                    <hr>
                    <table class="table table-hover" style="width: 100%;" border="1px" id="table1">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama File</th>
                                <th scope="col">Ekstensi File</th>
                                <th scope="col">Ukuran</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($repo->fileRepo as $r)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $r->nama_file }}</td>
                                    <td>{{ $r->ekstensi }}</td>
                                    <td>{{ $r->ukuran }} Kb</td>
                                    @php
                                        $ext = pathinfo($r->path, PATHINFO_EXTENSION);
                                        $ext = strtolower($ext); // biar lebih aman
                                    @endphp
                                    <td>
                                        @if ($ext === 'pdf')
                                            <a href="{{ route('file.pdf', $r->id) }}" class="btn btn-info btn-sm">Lihat
                                                Dokumen</a>
                                        @else
                                            <a href="{{ route('file.show', $r->id) }}"
                                                class="btn btn-info btn-sm">Unduh</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <div class="artikel-terkait">
                        <p style="font-size: large; font-style:oblique; color:#f48223;">Artikel Terkait : </p>
                        @if ($repo->artikel->isEmpty())
                            <p class="badge bg-info">Tidak Ada Artikel Terkait</p>
                        @endif
                        @foreach ($repo->artikel as $ra)
                            <br>
                            {{ $loop->iteration }}. <a href="{{ Route('article.detail', $ra->id) }}"
                                style="font-style: italic">{{ $ra->judul }}</a>
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
