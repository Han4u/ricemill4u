<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\RiwayatProduksi;
use App\Models\OperasionalPenggilingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produksi = RiwayatProduksi::with('operasional')
            ->where('user_id', Auth::id())
            ->latest('tanggal_proses')
            ->paginate(10);

        return view('ricemill.produksi.index', compact('produksi'));
    }

    public function create()
    {
        $operasional = OperasionalPenggilingan::where('user_id', Auth::id())
            ->where('status', '!=', 'selesai')
            ->get();

        return view('ricemill.produksi.create', compact('operasional'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operasional_id' => 'required|exists:operasional_penggilingan,id',
            'tanggal_proses' => 'required|date',
            'jumlah_beras'   => 'required|numeric|min:0.01',
            'catatan'        => 'nullable|string',
        ]);

        $operasional = OperasionalPenggilingan::findOrFail($request->operasional_id);
        
        $validated['user_id']      = Auth::id();
        $validated['batch_id']     = $operasional->batch_id;
        $validated['jumlah_gabah'] = $operasional->jumlah_gabah_masuk;

        // Cek rendemen rendah (misal di bawah 60%)
        $rendemen = ($request->jumlah_beras / $operasional->jumlah_gabah_masuk) * 100;
        $validated['notifikasi_rendemen_rendah'] = $rendemen < 60;

        RiwayatProduksi::create($validated);

        // Update status operasional & penerimaan gabah
        $operasional->update(['status' => 'selesai']);
        if ($operasional->penerimaanGabah) {
            $operasional->penerimaanGabah->update(['status' => 'selesai']);
        }

        return redirect()->route('ricemill.produksi.index')
            ->with('success', 'Hasil produksi berhasil dicatat!');
    }

    public function destroy(RiwayatProduksi $produksi)
    {
        abort_if($produksi->user_id !== Auth::id(), 403);
        $produksi->delete();
        return redirect()->route('ricemill.produksi.index')
            ->with('success', 'Data produksi berhasil dihapus!');
    }
}
