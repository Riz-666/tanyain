$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#userTable')) {
        $('#userTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            language: {
                sEmptyTable: "Tidak ada data yang tersedia",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                sLengthMenu: "Tampilkan _MENU_ entri",
                sLoadingRecords: "Sedang memuat...",
                sProcessing: "Sedang memproses...",
                sSearch: "Cari:",
                sZeroRecords: "Tidak ditemukan data yang sesuai",
                oPaginate: { 
                    sFirst: "Pertama",
                    sLast: "Terakhir",
                    sNext: "Selanjutnya",
                    sPrevious: "Sebelumnya",
                },
            },
            order: [[0, "asc"]],
            columnDefs: [{ targets: -1, orderable: false }]
        });
    }
});
