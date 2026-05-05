@extends('layouts.petani')

@section('title', 'Setoran Penggilingan')
@section('page-title', 'Setoran Penggilingan')
@section('breadcrumb', 'Setoran → Daftar')

@section('topbar-actions')
    <a href="{{ route('petani.setoran.create') }}" class="btn-primary-custom">
        <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Setoran
    </a>
@endsection

@section('content')

<!-- TOTAL PENDAPATAN -->
<div class="card mb-4" style="background:linear-gradient(135deg,#1a3a5c,#1a5b8f);border:none;">
    <div style="padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div style="color:rgba(255,255,255,.65);font-size:.8rem;margin-bottom:4px;">Total Pendapatan dari Setoran</div>
            <div style="color:#fff;font-size:1.6rem;font-weight:600;">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>
        <i data-lucide="package" style="width:40px;height:40px;color:rgba(255,255,255,.3);"></i>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Hasil</th>
                    <th>Jumlah</th>
                    <th>Hasil Bersih</th>
                    <th>Pendapatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($setorans as $setoran)
                <tr>
                    <td style="color:var(--text-muted);font-size:.85rem;">
                        {{ $setoran->tanggal_setoran->format('d M Y') }}
                    </td>
                    <td><span style="font-weight:500;">{{ $setoran->jenis_hasil_panen }}</span></td>
                    <td>{{ number_format($setoran->jumlah_setoran, 0) }} kg</td>
                    <td>
                        @if($setoran->hasil_bersih)
                            {{ number_format($setoran->hasil_bersih, 0) }} kg
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($setoran->total_pendapatan)
                            <span style="color:#1a5c38;font-weight:500;">
                                Rp {{ number_format($setoran->total_pendapatan, 0, ',', '.') }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
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
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('petani.setoran.edit', $setoran) }}"
                               class="btn-outline-custom" style="padding:6px 10px;">
                                <i data-lucide="pencil" style="width:14px;height:14px;"></i>
                            </a>
                            <form action="{{ route('petani.setoran.destroy', $setoran) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus data setoran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline-custom"
                                        style="padding:6px 10px;color:#c0392b;border-color:#f5b8b8;">
                                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">
                        <i data-lucide="package" style="width:40px;height:40px;margin-bottom:12px;display:block;margin-inline:auto;"></i>
                        Belum ada data setoran.<br>
                        <a href="{{ route('petani.setoran.create') }}" style="color:var(--primary);font-weight:500;">
                            Tambah setoran pertamamu →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($setorans->hasPages())
    <div style="padding:16px 24px;border-top:1px solid var(--border);">
        {{ $setorans->links() }}
    </div>
    @endif
</div>
@endsection