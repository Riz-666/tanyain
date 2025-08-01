@extends('components.app')

@section('body')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-0">Hasil Pencarian: "{{ $keyword }}"</h2>
            <p class="article-count mt-2">
                {{ $results->count() }} hasil ditemukan
            </p>
        </div>
    </div>
 
    @if ($results->count())
        <div class="row mt-4">
            @foreach ($results as $item)
                <div class="col-12">
                    <a href="{{ $item['link'] }}" class="text-decoration-none text-dark">
                        <div class="article-card card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="card-title mb-0">{{ $item['judul'] }}</h5>
                                    <span class="badge bg-primary">{{ $item['tipe'] }}</span>
                                </div>
                                <p class="card-text text-muted small">{{ $item['deskripsi'] }}</p>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Oleh: {{ $item['user'] }}</small>
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="text-muted">Tidak ada hasil yang cocok.</p>
            </div>
        </div>
    @endif
</div>
@endsection
