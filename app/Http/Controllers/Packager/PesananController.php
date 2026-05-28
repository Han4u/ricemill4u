<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        // Data untuk statistik
        $terlaris = Pesanan::where('user_id', Auth::id())
            ->select('jenis_produk', \DB::raw('SUM(jumlah) as total_qty'))
            ->groupBy('jenis_produk')
            ->orderByDesc('total_qty')
            ->first();

        return view('packager.pesanan.index', compact('pesanan', 'terlaris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('packager.pesanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'jenis_produk'   => 'required|string|max:100',
            'jumlah'         => 'required|integer|min:1',
            'harga_satuan'   => 'required|numeric|min:0',
            'status'         => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
            'catatan'        => 'nullable|string',
        ]);

        $validated['user_id']     = Auth::id();
        $validated['total_harga'] = $request->jumlah * $request->harga_satuan;

        Pesanan::create($validated);

        return redirect()->route('packager.pesanan.index')
            ->with('success', 'Pesanan berhasil dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pesanan $pesanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== Auth::id(), 403);
        
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
        ]);

        $pesanan->update($validated);

        return redirect()->route('packager.pesanan.index')
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== Auth::id(), 403);
        $pesanan->delete();
        return redirect()->route('packager.pesanan.index')
            ->with('success', 'Data pesanan berhasil dihapus!');
    }
}
