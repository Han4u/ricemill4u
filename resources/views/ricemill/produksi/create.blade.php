@extends('layouts.ricemill')

@section('title', 'Catat Hasil Produksi')
@section('page-title', 'Produksi Baru')
@section('breadcrumb', 'Produksi / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Hasil Produksi (Output Beras)</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.produksi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-custom">Pilih Operasional (Batch)</label>
                        <select class="form-select-custom" name="operasional_id" required>
                            <option value="">Pilih batch yang sedang diproses...</option>
                            @foreach($operasional as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->batch_id }} - {{ $item->penerimaanGabah->nama_petani }} (Gabah: {{ $item->jumlah_gabah_masuk }} Kg)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya menampilkan operasional yang belum selesai.</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Produksi Selesai</label>
                            <input type="date" class="form-control-custom" name="tanggal_proses" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Hasil Beras (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="jumlah_beras" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Produksi</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.produksi.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Hasil Produksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
