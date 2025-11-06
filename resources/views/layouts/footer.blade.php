<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-brand">
                    <h3 class="footer-title">
                        <i class="fas fa-question-circle me-2"></i>TanyaIn Knowledge Hub
                    </h3>
                    <p style="color: #cbd5e0; line-height: 1.6;">
                        Platform kolaboratif untuk berbagi artikel, ide, dan repositori ilmu. Satu tempat untuk belajar
                        dan tumbuh bersama komunitas digital Indonesia.
                    </p>

                    <div class="social-icons mt-3">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="GitHub"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h4 class="footer-title">Platform</h4>
                <a href="/" class="footer-link">Beranda</a>
                <a href="{{ route('repository') }}" class="footer-link">Repositori</a>
                <a href="{{ route('article') }}" class="footer-link">Artikel</a>
                <a href="{{ route('file') }}" class="footer-link">File</a>
                <a href="{{ route('saran') }}" class="footer-link">Tentang</a>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h4 class="footer-title">Eksplorasi</h4>
                <a href="{{ route('repository', ['sort' => 'popular']) }}" class="footer-link">Repositori Populer</a>
                <a href="{{ route('repository', ['sort' => 'latest']) }}" class="footer-link">Repositori Terbaru</a>
                <a href="{{ route('article', ['sort' => 'popular']) }}" class="footer-link">Artikel Populer</a>
                <a href="{{ route('article', ['sort' => 'latest']) }}" class="footer-link">Artikel Terbaru</a>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h4 class="footer-title">Dukungan</h4>
                <a href="{{ Route('bantuan') }}" class="footer-link">Bantuan Pengguna</a>
                <a href="{{ Route('saran') }}" class="footer-link">Tentang Website</a>
                <a href="{{ Route('ketentuan') }}" class="footer-link">Ketentuan Pengguna</a>
                <a href="{{ Route('privasi') }}" class="footer-link">kebijakan Privasi</a>
            </div>

        </div>

        <!-- Quick Stats -->
        <div class="row mt-4 pt-4" style="border-top: 1px solid #4a5568;">
            <div class="col-md-12">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="footer-stat">
                            <div class="footer-stat-number">{{ $totalRepo }}</div>
                            <div class="footer-stat-label">Total Repositori</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="footer-stat">
                            <div class="footer-stat-number">{{ $totalArtikel }}</div>
                            <div class="footer-stat-label">Total Artikel</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="footer-stat">
                            <div class="footer-stat-number">{{ $totalFile }}</div>
                            <div class="footer-stat-label">Total File Terupload</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="footer-stat">
                            <div class="footer-stat-number">{{ $totalPengguna }}</div>
                            <div class="footer-stat-label">Total Pengguna</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="copyright">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start">
                    <p>&copy; TanyaIn - Platform Berbagi Pengetahuan.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-orange);
    }

    .footer-stat-label {
        font-size: 0.9rem;
        color: #a0aec0;
    }

    .newsletter-form .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .newsletter-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .newsletter-form .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--primary-orange);
        box-shadow: none;
        color: white;
    }
</style>
