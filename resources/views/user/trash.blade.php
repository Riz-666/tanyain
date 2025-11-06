@extends('layouts.app')

@section('title', 'Sampah - Manajemen Pengetahuan SPBE Kota Bogor')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/trash.css') }}">

    <!-- DataTables CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/1.13.4/dataTables.bootstrap5.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid main-container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">


                <!-- Statistics -->
                <div class="stats-container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-item">
                                <div class="stat-icon bg-light-orange">
                                    <i class="fas fa-trash-alt text-orange"></i>
                                </div>
                                <div class="stat-number">{{ number_format($totalSampah) }}</div>
                                <div class="stat-label">Total Sampah</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-item">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                                <div class="stat-number">{{ number_format($willExpireCount) }}</div>
                                <div class="stat-label">Akan Kadaluarsa</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-item">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-calendar-check text-success"></i>
                                </div>
                                <div class="stat-number">{{ number_format($deletedTodayCount) }}</div>
                                <div class="stat-label">Dihapus Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main Content -->
                <div class="content-card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-list me-2 text-orange"></i>
                                    Daftar File Sampah
                                </h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-outline-orange" onclick="location.reload()">
                                    <i class="fas fa-sync-alt me-2"></i>
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Bulk Actions -->
                        <div id="bulkActions" class="bulk-actions">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <strong id="selectedCount">0</strong> file dipilih
                                </div>
                                <div class="col-md-6 text-end">
                                    <button class="btn btn-success me-2" onclick="restoreSelected()">
                                        <i class="fas fa-undo me-2"></i>
                                        Pulihkan Terpilih
                                    </button>
                                    <button class="btn btn-danger" onclick="deleteSelected()">
                                        <i class="fas fa-trash me-2"></i>
                                        Hapus Permanen
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="table-container" style="width: 95%; margin: 0 auto;">
                            <div class="table-responsive">
                                <table id="trashTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                <input type="checkbox" class="form-check-input custom-checkbox"
                                                    id="selectAll">
                                            </th>
                                            <th width="15%">Tipe</th>
                                            <th width="30%">Judul</th>
                                            <th width="15%">Dihapus Pada</th>
                                            <th width="15%">Batas Restore</th>
                                            <th width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trashItems as $item)
                                            <tr>
                                                <td>
                                                    <input type="checkbox"
                                                        class="form-check-input custom-checkbox row-checkbox"
                                                        data-id="{{ $item->id }}"
                                                        data-type="{{ strtolower($item->tipe) }}">
                                                </td>
                                                <td>

                                                    @if ($item->tipe == 'Artikel')
                                                        <span class="file-type-badge type-{{ strtolower($item->tipe) }}">
                                                            <i class="fas fa-file-alt"></i> ARTIKEL
                                                        </span>
                                                    @elseif($item->tipe == 'Repositori')
                                                        <span class="file-type-badge-repo type-{{ strtolower($item->tipe) }}">
                                                            <i class="fas fa-folder-open"></i> REPO
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $item->judul }}</div>
                                                    @if ($item->tipe == 'Repositori' && !empty($item->ukuran))
                                                        <small
                                                            class="text-muted">{{ number_format($item->ukuran / 1024, 1) }}
                                                            KB</small>
                                                    @else
                                                        <small class="text-muted">-</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="date-info">
                                                        {{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y') }}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($item->deleted_at)->format('H:i') }}
                                                        WIB</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $restoreDeadline = \Carbon\Carbon::parse(
                                                            $item->deleted_at,
                                                        )->addDays(30);
                                                        $daysLeft = $restoreDeadline->diffInDays(\Carbon\Carbon::now());
                                                    @endphp
                                                    @if ($daysLeft <= 0)
                                                        <div class="expired-warning">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Kadaluarsa
                                                        </div>
                                                    @elseif($daysLeft == 1)
                                                        <div class="date-info text-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Besok
                                                        </div>
                                                    @else
                                                        <div class="date-info text-success">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ $daysLeft }} hari lagi
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-success btn-sm me-1"
                                                        onclick="restoreFile({{ $item->id }})" title="Pulihkan">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="permanentDelete({{ $item->id }})"
                                                        title="Hapus Permanen">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (WAJIB DIPASANG DULU!) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net/1.13.4/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/1.13.4/dataTables.bootstrap5.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#trashTable').DataTable({
                pageLength: 25,
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                },
                order: [
                    [3, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                }]
            });
        });

        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                if (this.checked) {
                    checkbox.closest('tr').classList.add('selected');
                } else {
                    checkbox.closest('tr').classList.remove('selected');
                }
            });
            updateBulkActions();
        });

        // Individual checkbox handlers
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('row-checkbox')) {
                if (e.target.checked) {
                    e.target.closest('tr').classList.add('selected');
                } else {
                    e.target.closest('tr').classList.remove('selected');
                }
                updateBulkActions();

                const allCheckboxes = document.querySelectorAll('.row-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
                document.getElementById('selectAll').checked = allCheckboxes.length === checkedCheckboxes.length;
            }
        });

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            if (checkedBoxes.length > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        // ✅ Fungsi restoreFile(id) - TETAP PAKAI ROUTE LAMA (untuk single click)
        function restoreFile(id) {
            Swal.fire({
                title: 'Konfirmasi Pulihkan',
                text: 'Apakah Anda yakin ingin memulihkan file ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Pulihkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const checkbox = document.querySelector(`.row-checkbox[data-id="${id}"]`);
                    const type = checkbox.dataset.type;

                    let url, method;
                    if (type === 'artikel') {
                        url = `/artikel/restore/${id}`;
                        method = 'POST';
                    } else if (type === 'repositori') {
                        url = `/restore-repo/${id}`;
                        method = 'POST';
                    }

                    $.ajax({
                        url: url,
                        type: method,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'File berhasil dipulihkan!',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            location.reload();
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                            Swal.fire({
                                title: 'Gagal!',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        }

        // ✅ Fungsi permanentDelete(id) - TETAP PAKAI ROUTE LAMA (untuk single click)
        function permanentDelete(id) {
            Swal.fire({
                title: 'PERHATIAN!',
                text: 'File akan dihapus secara permanen dan tidak dapat dikembalikan. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus Permanen',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const checkbox = document.querySelector(`.row-checkbox[data-id="${id}"]`);
                    const type = checkbox.dataset.type;

                    let url, method;
                    if (type === 'artikel') {
                        url = `/artikel/force-delete/${id}`;
                        method = 'POST';
                    } else if (type === 'repositori') {
                        url = `/force-delete-repo/${id}`;
                        method = 'POST';
                    }

                    $.ajax({
                        url: url,
                        type: method,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'File berhasil dihapus permanen!',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            location.reload();
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                            Swal.fire({
                                title: 'Gagal!',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        }

        // ✅ Fungsi restoreSelected() - GUNAKAN ROUTE BARU (bulk)
        function restoreSelected() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Pilih file yang ingin dipulihkan terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            const ids = Array.from(checkedBoxes).map(cb => cb.dataset.id); // ← SEMUA ID, TANPA FILTER

            Swal.fire({
                title: 'Konfirmasi Pulihkan',
                text: `Apakah Anda yakin ingin memulihkan ${ids.length} file yang dipilih?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Pulihkan Semua',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('trash.bulk.restore') }}',
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let message = response.message;
                            if (response.errors && response.errors.length > 0) {
                                message += "\n\nCatatan: " + response.errors.length +
                                    " item mungkin sudah dipulihkan sebelumnya atau tidak ada di sampah.";
                            }

                            Swal.fire({
                                title: 'Berhasil!',
                                text: message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            location.reload();
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                            const errors = xhr.responseJSON?.errors || [];

                            let fullMsg = msg;
                            if (errors.length > 0) {
                                fullMsg += "\n\nDetail:\n" + errors.join("\n");
                            }

                            Swal.fire({
                                title: 'Gagal!',
                                text: fullMsg,
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        }

        // ✅ Fungsi deleteSelected() - GUNAKAN ROUTE BARU (bulk)
        function deleteSelected() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Pilih file yang ingin dihapus terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            const ids = Array.from(checkedBoxes).map(cb => cb.dataset.id); // ← SEMUA ID, TANPA FILTER

            Swal.fire({
                title: 'PERHATIAN!',
                text: `File akan dihapus secara permanen dan tidak dapat dikembalikan. Lanjutkan?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus Permanen Semua',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('trash.bulk.delete') }}',
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let message = response.message;
                            if (response.errors && response.errors.length > 0) {
                                message += "\n\nCatatan: " + response.errors.length +
                                    " item mungkin sudah dihapus sebelumnya.";
                            }

                            Swal.fire({
                                title: 'Berhasil!',
                                text: message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            location.reload();
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                            const errors = xhr.responseJSON?.errors || [];

                            let fullMsg = msg;
                            if (errors.length > 0) {
                                fullMsg += "\n\nDetail:\n" + errors.join("\n");
                            }

                            Swal.fire({
                                title: 'Gagal!',
                                text: fullMsg,
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
