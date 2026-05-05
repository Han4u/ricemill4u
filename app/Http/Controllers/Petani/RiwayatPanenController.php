<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\ProfilLahan;
use App\Models\RiwayatPanen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatPanenController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatPanen::with('profilLahan')->where('user_id', Auth::id());

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_panen', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_panen', $request->tahun);
        }
        if ($request->filled('lahan_id')) {
            $query->where('profil_lahan_id', $request->lahan_id);
        }

        $panens   = $query->latest('tanggal_panen')->paginate(10)->withQueryString();
        $lahans   = ProfilLahan::where('user_id', Auth::id())->get();

        $totalBulanIni = RiwayatPanen::where('user_id', Auth::id())
            ->whereMonth('tanggal_panen', now()->month)
            ->whereYear('tanggal_panen', now()->year)
            ->sum('jumlah_hasil');

        return view('petani.panen.index', compact('panens', 'lahans', 'totalBulanIni'));
    }

    public function create()
    {
        $lahans = ProfilLahan::where('user_id', Auth::id())->get();
        return view('petani.panen.create', compact('lahans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'profil_lahan_id' => 'required|exists:profil_lahans,id',
            'tanggal_panen'   => 'required|date',
            'jenis_tanaman'   => 'required|string|max:100',
            'jumlah_hasil'    => 'required|numeric|min:0.01',
            'satuan'          => 'required|in:kg,ton,kwintal',
            'harga_per_kg'    => 'nullable|numeric|min:0',
            'catatan'         => 'nullable|string',
            'bukti_foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('panen', 'public');
        }

        $lahan = ProfilLahan::findOrFail($validated['profil_lahan_id']);
        abort_if($lahan->user_id !== Auth::id(), 403);

        RiwayatPanen::create($validated);

        return redirect()->route('petani.panen.index')
            ->with('success', 'Riwayat panen berhasil dicatat!');
    }

    public function show(RiwayatPanen $panen)
    {
        abort_if($panen->user_id !== Auth::id(), 403);
        $panen->load('profilLahan');
        return view('petani.panen.show', compact('panen'));
    }

    public function edit(RiwayatPanen $panen)
    {
        abort_if($panen->user_id !== Auth::id(), 403);
        $lahans = ProfilLahan::where('user_id', Auth::id())->get();
        return view('petani.panen.edit', compact('panen', 'lahans'));
    }

    public function update(Request $request, RiwayatPanen $panen)
    {
        abort_if($panen->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'profil_lahan_id' => 'required|exists:profil_lahans,id',
            'tanggal_panen'   => 'required|date',
            'jenis_tanaman'   => 'required|string|max:100',
            'jumlah_hasil'    => 'required|numeric|min:0.01',
            'satuan'          => 'required|in:kg,ton,kwintal',
            'harga_per_kg'    => 'nullable|numeric|min:0',
            'catatan'         => 'nullable|string',
            'bukti_foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('bukti_foto')) {
            if ($panen->bukti_foto) {
                Storage::disk('public')->delete($panen->bukti_foto);
            }
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('panen', 'public');
        }

        $panen->update($validated);

        return redirect()->route('petani.panen.index')
            ->with('success', 'Riwayat panen berhasil diperbarui!');
    }

    public function destroy(RiwayatPanen $panen)
    {
        abort_if($panen->user_id !== Auth::id(), 403);

        if ($panen->bukti_foto) {
            Storage::disk('public')->delete($panen->bukti_foto);
        }

        $panen->delete();

        return redirect()->route('petani.panen.index')
            ->with('success', 'Data panen berhasil dihapus!');
    }
}