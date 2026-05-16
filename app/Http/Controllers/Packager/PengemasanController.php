<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBeras;
use App\Models\HasilPengemasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengemasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengemasan = HasilPengemasan::with('penerimaanBeras')
            ->where('user_id', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        return view('packager.pengemasan.index', compact('pengemasan'));
    }

    public function create()
    {
        $penerimaan = PenerimaanBeras::where('user_id', Auth::id())
            ->where('status', 'diterima')
            ->get();
        return view('packager.pengemasan.create', compact('penerimaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penerimaan_beras_id' => 'required|exists:penerimaan_beras,id',
            'tanggal'             => 'required|date',
            'jenis_beras'         => 'required|string|max:100',
            'jenis_kemasan'       => 'required|in:5kg,10kg,25kg,50kg',
            'jumlah_kemasan'      => 'required|integer|min:1',
            'kualitas'            => 'required|in:layak_jual,reject',
            'catatan'             => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        HasilPengemasan::create($validated);

        return redirect()->route('packager.pengemasan.index')
            ->with('success', 'Hasil pengemasan berhasil dicatat!');
    }

    public function destroy(HasilPengemasan $pengemasan)
    {
        abort_if($pengemasan->user_id !== Auth::id(), 403);
        $pengemasan->delete();
        return redirect()->route('packager.pengemasan.index')
            ->with('success', 'Data pengemasan berhasil dihapus!');
    }
}
