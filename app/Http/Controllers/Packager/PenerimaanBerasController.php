<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBeras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaanBerasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penerimaan = PenerimaanBeras::with('pengirimanBeras')
            ->where('user_id', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        return view('packager.penerimaan-beras.index', compact('penerimaan'));
    }

    public function create()
    {
        // Ambil pengiriman dari Rice Mill yang statusnya 'dikirim'
        $pengiriman = \App\Models\PengirimanBeras::where('status', 'dikirim')->get();
        return view('packager.penerimaan-beras.create', compact('pengiriman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengiriman_beras_id' => 'nullable|exists:pengiriman_beras,id',
            'asal_penggilingan'   => 'required|string|max:255',
            'jenis_beras'         => 'required|string|max:100',
            'jumlah_beras'        => 'required|numeric|min:0.01',
            'tanggal'             => 'required|date',
            'status'              => 'required|in:diterima,ditolak,sebagian',
            'bukti_foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'             => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('penerimaan_beras', 'public');
        }

        PenerimaanBeras::create($validated);

        // Jika ada kaitan dengan pengiriman rice mill, update status pengirimannya
        if ($request->pengiriman_beras_id) {
            $pengiriman = \App\Models\PengirimanBeras::find($request->pengiriman_beras_id);
            $pengiriman->update(['status' => 'diterima']);
        }

        return redirect()->route('packager.penerimaan-beras.index')
            ->with('success', 'Penerimaan beras berhasil dicatat!');
    }

    public function edit(PenerimaanBeras $penerimaan)
    {
        abort_if($penerimaan->user_id !== Auth::id(), 403);
        return view('packager.penerimaan-beras.edit', compact('penerimaan'));
    }

    public function update(Request $request, PenerimaanBeras $penerimaan)
    {
        abort_if($penerimaan->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'asal_penggilingan' => 'required|string|max:255',
            'jenis_beras'       => 'required|string|max:100',
            'jumlah_beras'      => 'required|numeric|min:0.01',
            'tanggal'           => 'required|date',
            'status'            => 'required|in:diterima,ditolak,sebagian',
            'bukti_foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'           => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_foto')) {
            if ($penerimaan->bukti_foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($penerimaan->bukti_foto);
            }
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('penerimaan_beras', 'public');
        }

        $penerimaan->update($validated);

        return redirect()->route('packager.penerimaan-beras.index')
            ->with('success', 'Data penerimaan berhasil diperbarui!');
    }

    public function destroy(PenerimaanBeras $penerimaan)
    {
        abort_if($penerimaan->user_id !== Auth::id(), 403);
        if ($penerimaan->bukti_foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($penerimaan->bukti_foto);
        }
        $penerimaan->delete();
        return redirect()->route('packager.penerimaan-beras.index')
            ->with('success', 'Data penerimaan berhasil dihapus!');
    }
}
