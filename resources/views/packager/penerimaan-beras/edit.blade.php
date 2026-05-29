@extends('layouts.packager')

@section('title', 'Edit Penerimaan Beras')
@section('page-title', 'Edit Penerimaan')
@section('breadcrumb', 'Penerimaan / Edit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Edit Data Penerimaan #TRX-{{ $penerimaan->id }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('packager.penerimaan-beras.update', $penerimaan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Asal Penggilingan / Rice Mill</label>
                            <input type="text" class="form-control-custom" name="asal_penggilingan" value="{{ $penerimaan->asal_penggilingan }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Terima</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ $penerimaan->tanggal->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Beras</label>
                            <input type="text" class="form-control-custom" name="jenis_beras" value="{{ $penerimaan->jenis_beras }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Beras (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="jumlah_beras" value="{{ $penerimaan->jumlah_beras }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Status Penerimaan</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="diterima" {{ $penerimaan->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak"  {{ $penerimaan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="sebagian" {{ $penerimaan->status == 'sebagian' ? 'selected' : '' }}>Diterima Sebagian</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Update Bukti Terima</label>
                            <input type="file" class="form-control-custom" name="bukti_foto" accept="image/*">
                            @if($penerimaan->bukti_foto)
                                <div class="mt-2">
                                    <span class="text-muted d-block mb-1" style="font-size:0.8rem;">Bukti Terima Saat Ini:</span>
                                    <a href="{{ route('packager.penerimaan-beras.bukti', $penerimaan) }}" target="_blank">
                                        <img src="{{ route('packager.penerimaan-beras.bukti', $penerimaan) }}" alt="Bukti Terima" class="img-thumbnail" style="max-height:100px; object-fit:cover;">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan / Komplain</label>
                        <textarea class="form-control-custom" name="catatan" rows="3">{{ $penerimaan->catatan }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('packager.penerimaan-beras.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
