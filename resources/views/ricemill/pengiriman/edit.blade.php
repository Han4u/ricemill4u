@extends('layouts.ricemill')

@section('title', 'Edit Pengiriman')
@section('page-title', 'Edit Pengiriman')
@section('breadcrumb', 'Pengiriman / Edit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Edit Data Pengiriman #SJ-{{ str_pad($pengiriman->id, 5, '0', STR_PAD_LEFT) }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.pengiriman.update', $pengiriman) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Packager / Tujuan</label>
                            <input type="text" class="form-control-custom" name="nama_packager" value="{{ $pengiriman->nama_packager }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Kirim</label>
                            <input type="date" class="form-control-custom" name="tanggal_kirim" value="{{ $pengiriman->tanggal_kirim->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Beras</label>
                            <select class="form-select-custom" name="jenis_beras" required>
                                <option value="premium"      {{ $pengiriman->jenis_beras == 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="medium"       {{ $pengiriman->jenis_beras == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="setra_ramos"  {{ $pengiriman->jenis_beras == 'setra_ramos' ? 'selected' : '' }}>Setra Ramos</option>
                                <option value="pandan_wangi" {{ $pengiriman->jenis_beras == 'pandan_wangi' ? 'selected' : '' }}>Pandan Wangi</option>
                                <option value="biasa"         {{ $pengiriman->jenis_beras == 'biasa' ? 'selected' : '' }}>Biasa</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Biaya Logistik (Rp)</label>
                            <input type="number" class="form-control-custom" name="biaya_logistik" value="{{ $pengiriman->biaya_logistik }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Karung</label>
                            <input type="number" class="form-control-custom" name="jumlah_karung" value="{{ $pengiriman->jumlah_karung }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Berat per Karung (Kg)</label>
                            <input type="number" class="form-control-custom" name="berat_per_karung" value="{{ $pengiriman->berat_per_karung }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="menunggu" {{ $pengiriman->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="dikirim"  {{ $pengiriman->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="diterima" {{ $pengiriman->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak"  {{ $pengiriman->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="diproses" {{ $pengiriman->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Update Bukti Kirim</label>
                            <input type="file" class="form-control-custom" name="bukti_kirim" accept="image/*">
                            @if($pengiriman->bukti_kirim)
                                <div class="mt-2">
                                    <span class="text-muted d-block mb-1" style="font-size:0.8rem;">Bukti Kirim Saat Ini:</span>
                                    <a href="{{ asset('storage/' . $pengiriman->bukti_kirim) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $pengiriman->bukti_kirim) }}" alt="Bukti Kirim" class="img-thumbnail" style="max-height:100px; object-fit:cover;">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Tambahan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3">{{ $pengiriman->catatan }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.pengiriman.index') }}" class="btn-outline-custom">
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
