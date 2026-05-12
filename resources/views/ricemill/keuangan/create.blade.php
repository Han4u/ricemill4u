@extends('layouts.ricemill')

@section('title', 'Catat Transaksi Keuangan')
@section('page-title', 'Transaksi Baru')
@section('breadcrumb', 'Keuangan / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Pencatatan Kas</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.keuangan.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tipe Transaksi</label>
                            <select class="form-select-custom" name="tipe" required>
                                <option value="pemasukan">Pemasukan (+)</option>
                                <option value="pengeluaran">Pengeluaran (-)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kategori</label>
                            <select class="form-select-custom" name="kategori" required>
                                <option value="Penjualan Beras">Penjualan Beras</option>
                                <option value="Jasa Penggilingan">Jasa Penggilingan</option>
                                <option value="Biaya Listrik/Mesin">Biaya Listrik/Mesin</option>
                                <option value="Gaji Karyawan">Gaji Karyawan</option>
                                <option value="Logistik/Transport">Logistik/Transport</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah (Rp)</label>
                            <input type="number" class="form-control-custom" name="jumlah" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Keterangan Singkat</label>
                        <input type="text" class="form-control-custom" name="keterangan" placeholder="Contoh: Pembayaran Jasa Giling Bpk. Ahmad" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Tambahan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.keuangan.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
