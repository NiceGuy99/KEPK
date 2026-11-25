@extends('layouts.app')

@section('title', 'Dashboard Ketua KEPK')

@section('content')
<!-- Welcome Banner Ketua -->
<div class="card card-modern p-4 mb-4 border-0 position-relative overflow-hidden text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #0d9488 100%); border-radius: 20px; box-shadow: 0 10px 30px rgba(30, 58, 138, 0.25);">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1.5 rounded-pill" style="font-size: 0.78rem;">
                <i class="bi bi-person-badge-fill me-1"></i> Ketua Komite Etik Penelitian Kesehatan (KEPK)
            </span>
            <h2 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()?->nama_lengkap ?? 'Ketua KEPK' }} 🩺</h2>
            <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                Pemantauan menyeluruh dan evaluasi kelaikan etik protokol riset di RSUD Sidoarjo Barat.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('ketua.sertifikat') }}" class="btn btn-light btn-modern shadow-sm text-dark px-3 py-2">
                    <i class="bi bi-award-fill text-primary me-1"></i> Daftar Sertifikat
                </a>
                <a href="{{ route('ketua.proposal-masuk') }}" class="btn btn-outline-light btn-modern px-3 py-2">
                    <i class="bi bi-inbox me-1"></i> Proposal Masuk
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total Usulan</span>
                <div class="stat-icon-wrapper" style="background: rgba(13, 148, 136, 0.1); color: #0d9488; width: 40px; height: 40px; font-size: 1.2rem;">
                    <i class="bi bi-journal-medical"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark"><span class="stat-counter" data-target="{{ $stats['total_proposal'] ?? 0 }}">0</span></h3>
            <small class="text-muted" style="font-size: 0.75rem;">Protokol terdaftar</small>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Perlu Verifikasi</span>
                <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; width: 40px; height: 40px; font-size: 1.2rem;">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-warning"><span class="stat-counter" data-target="{{ $stats['menunggu_verifikasi'] ?? 0 }}">0</span></h3>
            <small class="text-muted" style="font-size: 0.75rem;">Administrasi</small>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Sedang Ditelaah</span>
                <div class="stat-icon-wrapper" style="background: rgba(2, 132, 199, 0.1); color: #0284c7; width: 40px; height: 40px; font-size: 1.2rem;">
                    <i class="bi bi-search"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-info"><span class="stat-counter" data-target="{{ $stats['sedang_review'] ?? 0 }}">0</span></h3>
            <small class="text-muted" style="font-size: 0.75rem;">Oleh reviewer</small>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Perlu Revisi</span>
                <div class="stat-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; width: 40px; height: 40px; font-size: 1.2rem;">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-danger"><span class="stat-counter" data-target="{{ $stats['revisi'] ?? 0 }}">0</span></h3>
            <small class="text-muted" style="font-size: 0.75rem;">Menunggu perbaikan</small>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Lolos Etik</span>
                <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 40px; height: 40px; font-size: 1.2rem;">
                    <i class="bi bi-check-all"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-success"><span class="stat-counter" data-target="{{ $stats['selesai'] ?? 0 }}">0</span></h3>
            <small class="text-muted" style="font-size: 0.75rem;">Selesai telaah</small>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6">
        <div class="card card-modern card-hover p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Sertifikat Terbit</span>
                <div class="stat-icon-wrapper" style="background: rgba(99, 102, 241, 0.1); color: #6366f1; width: 40px; height: 40px; font-size: 1.2rem;">
                    <i class="bi bi-award-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-primary"><span class="stat-counter" data-target="{{ $stats['total_sertifikat'] ?? 0 }}">0</span></h3>
            <small class="text-muted" style="font-size: 0.75rem;">Ethical clearance</small>
        </div>
    </div>
</div>

<!-- Interactive Analytics Charts Section -->
<div class="row g-3 mb-4">
    <!-- Monthly Trend Chart -->
    <div class="col-lg-8">
        <div class="card card-modern p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-graph-up-arrow text-primary me-2"></i> Tren Pengajuan Usulan Protokol ({{ date('Y') }})</h6>
                    <small class="text-muted">Grafik tren pengajuan riset di Komite Etik per bulan</small>
                </div>
                <span class="badge bg-primary-subtle text-primary border px-2.5 py-1.5 rounded-pill" style="font-size: 0.75rem;">
                    <i class="bi bi-calendar-check me-1"></i> Data {{ date('Y') }}
                </span>
            </div>
            <div id="monthlyTrendChartKetua" style="min-height: 270px;"></div>
        </div>
    </div>

    <!-- Status Distribution Donut Chart -->
    <div class="col-lg-4">
        <div class="card card-modern p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-pie-chart-fill text-info me-2"></i> Komposisi Telaah</h6>
                    <small class="text-muted">Status usulan protokol</small>
                </div>
            </div>
            <div id="statusDonutChartKetua" style="min-height: 270px;"></div>
        </div>
    </div>
</div>

<!-- Recent Proposals Table -->
<div class="card card-modern mb-4">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f5f9; border-radius: 18px 18px 0 0;">
        <div>
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-clipboard2-pulse-fill text-primary me-2"></i> Usulan Penelitian Masuk Terbaru</h6>
            <small class="text-muted">Daftar usulan penelitian yang sedang dalam proses penelaahan</small>
        </div>
        <a href="{{ route('ketua.proposal-masuk') }}" class="btn btn-sm btn-outline-primary btn-modern">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Peneliti</th>
                        <th>Judul Penelitian</th>
                        <th>Jenis Riset</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals->where('status_id', '!=', 5) as $p)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold">{{ $p->kode_proposal ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 60) }}</div>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ date('d M Y', strtotime($p->tanggal_pengajuan)) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</span>
                            </td>
                            <td>
                                @if($p->status_id == 1)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-pill">Menunggu Verifikasi</span>
                                @elseif($p->status_id == 2)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5 rounded-pill">Sedang Ditelaah</span>
                                @elseif($p->status_id == 3)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill">Perlu Revisi</span>
                                @elseif($p->status_id == 4)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-pill">Sudah Direvisi</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1.5 rounded-pill">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('ketua.verifikasi', $p->id) }}" class="btn btn-sm btn-outline-primary btn-modern px-3 py-1.5">
                                    <i class="bi bi-eye me-1"></i> Tinjau
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada usulan penelitian yang aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#94a3b8' : '#64748b';
        const borderColor = isDark ? '#334155' : '#f1f5f9';

        // 1. Monthly Trend Area Chart
        const monthlyOptions = {
            series: [{
                name: 'Usulan Masuk',
                data: {!! json_encode(!empty($monthlyCounts) ? $monthlyCounts : array_fill(0, 12, 0)) !!}
            }],
            chart: {
                type: 'area',
                height: 270,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#1e3a8a'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                labels: { style: { colors: textColor } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: textColor },
                    formatter: function(val) { return Math.floor(val); }
                }
            },
            grid: {
                borderColor: borderColor,
                strokeDashArray: 4
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: { formatter: function(val) { return val + " Usulan"; } }
            }
        };

        const monthlyChart = new ApexCharts(document.querySelector("#monthlyTrendChartKetua"), monthlyOptions);
        monthlyChart.render();

        // 2. Status Donut Chart
        const statusCounts = [
            {{ $stats['menunggu_verifikasi'] ?? 0 }},
            {{ $stats['sedang_review'] ?? 0 }},
            {{ $stats['revisi'] ?? 0 }},
            {{ $stats['selesai'] ?? 0 }}
        ];
        const hasData = statusCounts.some(v => v > 0);

        const statusOptions = {
            series: hasData ? statusCounts : [1, 1, 1, 1],
            chart: {
                type: 'donut',
                height: 270,
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            labels: ['Verifikasi', 'Sedang Telaah', 'Revisi', 'Selesai'],
            colors: hasData ? ['#f59e0b', '#0284c7', '#ef4444', '#10b981'] : ['#e2e8f0', '#cbd5e1', '#94a3b8', '#64748b'],
            legend: {
                position: 'bottom',
                labels: { colors: textColor }
            },
            stroke: {
                show: true,
                colors: [isDark ? '#1e293b' : '#ffffff'],
                width: 2
            },
            dataLabels: { enabled: hasData },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function(val) {
                        return hasData ? val + " Protokol" : "Belum ada data";
                    }
                }
            }
        };

        const statusChart = new ApexCharts(document.querySelector("#statusDonutChartKetua"), statusOptions);
        statusChart.render();

        // Theme Switch Live Reaction
        window.addEventListener('themeChanged', function(e) {
            const dark = (e.detail.theme === 'dark');
            const col = dark ? '#94a3b8' : '#64748b';
            const bCol = dark ? '#334155' : '#f1f5f9';

            monthlyChart.updateOptions({
                xaxis: { labels: { style: { colors: col } } },
                yaxis: { labels: { style: { colors: col } } },
                grid: { borderColor: bCol },
                tooltip: { theme: dark ? 'dark' : 'light' }
            });

            statusChart.updateOptions({
                legend: { labels: { colors: col } },
                stroke: { colors: [dark ? '#1e293b' : '#ffffff'] },
                tooltip: { theme: dark ? 'dark' : 'light' }
            });
        });
    });
</script>
@endpush