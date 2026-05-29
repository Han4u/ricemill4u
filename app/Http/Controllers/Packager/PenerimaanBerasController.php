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
            $file = $request->file('bukti_foto');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['bukti_foto'] = 'data:' . $mimeType . ';base64,' . $base64;
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
            if ($penerimaan->bukti_foto && strpos($penerimaan->bukti_foto, 'data:') !== 0) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($penerimaan->bukti_foto);
            }
            $file = $request->file('bukti_foto');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['bukti_foto'] = 'data:' . $mimeType . ';base64,' . $base64;
        }

        $penerimaan->update($validated);

        return redirect()->route('packager.penerimaan-beras.index')
            ->with('success', 'Data penerimaan berhasil diperbarui!');
    }

    public function destroy(PenerimaanBeras $penerimaan)
    {
        abort_if($penerimaan->user_id !== Auth::id(), 403);
        if ($penerimaan->bukti_foto && strpos($penerimaan->bukti_foto, 'data:') !== 0) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($penerimaan->bukti_foto);
        }
        $penerimaan->delete();
        return redirect()->route('packager.penerimaan-beras.index')
            ->with('success', 'Data penerimaan berhasil dihapus!');
    }

    public function showBukti($id)
    {
        $penerimaan = PenerimaanBeras::findOrFail($id);
        abort_if($penerimaan->user_id !== Auth::id(), 403);

        if (!$penerimaan->bukti_foto) {
            abort(404);
        }

        if (str_starts_with($penerimaan->bukti_foto, 'data:')) {
            // Parse base64 URL
            list($type, $data) = explode(';', $penerimaan->bukti_foto);
            list(, $data)      = explode(',', $data);
            $mime = str_replace('data:', '', $type);
            return response(base64_decode($data))
                ->header('Content-Type', $mime)
                ->header('Cache-Control', 'public, max-age=86400');
        }

        // Fallback for file path
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if ($disk->exists($penerimaan->bukti_foto)) {
            return response()->file($disk->path($penerimaan->bukti_foto), [
                'Content-Type' => $disk->mimeType($penerimaan->bukti_foto) ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        abort(404);
    }
}
