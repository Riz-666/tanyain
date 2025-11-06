@extends('admin.layouts.app')
@section('content')
    <!-- Stats Row -->
    <div class="row g-4 stats-row">
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card" :class="{ 'dark': darkMode }">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalPengguna }}</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-description">Total User Yang Sudah Terdaftar</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="stat-card" :class="{ 'dark': darkMode }">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalArtikel }} </div>
                <div class="stat-label">Total Article</div>
                <div class="stat-description">Total Artikel Yang sudah Di Post</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="stat-card" :class="{ 'dark': darkMode }">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalRepo }}</div>
                <div class="stat-label">Total Repository</div>
                <div class="stat-description">Total Repository Yang telah di Buat</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="stat-card" :class="{ 'dark': darkMode }">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-file"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalFile }}</div>
                <div class="stat-label">File Terupload</div>
                <div class="stat-description">Total File Yang Di Upload ke Repository</div>
            </div>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Chart Section -->
        <div class="chart-card" :class="{ 'dark': darkMode }">
            <div class="chart-header">
                <div>
                    <h5 class="chart-title"><i class="fa fa-chart-bar" style="margin-right:10px;"></i> Statistik Bulan Ini
                    </h5>
                    <small class="text-month">Statistik {{ now()->format('d M Y') }}</small>
                </div>
            </div>
            <!-- Dropdown filter bulan -->
            <span style="margin-bottom: 3px">Pilih Bulan :</span>
            <select id="monthSelect" class="form-control select-custome mt-2" :class="{ 'dark': darkMode }">
                <option value="all" selected>Semua Bulan</option>
                @foreach ($months as $index => $month)
                    <option value="{{ $index }}">{{ $month }}</option>
                @endforeach
            </select>

            <!-- HighCharts Line Chart -->
            <div id="lineChart" style="width:100%;height:400px; margin-top:10px"></div>

            <!-- Recap Section -->
            <div class="recap-section">
                <h6 class="recap-title">
                    <i class="fa fa-table" style="margin-right:8px;"></i>
                    Rekap Data
                </h6>
                <table class="recap-table" id="recapTable">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Visitor</th>
                            <th>Artikel</th>
                            <th>Repositori</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="right-sidebar">
            <!-- Quick Actions -->
            <div class="widget-card" :class="{ 'dark': darkMode }">
                <h6 class="widget-title">Quick Actions</h6>
                <a href="{{ route('admin.user.create') }}" style="text-decoration: none;" class="quick-action-btn">
                    <i class="fas fa-plus"></i>
                    Tambah Pengguna Baru
                </a>
                <a href="{{ Route('admin.aktivitas') }}" style="text-decoration: none;" class="quick-action-btn">
                    <i class="fas fa-box"></i>
                    Artikel terbaru
                </a>
                <a href="{{ route('admin.aktivitas') }}" class="quick-action-btn" style="text-decoration: none;">
                    <i class="fas fa-file-alt"></i>
                    Repository Terbaru
                </a>
                <a href="{{ Route('admin.saran') }}" class="quick-action-btn" style="text-decoration: none;">
                    <i class="fas fa-comments"></i>
                    Saran Dan Masukan
                </a>
                <a href="{{ Route('admin.profile', Auth::user()->id) }}" class="quick-action-btn"
                    style="text-decoration: none;">
                    <i class="fas fa-cog"></i>
                    Profile
                </a>
            </div>

            <!-- Recent Activity -->
            <div class="widget-card" :class="{ 'dark': darkMode }">
                <h6 class="widget-title">Aktivitas Terbaru</h6>

                @foreach ($allActivities->take(3) as $act)
                    <div class="activity-item flex items-start gap-3 p-2 border-b last:border-b-0">

                        <!-- Icon -->
                        <div class="activity-icon text-xl flex-shrink-0">
                            @if ($act->type == 'artikel')
                                <i class="fas fa-newspaper"></i>
                            @elseif($act->type == 'repo')
                                <i class="fas fa-folder-open"></i>
                            @elseif($act->type == 'file')
                                <i class="fas fa-file"></i>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="activity-content flex-1">
                            <h6 class="font-medium text-sm">
                                @if ($act->type == 'artikel')
                                    Artikel
                                @elseif($act->type == 'repo')
                                    Repository
                                @else
                                    File
                                @endif Terbaru
                            </h6>

                            <!-- User -->
                            <p class="text-xs text-gray-500 mb-1 mt-3   ">
                                {{ $act->user->nama ?? 'System' }}
                            </p>

                            <!-- Activity Title -->
                            <p class="text-sm truncate">
                                {{ \Illuminate\Support\Str::limit($act->activity, 30, '...') }}
                            </p>

                            <!-- Time -->
                            <small class="text-xs text-gray-400">
                                {{ $act->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @endforeach
                <!-- Lihat Semua -->
                <a href="{{ Route('admin.aktivitas') }}"
                    class="btn btn-small w-full mt-2 text-white text-center py-1 rounded w-100"
                    style="background-color:orange">
                    Lihat Aktivitas Terbaru
                </a>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/themes/dark-unica.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari Laravel
        const months = @json($months);
        const dataVisitor = @json($dataVisitor);
        const dataArtikel = @json($dataArtikel);
        const dataRepo = @json($dataRepo);
        const recap = @json($recap);

        let currentChart = null;
        let isDarkMode = window.darkMode || false;

        function initChart() {
            // Detect current dark mode state from Alpine.js or CSS
            isDarkMode = document.documentElement.classList.contains('dark') ||
                document.body.classList.contains('dark') ||
                window.darkMode === true;

            // Apply theme based on dark mode
            if (isDarkMode) {
                // Set dark theme options
                Highcharts.setOptions({
                    colors: ['#28a745', '#ffc107', '#17a2b8', '#dc3545', '#6f42c1'],
                    chart: {
                        backgroundColor: '#1a1a1a',
                        plotBorderColor: '#3b3b4f'
                    },
                    title: {
                        style: {
                            color: '#ffffff'
                        }
                    },
                    subtitle: {
                        style: {
                            color: '#cccccc'
                        }
                    },
                    xAxis: {
                        gridLineColor: '#3b3b4f',
                        lineColor: '#3b3b4f',
                        minorGridLineColor: '#3b3b4f',
                        tickColor: '#3b3b4f',
                        title: {
                            style: {
                                color: '#cccccc'
                            }
                        }
                    },
                    yAxis: {
                        gridLineColor: '#3b3b4f',
                        lineColor: '#3b3b4f',
                        minorGridLineColor: '#3b3b4f',
                        tickColor: '#3b3b4f',
                        title: {
                            style: {
                                color: '#cccccc'
                            }
                        }
                    },
                    legend: {
                        itemStyle: {
                            color: '#cccccc'
                        },
                        itemHoverStyle: {
                            color: '#ffffff'
                        }
                    }
                });
            } else {
                // Reset to default light theme
                Highcharts.setOptions({
                    colors: ['#28a745', '#ffc107', '#17a2b8', '#dc3545', '#6f42c1'],
                    chart: {
                        backgroundColor: '#ffffff',
                        plotBorderColor: '#e0e0e0'
                    },
                    title: {
                        style: {
                            color: '#333333'
                        }
                    },
                    subtitle: {
                        style: {
                            color: '#666666'
                        }
                    },
                    xAxis: {
                        gridLineColor: '#e0e0e0',
                        lineColor: '#e0e0e0',
                        minorGridLineColor: '#f0f0f0',
                        tickColor: '#e0e0e0',
                        title: {
                            style: {
                                color: '#666666'
                            }
                        }
                    },
                    yAxis: {
                        gridLineColor: '#e0e0e0',
                        lineColor: '#e0e0e0',
                        minorGridLineColor: '#f0f0f0',
                        tickColor: '#e0e0e0',
                        title: {
                            style: {
                                color: '#666666'
                            }
                        }
                    },
                    legend: {
                        itemStyle: {
                            color: '#333333'
                        },
                        itemHoverStyle: {
                            color: '#000000'
                        }
                    }
                });
            }

            currentChart = Highcharts.chart('lineChart', {
                chart: {
                    type: 'line',
                    backgroundColor: isDarkMode ? '#1a1a1a' : '#ffffff',
                    style: {
                        fontFamily: 'Arial, sans-serif'
                    }
                },
                title: {
                    text: 'Data Bulanan',
                    style: {
                        color: isDarkMode ? '#ffffff' : '#333333',
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    categories: months,
                    labels: {
                        style: {
                            color: isDarkMode ? '#cccccc' : '#666666'
                        }
                    },
                    lineColor: isDarkMode ? '#3b3b4f' : '#e0e0e0',
                    tickColor: isDarkMode ? '#3b3b4f' : '#e0e0e0'
                },
                yAxis: {
                    title: {
                        text: 'Jumlah',
                        style: {
                            color: isDarkMode ? '#cccccc' : '#666666'
                        }
                    },
                    labels: {
                        style: {
                            color: isDarkMode ? '#cccccc' : '#666666'
                        }
                    },
                    gridLineColor: isDarkMode ? '#3b3b4f' : '#e0e0e0'
                },
                legend: {
                    itemStyle: {
                        color: isDarkMode ? '#cccccc' : '#333333'
                    }
                },
                tooltip: {
                    backgroundColor: isDarkMode ? '#2a2a3d' : '#ffffff',
                    borderColor: isDarkMode ? '#3b3b4f' : '#cccccc',
                    style: {
                        color: isDarkMode ? '#ffffff' : '#333333'
                    },
                    shared: true,
                    crosshairs: true,
                    formatter: function() {
                        let tooltip = '<b>' + this.x + '</b><br/>';
                        this.points.forEach(function(point) {
                            tooltip += '<span style="color:' + point.color + '">' + point.series.name + '</span>: <b>' +
                                (point.series.name === 'Visitor' ? point.y.toLocaleString() : point.y) + '</b><br/>';
                        });
                        return tooltip;
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: false
                        },
                        enableMouseTracking: true,
                        marker: {
                            radius: 5,
                            symbol: 'circle'
                        }
                    }
                },
                series: [{
                    name: 'Visitor',
                    data: dataVisitor,
                    color: '#28a745',
                    lineWidth: 3,
                    marker: {
                        fillColor: '#28a745',
                        lineColor: isDarkMode ? '#1a1a1a' : '#ffffff',
                        lineWidth: 2
                    }
                }, {
                    name: 'Artikel',
                    data: dataArtikel,
                    color: '#ffc107',
                    lineWidth: 3,
                    marker: {
                        fillColor: '#ffc107',
                        lineColor: isDarkMode ? '#1a1a1a' : '#ffffff',
                        lineWidth: 2
                    }
                }, {
                    name: 'Repositori',
                    data: dataRepo,
                    color: '#17a2b8',
                    lineWidth: 3,
                    marker: {
                        fillColor: '#17a2b8',
                        lineColor: isDarkMode ? '#1a1a1a' : '#ffffff',
                        lineWidth: 2
                    }
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });
        }

        function updateRecapTable(selectedMonths, visitorData, artikelData, repoData) {
            const tbody = document.querySelector('#recapTable tbody');
            if (!tbody) {
                console.error("TBody #recapTable tidak ditemukan!");
                return;
            }
            tbody.innerHTML = '';

            selectedMonths.forEach((month, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${month}</strong></td>
                    <td>${visitorData[index].toLocaleString()}</td>
                    <td>${artikelData[index]}</td>
                    <td>${repoData[index]}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function updateStatsCards(visitorData, artikelData, repoData, selectedMonth = null) {
            const currentMonth = selectedMonth !== null ? selectedMonth : (new Date().getMonth());
            // Jika elemen stats card ada, update — jika tidak, abaikan
            const visitorEl = document.getElementById('currentVisitor');
            const artikelEl = document.getElementById('currentArtikel');
            const repoEl = document.getElementById('currentRepo');

            if (visitorEl) visitorEl.textContent = visitorData[currentMonth].toLocaleString();
            if (artikelEl) artikelEl.textContent = artikelData[currentMonth];
            if (repoEl) repoEl.textContent = repoData[currentMonth];
        }

        function filterChart() {
            const selectEl = document.getElementById('monthSelect');
            if (!selectEl) return;

            const selectedValue = selectEl.value;

            // 👇 AMBIL ELEMEN SETIAP KALI — INI KUNCI PERBAIKAN!
            const recapSection = document.querySelector('.recap-section');

            let filteredMonths = months;
            let filteredVisitor = dataVisitor;
            let filteredArtikel = dataArtikel;
            let filteredRepo = dataRepo;

            if (selectedValue !== 'all') {
                const monthIndex = parseInt(selectedValue);
                filteredMonths = [months[monthIndex]];
                filteredVisitor = [dataVisitor[monthIndex]];
                filteredArtikel = [dataArtikel[monthIndex]];
                filteredRepo = [dataRepo[monthIndex]];

                // Update stats cards for selected month
                updateStatsCards(dataVisitor, dataArtikel, dataRepo, monthIndex);

                // Isi tabel DULU
                updateRecapTable(filteredMonths, filteredVisitor, filteredArtikel, filteredRepo);

                // Tampilkan tabel — hanya jika elemen ada
                if (recapSection) {
                    recapSection.style.display = 'block';
                }
            } else {
                // Sembunyikan tabel
                if (recapSection) {
                    recapSection.style.display = 'none';
                }
                // Update stats cards ke bulan sekarang
                updateStatsCards(dataVisitor, dataArtikel, dataRepo);
            }

            // Update chart
            if (currentChart) {
                currentChart.update({
                    xAxis: {
                        categories: filteredMonths
                    },
                    series: [{
                        data: filteredVisitor
                    }, {
                        data: filteredArtikel
                    }, {
                        data: filteredRepo
                    }]
                });
            }
        }

        // Initialize chart
        initChart();

        // 👇 SEMBUNYIKAN TABEL SAAT LOAD — TAPI CEK DULU APAKAH ADA
        const initialRecap = document.querySelector('.recap-section');
        if (initialRecap) {
            initialRecap.style.display = 'none';
        }

        // Event listener untuk dropdown
        const monthSelect = document.getElementById('monthSelect');
        if (monthSelect) {
            monthSelect.addEventListener('change', filterChart);
        }

        // Theme change listener
        window.addEventListener('theme-changed', (e) => {
            isDarkMode = e.detail.darkMode;

            const chartCard = document.getElementById('chartCard');
            const selectEl = document.getElementById('monthSelect');

            if (isDarkMode) {
                if (chartCard) chartCard.classList.add('dark');
                if (selectEl) selectEl.classList.add('dark');
            } else {
                if (chartCard) chartCard.classList.remove('dark');
                if (selectEl) selectEl.classList.remove('dark');
            }

            if (currentChart) {
                currentChart.destroy();
            }
            initChart();
            filterChart();
        });

        // Observer untuk perubahan class (dark mode manual)
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const currentDarkMode = document.documentElement.classList.contains('dark') ||
                        document.body.classList.contains('dark');

                    if (currentDarkMode !== isDarkMode) {
                        isDarkMode = currentDarkMode;

                        const chartCard = document.getElementById('chartCard');
                        const selectEl = document.getElementById('monthSelect');

                        if (isDarkMode) {
                            if (chartCard) chartCard.classList.add('dark');
                            if (selectEl) selectEl.classList.add('dark');
                        } else {
                            if (chartCard) chartCard.classList.remove('dark');
                            if (selectEl) selectEl.classList.remove('dark');
                        }

                        if (currentChart) {
                            currentChart.destroy();
                        }
                        initChart();
                        filterChart();
                    }
                }
            });
        });

        observer.observe(document.documentElement, { attributes: true });
        observer.observe(document.body, { attributes: true });
    });
</script>
@endsection
