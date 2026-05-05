<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\SetoranPenggilingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SetoranController extends Controller
{
    public function index()
    {
        $setorans = SetoranPenggilingan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $totalPendapatan = SetoranPenggilingan::where('user_id', Auth::id())
            ->sum('total_pendapatan');

        return view('petani.setoran.index', compact('setorans', 'totalPendapatan'));
    }

    public function create()
    {
        return view('petani.setoran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_setoran'    => 'required|date',
            'jenis_hasil_panen'  => 'required|string|max:100',
            'jumlah_setoran'     => 'required|numeric|min:0.01',
            'biaya_penggilingan' => 'nullable|numeric|min:0',
            'hasil_bersih'       => 'nullable|numeric|min:0',
            'total_pendapatan'   => 'nullable|numeric|min:0',
            'catatan'            => 'nullable|string',
            'bukti_nota'         => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_nota')) {
            $validated['bukti_nota'] = $request->file('bukti_nota')->store('setoran', 'public');
        }

        SetoranPenggilingan::create($validated);

        return redirect()->route('petani.setoran.index')
            ->with('success', 'Transaksi setoran berhasil dicatat!');
    }

    public function edit(SetoranPenggilingan $setoran)
    {
        abort_if($setoran->user_id !== Auth::id(), 403);
        return view('petani.setoran.edit', compact('setoran'));
    }

    public function update(Request $request, SetoranPenggilingan $setoran)
    {
        abort_if($setoran->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'tanggal_setoran'    => 'required|date',
            'jenis_hasil_panen'  => 'required|string|max:100',
            'jumlah_setoran'     => 'required|numeric|min:0.01',
            'biaya_penggilingan' => 'nullable|numeric|min:0',
            'hasil_bersih'       => 'nullable|numeric|min:0',
            'total_pendapatan'   => 'nullable|numeric|min:0',
            'catatan'            => 'nullable|string',
            'bukti_nota'         => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('bukti_nota')) {
            if ($setoran->bukti_nota) {
                Storage::disk('public')->delete($setoran->bukti_nota);
            }
            $validated['bukti_nota'] = $request->file('bukti_nota')->store('setoran', 'public');
        }

        $setoran->update($validated);

        return redirect()->route('petani.setoran.index')
            ->with('success', 'Data setoran berhasil diperbarui!');
    }

    public function destroy(SetoranPenggilingan $setoran)
    {
        abort_if($setoran->user_id !== Auth::id(), 403);

        if ($setoran->bukti_nota) {
            Storage::disk('public')->delete($setoran->bukti_nota);
        }

        $setoran->delete();

        return redirect()->route('petani.setoran.index')
            ->with('success', 'Data setoran berhasil dihapus!');
    }
}