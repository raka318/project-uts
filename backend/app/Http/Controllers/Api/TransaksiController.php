<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Akun;
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
                'akun',
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
                'id_akun' => 'required|integer',
                'id_kategori' => 'required|integer',
                'jenis' => 'required|in:pemasukan,pengeluaran',
                'jumlah' => 'required|numeric|min:0.01',
                'judul' => 'required|string|max:150',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
            ]);

            $account = Akun::where('id_akun', $validated['id_akun'])
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$account) {
                return response()->json([
                    'status' => false,
                    'message' => 'Account not found.',
                ], 404);
            }

            $category = Kategori::where('id_kategori', $validated['id_kategori'])
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.',
                ], 404);
            }

            DB::beginTransaction();

            $transaction = Transaksi::create([
                'id_user' => $request->user()->id,
                'id_akun' => $validated['id_akun'],
                'id_kategori' => $validated['id_kategori'],
                'jenis' => $validated['jenis'],
                'jumlah' => $validated['jumlah'],
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanggal' => $validated['tanggal'],
            ]);

            if ($validated['jenis'] === 'pemasukan') {
                $account->saldo += $validated['jumlah'];
            } else {
                $account->saldo -= $validated['jumlah'];
            }

            $account->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction created successfully.',
                'data' => $transaction->load([
                    'akun',
                    'kategori'
                ]),
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
                'akun',
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

            $account = Akun::where('id_akun', $transaction->id_akun)
                ->where('id_user', $request->user()->id)
                ->first();

            DB::beginTransaction();

            if ($transaction->jenis === 'pemasukan') {
                $account->saldo -= $transaction->jumlah;
            } else {
                $account->saldo += $transaction->jumlah;
            }

            $account->save();

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