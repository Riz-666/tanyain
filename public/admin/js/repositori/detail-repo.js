$(document).ready(function () {
    $("#repo-files").DataTable({
        language: {
            processing: "Sedang memproses...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
            infoFiltered: "(disaring dari _MAX_ total entri)",
            loadingRecords: "Memuat...",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            emptyTable: "Tidak ada data tersedia di tabel",
            paginate: {
                first: "Pertama",
                previous: "Sebelumnya",
                next: "Berikutnya",
                last: "Terakhir",
            },
            aria: {
                sortAscending: ": aktifkan untuk mengurutkan kolom secara naik",
                sortDescending:
                    ": aktifkan untuk mengurutkan kolom secara turun",
            },
        },
    });
});

// File: public/admin/js/repositori/detail-repo.js

// Simple version - langsung tanpa wrapper function
document.addEventListener("DOMContentLoaded", function () {
    // Handle file deletion
    const deleteButtons = document.querySelectorAll(
        ".btn-delete-file-permanent"
    );

    deleteButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            // Fix: getAttribute tanpa trailing dash
            const id = this.getAttribute("data-id-alert");

            if (!id) {
                console.error("File ID not found in data-id-alert attribute");
                return;
            }

            Swal.fire({
                title: "Hapus File?",
                text: "File yang dihapus tidak bisa dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Coba cari form desktop atau mobile
                    const desktopForm = document.getElementById(
                        "form-delete-file-alert-" + id
                    );
                    const mobileForm = document.getElementById(
                        "form-delete-file-alert-mobile-" + id
                    );

                    const form = desktopForm || mobileForm;

                    if (form) {
                        console.log("Submitting form for file ID:", id);
                        form.submit();
                    } else {
                        console.error("Form not found for file ID:", id);
                        console.log(
                            "Looking for forms:",
                            "form-delete-file-alert-" + id,
                            "form-delete-file-alert-mobile-" + id
                        );
                        Swal.fire("Error!", "Form tidak ditemukan", "error");
                    }
                }
            });
        });
    });

    // Handle repository deletion
    const repoDeleteButton = document.querySelector(".btn-file-delete.danger");
    if (repoDeleteButton) {
        repoDeleteButton.addEventListener("click", function (e) {
            e.preventDefault();

            const repoId = this.getAttribute("data-id");

            if (!repoId) {
                console.error("Repository ID not found");
                return;
            }

            Swal.fire({
                title: "Hapus Repositori?",
                text: "Repositori dan semua file di dalamnya akan dihapus permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(
                        "form-delete-" + repoId
                    );
                    if (form) {
                        form.submit();
                    } else {
                        console.error(
                            "Repository form not found for ID:",
                            repoId
                        );
                        Swal.fire("Error!", "Form tidak ditemukan", "error");
                    }
                }
            });
        });
    }
});

// Preview File Handler
document.addEventListener("DOMContentLoaded", function () {
    const viewButtons = document.querySelectorAll(".view-file[data-file-id]");
    const mediaModal = new bootstrap.Modal(
        document.getElementById("mediaPreviewModal")
    );
    const previewImage = document.getElementById("previewImage");
    const previewVideo = document.getElementById("previewVideo");
    const videoSource = document.getElementById("videoSource");

    viewButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const fileId = this.getAttribute("data-file-id");
            const ext = this.getAttribute("data-file-ext");
            const fileUrl = this.getAttribute("data-file-url");

            if (!fileId || !ext || !fileUrl) {
                console.error("Missing required data attributes");
                return;
            }

            // Handle PDF
            if (ext === "pdf") {
                window.open(fileUrl, "_blank");
            }
            // Handle Images
            else if (
                ["png", "jpg", "jpeg", "gif", "bmp", "webp"].includes(
                    ext.toLowerCase()
                )
            ) {
                previewImage.src = fileUrl;
                previewImage.style.display = "block";
                previewVideo.style.display = "none";
                previewVideo.pause();
                mediaModal.show();
            }
            // Handle Video
            else if (["mp4", "webm", "ogg"].includes(ext.toLowerCase())) {
                videoSource.src = fileUrl;
                videoSource.type = `video/${ext.toLowerCase()}`;
                previewVideo.load();
                previewVideo.style.display = "block";
                previewImage.style.display = "none";
                mediaModal.show();
            }
        });
    });

    // Reset media when modal closed
    document
        .getElementById("mediaPreviewModal")
        .addEventListener("hidden.bs.modal", function () {
            previewVideo.pause();
            previewVideo.currentTime = 0;
            videoSource.src = "";
            previewImage.src = "";
        });
});
