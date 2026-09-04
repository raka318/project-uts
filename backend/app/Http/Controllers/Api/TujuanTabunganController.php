<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TujuanTabungan;
use Exception;
use Illuminate\Http\Request;

class TujuanTabunganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $goals = TujuanTabungan::where(
                'id_user',
                $request->user()->id
            )
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Savings goals retrieved successfully.',
                'data' => $goals,
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
                'nama_tujuan' => 'required|string|max:150',
                'target_jumlah' => 'required|numeric|min:0.01',
                'jumlah_terkumpul' => 'nullable|numeric|min:0',
                'deadline' => 'nullable|date',
                'deskripsi' => 'nullable|string',
            ]);

            $currentAmount = $validated['jumlah_terkumpul'] ?? 0;

            if ($currentAmount > $validated['target_jumlah']) {
                return response()->json([
                    'status' => false,
                    'message' => 'The current amount cannot exceed the target amount.',
                ], 422);
            }

            $goal = TujuanTabungan::create([
                'id_user' => $request->user()->id,
                'nama_tujuan' => $validated['nama_tujuan'],
                'target_jumlah' => $validated['target_jumlah'],
                'jumlah_terkumpul' => $currentAmount,
                'deadline' => $validated['deadline'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Savings goal created successfully.',
                'data' => $goal,
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
            $goal = TujuanTabungan::where('id_tujuan', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$goal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Savings goal not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Savings goal retrieved successfully.',
                'data' => $goal,
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
            $goal = TujuanTabungan::where('id_tujuan', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$goal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Savings goal not found.',
                ], 404);
            }

            $validated = $request->validate([
                'nama_tujuan' => 'required|string|max:150',
                'target_jumlah' => 'required|numeric|min:0.01',
                'jumlah_terkumpul' => 'required|numeric|min:0',
                'deadline' => 'nullable|date',
                'deskripsi' => 'nullable|string',
            ]);

            if (
                $validated['jumlah_terkumpul']
                > $validated['target_jumlah']
            ) {
                return response()->json([
                    'status' => false,
                    'message' => 'The current amount cannot exceed the target amount.',
                ], 422);
            }

            $goal->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Savings goal updated successfully.',
                'data' => $goal,
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
            $goal = TujuanTabungan::where('id_tujuan', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$goal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Savings goal not found.',
                ], 404);
            }

            $goal->delete();

            return response()->json([
                'status' => true,
                'message' => 'Savings goal deleted successfully.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}