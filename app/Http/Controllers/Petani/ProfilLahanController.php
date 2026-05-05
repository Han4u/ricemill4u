<?php

namespace App\Http\Controllers\Petani;


use App\Http\Controllers\Controller;
use App\Models\ProfilLahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilLahanController extends Controller
{
    public function index(Request $request)
    {
        $query = ProfilLahan::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lahan', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('jenis_tanah')) {
            $query->where('jenis_tanah', $request->jenis_tanah);
        }

        if ($request->filled('luas_min')) {
            $query->where('luas_lahan', '>=', $request->luas_min);
        }
        if ($request->filled('luas_max')) {
            $query->where('luas_lahan', '<=', $request->luas_max);
        }

        $lahans = $query->latest()->paginate(10)->withQueryString();

        return view('petani.lahan.index', compact('lahans'));
    }

    public function create()
    {
        return view('petani.lahan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lahan'  => 'required|string|max:255',
            'lokasi'      => 'required|string|max:255',
            'luas_lahan'  => 'required|numeric|min:0.01',
            'jenis_tanah' => 'required|in:tanah_liat,tanah_pasir,tanah_humus,tanah_gambut,lainnya',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('lahan', 'public');
        }

        ProfilLahan::create($validated);

        return redirect()->route('petani.lahan.index')
            ->with('success', 'Profil lahan berhasil ditambahkan!');
    }

    public function show(ProfilLahan $lahan)
    {
        $this->authorizeOwner($lahan);
        $lahan->load('riwayatPanen');
        return view('petani.lahan.show', compact('lahan'));
    }

    public function edit(ProfilLahan $lahan)
    {
        $this->authorizeOwner($lahan);
        return view('petani.lahan.edit', compact('lahan'));
    }

    public function update(Request $request, ProfilLahan $lahan)
    {
        $this->authorizeOwner($lahan);

        $validated = $request->validate([
            'nama_lahan'  => 'required|string|max:255',
            'lokasi'      => 'required|string|max:255',
            'luas_lahan'  => 'required|numeric|min:0.01',
            'jenis_tanah' => 'required|in:tanah_liat,tanah_pasir,tanah_humus,tanah_gambut,lainnya',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($lahan->foto) {
                Storage::disk('public')->delete($lahan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('lahan', 'public');
        }

        $lahan->update($validated);

        return redirect()->route('petani.lahan.index')
            ->with('success', 'Profil lahan berhasil diperbarui!');
    }

    public function destroy(ProfilLahan $lahan)
    {
        $this->authorizeOwner($lahan);

        if ($lahan->foto) {
            Storage::disk('public')->delete($lahan->foto);
        }

        $lahan->delete();

        return redirect()->route('petani.lahan.index')
            ->with('success', 'Profil lahan berhasil dihapus!');
    }
    private function authorizeOwner(ProfilLahan $lahan): void
    {
        abort_if($lahan->user_id !== Auth::id(), 403, 'Akses ditolak.');
    }
}
