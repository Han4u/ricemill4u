@extends('layouts.ricemill')

@section('title', 'Riwayat Produksi')
@section('page-title', 'Riwayat Produksi')
@section('breadcrumb', 'Dashboard / Produksi')

@section('topbar-actions')
<a href="{{ route('ricemill.produksi.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Catat Hasil Produksi
</a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <span class="iconify" data-icon="heroicons:archive-box"></span>
            </div>
            <div class="stat-value">{{ number_format($produksi->sum('jumlah_beras'), 0, ',', '.') }} Kg</div>
            <div class="stat-label">Total Produksi Beras</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <span class="iconify" data-icon="heroicons:receipt-percent"></span>
            </div>
            @php
                $totalGabah = $produksi->sum('jumlah_gabah');
                $totalBeras = $produksi->sum('jumlah_beras');
                $rendemen = $totalGabah > 0 ? round(($totalBeras / $totalGabah) * 100, 1) : 0;
            @endphp
            <div class="stat-value">{{ $rendemen }}%</div>
            <div class="stat-label">Rata-rata Rendemen</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-clean">
        <h5>Laporan Produksi Harian</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Batch</th>
                    <th>Input Gabah</th>
                    <th>Output Beras</th>
                    <th>Rendemen</th>
                    <th>Kualitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksi as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_proses)->format('d M Y') }}</td>
                    <td class="fw-medium">{{ $item->batch_id }}</td>
                    <td>{{ number_format($item->jumlah_gabah, 0, ',', '.') }} Kg</td>
                    <td class="fw-bold text-success">{{ number_format($item->jumlah_beras, 0, ',', '.') }} Kg</td>
                    <td>
                        <span class="{{ $item->notifikasi_rendemen_rendah ? 'text-danger fw-bold' : '' }}">
                            {{ $item->rendemen }}%
                        </span>
                        @if($item->notifikasi_rendemen_rendah)
                            <span class="iconify text-danger" data-icon="heroicons:exclamation-triangle" title="Rendemen Rendah"></span>
                        @endif
                    </td>
                    <td><span class="badge-custom badge-info-custom">Standard</span></td>
                    <td>
                        <form action="{{ route('ricemill.produksi.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-outline-custom btn-sm text-danger" style="border-color:#f5b8b8;">
                                <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:arrow-trending-up" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data riwayat produksi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produksi->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $produksi->links() }}
    </div>
    @endif
</div>
@endsection
