@extends('admin.layouts.app')

@section('content')

    <head>
        <link rel="stylesheet" href="{{ asset('admin/css/user.css') }}">
    </head>

    <div class="content" :class="darkMode ? 'table-dark' : 'table-light'">
        <!-- Tombol Kontrol -->
        <div class="table-controls mb-3">
            <a href="{{ route('admin.user.create') }}" class="btn" onclick="addUser()">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>

        <!-- Tabel -->
        <table id="userTable" class="display w-full border-collapse">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Pengguna</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead> 
            <tbody>
                @foreach ($user as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="user-table flex items-center gap-3">
                                @if ($u->foto)
                                    <img src="{{ asset('storage/user-img/' . $u->foto) }}" alt="Avatar"
                                        class="avatar w-10 h-10 rounded-full object-cover">
                                @else
                                    <img src="{{ asset('storage/user-img/default-user.jpg') }}" alt="Avatar"
                                        class="avatar w-10 h-10 rounded-full object-cover">
                                @endif
                                <div class="user-details">
                                    {{ $u->nama }}
                                    <br>
                                    <span class="text-xs opacity-90">USER ID : {{ $u->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->created_at?->format('d M Y') ?? '-' }}</td>
                        <td>
                            <div class="action-buttons flex gap-2">
                                <a href="{{ Route('admin.user.edit', $u->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ Route('admin.user.softDelete', $u->id) }}" method="POST" id="form-delete-{{ $u->id }}">
                                    @csrf
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $u->id }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('admin/js/user.js') }}"></script>
@endsection
