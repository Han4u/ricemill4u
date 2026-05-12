@extends('layouts.ricemill')

@section('title', 'Edit Operasional')
@section('page-title', 'Edit Operasional')
@section('breadcrumb', 'Operasional / Edit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Edit Data Operasional {{ $operasional->batch_id }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.operasional.update', $operasional) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label-custom">Penerimaan Gabah</label>
                        <select class="form-select-custom" name="penerimaan_gabah_id" required>
                            @foreach($penerimaan as $item)
                                <option value="{{ $item->id }}" {{ $operasional->penerimaan_gabah_id == $item->id ? 'selected' : '' }}>
                                    #TRX-{{ $item->id }} - {{ $item->nama_petani }} ({{ $item->jumlah_gabah }} Kg)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Batch ID</label>
                            <input type="text" class="form-control-custom" name="batch_id" value="{{ $operasional->batch_id }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Proses</label>
                            <input type="date" class="form-control-custom" name="tanggal_proses" value="{{ $operasional->tanggal_proses->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Gabah Masuk (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="jumlah_gabah_masuk" value="{{ $operasional->jumlah_gabah_masuk }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="menunggu" {{ $operasional->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diproses" {{ $operasional->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai"  {{ $operasional->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Proses</label>
                        <textarea class="form-control-custom" name="catatan" rows="3">{{ $operasional->catatan }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.operasional.index') }}" class="btn-outline-custom">
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
