<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanGabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenerimaanGabahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PenerimaanGabah::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('nama_petani', 'like', '%' . $request->search . '%')
                  ->orWhere('asal_lahan', 'like', '%' . $request->search . '%');
        }

        $penerimaan = $query->latest('tanggal')->paginate(10)->withQueryString();

        return view('ricemill.penerimaan-gabah.index', compact('penerimaan'));
    }

    public function create()
    {
        return view('ricemill.penerimaan-gabah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_petani'    => 'required|string|max:255',
            'asal_lahan'     => 'nullable|string|max:255',
            'tanggal'        => 'required|date',
            'jumlah_gabah'   => 'required|numeric|min:0.01',
            'kualitas_gabah' => 'required|in:kering,basah,grade_a,grade_b',
            'status'         => 'required|in:menunggu,diterima,diproses,selesai',
            'bukti_foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'        => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['bukti_foto'] = 'data:' . $mimeType . ';base64,' . $base64;
        }

        PenerimaanGabah::create($validated);

        return redirect()->route('ricemill.penerimaan-gabah.index')
            ->with('success', 'Data penerimaan gabah berhasil dicatat!');
    }

    /**
     * BUG FIX: Nama parameter diubah dari $penerimaan → $penerimaanGabah
     * agar cocok dengan route parameter {penerimaan_gabah} (Laravel snake_case ke camelCase).
     * Sebelumnya: edit(PenerimaanGabah $penerimaan) → model binding GAGAL → $penerimaan kosong
     *             → user_id null !== Auth::id() → 403 Forbidden
     */
    public function edit(PenerimaanGabah $penerimaanGabah)
    {
        abort_if($penerimaanGabah->user_id !== Auth::id(), 403);
        return view('ricemill.penerimaan-gabah.edit', ['penerimaan' => $penerimaanGabah]);
    }

    public function update(Request $request, PenerimaanGabah $penerimaanGabah)
    {
        abort_if($penerimaanGabah->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'nama_petani'    => 'required|string|max:255',
            'asal_lahan'     => 'nullable|string|max:255',
            'tanggal'        => 'required|date',
            'jumlah_gabah'   => 'required|numeric|min:0.01',
            'kualitas_gabah' => 'required|in:kering,basah,grade_a,grade_b',
            'status'         => 'required|in:menunggu,diterima,diproses,selesai',
            'bukti_foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'        => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_foto')) {
            if ($penerimaanGabah->bukti_foto && strpos($penerimaanGabah->bukti_foto, 'data:') !== 0) {
                Storage::disk('public')->delete($penerimaanGabah->bukti_foto);
            }
            $file = $request->file('bukti_foto');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['bukti_foto'] = 'data:' . $mimeType . ';base64,' . $base64;
        }

        $penerimaanGabah->update($validated);

        return redirect()->route('ricemill.penerimaan-gabah.index')
            ->with('success', 'Data penerimaan berhasil diperbarui!');
    }

    public function destroy(PenerimaanGabah $penerimaanGabah)
    {
        abort_if($penerimaanGabah->user_id !== Auth::id(), 403);

        if ($penerimaanGabah->bukti_foto && strpos($penerimaanGabah->bukti_foto, 'data:') !== 0) {
            Storage::disk('public')->delete($penerimaanGabah->bukti_foto);
        }

        $penerimaanGabah->delete();

        return redirect()->route('ricemill.penerimaan-gabah.index')
            ->with('success', 'Data penerimaan berhasil dihapus!');
    }

    public function showBukti($id)
    {
        $penerimaan = PenerimaanGabah::findOrFail($id);
        abort_if($penerimaan->user_id !== Auth::id(), 403);

        if (!$penerimaan->bukti_foto) {
            abort(404);
        }

        if (str_starts_with($penerimaan->bukti_foto, 'data:')) {
            list($type, $data) = explode(';', $penerimaan->bukti_foto);
            list(, $data)      = explode(',', $data);
            $mime = str_replace('data:', '', $type);
            return response(base64_decode($data))
                ->header('Content-Type', $mime)
                ->header('Cache-Control', 'public, max-age=86400');
        }

        $disk = Storage::disk('public');
        if ($disk->exists($penerimaan->bukti_foto)) {
            return response()->file($disk->path($penerimaan->bukti_foto), [
                'Content-Type' => $disk->mimeType($penerimaan->bukti_foto) ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        abort(404);
    }
}
