@extends('layouts.ricemill')

@section('title', 'Mulai Proses Penggilingan')
@section('page-title', 'Operasional Baru')
@section('breadcrumb', 'Operasional / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Operasional Penggilingan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.operasional.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-custom">Pilih Gabah Masuk</label>
                        <select class="form-select-custom" name="penerimaan_gabah_id" required>
                            <option value="">Pilih data penerimaan...</option>
                            @foreach($penerimaan as $item)
                                <option value="{{ $item->id }}">
                                    #TRX-{{ $item->id }} - {{ $item->nama_petani }} ({{ $item->jumlah_gabah }} Kg)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya menampilkan gabah yang belum selesai diproses.</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Batch ID / Kode Proses</label>
                            <input type="text" class="form-control-custom" name="batch_id" placeholder="Contoh: BATCH-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Proses</label>
                            <input type="date" class="form-control-custom" name="tanggal_proses" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Gabah Masuk (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="jumlah_gabah_masuk" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Status Awal</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="menunggu">Menunggu</option>
                                <option value="diproses" selected>Diproses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Proses</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Contoh: Menggunakan mesin A..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.operasional.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan & Mulai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
