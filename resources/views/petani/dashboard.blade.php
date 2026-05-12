@extends('layouts.petani')

@section('title', 'Dashboard Petani')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . Auth::user()->name)

@section('topbar-actions')
    <a href="{{ route('petani.lahan.create') }}" class="btn-primary-custom">
        <span class="iconify" data-icon="heroicons:plus" style="width:16px;height:16px;"></span>
        Tambah Lahan
    </a>
@endsection

@section('content')

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5ee;">
                <span class="iconify" data-icon="heroicons:map" style="color:#1a5c38;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value">{{ $stats['total_lahan'] }}</div>
            <div class="stat-label">Total Lahan</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff8e0;">
                <span class="iconify" data-icon="heroicons:hand-raised" style="color:#a0720f;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value">{{ $stats['total_panen'] }}</div>
            <div class="stat-label">Total Panen</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f0ff;">
                <span class="iconify" data-icon="heroicons:archive-box" style="color:#1a5b8f;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value">{{ $stats['total_setoran'] }}</div>
            <div class="stat-label">Total Setoran</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fde8ff;">
                <span class="iconify" data-icon="heroicons:arrow-trending-up" style="color:#7b1fa2;width:22px;height:22px;"></span>
            </div>
            <div class="stat-value" style="font-size:1.25rem;">
                Rp {{ number_format($stats['pendapatan_bulan'], 0, ',', '.') }}
            </div>
            <div class="stat-label">Pendapatan Bulan Ini</div>
        </div>
    </div>
</div>

<!-- RECENT DATA -->
<div class="row g-3">
    <!-- Panen Terbaru -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Panen Terbaru</h5>
                <a href="{{ route('petani.panen.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Lahan</th>
                            <th>Tanaman</th>
                            <th>Hasil</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPanen as $panen)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $panen->profilLahan->nama_lahan ?? '-' }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">{{ $panen->profilLahan->lokasi ?? '' }}</span>
                            </td>
                            <td>{{ $panen->jenis_tanaman }}</td>
                            <td>
                                <strong>{{ number_format($panen->jumlah_hasil, 0, ',', '.') }}</strong>
                                <span style="color:var(--text-muted);font-size:.8rem;">{{ $panen->satuan }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ $panen->tanggal_panen->format('d M Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada data panen.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Setoran Terbaru -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header-clean">
                <h5>Setoran Terbaru</h5>
                <a href="{{ route('petani.setoran.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSetoran as $setoran)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $setoran->jenis_hasil_panen }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">{{ $setoran->tanggal_setoran->format('d M Y') }}</span>
                            </td>
                            <td>{{ number_format($setoran->jumlah_setoran, 0) }} kg</td>
                            <td>
                                @php
                                    $badge = match($setoran->status) {
                                        'selesai'  => 'badge-success-custom',
                                        'diproses' => 'badge-info-custom',
                                        default    => 'badge-warning-custom',
                                    };
                                @endphp
                                <span class="badge-custom {{ $badge }}">{{ ucfirst($setoran->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada setoran.
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