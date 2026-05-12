@extends('layouts.packager')

@section('title', 'Dashboard Packager')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . Auth::user()->name)

@section('topbar-actions')
    <a href="{{ route('packager.penerimaan-beras.create') }}" class="btn-primary-custom">
        <span class="iconify" data-icon="heroicons:plus" style="width:16px;height:16px;"></span>
        Terima Beras
    </a>
@endsection

@section('content')

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5ee;">
                <span class="iconify" data-icon="heroicons:inbox-stack" style="color:#1a5c38;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value">{{ number_format($stats['total_terima_kg'], 0, ',', '.') }} Kg</div>
            <div class="stat-label">Beras Diterima (Bulan Ini)</div>
            <div class="stat-trend neutral">
                <span class="iconify" data-icon="heroicons:calendar" style="width:13px;height:13px;"></span>
                Update terbaru
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5ee;">
                <span class="iconify" data-icon="heroicons:cube" style="color:#1a5c38;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value">{{ number_format($stats['total_kemas_pack'], 0, ',', '.') }} Pack</div>
            <div class="stat-label">Produksi Kemasan</div>
            <div class="stat-trend up">
                <span class="iconify" data-icon="heroicons:arrow-trending-up" style="width:13px;height:13px;"></span>
                Siap didistribusi
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff8e0;">
                <span class="iconify" data-icon="heroicons:shopping-cart" style="color:#a0720f;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value">{{ $stats['pesanan_baru'] }}</div>
            <div class="stat-label">Pesanan Menunggu</div>
            <div class="stat-trend neutral">
                <span class="iconify" data-icon="heroicons:clock" style="width:13px;height:13px;"></span>
                Perlu diproses
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5e9;">
                <span class="iconify" data-icon="heroicons:presentation-chart-bar" style="color:#2e7d32;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value" style="font-size:1.25rem;">
                Rp {{ number_format($stats['total_penjualan'], 0, ',', '.') }}
            </div>
            <div class="stat-label">Omzet (Bulan Ini)</div>
            <div class="stat-trend up">
                <span class="iconify" data-icon="heroicons:arrow-up" style="width:13px;height:13px;"></span>
                Total penjualan
            </div>
        </div>
    </div>
</div>

<!-- SECOND ROW: Penerimaan Beras + Ringkasan Stok -->
<div class="row g-3 mb-3">
    <!-- Penerimaan Beras Terbaru -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Penerimaan Beras Terbaru</h5>
                <a href="{{ route('packager.penerimaan-beras.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Asal Penggilingan</th>
                            <th>Jumlah Beras</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPenerimaan as $item)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $item->asal_penggilingan ?? '-' }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">{{ $item->jenis_beras ?? '' }}</span>
                            </td>
                            <td>
                                <strong>{{ number_format($item->jumlah_beras ?? 0, 0, ',', '.') }}</strong>
                                <span style="color:var(--text-muted);font-size:.8rem;"> kg</span>
                            </td>
                            <td>
                                @php
                                    $statusBadge = match($item->status ?? '') {
                                        'diterima'  => 'badge-success-custom',
                                        'ditolak'   => 'badge-danger-custom',
                                        'sebagian'  => 'badge-warning-custom',
                                        default     => 'badge-info-custom',
                                    };
                                @endphp
                                <span class="badge-custom {{ $statusBadge }}">{{ ucfirst($item->status ?? 'menunggu') }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ isset($item->tanggal) ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada data penerimaan beras.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ringkasan Produksi Kemasan -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header-clean">
                <h5>Ringkasan Kemasan</h5>
                <a href="{{ route('packager.pengemasan.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Detail
                </a>
            </div>
            <div style="padding:20px 24px;">

                <!-- Distribusi Jenis Kemasan -->
                <div class="mb-3">
                    <div style="font-size:.78rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">
                        Distribusi Jenis Kemasan
                    </div>
                    <div class="d-flex flex-column gap-2">
                        @foreach($stats['pie_labels'] as $index => $label)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#f0fdf4;border-radius:8px;border:1px solid var(--border);">
                            <span style="font-size:.85rem;">{{ $label }}</span>
                            <strong style="color:#1a5c38;">{{ $stats['pie_values'][$index] }}</strong>
                        </div>
                        @endforeach
                        @if(empty($stats['pie_labels']))
                            <div class="text-center py-3 text-muted" style="font-size:.8rem;">Belum ada data distribusi.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- THIRD ROW: Pengemasan Terbaru + Pesanan Masuk -->
<div class="row g-3">
    <!-- Hasil Pengemasan Terbaru -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Hasil Pengemasan Terbaru</h5>
                <a href="{{ route('packager.pengemasan.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Beras</th>
                            <th>Jenis Kemasan</th>
                            <th>Jumlah</th>
                            <th>Kualitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPengemasan as $kemas)
                        <tr>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ isset($kemas->tanggal) ? \Carbon\Carbon::parse($kemas->tanggal)->format('d M Y') : '-' }}
                            </td>
                            <td>{{ $kemas->jenis_beras ?? '-' }}</td>
                            <td>
                                <span class="badge-custom badge-purple-custom">{{ $kemas->jenis_kemasan ?? '-' }}</span>
                            </td>
                            <td><strong>{{ number_format($kemas->jumlah_kemasan ?? 0) }}</strong> pcs</td>
                            <td>
                                @php $layak = $kemas->kualitas === 'layak jual'; @endphp
                                <span class="badge-custom {{ $layak ? 'badge-success-custom' : 'badge-danger-custom' }}">
                                    {{ $kemas->kualitas ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada data pengemasan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pesanan Masuk Terbaru -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header-clean">
                <h5>Pesanan Terbaru</h5>
                <a href="{{ route('packager.pesanan.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPesanan as $pesan)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $pesan->nama_pelanggan ?? '-' }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">Jml: {{ $pesan->jumlah ?? '-' }}</span>
                            </td>
                            <td>
                                <span style="font-size:.85rem;">{{ $pesan->jenis_produk ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $pesanBadge = match($pesan->status ?? '') {
                                        'selesai'       => 'badge-success-custom',
                                        'diproses'      => 'badge-info-custom',
                                        'dibatalkan'    => 'badge-danger-custom',
                                        default         => 'badge-warning-custom',
                                    };
                                @endphp
                                <span class="badge-custom {{ $pesanBadge }}">{{ ucfirst($pesan->status ?? 'pending') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada pesanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
