@extends('layouts.packager')

@section('title', 'Catat Hasil Pengemasan')
@section('page-title', 'Pengemasan Baru')
@section('breadcrumb', 'Pengemasan / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Hasil Produksi Pengemasan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('packager.pengemasan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-custom">Pilih Stok Beras Putih</label>
                        <select class="form-select-custom" name="penerimaan_beras_id" onchange="fillBeras(this)" required>
                            <option value="">-- Pilih Penerimaan --</option>
                            @foreach($penerimaan as $item)
                                <option value="{{ $item->id }}" data-jenis="{{ $item->jenis_beras }}">
                                    #SJ-{{ $item->pengiriman_beras_id ?? $item->id }} - {{ $item->jenis_beras }} (Stok: {{ $item->jumlah_beras }} Kg)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Beras</label>
                            <input type="text" class="form-control-custom" name="jenis_beras" id="jenis_beras" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Pengemasan</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Ukuran Kemasan</label>
                            <select class="form-select-custom" name="jenis_kemasan" required>
                                <option value="5kg">5 Kg</option>
                                <option value="10kg">10 Kg</option>
                                <option value="25kg">25 Kg</option>
                                <option value="50kg">50 Kg</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Pack / Karung</label>
                            <input type="number" class="form-control-custom" name="jumlah_kemasan" placeholder="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Kualitas Hasil</label>
                            <select class="form-select-custom" name="kualitas" required>
                                <option value="layak_jual">Layak Jual</option>
                                <option value="reject">Reject / Rusak</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Tambahan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('packager.pengemasan.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Data Kemasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function fillBeras(select) {
    const option = select.options[select.selectedIndex];
    if (option.value) {
        document.getElementById('jenis_beras').value = option.getAttribute('data-jenis');
    }
}
</script>
@endsection
