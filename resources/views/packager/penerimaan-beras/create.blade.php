@extends('layouts.packager')

@section('title', 'Terima Beras Putih')
@section('page-title', 'Penerimaan Baru')
@section('breadcrumb', 'Penerimaan / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Penerimaan Beras dari Rice Mill</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('packager.penerimaan-beras.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-custom">Pilih Surat Jalan (Pengiriman Rice Mill)</label>
                        <select class="form-select-custom" name="pengiriman_beras_id" onchange="fillData(this)">
                            <option value="">-- Input Manual atau Pilih SJ --</option>
                            @foreach($pengiriman as $item)
                                <option value="{{ $item->id }}" 
                                        data-asal="{{ $item->operator->name ?? 'Rice Mill' }}" 
                                        data-jenis="{{ $item->jenis_beras }}" 
                                        data-jumlah="{{ $item->jumlah_karung * $item->berat_per_karung }}">
                                    #SJ-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }} - {{ $item->jenis_beras }} ({{ $item->jumlah_karung * $item->berat_per_karung }} Kg)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Asal Penggilingan / Rice Mill</label>
                            <input type="text" class="form-control-custom" name="asal_penggilingan" id="asal_penggilingan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Terima</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Beras</label>
                            <input type="text" class="form-control-custom" name="jenis_beras" id="jenis_beras" placeholder="Contoh: Premium IR64" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Beras (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="jumlah_beras" id="jumlah_beras" placeholder="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Status Penerimaan</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="sebagian">Diterima Sebagian</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Bukti Terima (Foto/Nota)</label>
                            <input type="file" class="form-control-custom" name="bukti_foto" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan / Komplain</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('packager.penerimaan-beras.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Penerimaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function fillData(select) {
    const option = select.options[select.selectedIndex];
    if (option.value) {
        document.getElementById('asal_penggilingan').value = option.getAttribute('data-asal');
        document.getElementById('jenis_beras').value = option.getAttribute('data-jenis');
        document.getElementById('jumlah_beras').value = option.getAttribute('data-jumlah');
    }
}
</script>
@endsection
