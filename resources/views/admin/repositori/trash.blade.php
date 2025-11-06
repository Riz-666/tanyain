@extends('admin.layouts.app')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('admin/css/trash.css') }}">
    @endpush
    <div class="container-fluid" :class="darkMode ? 'dark' : 'light'">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Card 1: Pengguna Terhapus -->
            <div class="col-lg-4 col-md-6">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon me-3" style="background: linear-gradient(45deg, #f48223, #e06d0a);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Pengguna Terhapus</h5>
                            <h3 class="mb-0 text-primary" id="user-count">{{ $trashedUsersCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Artikel Terhapus -->
            <div class="col-lg-4 col-md-6">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon me-3" style="background: linear-gradient(45deg, #28a745, #20c997);">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Artikel Terhapus</h5>
                            <h3 class="mb-0 text-success" id="artikel-count">{{ $trashedArticlesCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Repo Terhapus -->
            <div class="col-lg-4 col-md-6">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon me-3" style="background: linear-gradient(45deg, #6f42c1, #9b59b6);">
                            <i class="fas fa-code-branch"></i> <!-- atau icon repo lainnya -->
                        </div>
                        <div>
                            <h5 class="mb-1">Repo Terhapus</h5>
                            <h3 class="mb-0 text-info" id="repo-count">{{ $trashedReposCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables -->
        <div class="table-container">
            <!-- User Table -->
            <div id="user-table-container" class="table-responsive">
                @if ($data->isEmpty())
                    <div class="alert alert-info text-center">Sampah Masih Kosong</div>
                @else
                    <table class="table table-hover" id="userTable">
                        <thead>
                            <tr>
                                <th>No. </th>
                                <th>Judul Repository</th>
                                <th>Pembuat Repository</th>
                                <th>Status</th>
                                <th>Tanggal Dihapus</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="user-table flex items-center gap-3">
                                            <div class="user-details">
                                                {{ Str::limit($item->judul_repo, 20) }}
                                                <br>
                                                <span class="text-xs opacity-90">REPOSITORY ID : {{ $item->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-table flex items-center gap-3">
                                            @if ($item->foto)
                                                <img src="{{ asset('storage/user-img/' . $item->foto) }}" alt="Avatar"
                                                    class="avatar w-10 h-10 rounded-full object-cover">
                                            @else
                                                <img src="{{ asset('storage/user-img/default-user.jpg') }}" alt="Avatar"
                                                    class="avatar w-10 h-10 rounded-full object-cover">
                                            @endif
                                            <div class="user-details">
                                                {{ $item->userTrash->nama ?? 'Pengguna Di Non-Aktifkan' }}
                                                <br>
                                                <span class="text-xs opacity-90">USER ID : {{ $item->id ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->status == "publik")
                                                <span class="badge bg-secondary text-white">{{ $item->status  }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ $item->status  }}</span>
                                            @endif
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $item->deleted_at }}</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <form action="{{ route('repo.trash.restore', $item->id) }}" method="post"
                                                class="d-inline" id="form-restore-alert-{{ $item->id }}">
                                                @csrf
                                                <button data-id-alert-restore-="{{ $item->id }}" class="btn btn-restore"><i
                                                        class="fas fa-undo me-1"></i>Pulihkan</button>
                                            </form>
                                            <form action="{{ route('repo.trash.forceDelete', $item->id) }}" method="post"
                                                class="d-inline" id="form-delete-alert-{{ $item->id }}">
                                                @csrf
                                                <button class="btn btn-delete-permanent" data-id-alert-="{{ $item->id }}"><i
                                                        class="fas fa-trash-alt me-1"></i>Hapus Permanen</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        @push('script')
            <script src="{{ asset('admin/js/trash.js') }}"></script>
        @endpush
    @endsection
