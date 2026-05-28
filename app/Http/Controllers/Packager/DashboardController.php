<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBeras;
use App\Models\HasilPengemasan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $bulan  = now()->month;
        $tahun  = now()->year;

        // ── Stat Cards ──────────────────────────────────────────────
        $totalTerimaKg = PenerimaanBeras::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah_beras');

        $totalKemasPack = HasilPengemasan::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah_kemasan');

        $pesananBaru = Pesanan::where('user_id', $userId)
            ->where('status', 'menunggu')->count();

        $totalPenjualan = Pesanan::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('total_harga');

        // ── Grafik Produksi (Last 7 Days) ───────────────────────────
        $produksiHarian = HasilPengemasan::where('user_id', $userId)
            ->select(DB::raw('DATE(tanggal) as date'), DB::raw('SUM(jumlah_kemasan) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get()
            ->reverse();

        // ── Distribusi Kemasan (Pie Chart Data) ─────────────────────
        $distribusiKemasan = HasilPengemasan::where('user_id', $userId)
            ->select('jenis_kemasan', DB::raw('count(*) as total'))
            ->groupBy('jenis_kemasan')
            ->get();

        $stats = [
            'total_terima_kg'  => $totalTerimaKg,
            'total_kemas_pack' => $totalKemasPack,
            'pesanan_baru'     => $pesananBaru,
            'total_penjualan'  => $totalPenjualan,
            'chart_labels'     => $produksiHarian->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
            'chart_values'     => $produksiHarian->pluck('total')->toArray(),
            'pie_labels'       => $distribusiKemasan->pluck('jenis_kemasan')->toArray(),
            'pie_values'       => $distribusiKemasan->pluck('total')->toArray(),
        ];

        // ── Recent Data ─────────────────────────────────────────────
        $recentPenerimaan = PenerimaanBeras::where('user_id', $userId)
            ->latest('tanggal')->take(5)->get();

        $recentPengemasan = HasilPengemasan::where('user_id', $userId)
            ->latest('tanggal')->take(5)->get();

        $recentPesanan = Pesanan::where('user_id', $userId)
            ->latest('tanggal')->take(5)->get();

        return view('packager.dashboard', compact('stats', 'recentPenerimaan', 'recentPengemasan', 'recentPesanan'));
    }
}
