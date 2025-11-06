@extends('layouts.app')
@section('title', 'Manajemen Pengetahuan SPBE ')

@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/index.css') }}">
@endsection

@section('content')
    <!-- Stats Section -->
    @include('layouts.header')
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon"> <i class="fas fa-folder-open"></i> </div>
                        <div class="stat-number">{{ $totalRepo }}</div>
                        <div class="stat-label">Total Repositori</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon"> <i class="fas fa-newspaper"></i> </div>
                        <div class="stat-number">{{ $totalArtikel }}</div>
                        <div class="stat-label">Total Artikel</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon"> <i class="fas fa-file"></i> </div>
                        <div class="stat-number">{{ $totalFile }}</div>
                        <div class="stat-label">Total File</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon"> <i class="fas fa-users"></i> </div>
                        <div class="stat-number">{{ $totalPengguna }}</div>
                        <div class="stat-label">Total Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </section> <!-- Highlight Data Section (from Open Data Bogor) -->
    <section class="highlight-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="highlight-content">
                        <h2 class="section-title text-start"> <i class="fas fa-star text-warning me-2"></i>Konten Tebaru
                        </h2>
                        <!-- Featured Repository -->
                        @forelse ($items as $item)
                            @php
                                $show = true;

                                // Cek artikel publik
                                if ($item['type'] === 'artikel' && $item['status'] !== 'publik' && !auth()->check()) {
                                    $show = false;
                                }

                                // Cek repositori privat
                                if (
                                    $item['type'] === 'repositori' &&
                                    $item['status'] !== 'publik' &&
                                    !auth()->check()
                                ) {
                                    $show = false;
                                }

                                // Cek fileRepo ikut repositori
                                if (
                                    $item['type'] === 'file' &&
                                    $item['repositori']->status !== 'publik' &&
                                    !auth()->check()
                                ) {
                                    $show = false;
                                }
                            @endphp
                            @if ($show)
                                <div class="highlight-item mb-4">
                                    <div class="highlight-card">
                                        <div class="d-flex">
                                            <div class="highlight-icon">
                                                @if ($item['type'] === 'artikel')
                                                    <i class="fas fa-newspaper"></i>
                                                @elseif($item['type'] === 'repositori')
                                                    <i class="fas fa-folder-open"></i>
                                                @elseif($item['type'] === 'file')
                                                    <i class="fas fa-file"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="highlight-title">
                                                    <a
                                                        href="
                                                    @if ($item['type'] === 'artikel') {{ route('article.detail', $item['id']) }}
                                                    @elseif($item['type'] === 'repositori')
                                                        {{ route('repo.detail', $item['id']) }}
                                                    @elseif($item['type'] === 'file')
                                                        {{ route('repo.detail', $item['repositori']->id ?? '#') }} @endif
                                                ">
                                                        {{ Str::after($item['judul'], '_') }}
                                                    </a>
                                                </h4>
                                                <div class="highlight-meta">
                                                    <span
                                                        class="badge
                                                    @if ($item['type'] === 'artikel') bg-success
                                                    @elseif($item['type'] === 'repositori') bg-primary
                                                    @elseif($item['type'] === 'file') bg-info @endif me-2">
                                                        {{ ucfirst($item['type']) }}
                                                    </span>
                                                    <span class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ \Carbon\Carbon::parse($item['created_at'])->translatedFormat('d F Y') }}
                                                    </span>
                                                    @if ($item['type'] === 'artikel')
                                                        <span class="text-muted ms-3">
                                                            <i class="fas fa-eye me-1"></i>{{ $item['views'] ?? 0 }}
                                                            views
                                                        </span>
                                                    @else
                                                        <span class="text-muted ms-3">
                                                            <i class="fas fa-download me-1"></i>
                                                            {{ $item['downloads'] ?? 0 }} downloads
                                                        </span>
                                                    @endif
                                                </div>

                                                <p class="highlight-desc">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($item['isi']), 100, '…') }}
                                                </p>

                                                @if (isset($item['tag']) && $item['tag'])
                                                    <div class="highlight-tags">
                                                        @foreach ($item['tag'] as $tag)
                                                            <a href="{{ route('article', ['tag' => $tag->slug]) }}"><span
                                                                    class="badge bg-light text-dark me-1">{{ $tag->nama_tag }}</span></a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="info-box">
                                <div class="info-header">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Info</strong>
                                </div>
                                <p>
                                    Tidak Ada Konten Terbaru
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div> <!-- Statistics Sidebar -->
                <div class="col-lg-4">
                    <div class="stats-sidebar">
                        <h3 class="sidebar-title"> <i class="fas fa-chart-pie me-2"></i>Statistik Website </h3>
                        <!-- Quick Stats -->
                        <div class="sidebar-stats mb-4">
                            <div class="sidebar-stat-item">
                                @php
                                    $maxFile = 500;
                                    $maxPengguna = 1000;
                                    $maxArtikel = 500;

                                    $percentFile = $totalFile > 0 ? ($totalFile / $maxFile) * 100 : 0;
                                    $percentPengguna = $totalPengguna > 0 ? ($totalPengguna / $maxPengguna) * 100 : 0;
                                    $percentArtikel = $totalArtikel > 0 ? ($totalArtikel / $maxArtikel) * 100 : 0;
                                @endphp

                                <div class="sidebar-stat-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="stat-label">Total File Di Unggah</span>
                                        <span class="stat-number">{{ $totalFile }}</span>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-primary" style="width: {{ min($percentFile, 100) }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="sidebar-stat-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="stat-label">Total Pengguna</span>
                                        <span class="stat-number">{{ $totalPengguna }}</span>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ min($percentPengguna, 100) }}%"></div>
                                    </div>
                                </div>

                                <div class="sidebar-stat-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="stat-label">Total Artikel</span>
                                        <span class="stat-number">{{ $totalArtikel }}</span>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ min($percentArtikel, 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="sidebar-stat-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="stat-label">Total Repository</span>
                                        <span class="stat-number">{{ $totalRepo }}</span>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-danger"
                                            style="width: {{ min($percentArtikel, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::check() && $recentActivities->count())
                                <div class="recent-activity">
                                    <h4 class="activity-title">Aktivitas Terbaru</h4>
                                    @foreach ($recentActivities as $act)
                                        <div class="activity-item">
                                            <div class="activity-icon" style="background: #ff8941">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-text">{{ $act->pesan }}</div>
                                                <div class="activity-time">{{ $act->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
    </section> <!-- Features Section -->
    <section class="features-section" id="repositori">
        <div class="container">
            <h2 class="section-title">Fitur Unggulan Website</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"> <i class="fas fa-folder-open"></i> </div>
                        <h3 class="feature-title">Repositori Terstruktur</h3>
                        <p class="feature-description"> Akses ribuan repositori yang terstruktur, lengkap dengan
                            dokumentasi dan contoh implementasi.</p> <a href="{{ Route('repository') }}"
                            class="feature-link"> Jelajahi
                            Repositori <i class="fas fa-arrow-right ms-1"></i> </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"> <i class="fas fa-edit"></i> </div>
                        <h3 class="feature-title">Artikel Berkualitas</h3>
                        <p class="feature-description"> Baca artikel dengan konten yang selalu terupdate
                            dan relevan dengan perkembangan terkini. </p> <a href="{{ Route('article') }}"
                            class="feature-link"> Baca
                            Artikel <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h3 class="feature-title">Akses Dokumen Lebih Cepat</h3>
                        <p class="feature-description">Cari dan temukan dokumen dengan mudah untuk mempercepat proses
                            belajar, riset, dan analisis informasi.</p>
                        <a href="{{ Route('file') }}" class="feature-link">
                            Jelajahi Dokumen <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"> <i class="fas fa-search"></i> </div>
                        <h3 class="feature-title">Pencarian Cerdas</h3>
                        <p class="feature-description"> Temukan konten yang Anda butuhkan dengan sistem pencarian yang
                            canggih dan filter yang dapat disesuaikan. </p> <a href="#pencarian" id="link-cari"
                            class="feature-link"> Coba
                            Pencarian <i class="fas fa-arrow-right ms-1"></i> </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"> <i class="fas fa-download"></i> </div>
                        <h3 class="feature-title">Download Mudah</h3>
                        <p class="feature-description"> Akses dan unduh file dari repositori dengan mudah untuk mendukung
                            pekerjaan dan pengembangan tim. </p> <a href="{{ Route('file') }}" id="link-cari"
                            class="feature-link"> Coba Unduh File <i class="fas fa-arrow-right ms-1"></i> </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon"> <i class="fas fa-comments"></i> </div>
                        <h3 class="feature-title">Diskusi Mudah</h3>
                        <p class="feature-description"> Berikan pendapat tentang konten yang di buat dan berdiskusi
                            langsung. dengan pembuat konten </p>
                    </div>
                </div>

            </div>
        </div>
    </section> <!-- Charts Section -->
    <section class="charts-section">
        <div class="container">
            <h2 class="section-title">Statistik Website</h2>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="chart-card">
                        <h3 class="chart-title"> <i class="fas fa-eye me-2 text-warning"></i>Statistik Pengunjung </h3>
                        <div id="visitorChart" style="height: 300px;"></div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="chart-card">
                        <h3 class="chart-title"> <i class="fas fa-download me-2 text-success"></i>File Terdownload
                        </h3>
                        <div id="downloadChart" style="height: 300px;"></div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="chart-card">
                        <h3 class="chart-title"> <i class="fas fa-chart-bar me-2 text-info"></i>Statistik Artikel Dan
                            Repositori </h3>
                        <div id="growthChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);

            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString();
                }
            }
            updateCounter();
        } // Trigger counter animation when stats section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-number');
                    counters.forEach((counter, index) => {
                        const targets = [{{ $totalRepo }}, {{ $totalArtikel }},
                            {{ $totalFile }}, {{ $totalPengguna }}
                        ];
                        animateCounter(counter, targets[index]);
                    });
                    observer.unobserve(entry.target);
                }
            });
        });
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Add search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchInput = document.querySelector('.search-box input');
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = '/search?q=' + encodeURIComponent(query);
            }
        }); // Enter key for search
        document.querySelector('.search-box input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.search-btn').click();
            }
        }); // Lazy loading for performance
        const lazyElements = document.querySelectorAll('.feature-card, .stat-card, .chart-card, .highlight-card');
        const lazyObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    lazyObserver.unobserve(entry.target);
                }
            });
        });
        lazyElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            lazyObserver.observe(el);
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Warna tema konsisten
            const themeColors = {
                visitor: '#6c5ce7', // ungu lembut
                download: '#00cec9', // cyan segar
                repo: '#007bff', // biru bootstrap
                artikel: '#28a745' // hijau sukses
            };

            // Gunakan months yang dikirim controller
            const months = {!! json_encode($months) !!};

            // ======================================
            // CHART 1: Visitor (Area Chart)
            // ======================================
            Highcharts.chart('visitorChart', {
                chart: {
                    type: 'area',
                    backgroundColor: 'transparent',
                    animation: Highcharts.svg, // animasi halus
                    spacingTop: 10,
                    spacingBottom: 15,
                    spacingLeft: 10,
                    spacingRight: 10
                },
                title: {
                    text: 'Statistik Pengunjung Bulanan',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: '600',
                        color: '#333'
                    },
                    margin: 20
                },
                xAxis: {
                    categories: months,
                    lineWidth: 0,
                    tickLength: 0,
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#666'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    gridLineColor: '#e0e0e0',
                    gridLineWidth: 1,
                    lineWidth: 0,
                    labels: {
                        style: {
                            color: '#666',
                            fontSize: '11px'
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    backgroundColor: 'rgba(255,255,255,0.9)',
                    borderColor: '#ddd',
                    borderRadius: 8,
                    shadow: true,
                    style: {
                        fontSize: '12px',
                        color: '#333'
                    },
                    formatter: function() {
                        return `<b>${this.x}</b><br/>Pengunjung: <b>${this.y.toLocaleString()}</b>`;
                    }
                },
                plotOptions: {
                    area: {
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, Highcharts.color(themeColors.visitor).setOpacity(0.8).get('rgba')],
                                [1, Highcharts.color(themeColors.visitor).setOpacity(0.1).get('rgba')]
                            ]
                        },
                        marker: {
                            radius: 4,
                            fillColor: '#fff',
                            lineWidth: 2,
                            lineColor: themeColors.visitor
                        },
                        lineWidth: 3,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        },
                        threshold: null
                    }
                },
                series: [{
                    name: 'Pengunjung',
                    data: {!! json_encode($visitorStats) !!},
                    color: themeColors.visitor
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 480
                        },
                        chartOptions: {
                            legend: {
                                enabled: false
                            },
                            title: {
                                text: 'Pengunjung'
                            }
                        }
                    }]
                }
            });

            // ======================================
            // CHART 2: Download (Column Chart)
            // ======================================
            Highcharts.chart('downloadChart', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                    animation: true,
                    spacingTop: 10,
                    spacingBottom: 15
                },
                title: {
                    text: 'Statistik Download Repositori',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: '600',
                        color: '#333'
                    }
                },
                xAxis: {
                    categories: months,
                    lineWidth: 0,
                    tickLength: 0,
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#666'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    gridLineColor: '#e0e0e0',
                    gridLineWidth: 1,
                    lineWidth: 0,
                    labels: {
                        style: {
                            color: '#666',
                            fontSize: '11px'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    borderColor: '#ddd',
                    borderRadius: 8,
                    shadow: true,
                    formatter: function() {
                        return `<b>${this.x}</b><br/>Download: <b>${this.y.toLocaleString()}</b>`;
                    }
                },
                plotOptions: {
                    column: {
                        borderRadius: 5,
                        pointPadding: 0.1,
                        groupPadding: 0.1,
                        borderWidth: 0,
                        states: {
                            hover: {
                                brightness: 0.1
                            }
                        }
                    }
                },
                series: [{
                    name: 'Download',
                    data: {!! json_encode($downloadStats) !!},
                    color: themeColors.download
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 480
                        },
                        chartOptions: {
                            title: {
                                text: 'Download'
                            }
                        }
                    }]
                }
            });

            // ======================================
            // CHART 3: Growth (Line Chart - Artikel vs Repositori)
            // ======================================
            Highcharts.chart('growthChart', {
                chart: {
                    type: 'line',
                    backgroundColor: 'transparent',
                    animation: true,
                    spacingTop: 10,
                    spacingBottom: 15
                },
                title: {
                    text: 'Pertumbuhan Artikel vs Repositori',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: '600',
                        color: '#333'
                    }
                },
                xAxis: {
                    categories: months,
                    lineWidth: 0,
                    tickLength: 0,
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#666'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    gridLineColor: '#e0e0e0',
                    gridLineWidth: 1,
                    lineWidth: 0,
                    labels: {
                        style: {
                            color: '#666',
                            fontSize: '11px'
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    borderColor: '#ddd',
                    borderRadius: 8,
                    shadow: true,
                    formatter: function() {
                        let s = `<b>${this.x}</b><br/>`;
                        this.points.forEach(point => {
                            s +=
                                `${point.series.name}: <b>${point.y.toLocaleString()}</b><br/>`;
                        });
                        return s;
                    }
                },
                plotOptions: {
                    line: {
                        marker: {
                            radius: 5,
                            lineWidth: 2,
                            lineColor: '#ffffff',
                            symbol: 'circle'
                        },
                        lineWidth: 3,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        }
                    }
                },
                series: [{
                        name: 'Repositori',
                        data: {!! json_encode($repoStats) !!},
                        color: themeColors.repo,
                        marker: {
                            fillColor: themeColors.repo
                        }
                    },
                    {
                        name: 'Artikel',
                        data: {!! json_encode($artikelStats) !!},
                        color: themeColors.artikel,
                        marker: {
                            fillColor: themeColors.artikel
                        }
                    }
                ],
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    layout: 'horizontal',
                    itemStyle: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    },
                    itemHoverStyle: {
                        color: '#000'
                    }
                },
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 480
                        },
                        chartOptions: {
                            legend: {
                                align: 'center',
                                verticalAlign: 'bottom',
                                layout: 'horizontal'
                            }
                        }
                    }]
                }
            });

        });
    </script>


@endsection
