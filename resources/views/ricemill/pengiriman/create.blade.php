@extends('layouts.ricemill')

@section('title', 'Buat Pengiriman Beras')
@section('page-title', 'Pengiriman Baru')
@section('breadcrumb', 'Pengiriman / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Pengiriman ke Packager</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.pengiriman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Packager / Tujuan</label>
                            <input type="text" class="form-control-custom" name="nama_packager" placeholder="Contoh: PT. Kemas Sejahtera" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Kirim</label>
                            <input type="date" class="form-control-custom" name="tanggal_kirim" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Beras</label>
                            <select class="form-select-custom" name="jenis_beras" required>
                                <option value="premium">Premium</option>
                                <option value="medium">Medium</option>
                                <option value="setra_ramos">Setra Ramos</option>
                                <option value="pandan_wangi">Pandan Wangi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Biaya Logistik (Rp)</label>
                            <input type="number" class="form-control-custom" name="biaya_logistik" placeholder="0">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Karung</label>
                            <input type="number" class="form-control-custom" name="jumlah_karung" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Berat per Karung (Kg)</label>
                            <input type="number" class="form-control-custom" name="berat_per_karung" placeholder="50" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="menunggu">Menunggu</option>
                                <option value="dikirim" selected>Dikirim</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Bukti Kirim (Surat Jalan/Foto)</label>
                            <input type="file" class="form-control-custom" name="bukti_kirim" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Tambahan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.pengiriman.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Pengiriman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
