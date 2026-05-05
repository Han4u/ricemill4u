@extends('layouts.petani')

@section('title', 'Riwayat Panen')
@section('page-title', 'Riwayat Panen')
@section('breadcrumb', 'Panen → Daftar Riwayat')

@section('topbar-actions')
    <a href="{{ route('petani.panen.create') }}" class="btn-primary-custom">
        <i data-lucide="plus" style="width:16px;height:16px;"></i> Catat Panen
    </a>
@endsection

@section('content')

<!-- STAT TOTAL BULAN INI -->
<div class="card mb-4" style="background:linear-gradient(135deg,#1a5c38,#2d7a50);border:none;">
    <div style="padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div style="color:rgba(255,255,255,.65);font-size:.8rem;margin-bottom:4px;">Total Panen Bulan Ini</div>
            <div style="color:#fff;font-size:1.6rem;font-weight:600;">
                {{ number_format($totalBulanIni, 0, ',', '.') }} kg
            </div>
        </div>
        <i data-lucide="wheat" style="width:40px;height:40px;color:rgba(255,255,255,.3);"></i>
    </div>
</div>

<!-- FILTER -->
<div class="card mb-4">
    <div style="padding:18px 24px;">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label-custom">Bulan</label>
                <select name="bulan" class="form-select-custom">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ request('bulan')==$b ? 'selected':'' }}>
                            {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Tahun</label>
                <select name="tahun" class="form-select-custom">
                    <option value="">Semua Tahun</option>
                    @foreach(range(now()->year, now()->year-5) as $y)
                        <option value="{{ $y }}" {{ request('tahun')==$y ? 'selected':'' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label-custom">Lahan</label>
                <select name="lahan_id" class="form-select-custom">
                    <option value="">Semua Lahan</option>
                    @foreach($lahans as $lahan)
                        <option value="{{ $lahan->id }}" {{ request('lahan_id')==$lahan->id ? 'selected':'' }}>
                            {{ $lahan->nama_lahan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-primary-custom w-100" style="justify-content:center;">
                    <i data-lucide="filter" style="width:15px;height:15px;"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Lahan</th>
                    <th>Tanaman</th>
                    <th>Tanggal Panen</th>
                    <th>Hasil</th>
                    <th>Pendapatan</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($panens as $panen)
                <tr>
                    <td>
                        <span style="font-weight:500;">{{ $panen->profilLahan->nama_lahan ?? '-' }}</span><br>
                        <span style="font-size:.75rem;color:var(--text-muted);">{{ $panen->profilLahan->lokasi ?? '' }}</span>
                    </td>
                    <td>
                        <span class="badge-custom badge-success-custom">{{ $panen->jenis_tanaman }}</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:.85rem;">
                        {{ $panen->tanggal_panen->format('d M Y') }}
                    </td>
                    <td>
                        <strong>{{ number_format($panen->jumlah_hasil, 0, ',', '.') }}</strong>
                        <span style="color:var(--text-muted);font-size:.8rem;">{{ $panen->satuan }}</span>
                    </td>
                    <td>
                        @if($panen->total_pendapatan)
                            <span style="color:#1a5c38;font-weight:500;">
                                Rp {{ number_format($panen->total_pendapatan, 0, ',', '.') }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($panen->bukti_foto)
                            <a href="{{ Storage::url($panen->bukti_foto) }}" target="_blank">
                                <img src="{{ Storage::url($panen->bukti_foto) }}"
                                     style="width:36px;height:36px;border-radius:8px;object-fit:cover;">
                            </a>
                        @else
                            <span style="color:var(--text-muted);font-size:.8rem;">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('petani.panen.edit', $panen) }}"
                               class="btn-outline-custom" style="padding:6px 10px;">
                                <i data-lucide="pencil" style="width:14px;height:14px;"></i>
                            </a>
                            <form action="{{ route('petani.panen.destroy', $panen) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus data panen ini?')">
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
                        <i data-lucide="wheat" style="width:40px;height:40px;margin-bottom:12px;display:block;margin-inline:auto;"></i>
                        Belum ada data panen.<br>
                        <a href="{{ route('petani.panen.create') }}" style="color:var(--primary);font-weight:500;">
                            Catat panen pertamamu →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($panens->hasPages())
    <div style="padding:16px 24px;border-top:1px solid var(--border);">
        {{ $panens->links() }}
    </div>
    @endif
</div>
@endsection