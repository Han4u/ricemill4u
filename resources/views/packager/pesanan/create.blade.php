@extends('layouts.packager')

@section('title', 'Input Pesanan Manual')
@section('page-title', 'Pesanan Baru')
@section('breadcrumb', 'Pesanan / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Pencatatan Pesanan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('packager.pesanan.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Pelanggan</label>
                            <input type="text" class="form-control-custom" name="nama_pelanggan" placeholder="Contoh: Toko Beras Maju" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Pesanan</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Produk</label>
                            <select class="form-select-custom" name="jenis_produk" required>
                                <option value="Beras Premium 5kg">Beras Premium 5kg</option>
                                <option value="Beras Premium 10kg">Beras Premium 10kg</option>
                                <option value="Beras Premium 25kg">Beras Premium 25kg</option>
                                <option value="Beras Medium 5kg">Beras Medium 5kg</option>
                                <option value="Beras Medium 10kg">Beras Medium 10kg</option>
                                <option value="Beras Medium 25kg">Beras Medium 25kg</option>
                                <option value="Beras Biasa 5kg">Beras Biasa 5kg</option>
                                <option value="Beras Biasa 10kg">Beras Biasa 10kg</option>
                                <option value="Beras Biasa 25kg">Beras Biasa 25kg</option>
                                <option value="Beras Setra Ramos 5kg">Beras Setra Ramos 5kg</option>
                                <option value="Beras Setra Ramos 10kg">Beras Setra Ramos 10kg</option>
                                <option value="Beras Pandan Wangi 5kg">Beras Pandan Wangi 5kg</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Status Awal</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="menunggu">Menunggu</option>
                                <option value="diproses">Diproses</option>
                                <option value="dikirim">Dikirim</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah (Pack/Karung)</label>
                            <input type="number" class="form-control-custom" name="jumlah" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Satuan (Rp)</label>
                            <input type="number" class="form-control-custom" name="harga_satuan" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan / Alamat Kirim</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('packager.pesanan.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
