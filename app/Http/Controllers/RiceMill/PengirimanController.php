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
            'jenis_beras'      => 'required|in:premium,medium,setra_ramos,pandan_wangi,biasa',
            'jumlah_karung'    => 'required|integer|min:1',
            'berat_per_karung' => 'required|numeric|min:1',
            'tanggal_kirim'    => 'required|date',
            'biaya_logistik'   => 'nullable|numeric|min:0',
            'status'           => 'required|in:menunggu,dikirim,diterima,ditolak,diproses',
            'bukti_kirim'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'          => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_kirim')) {
            $file = $request->file('bukti_kirim');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['bukti_kirim'] = 'data:' . $mimeType . ';base64,' . $base64;
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
            'jenis_beras'      => 'required|in:premium,medium,setra_ramos,pandan_wangi,biasa',
            'jumlah_karung'    => 'required|integer|min:1',
            'berat_per_karung' => 'required|numeric|min:1',
            'tanggal_kirim'    => 'required|date',
            'biaya_logistik'   => 'nullable|numeric|min:0',
            'status'           => 'required|in:menunggu,dikirim,diterima,ditolak,diproses',
            'bukti_kirim'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'          => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_kirim')) {
            if ($pengiriman->bukti_kirim && strpos($pengiriman->bukti_kirim, 'data:') !== 0) {
                Storage::disk('public')->delete($pengiriman->bukti_kirim);
            }
            $file = $request->file('bukti_kirim');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['bukti_kirim'] = 'data:' . $mimeType . ';base64,' . $base64;
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

    public function showBukti($id)
    {
        $pengiriman = PengirimanBeras::findOrFail($id);
        abort_if($pengiriman->user_id !== Auth::id(), 403);

        if (!$pengiriman->bukti_kirim) {
            abort(404);
        }

        if (str_starts_with($pengiriman->bukti_kirim, 'data:')) {
            // Parse base64 URL
            list($type, $data) = explode(';', $pengiriman->bukti_kirim);
            list(, $data)      = explode(',', $data);
            $mime = str_replace('data:', '', $type);
            return response(base64_decode($data))
                ->header('Content-Type', $mime)
                ->header('Cache-Control', 'public, max-age=86400');
        }

        // Fallback for file path
        $disk = Storage::disk('public');
        if ($disk->exists($pengiriman->bukti_kirim)) {
            return response()->file($disk->path($pengiriman->bukti_kirim), [
                'Content-Type' => $disk->mimeType($pengiriman->bukti_kirim) ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        abort(404);
    }
}
