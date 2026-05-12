<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\PengirimanBeras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengiriman = PengirimanBeras::where('user_id', Auth::id())
            ->latest('tanggal_kirim')
            ->paginate(10);

        return view('ricemill.pengiriman.index', compact('pengiriman'));
    }

    public function create()
    {
        return view('ricemill.pengiriman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_packager'    => 'required|string|max:255',
            'jenis_beras'      => 'required|in:premium,medium,setra_ramos,pandan_wangi',
            'jumlah_karung'    => 'required|integer|min:1',
            'berat_per_karung' => 'required|numeric|min:1',
            'tanggal_kirim'    => 'required|date',
            'biaya_logistik'   => 'nullable|numeric|min:0',
            'status'           => 'required|in:menunggu,dikirim,diterima,ditolak',
            'bukti_kirim'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'          => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_kirim')) {
            $validated['bukti_kirim'] = $request->file('bukti_kirim')->store('pengiriman', 'public');
        }

        PengirimanBeras::create($validated);

        return redirect()->route('ricemill.pengiriman.index')
            ->with('success', 'Data pengiriman berhasil dicatat!');
    }

    public function edit(PengirimanBeras $pengiriman)
    {
        abort_if($pengiriman->user_id !== Auth::id(), 403);
        return view('ricemill.pengiriman.edit', compact('pengiriman'));
    }

    public function update(Request $request, PengirimanBeras $pengiriman)
    {
        abort_if($pengiriman->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'nama_packager'    => 'required|string|max:255',
            'jenis_beras'      => 'required|in:premium,medium,setra_ramos,pandan_wangi',
            'jumlah_karung'    => 'required|integer|min:1',
            'berat_per_karung' => 'required|numeric|min:1',
            'tanggal_kirim'    => 'required|date',
            'biaya_logistik'   => 'nullable|numeric|min:0',
            'status'           => 'required|in:menunggu,dikirim,diterima,ditolak',
            'bukti_kirim'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'          => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_kirim')) {
            if ($pengiriman->bukti_kirim) {
                Storage::disk('public')->delete($pengiriman->bukti_kirim);
            }
            $validated['bukti_kirim'] = $request->file('bukti_kirim')->store('pengiriman', 'public');
        }

        $pengiriman->update($validated);

        return redirect()->route('ricemill.pengiriman.index')
            ->with('success', 'Data pengiriman berhasil diperbarui!');
    }

    public function destroy(PengirimanBeras $pengiriman)
    {
        abort_if($pengiriman->user_id !== Auth::id(), 403);
        if ($pengiriman->bukti_kirim) {
            Storage::disk('public')->delete($pengiriman->bukti_kirim);
        }
        $pengiriman->delete();
        return redirect()->route('ricemill.pengiriman.index')
            ->with('success', 'Data pengiriman berhasil dihapus!');
    }
}
