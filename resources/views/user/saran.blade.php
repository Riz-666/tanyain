@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">4
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

@endsection
@section('title', 'Tentang - Manajemen Pengetahuan SPBE Kota Bogor')
@section('content')
    <div class="container mt-6 body-saran" style="margin-top: -20px">
        <!-- Tentang Website -->
        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Tentang Manajemen Pengetahuan SPBE Kota Bogor
            </h2>
            <div class="about-content">
                <p><strong>Manajemen Pengetahuan SPBE</strong> merupakan platform yang mendukung pelaksanaan
                    MP-SPBE Kota Bogor.
                    Melalui platform ini, seluruh perangkat daerah dapat mengakses artikel, dokumen, dan repositori secara
                    cepat,
                    sehingga mendukung kolaborasi, transfer pengetahuan, serta peningkatan kapasitas dalam implementasi
                    SPBE.
                    Tujuannya adalah memastikan setiap pihak memiliki akses mudah terhadap informasi yang relevan dan
                    mutakhir untuk menunjang kinerja pemerintahan.


                <h3><i class="fas fa-rocket"></i> Misi</h3>
                <ol>
                    <li>Mewujudkan tata kelola pemerintahan berbasis digital yang terpadu, efektif, dan efisien.</li>
                    <li>Meningkatkan kualitas pelayanan publik melalui pemanfaatan teknologi informasi dan komunikasi.</li>
                    <li>Meningkatkan transparansi, akuntabilitas, dan integritas dalam penyelenggaraan pemerintahan.</li>
                    <li>Mendorong kolaborasi dan integrasi data lintas perangkat daerah untuk mendukung pengambilan
                        keputusan.</li>
                    <li>Meningkatkan literasi dan budaya digital aparatur serta masyarakat.</li>
                    <li>Mengoptimalkan pemanfaatan infrastruktur SPBE guna mendukung pelayanan prima.</li>
                </ol>

                <h3><i class="fas fa-eye"></i> Visi</h3>
                <p>Terwujudnya Tata Kelola Pemerintahan Kota Bogor yang Terintegrasi, Transparan, dan Inovatif melalui
                    Implementasi SPBE.</p>


                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fas fa-newspaper"></i>
                        <h4>Artikel Berkualitas</h4>
                        <p>Kumpulan informasi dan panduan praktis untuk mendukung implementasi MP-SPBE Kota Bogor.</p>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-folder-open"></i>
                        <h4>Repositori Lengkap</h4>
                        <p>Akses berbagai repositori, dokumen, dan kode sumber yang relevan untuk mendukung penerapan SPBE.
                        </p>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-lightbulb"></i>
                        <h4>Pencarian Pintar</h4>
                        <p>Temukan artikel, dokumen, dan repositori dengan cepat untuk mempercepat kolaborasi dan
                            pengambilan keputusan.</p>
                    </div>

                    <div class="feature-item">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>Penguatan Kapasitas</h4>
                        <p>Akses materi pembelajaran untuk meningkatkan pengetahuan dan kompetensi dalam implementasi SPBE.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        <!-- Kontak Administrator -->
        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-book-bookmark"></i>
                Kontak Dan Bantuan
            </h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <h4><i class="fas fa-book-open"></i> Panduan Dan Peraturan</h4>
                    <div class="contact-info">
                        <i class="fas fa-question-circle"></i>
                        <a href="{{ Route('bantuan') }}">Bantuan Pengguna</a>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-file-alt"></i>
                        <a href="{{ Route('ketentuan') }}">Ketentuan Pengguna</a>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-shield-alt"></i>
                        <a href="{{ Route('privasi') }}">Kebijakan Privasi</a>
                    </div>
                </div>


                <div class="contact-card">
                    <h4><i class="fas fa-building"></i> Instansi Pengelola</h4>
                    <div class="contact-info">
                        <i class="fas fa-university"></i> Dinas Komunikasi dan Informatika Kota Bogor (Diskominfo)
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i> Komplek Balaikota Bogor, Jl. Ir. H. Juanda No. 10, Bogor, Jawa
                        Barat, Indonesia
                    </div>
                </div>


                <div class="contact-card">
                    <h4><i class="fas fa-life-ring"></i> Bantuan & Support</h4>
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i> <a href="mailto:kominfo@kotabogor.go.id">kominfo@kotabogor.go.id</a>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i> <a href="tel:+622518321075">+62 251 8321075 Ext. 287</a>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-globe"></i> <a href="https://kominfo.kotabogor.go.id"
                            target="_blank">www.kominfo.kotabogor.go.id</a>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-clock"></i> Jam Kerja: Senin-Jumat, 08:00-16:00 WIB
                    </div>
                </div>

            </div>
        </section>

        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-sitemap"></i>
                Struktur Organisasi Diskominfo Kota Bogor
            </h2>

            <div class="org-chart-wrapper">
                <div class="org-chart">

                    <!-- Level 1 - Kepala Dinas -->
                    <div class="org-level">
                        <div class="org-card" data-nama="Rudiyana, S.STP., M.Sc"
                            data-jabatan="Kepala Dinas Komunikasi Dan Informatika Kota Bogor"
                            data-foto="{{ asset('organisasi/1.jpg') }}">
                            <i class="fas fa-user-tie"></i>
                            <div class="org-name">Kepala Dinas Komunikasi Dan Informatika Kota Bogor</div>
                            <div class="org-person">Rudiyana, S.STP., M.Sc</div>
                        </div>
                    </div>

                    <!-- Level 2 - Sekretaris & Kabid -->
                    <div class="org-level level-two">
                        <!-- Sekretaris Dinas -->
                        <div class="org-card" data-nama="Oki Tri Fasiasta Nurmala Alam, S.STP."
                            data-jabatan="Sekretaris Dinas Komunikasi Dan Informatika Kota Bogor"
                            data-foto="{{ asset('organisasi/2.jpg') }}">
                            <i class="fas fa-user-cog"></i>
                            <div class="org-name">Sekretaris Dinas Komunikasi Dan Informatika Kota Bogor</div>
                            <div class="org-person">Oki Tri Fasiasta Nurmala Alam, S.STP.</div>
                        </div>

                        <!-- Kabid Statistik Sektoral -->
                        <div class="org-card" data-nama="Tosan Wiar Ramadhani, S.Kom., M.TI"
                            data-jabatan="Kabid Statistik Sektoral" data-foto="{{ asset('organisasi/6.jpg') }}">
                            <i class="fas fa-chart-bar"></i>
                            <div class="org-name">Kabid Statistik Sektoral</div>
                            <div class="org-person">Tosan Wiar Ramadhani, S.Kom., M.TI</div>
                        </div>

                        <!-- Kabid Informasi & Komunikasi Publik -->
                        <div class="org-card" data-nama="Dian Intannia Lesmana S.Sos. ME"
                            data-jabatan="Kabid Informasi Dan Komunikasi Publik"
                            data-foto="{{ asset('organisasi/4.jpg') }}">
                            <i class="fas fa-bullhorn"></i>
                            <div class="org-name">Kabid Informasi Dan Komunikasi Publik</div>
                            <div class="org-person">Dian Intannia Lesmana S.Sos. ME</div>
                        </div>

                        <!-- Kabid Persandian & Keamanan Informasi -->
                        <div class="org-card" data-nama="Arofa Abdilla Rahman ST MT"
                            data-jabatan="Kabid Persandian & Keamanan Informasi"
                            data-foto="{{ asset('organisasi/5.jpg') }}">
                            <i class="fas fa-lock"></i>
                            <div class="org-name">Kabid Persandian & Keamanan Informasi</div>
                            <div class="org-person">Arofa Abdilla Rahman, ST. MT.</div>
                        </div>

                        <!-- Kabid Aplikasi Informatika -->
                        <div class="org-card" data-nama="Junenti Kolbert Nadeak, ST. ME"
                            data-jabatan="Kabid Aplikasi Informatika" data-foto="{{ asset('organisasi/3.jpg') }}">
                            <i class="fas fa-cogs"></i>
                            <div class="org-name">Kabid Aplikasi Informatika</div>
                            <div class="org-person">Junenti Kolbert Nadeak, ST. ME</div>
                        </div>
                    </div>

                    <!-- Level 3 - Kasubag Umum & Kepegawaian -->
                    <div class="org-level level-three">
                        <div class="org-card" data-nama="Susilawaty Syariefah, S.Sos. MA"
                            data-jabatan="Kasubag Umum dan Kepegawaian" data-foto="{{ asset('organisasi/7.jpg') }}">
                            <i class="fas fa-users-cog"></i>
                            <div class="org-name">Kasubag Umum dan Kepegawaian</div>
                            <div class="org-person">Susilawaty Syariefah, S.Sos. MA.</div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                Lokasi Kantor Dinas Komunikasi dan Informatika Kota Bogor
            </h2>

            <div id="map" style="height: 500px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            </div>
        </section>


        @if (Auth::check())
            <!-- Saran -->
            <section class="section suggestion-section">
                <h2 class="section-title">
                    <i class="fas fa-comment-dots"></i>
                    Saran & Masukan
                </h2>

                <div class="suggestion-card" onclick="toggleForm()">
                    <div class="suggestion-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="suggestion-title">Berikan Saran Anda</h3>
                    <p class="suggestion-desc">
                        Masukan Anda sangat berharga! Sampaikan saran, kritik, atau ide untuk membantu pengembangan MP-SPBE
                        Kota Bogor agar semakin optimal dan bermanfaat bagi seluruh pengguna.
                    </p>

                    <button class="suggestion-btn">
                        <i class="fas fa-pen"></i>
                        Tulis Saran
                    </button>
                </div>

                <form class="suggestion-form" id="suggestionForm" method="POST" action="{{ route('saran.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="nama">
                            <i class="fas fa-user"></i>
                            Nama
                        </label>
                        @auth
                            <input type="text" id="nama" name="nama" value="{{ auth()->user()->nama }}"
                                style="background-color: rgba(225, 225, 225, 0.314)" readonly>
                        @else
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                        @endauth
                    </div>

                    <div class="form-group">
                        <label for="pesan">
                            <i class="fas fa-comment-alt"></i>
                            Pesan
                        </label>
                        <textarea id="pesan" name="pesan" placeholder="Tulis saran, kritik, atau masukan Anda di sini..." required></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Saran
                        </button>
                        <button type="button" class="cancel-btn" onclick="hideForm()">
                            <i class="fas fa-times"></i>
                            Batal
                        </button>
                    </div>
                </form>
            </section>
        @endif
    </div>


    <!-- Modal Structure -->
    <div id="staffModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-body">
                <img id="modalFoto" src="" alt="Foto Pegawai" class="modal-foto">
                <h3 id="modalNama" class="modal-nama"></h3>
                <p id="modalJabatan" class="modal-jabatan"></p>
            </div>
        </div>
    </div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.org-card');
            const modal = document.getElementById('staffModal');
            const modalFoto = document.getElementById('modalFoto');
            const modalNama = document.getElementById('modalNama');
            const modalJabatan = document.getElementById('modalJabatan');
            const closeBtn = document.querySelector('.close');

            // Buka modal saat card diklik
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const nama = this.getAttribute('data-nama');
                    const jabatan = this.getAttribute('data-jabatan');
                    const foto = this.getAttribute('data-foto');

                    modalFoto.src = foto ||
                        'https://via.placeholder.com/150/cccccc/666666?text=No+Photo';
                    modalNama.textContent = nama;
                    modalJabatan.textContent = jabatan;

                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Cegah scroll background
                });
            });

            // Tutup modal saat klik tombol X
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Kembalikan scroll
            });

            // Tutup modal saat klik di luar konten
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });

            // Tutup modal dengan ESC
            window.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modal.style.display === 'block') {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Koordinat Diskominfo Kota Bogor
            const lat = -6.5951236;
            const lng = 106.7937161;

            // Inisialisasi peta
            const map = L.map('map').setView([lat, lng], 15); // Zoom level 17

            // Tile Layer (peta dasar)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Marker lokasi
            const marker = L.marker([lat, lng]).addTo(map);

            // Popup info
            marker.bindPopup(`
      <div style="text-align: center; font-family: Arial; font-size: 14px;">
        <strong>Dinas Komunikasi dan Informatika</strong><br>
        Kota Bogor<br>
        Jl. Kapten Muslihat No.28<br>
        Bogor Tengah, Kota Bogor 16121<br>
        <a href="https://www.google.com/maps/place/Kantor+DISKOMINFO+Kota+Bogor/@-6.5951236,106.7937161,21z/data=!4m6!3m5!1s0x2e69c5b7d2e12d0d:0x9b59e38fc692d9fe!8m2!3d-6.595095!4d106.793665!16s%2Fg%2F11cnc4b1p8?entry=tts&g_ep=EgoyMDI1MDkxNy4wIPu8ASoASAFQAw%3D%3D&skid=f43bf1fe-d518-4992-9324-501cc6280cb2" target="_blank" style="color: #ff8c42; text-decoration: none; font-weight: bold;">
          Lihat di Google Maps →
        </a>
      </div>
    `).openPopup();
            // Animasi fly to location (opsional)
            setTimeout(() => {
                map.flyTo([lat, lng], 17, {
                    duration: 2,
                    easeLinearity: 0.25
                });
            }, 500);

            // Responsif: resize peta saat window diresize
            window.addEventListener('resize', function() {
                map.invalidateSize();
            });
        });
    </script>
@endsection
