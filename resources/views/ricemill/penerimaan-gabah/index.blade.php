@extends('layouts.ricemill')

@section('title', 'Penerimaan Gabah')
@section('page-title', 'Penerimaan Gabah')
@section('breadcrumb', 'Dashboard / Penerimaan Gabah')

@section('topbar-actions')
<a href="{{ route('ricemill.penerimaan-gabah.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Tambah Penerimaan
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Daftar Penerimaan Gabah</h5>
        <div class="d-flex gap-2">
            <button class="btn-outline-custom btn-sm"><span class="iconify" data-icon="heroicons:funnel"></span> Filter</button>
            <button class="btn-outline-custom btn-sm"><span class="iconify" data-icon="heroicons:arrow-down-tray"></span> Export</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Petani</th>
                    <th>Berat Gabah (Kg)</th>
                    <th>Jenis Gabah</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penerimaan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">#TRX-{{ $item->id }}</td>
                    <td>{{ $item->nama_petani ?? 'Umum' }}</td>
                    <td>{{ number_format($item->jumlah_gabah, 0, ',', '.') }} Kg</td>
                    <td>{{ ucfirst($item->kualitas_gabah) }}</td>
                    <td>
                        <span class="badge-custom {{ $item->status == 'selesai' ? 'badge-success-custom' : ($item->status == 'diproses' ? 'badge-info-custom' : 'badge-warning-custom') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        @if($item->bukti_foto)
                            <a href="{{ route('ricemill.penerimaan-gabah.bukti', $item) }}?t={{ $item->updated_at->timestamp }}" target="_blank" class="btn-outline-custom btn-sm d-inline-flex align-items-center gap-1" style="font-size:0.75rem; padding: 4px 8px;">
                                <span class="iconify" data-icon="heroicons:photo" style="width:14px;height:14px;"></span> Lihat
                            </a>
                        @else
                            <span class="text-muted" style="font-size:0.75rem;">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ricemill.penerimaan-gabah.edit', $item) }}" class="btn-outline-custom btn-sm" title="Edit">
                                <span class="iconify" data-icon="heroicons:pencil" style="width:14px;height:14px;"></span>
                            </a>
                            <form action="{{ route('ricemill.penerimaan-gabah.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline-custom btn-sm text-danger" style="border-color:#f5b8b8;">
                                    <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:inbox-stack" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data penerimaan gabah.</p>
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
