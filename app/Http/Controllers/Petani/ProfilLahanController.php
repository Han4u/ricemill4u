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
            $file = $request->file('foto');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['foto'] = 'data:' . $mimeType . ';base64,' . $base64;
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
            if ($lahan->foto && strpos($lahan->foto, 'data:') !== 0) {
                Storage::disk('public')->delete($lahan->foto);
            }
            $file = $request->file('foto');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['foto'] = 'data:' . $mimeType . ';base64,' . $base64;
        }

        $lahan->update($validated);

        return redirect()->route('petani.lahan.index')
            ->with('success', 'Profil lahan berhasil diperbarui!');
    }

    public function destroy(ProfilLahan $lahan)
    {
        $this->authorizeOwner($lahan);

        if ($lahan->foto && strpos($lahan->foto, 'data:') !== 0) {
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

    public function showBukti($id)
    {
        $lahan = ProfilLahan::findOrFail($id);
        abort_if($lahan->user_id !== Auth::id(), 403);

        if (!$lahan->foto) {
            abort(404);
        }

        if (str_starts_with($lahan->foto, 'data:')) {
            list($type, $data) = explode(';', $lahan->foto);
            list(, $data)      = explode(',', $data);
            $mime = str_replace('data:', '', $type);
            return response(base64_decode($data))
                ->header('Content-Type', $mime)
                ->header('Cache-Control', 'public, max-age=86400');
        }

        $disk = Storage::disk('public');
        if ($disk->exists($lahan->foto)) {
            return response()->file($disk->path($lahan->foto), [
                'Content-Type' => $disk->mimeType($lahan->foto) ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        abort(404);
    }
}
