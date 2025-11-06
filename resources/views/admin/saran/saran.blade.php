@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/saran/saran.css') }}">
    @endpush
    <div class="content-container" :class="{ 'dark': darkMode }">
        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number">{{ $totalSaran }}</div>
                <div class="stat-label">Total Pesan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $terbaru }}</div>
                <div class="stat-label">Pesan Baru</div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="feedback-list">
            <div class="list-header">
                <h3 class="list-title">
                    <i class="fas fa-inbox"></i>
                    Daftar Pesan Masuk
                </h3>
            </div>

            <!-- Feedback Item 1 -->
            @foreach ($saran as $s)
                <div class="feedback-item">
                    <div class="feedback-content">
                        <div class="feedback-main">
                            <div class="user-info-saran">
                                <img src="{{ asset('storage/user-img/default-user.jpg') }}" alt=""
                                    style="width: 60px">
                                <div class="user-details">
                                    <h5 class="user-name">
                                        {{ $s->user?->nama ?? $s->nama }}
                                    </h5>
                                    <p class="feedback-date">{{ $s->created_at->translatedFormat('d F Y') }} WIB</p>
                                </div>
                            </div>
                            <div class="feedback-message">
                                {{ $s->pesan }}
                            </div>
                        </div>
                        <div class="feedback-actions">
                            <div class="dropdown">
                                <button class="settings-btn" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <form action="{{ Route('admin.saran.destroy', $s->id) }}" method="POST">
                                        @csrf
                                        <li>
                                            <button class="dropdown-item delete-item"><i class="fas fa-trash"></i>
                                                Hapus Pesan
                                            </button>
                                        </li>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


            <div class="pagination-container" style="margin-right: 20px">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{ $saran->links('pagination::bootstrap-5') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
