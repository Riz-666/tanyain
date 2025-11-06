@extends('layouts.app')
@section('title', 'Kebijakan Privasi - Platform Berbagi Pengetahuan & Repositori Digital')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">
@endsection

@section('content')
<div class="container mt-6 body-saran" style="margin-top: 60px">
    <section class="section">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i>
            Kebijakan Privasi – TanyaIn
        </h2>
        <div class="about-content">
            <p>
                Selamat datang di <strong>TanyaIn</strong>, platform berbagi pengetahuan dan repositori digital yang berkomitmen untuk
                melindungi privasi dan keamanan data pengguna. Kebijakan ini menjelaskan bagaimana kami mengumpulkan,
                menggunakan, dan menjaga data pribadi selama Anda menggunakan layanan TanyaIn.
            </p>

            <h3><i class="fas fa-id-card"></i> Data Pengguna</h3>
            <p>
                Kami dapat mengumpulkan data seperti nama, email, username, alamat IP, serta informasi perangkat
                yang digunakan saat Anda berinteraksi dengan platform. Data ini digunakan untuk menjaga keamanan,
                mempersonalisasi pengalaman pengguna, serta meningkatkan kualitas layanan.
            </p>

            <h3><i class="fas fa-cookie"></i> Cookies</h3>
            <p>
                TanyaIn menggunakan cookies untuk meningkatkan pengalaman pengguna. Cookies membantu kami memahami
                preferensi Anda, namun Anda dapat menonaktifkannya melalui pengaturan browser. Beberapa fitur mungkin tidak berfungsi
                sepenuhnya jika cookies dinonaktifkan.
            </p>

            <h3><i class="fas fa-file-text"></i> Penggunaan Data</h3>
            <p>Data Anda digunakan untuk tujuan berikut:</p>
            <ol>
                <li>Menyediakan, mengelola, dan meningkatkan layanan TanyaIn.</li>
                <li>Memastikan keamanan dan mencegah penyalahgunaan akun.</li>
                <li>Meningkatkan kualitas konten dan pengalaman pengguna.</li>
                <li>Menangani permintaan, saran, dan dukungan pengguna.</li>
                <li>Mematuhi ketentuan hukum yang berlaku di Indonesia.</li>
            </ol>

            <h3><i class="fas fa-shield"></i> Perlindungan Data</h3>
            <p>
                Kami menggunakan langkah keamanan teknis dan administratif untuk melindungi data pengguna dari akses tidak sah,
                kehilangan, atau penyalahgunaan. Namun, tidak ada sistem yang sepenuhnya aman, sehingga kami tetap menganjurkan
                pengguna menjaga kerahasiaan akun masing-masing.
            </p>

            <h3><i class="fas fa-share-nodes"></i> Berbagi Data dengan Pihak Ketiga</h3>
            <p>
                Kami tidak menjual atau menyebarkan data pribadi pengguna kepada pihak mana pun. Namun, kami dapat bekerja sama dengan pihak ketiga
                untuk mendukung operasional sistem seperti hosting, analitik, atau pengembangan fitur — dengan kewajiban menjaga kerahasiaan data.
            </p>

            <h3><i class="fas fa-history"></i> Perubahan Kebijakan</h3>
            <p>
                TanyaIn dapat memperbarui Kebijakan Privasi ini sewaktu-waktu untuk menyesuaikan dengan pengembangan layanan
                atau regulasi baru. Setiap perubahan akan diumumkan melalui situs resmi.
            </p>

            <h3><i class="fas fa-ban"></i> Larangan Penggunaan Otomatis</h3>
            <p>
                Pengguna dilarang menggunakan perangkat lunak otomatis seperti bot, crawler, atau plug-in yang dapat mengambil data,
                mengubah tampilan, atau mengotomatisasi aktivitas tanpa izin resmi. Larangan ini termasuk:
            </p>
            <ul>
                <li>Mengambil data secara otomatis tanpa izin.</li>
                <li>Mengubah tampilan atau fungsi situs tanpa persetujuan.</li>
                <li>Membuat deep-link ke konten internal tanpa izin.</li>
            </ul>

            <h3><i class="fas fa-thumbs-up"></i> Persetujuan</h3>
            <p>
                Dengan menggunakan layanan TanyaIn, Anda dianggap telah membaca, memahami, dan menyetujui seluruh isi Kebijakan Privasi ini.
                Jika Anda tidak setuju dengan ketentuan yang berlaku, mohon untuk tidak melanjutkan penggunaan platform.
            </p>
        </div>
    </section>

    <script>
        function toggleForm() {
            const form = document.getElementById('suggestionForm');
            const card = document.querySelector('.suggestion-card');
            form.classList.contains('active') ? hideForm() : showForm();
        }
        function showForm() {
            const form = document.getElementById('suggestionForm');
            const card = document.querySelector('.suggestion-card');
            const btn = document.querySelector('.suggestion-btn');
            form.classList.add('active');
            card.style.opacity = '0.7';
            btn.innerHTML = '<i class="fas fa-chevron-up"></i> Tutup Form';
            setTimeout(() => form.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 100);
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
</div>
@endsection
