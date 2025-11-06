@extends('layouts.app')
@section('title', 'Kebijakan Privasi - Manajemen Pengetahuan SPBE Kota Bogor')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">
@endsection
@section('content')
    <div class="container mt-6 body-saran" style="margin-top: 60px">
        <!-- Tentang Website -->
        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Kebijakan Privasi – Manajemen Pengetahuan SPBE Kota Bogor
            </h2>
            <div class="about-content">
                <p> Selamat datang di <strong>Manajemen Pengetahuan SPBE Kota Bogor</strong>, sebuah platform berbasis
                    web yang mendukung pengelolaan pengetahuan untuk mendukung implementasi SPBE.
                    Kebijakan Privasi ini mengatur mengenai kebijakan dan prosedur dalam mengumpulkan, menggunakan, dan
                    menjaga data yang diperoleh dari pengunjung (selanjutnya disebut “Anda” atau “Pengguna”) sehubungan
                    dengan penggunaan layanan yang disediakan situs domain MP-SPBE Kota Bogor beserta seluruh situs
                    sub-domain terkait (“Situs”).</p>

                <h3><i class="fas fa-id-card"></i> Data Pribadi Pengguna</h3>
                <p>Kami dapat mengumpulkan data non-pribadi ketika Pengguna berinteraksi dengan Situs. Data ini meliputi
                    nama browser, tipe perangkat, sistem operasi, data lokasi, alamat IP, penyedia layanan internet, serta
                    informasi lain terkait cara Pengguna terhubung dengan Situs.</p>

                <h3><i class="fas fa-cookie"></i> Cookies</h3>
                <p>Situs MP-SPBE Kota Bogor tidak menggunakan cookies secara eksplisit. Pengguna dapat mengatur browser
                    masing-masing untuk menolak cookies atau meminta konfirmasi sebelum menerimanya. Jika cookies ditolak,
                    sebagian layanan atau fitur mungkin tidak dapat digunakan sepenuhnya.</p>

                <h3><i class="fas fa-file-text"></i> Bagaimana Kami Menggunakan Data</h3>
                <p>Data pribadi maupun non-pribadi hanya digunakan sesuai dengan Kebijakan Privasi ini, kecuali jika
                    Pengguna memberikan persetujuan lain. Tujuan penggunaan data antara lain:</p>
                <ol>
                    <li>Menyediakan dan meningkatkan layanan, fitur, dan konten Situs.</li>
                    <li>Memenuhi permintaan dan kebutuhan Pengguna.</li>
                    <li>Memberikan pengalaman yang lebih personal.</li>
                    <li>Menyebarluaskan informasi publik yang relevan.</li>
                    <li>Mendukung implementasi kebijakan SPBE Kota Bogor.</li>
                    <li>Membantu pelayanan publik oleh instansi terkait.</li>
                    <li>Menjalankan kewajiban sesuai peraturan perundang-undangan.</li>
                    <li>Memberikan data kepada pihak berwenang jika diminta secara sah.</li>
                </ol>

                <h3><i class="fas fa-shield"></i> Perlindungan Data</h3>
                <p>Kami berkomitmen menjaga kerahasiaan dan keamanan data pribadi Pengguna dengan langkah-langkah wajar untuk mencegah akses tidak sah.</p>

                <h3><i class="fas fa-share-nodes"></i> Berbagi Data dengan Pihak Ketiga</h3>
                <p>Kami dapat bekerja sama dengan pihak ketiga (badan usaha, lembaga, yayasan, atau individu) untuk tujuan berikut:</p>
                <ul>
                    <li>Memfasilitasi layanan.</li>
                    <li>Menyediakan layanan atas nama kami.</li>
                    <li>Melaksanakan layanan terkait.</li>
                    <li>Menganalisis penggunaan layanan.</li>
                    <li>Mendukung kegiatan lain sesuai Kebijakan Privasi.</li>
                </ul>
                <p>Pihak ketiga tersebut hanya dapat menggunakan data sesuai dengan tujuan yang disetujui dan wajib menjaga kerahasiaannya.</p>

                <h3><i class="fas fa-history"></i> Perubahan Kebijakan Privasi</h3>
                <p>MP-SPBE Kota Bogor berhak mengubah atau memperbarui Kebijakan Privasi ini sewaktu-waktu. Jika ada perubahan signifikan, Pengguna akan diinformasikan. Dengan terus menggunakan Situs setelah perubahan diumumkan, Pengguna dianggap menerima perubahan tersebut.
                </p>

                <h3><i class="fas fa-circle-exclamation"></i> Perangkat Lunak & Ekstensi yang Dilarang</h3>
                <p>Untuk melindungi data dalam Situs, kami tidak mengizinkan Pengguna menggunakan perangkat lunak pihak ketiga seperti bot, crawler, plug-in, atau ekstensi browser yang dapat mengambil data, mengubah tampilan, atau mengotomatisasi aktivitas. Larangan ini mencakup:</p>
                <ul>
                    <li>Mengambil sebagian atau seluruh data secara melawan hukum.</li>
                    <li>Mengubah tampilan atau antarmuka Situs tanpa izin.</li>
                    <li>Melakukan deep-link ke produk/layanan tanpa persetujuan resmi.</li>
                </ul>

                <h3><i class="fas fa-thumbs-up"></i> Persetujuan</h3>
                <p>Dengan menggunakan MP-SPBE Kota Bogor, Pengguna dianggap telah membaca, memahami, dan menyetujui seluruh isi Kebijakan Privasi ini.</p>
            </div>
        </section>
        <script>
            function toggleForm() {
                const form = document.getElementById('suggestionForm');
                const card = document.querySelector('.suggestion-card');

                if (form.classList.contains('active')) {
                    hideForm();
                } else {
                    showForm();
                }
            }

            function showForm() {
                const form = document.getElementById('suggestionForm');
                const card = document.querySelector('.suggestion-card');
                const btn = document.querySelector('.suggestion-btn');

                form.classList.add('active');
                card.style.opacity = '0.7';
                btn.innerHTML = '<i class="fas fa-chevron-up"></i> Tutup Form';

                setTimeout(() => {
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }

            function hideForm() {
                const form = document.getElementById('suggestionForm');
                const card = document.querySelector('.suggestion-card');
                const btn = document.querySelector('.suggestion-btn');

                form.classList.remove('active');
                card.style.opacity = '1';
                btn.innerHTML = '<i class="fas fa-pen"></i> Tulis Saran';
                form.reset();
            }
        </script>
    @endsection
