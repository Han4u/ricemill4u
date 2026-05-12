@extends('layouts.ricemill')

@section('title', 'Operasional Penggilingan')
@section('page-title', 'Operasional Penggilingan')
@section('breadcrumb', 'Dashboard / Operasional')

@section('topbar-actions')
<a href="{{ route('ricemill.operasional.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:play-circle"></span> Mulai Proses Baru
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Antrean & Riwayat Penggilingan</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal Proses</th>
                    <th>Batch ID</th>
                    <th>Mesin</th>
                    <th>Kapasitas (Kg)</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($operasional as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_proses)->format('d M Y') }}</td>
                    <td class="fw-medium">{{ $item->batch_id }}</td>
                    <td>{{ $item->penerimaanGabah->nama_petani ?? '-' }}</td>
                    <td>{{ number_format($item->jumlah_gabah_masuk, 0, ',', '.') }} Kg</td>
                    <td>
                        @if($item->status == 'selesai')
                            <span class="badge-custom badge-success-custom">Selesai</span>
                        @elseif($item->status == 'diproses')
                            <span class="badge-custom badge-info-custom">Diproses</span>
                        @else
                            <span class="badge-custom badge-warning-custom">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ricemill.operasional.edit', $item) }}" class="btn-outline-custom btn-sm">
                                <span class="iconify" data-icon="heroicons:pencil" style="width:14px;height:14px;"></span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:cog-6-tooth" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data operasional.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($operasional->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $operasional->links() }}
    </div>
    @endif
</div>
@endsection
