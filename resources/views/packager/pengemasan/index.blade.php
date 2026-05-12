@extends('layouts.packager')

@section('title', 'Hasil Pengemasan')
@section('page-title', 'Hasil Pengemasan')
@section('breadcrumb', 'Dashboard / Pengemasan')

@section('topbar-actions')
<a href="{{ route('packager.pengemasan.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Catat Pengemasan
</a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <span class="iconify" data-icon="heroicons:cube"></span>
            </div>
            <div class="stat-value">{{ number_format($pengemasan->count(), 0, ',', '.') }}</div>
            <div class="stat-label">Total Batch Kemasan</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-clean">
        <h5>Riwayat Pengemasan Beras</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Batch ID</th>
                    <th>Ukuran Kemasan</th>
                    <th>Jumlah Pack</th>
                    <th>Total Berat (Kg)</th>
                    <th>Merek</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengemasan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">#PKG-{{ $item->id }}</td>
                    <td>{{ $item->jenis_kemasan }}</td>
                    <td>{{ number_format($item->jumlah_kemasan, 0, ',', '.') }} Pack</td>
                    <td>{{ $item->jenis_beras }}</td>
                    <td>
                        <span class="badge-custom {{ $item->kualitas == 'layak_jual' ? 'badge-success-custom' : 'badge-danger-custom' }}">
                            {{ str_replace('_', ' ', ucfirst($item->kualitas)) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('packager.pengemasan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
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
                        <span class="iconify" data-icon="heroicons:cube" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data pengemasan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengemasan->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $pengemasan->links() }}
    </div>
    @endif
</div>
@endsection
