@extends('layouts.packager')

@section('title', 'Penerimaan Beras Putih')
@section('page-title', 'Penerimaan Beras Putih')
@section('breadcrumb', 'Dashboard / Penerimaan')

@section('topbar-actions')
<a href="{{ route('packager.penerimaan-beras.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Terima Beras
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Daftar Penerimaan Beras dari Rice Mill</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal Terima</th>
                    <th>No. Surat Jalan</th>
                    <th>Pengirim</th>
                    <th>Jumlah (Kg)</th>
                    <th>Kualitas</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penerimaan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">#SJ-{{ $item->pengiriman_beras_id ?? 'Manual' }}</td>
                    <td>{{ $item->asal_penggilingan }}</td>
                    <td>{{ number_format($item->jumlah_beras, 0, ',', '.') }} Kg</td>
                    <td>{{ $item->jenis_beras }}</td>
                    <td>
                        <span class="badge-custom {{ $item->status == 'diterima' ? 'badge-success-custom' : ($item->status == 'ditolak' ? 'badge-danger-custom' : 'badge-warning-custom') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        @if($item->bukti_foto)
                            <a href="{{ route('packager.penerimaan-beras.bukti', $item) }}" target="_blank" class="btn-outline-custom btn-sm d-inline-flex align-items-center gap-1" style="font-size:0.75rem; padding: 4px 8px;">
                                <span class="iconify" data-icon="heroicons:photo" style="width:14px;height:14px;"></span> Lihat
                            </a>
                        @else
                            <span class="text-muted" style="font-size:0.75rem;">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('packager.penerimaan-beras.edit', $item) }}" class="btn-outline-custom btn-sm">
                                <span class="iconify" data-icon="heroicons:pencil" style="width:14px;height:14px;"></span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:inbox-stack" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data penerimaan beras.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penerimaan->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $penerimaan->links() }}
    </div>
    @endif
</div>
@endsection
