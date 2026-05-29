@extends('layouts.petani')

@section('title', 'Profil Lahan')
@section('page-title', 'Profil Lahan')
@section('breadcrumb', 'Manajemen → Profil Lahan')

@section('topbar-actions')
    <a href="{{ route('petani.lahan.create') }}" class="btn-primary-custom">
        <span class="iconify" data-icon="heroicons:plus" style="width:16px;height:16px;"></span> Tambah Lahan
    </a>
@endsection

@section('content')

<!-- FILTER & SEARCH -->
<div class="card mb-4">
    <div style="padding:18px 24px;">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label-custom">Cari Nama / Lokasi</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control-custom" placeholder="Ketik nama lahan atau lokasi...">
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Jenis Tanah</label>
                <select name="jenis_tanah" class="form-select-custom">
                    <option value="">Semua Jenis</option>
                    <option value="tanah_liat"   {{ request('jenis_tanah')=='tanah_liat'   ? 'selected':'' }}>Tanah Liat</option>
                    <option value="tanah_pasir"  {{ request('jenis_tanah')=='tanah_pasir'  ? 'selected':'' }}>Tanah Pasir</option>
                    <option value="tanah_humus"  {{ request('jenis_tanah')=='tanah_humus'  ? 'selected':'' }}>Tanah Humus</option>
                    <option value="tanah_gambut" {{ request('jenis_tanah')=='tanah_gambut' ? 'selected':'' }}>Tanah Gambut</option>
                    <option value="lainnya"      {{ request('jenis_tanah')=='lainnya'      ? 'selected':'' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Luas Min (ha)</label>
                <input type="number" name="luas_min" value="{{ request('luas_min') }}"
                       class="form-control-custom" placeholder="0">
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Luas Max (ha)</label>
                <input type="number" name="luas_max" value="{{ request('luas_max') }}"
                       class="form-control-custom" placeholder="999">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn-primary-custom w-100" style="justify-content:center;">
                    <span class="iconify" data-icon="heroicons:magnifying-glass" style="width:16px;height:16px;"></span>
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
                    <th>Lokasi</th>
                    <th>Luas (ha)</th>
                    <th>Jenis Tanah</th>
                    <th>Jumlah Panen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lahans as $lahan)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($lahan->foto)
                                <img src="{{ route('petani.lahan.bukti', $lahan) }}?t={{ $lahan->updated_at->timestamp }}"
                                     style="width:40px;height:40px;border-radius:10px;object-fit:cover;">
                            @else
                                <div style="width:40px;height:40px;border-radius:10px;
                                            background:#e8f5ee;display:flex;align-items:center;justify-content:center;">
                                    <span class="iconify" data-icon="heroicons:map" style="width:18px;color:#1a5c38;"></span>
                                </div>
                            @endif
                            <span style="font-weight:500;">{{ $lahan->nama_lahan }}</span>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);">{{ $lahan->lokasi }}</td>
                    <td><strong>{{ number_format($lahan->luas_lahan, 2) }}</strong></td>
                    <td>
                        <span class="badge-custom badge-success-custom">
                            {{ $lahan->jenis_tanah_label }}
                        </span>
                    </td>
                    <td>{{ $lahan->riwayatPanen()->count() }} panen</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('petani.lahan.show', $lahan) }}"
                               class="btn-outline-custom" style="padding:6px 10px;">
                                <span class="iconify" data-icon="heroicons:eye" style="width:14px;height:14px;"></span>
                            </a>
                            <a href="{{ route('petani.lahan.edit', $lahan) }}"
                               class="btn-outline-custom" style="padding:6px 10px;">
                                <span class="iconify" data-icon="heroicons:pencil" style="width:14px;height:14px;"></span>
                            </a>
                            <form action="{{ route('petani.lahan.destroy', $lahan) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus lahan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline-custom"
                                        style="padding:6px 10px;color:#c0392b;border-color:#f5b8b8;">
                                    <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:48px;color:var(--text-muted);">
                        <span class="iconify" data-icon="heroicons:map" style="width:40px;height:40px;margin-bottom:12px;display:block;margin-inline:auto;"></span>
                        Belum ada lahan yang terdaftar.<br>
                        <a href="{{ route('petani.lahan.create') }}" style="color:var(--primary);font-weight:500;">
                            Tambah lahan pertamamu →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($lahans->hasPages())
    <div style="padding:16px 24px;border-top:1px solid var(--border);">
        {{ $lahans->links() }}
    </div>
    @endif
</div>
@endsection