@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/recent.css') }}">
        <style>
            .activity-card pre.ql-syntax {
                max-width: 100%;
                max-height: 300px;
                /* misal 300px aja */
                overflow: auto;
                /* scroll horizontal & vertical */
                white-space: pre;
                word-break: normal;
                padding: 8px;
                border-radius: 6px;
            }
        </style>
    @endpush
    <div class="container" :class="darkMode ? 'dark' : 'light'">

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-buttons">
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}"
                    class="filter-btn {{ $filter == 'all' ? 'active' : '' }}" style="text-decoration: none;">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Semua Aktivitas
                </a>

                <a href="{{ request()->fullUrlWithQuery(['filter' => 'artikel']) }}"
                    class="filter-btn {{ $filter == 'artikel' ? 'active' : '' }}" style="text-decoration: none;">
                    <i class="fa fa-newspaper"></i>
                    Artikel
                </a>

                <a href="{{ request()->fullUrlWithQuery(['filter' => 'repositori']) }}"
                    class="filter-btn {{ $filter == 'repositori' ? 'active' : '' }}" style="text-decoration: none;">
                    <i class="fa fa-folder-open"></i>
                    Repositori
                </a>
            </div>
        </div>
        <!-- Search Section -->
        <div class="search-section mb-4">
            <form method="GET" action="{{ route('admin.aktivitas') }}" class="search-form">
                <input type="text" name="search" class="search-input" placeholder="Cari judul aktivitas..."
                    value="{{ request('search') }}">
                <button type="submit" class="search-btn">
                    <i class="fa fa-search"></i>
                </button>
                @if (request('search'))
                    <a href="{{ request()->fullUrlWithoutQuery(['search', 'page']) }}" class="search-clear-btn">
                        Clear
                    </a>
                @endif
            </form>
        </div>
        <!-- Activity Grid -->
        <div class="activity-grid" id="activityGrid">
            @foreach ($activities as $activity)
                <a href="{{ $activity['type'] === 'artikel'
                    ? route('admin.artikel.detail', $activity['id'])
                    : ($activity['type'] === 'repositori'
                        ? route('admin.repo.detail', $activity['id'])
                        : '#') }}"
                    style="text-decoration: none; color:inherit">
                    <div class="activity-card fade-in" data-type="{{ $activity['type'] }}">
                        <div class="card-header">
                            <div>
                                <div class="card-type {{ $activity['type'] }}">
                                    @if ($activity['type'] === 'artikel')
                                        <svg class="icon" viewBox="0 0 16 16" width="12" height="12">
                                            <path
                                                d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zm4 18H6V4h7v5h5v11z" />
                                        </svg>
                                        Artikel
                                    @else
                                        <svg class="icon" viewBox="0 0 16 16" width="12" height="12">
                                            <path d="M2 2h12v12H2z" />
                                        </svg>
                                        Repositori
                                    @endif
                                </div>
                                <span class="card-date">{{ $activity['created_at']->diffForHumans() }}</span>
                            </div>
                            <div class="author-info">
                                @if ($activity['author']['avatar'])
                                    <img src="{{ asset('storage/user-img/' . $activity['author']['avatar']) }}"
                                        class="author-avatar" alt="avatar">
                                @else
                                    <img src="{{ asset('storage/user-img/default-user.jpg') }}" class="author-avatar"
                                        alt="avatar">
                                @endif
                                <span
                                    class="author-name">{{ $activity['author']['name'] ?? 'Pengguna Di Non Aktifkan' }}</span>
                            </div>
                        </div>

                        <h3 class="card-title">{{ $activity['judul'] }}</h3>

                        @if ($activity['type'] === 'artikel')
                            <p class="card-description">
                                {{ Str::limit(strip_tags($activity['isi'] ?? ''), 120) }}
                            </p>
                            <div class="card-meta">
                                <div class="meta-info">
                                    <i class="fa fa-eye"></i>
                                    {{ $activity['views'] }} views
                                </div>
                                @if (empty($activity['tag']))
                                    <span class="status-badge">Tidak Ada Tag</span>
                                @else
                                    @foreach ($activity['tag'] as $tag)
                                        <span class="status-badge bg-secondary text-white">{{ $tag->nama_tag }}</span>
                                    @endforeach
                                @endif
                                <span class="status-badge status-{{ $activity['status'] }}">
                                    {{ ucfirst($activity['status']) }}
                                </span>
                            </div>
                        @else
                            <p class="card-description">
                                {!! Str::limit($activity['deskripsi'] ?? '', 120) !!}
                            </p>
                            <div class="card-meta">
                                @if ($activity['status'] == 'private')
                                    <span class="status-badge    status-{{ $activity['status'] }}">
                                        {{ ucfirst($activity['status']) }}
                                    </span>
                                @else
                                    <span class="status-badge-publik status-{{ $activity['status'] }}">
                                        {{ ucfirst($activity['status']) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
            @endforeach
        </div>
        </a>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $activities->appends(['filter' => $filter])->links('pagination::bootstrap-4') }}
        </div>
        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <svg class="empty-icon" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
            <h3 class="empty-title">Tidak ada aktivitas ditemukan</h3>
            <p class="empty-description">Belum ada aktivitas untuk kategori yang dipilih</p>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('admin/js/recent.js') }}"></script>
    @endpush
@endsection
