<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dompet;
use Exception;
use Illuminate\Http\Request;

class DompetController extends Controller
{
    public function index(Request $request)
    {
        try {
            $wallets = Dompet::where(
                'id_user',
                $request->user()->id
            )
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Wallets retrieved successfully.',
                'data' => $wallets,
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
                'nama_dompet' => 'required|string|max:100',
                'jenis' => 'required|in:cash,bank,ewallet,tabungan',
                'saldo' => 'nullable|numeric|min:0',
                'mata_uang' => 'nullable|string|max:10',
            ]);

            $wallet = Dompet::create([
                'id_user' => $request->user()->id,
                'nama_dompet' => $validated['nama_dompet'],
                'jenis' => $validated['jenis'],
                'saldo' => $validated['saldo'] ?? 0,
                'mata_uang' => $validated['mata_uang'] ?? 'IDR',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Wallet created successfully.',
                'data' => $wallet,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $wallet = Dompet::where('id_dompet', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$wallet) {
                return response()->json([
                    'status' => false,
                    'message' => 'Wallet not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Wallet retrieved successfully.',
                'data' => $wallet,
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
            $wallet = Dompet::where('id_dompet', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$wallet) {
                return response()->json([
                    'status' => false,
                    'message' => 'Wallet not found.',
                ], 404);
            }

            $validated = $request->validate([
                'nama_dompet' => 'required|string|max:100',
                'jenis' => 'required|in:cash,bank,ewallet,tabungan',
                'saldo' => 'required|numeric|min:0',
                'mata_uang' => 'required|string|max:10',
            ]);

            $wallet->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Wallet updated successfully.',
                'data' => $wallet,
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
            $wallet = Dompet::where('id_dompet', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$wallet) {
                return response()->json([
                    'status' => false,
                    'message' => 'Wallet not found.',
                ], 404);
            }

            if ($wallet->transaksi()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This wallet cannot be deleted because it has transactions.',
                ], 422);
            }

            $wallet->delete();

            return response()->json([
                'status' => true,
                'message' => 'Wallet deleted successfully.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}