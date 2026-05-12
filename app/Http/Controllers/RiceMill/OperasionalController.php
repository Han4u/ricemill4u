<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\OperasionalPenggilingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operasional = OperasionalPenggilingan::with('penerimaanGabah')
            ->where('user_id', Auth::id())
            ->latest('tanggal_proses')
            ->paginate(10);

        return view('ricemill.operasional.index', compact('operasional'));
    }

    public function create()
    {
        $penerimaan = PenerimaanGabah::where('user_id', Auth::id())
            ->where('status', '!=', 'selesai')
            ->get();

        return view('ricemill.operasional.create', compact('penerimaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penerimaan_gabah_id' => 'required|exists:penerimaan_gabah,id',
            'batch_id'            => 'required|string|max:50|unique:operasional_penggilingan,batch_id',
            'tanggal_proses'      => 'required|date',
            'jumlah_gabah_masuk'  => 'required|numeric|min:0.01',
            'status'              => 'required|in:menunggu,diproses,selesai',
            'catatan'             => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        OperasionalPenggilingan::create($validated);

        // Update status gabah asal
        $gabah = PenerimaanGabah::find($request->penerimaan_gabah_id);
        $gabah->update(['status' => 'diproses']);

        return redirect()->route('ricemill.operasional.index')
            ->with('success', 'Operasional penggilingan berhasil dicatat!');
    }

    public function edit(OperasionalPenggilingan $operasional)
    {
        abort_if($operasional->user_id !== Auth::id(), 403);
        $penerimaan = PenerimaanGabah::where('user_id', Auth::id())->get();
        return view('ricemill.operasional.edit', compact('operasional', 'penerimaan'));
    }

    public function update(Request $request, OperasionalPenggilingan $operasional)
    {
        abort_if($operasional->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'penerimaan_gabah_id' => 'required|exists:penerimaan_gabah,id',
            'batch_id'            => 'required|string|max:50|unique:operasional_penggilingan,batch_id,' . $operasional->id,
            'tanggal_proses'      => 'required|date',
            'jumlah_gabah_masuk'  => 'required|numeric|min:0.01',
            'status'              => 'required|in:menunggu,diproses,selesai',
            'catatan'             => 'nullable|string',
        ]);

        $operasional->update($validated);

        return redirect()->route('ricemill.operasional.index')
            ->with('success', 'Data operasional berhasil diperbarui!');
    }

    public function destroy(OperasionalPenggilingan $operasional)
    {
        abort_if($operasional->user_id !== Auth::id(), 403);
        $operasional->delete();
        return redirect()->route('ricemill.operasional.index')
            ->with('success', 'Data operasional berhasil dihapus!');
    }
}
