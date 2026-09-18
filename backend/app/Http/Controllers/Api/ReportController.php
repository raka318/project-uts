<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exports\TransaksiReportExport;
use App\Models\Transaksi;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Endpoint untuk mendapatkan ringkasan (Summary) UI Laporan Harian/Periodik
     */
    public function dailySummary(Request $request)
    {
        try {
            $dateFrom = $request->query('date_from', now()->toDateString());
            $dateTo   = $request->query('date_to', now()->toDateString());
            $userId   = $request->user()->id;

            // Summary Total Pemasukan & Pengeluaran
            $summary = Transaksi::where('id_user', $userId)
                ->whereDate('tanggal', '>=', $dateFrom)
                ->whereDate('tanggal', '<=', $dateTo)
                ->selectRaw("
                    SUM(CASE WHEN jenis = 'pemasukan' THEN jumlah ELSE 0 END) as total_pemasukan,
                    SUM(CASE WHEN jenis = 'pengeluaran' THEN jumlah ELSE 0 END) as total_pengeluaran,
                    COUNT(*) as total_transaksi
                ")
                ->first();

            // Data transaksi terpaginasi untuk tabel di UI Frontend
            $transactions = Transaksi::with(['kategori', 'dompet'])
                ->where('id_user', $userId)
                ->whereDate('tanggal', '>=', $dateFrom)
                ->whereDate('tanggal', '<=', $dateTo)
                ->orderBy('tanggal', 'desc')
                ->paginate(15);

            return response()->json([
                'status'  => true,
                'message' => 'Daily report summary retrieved successfully.',
                'data'    => [
                    'summary'      => $summary,
                    'transactions' => $transactions,
                    'date_from'    => $dateFrom,
                    'date_to'      => $dateTo,
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint untuk memicu Download Excel dari Frontend
     */
    public function exportDaily(Request $request)
    {
        try {
            $dateFrom = $request->query('date_from', now()->toDateString());
            $dateTo   = $request->query('date_to', now()->toDateString());
            $userId   = $request->user()->id;

            $fileName = "laporan-transaksi-{$dateFrom}-sd-{$dateTo}.xlsx";

            return Excel::download(
                new TransaksiReportExport($userId, $dateFrom, $dateTo),
                $fileName
            );

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
