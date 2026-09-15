<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Dompet;
use App\Models\Kategori;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $transactions = Transaksi::with([
                'user',
                'kategori'
            ])
                ->where('id_user', $request->user()->id)
                ->orderBy('tanggal', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Transactions retrieved successfully.',
                'data' => $transactions,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_dompet' => 'required|integer',
                'id_kategori' => 'required|integer',
                'jenis' => 'required|in:pemasukan,pengeluaran',
                'jumlah' => 'required|numeric|min:0.01',
                'judul' => 'required|string|max:150',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
            ]);

            DB::beginTransaction();

            $dompet = Dompet::where('id_dompet', $validated['id_dompet'])
                ->where('id_user', $request->user()->id)
                ->lockForUpdate()
                ->first();

            if (!$dompet) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Dompet tidak ditemukan.',
                ], 404);
            }

            $category = Kategori::where('id_kategori', $validated['id_kategori'])
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$category) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.',
                ], 404);
            }

            if ($validated['jenis'] === 'pengeluaran' && $dompet->saldo < $validated['jumlah']) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Saldo dompet tidak mencukupi.',
                ], 400);
            }

            $transaction = Transaksi::create([
                'id_user' => $request->user()->id,
                'id_dompet' => $validated['id_dompet'],
                'id_kategori' => $validated['id_kategori'],
                'jenis' => $validated['jenis'],
                'jumlah' => $validated['jumlah'],
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanggal' => $validated['tanggal'],
            ]);

            if ($validated['jenis'] === 'pemasukan') {
                $dompet->saldo += $validated['jumlah'];
            } else {
                $dompet->saldo -= $validated['jumlah'];
            }

            $dompet->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction created successfully.',
                'data' => $transaction->load(['user', 'kategori']),
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $transaction = Transaksi::with([
                'user',
                'kategori'
            ])
                ->where('id_transaksi', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaction not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Transaction retrieved successfully.',
                'data' => $transaction,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transaction = Transaksi::where('id_transaksi', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaction not found.',
                ], 404);
            }

            $validated = $request->validate([
                'id_dompet' => 'required|integer',
                'id_kategori' => 'required|integer',
                'jenis' => 'required|in:pemasukan,pengeluaran',
                'jumlah' => 'required|numeric|min:0.01',
                'judul' => 'required|string|max:150',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
            ]);

            DB::beginTransaction();

            // 1. Kembalikan (revert) saldo dompet lama sebelum diedit
            $oldDompet = Dompet::where('id_dompet', $transaction->id_dompet)
                ->where('id_user', $request->user()->id)
                ->first();

            if ($oldDompet) {
                if ($transaction->jenis === 'pemasukan') {
                    $oldDompet->saldo -= $transaction->jumlah;
                } else {
                    $oldDompet->saldo += $transaction->jumlah;
                }
                $oldDompet->save();
            }

            // 2. Ambil data dompet baru
            $newDompet = Dompet::where('id_dompet', $validated['id_dompet'])
                ->where('id_user', $request->user()->id)
                ->lockForUpdate()
                ->first();

            if (!$newDompet) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Dompet tidak ditemukan.',
                ], 404);
            }

            // 3. Cek kecukupan saldo jika jenis transaksi baru adalah pengeluaran
            if ($validated['jenis'] === 'pengeluaran' && $newDompet->saldo < $validated['jumlah']) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Saldo dompet tidak mencukupi.',
                ], 400);
            }

            // 4. Update data transaksi
            $transaction->update([
                'id_dompet' => $validated['id_dompet'],
                'id_kategori' => $validated['id_kategori'],
                'jenis' => $validated['jenis'],
                'jumlah' => $validated['jumlah'],
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanggal' => $validated['tanggal'],
            ]);

            // 5. Terapkan saldo pada dompet baru
            if ($validated['jenis'] === 'pemasukan') {
                $newDompet->saldo += $validated['jumlah'];
            } else {
                $newDompet->saldo -= $validated['jumlah'];
            }
            $newDompet->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction updated successfully.',
                'data' => $transaction->load(['user', 'kategori']),
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $transaction = Transaksi::where('id_transaksi', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaction not found.',
                ], 404);
            }

            $dompet = Dompet::where('id_dompet', $transaction->id_dompet)
                ->where('id_user', $request->user()->id)
                ->first();

            DB::beginTransaction();

            if ($dompet) {
                if ($transaction->jenis === 'pemasukan') {
                    $dompet->saldo -= $transaction->jumlah;
                } else {
                    $dompet->saldo += $transaction->jumlah;
                }
                $dompet->save();
            }

            $transaction->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction deleted successfully.',
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}