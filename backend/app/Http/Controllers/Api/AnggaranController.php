<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggaran;
use App\Models\Kategori;
use Exception;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index(Request $request)
    {
        try {
            $budgets = Anggaran::with('kategori')
                ->where('id_user', $request->user()->id)
                ->orderBy('tanggal_mulai', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Budgets retrieved successfully.',
                'data' => $budgets,
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
                'id_kategori' => 'required|integer',
                'nama_anggaran' => 'required|string|max:150',
                'jumlah' => 'required|numeric|min:0.01',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            ]);

            $category = Kategori::where('id_kategori', $validated['id_kategori'])
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.',
                ], 404);
            }

            $budget = Anggaran::create([
                'id_user' => $request->user()->id,
                'id_kategori' => $validated['id_kategori'],
                'nama_anggaran' => $validated['nama_anggaran'],
                'jumlah' => $validated['jumlah'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Budget created successfully.',
                'data' => $budget->load('kategori'),
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
            $budget = Anggaran::with('kategori')
                ->where('id_anggaran', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$budget) {
                return response()->json([
                    'status' => false,
                    'message' => 'Budget not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Budget retrieved successfully.',
                'data' => $budget,
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
            $budget = Anggaran::where('id_anggaran', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$budget) {
                return response()->json([
                    'status' => false,
                    'message' => 'Budget not found.',
                ], 404);
            }

            $validated = $request->validate([
                'id_kategori' => 'required|integer',
                'nama_anggaran' => 'required|string|max:150',
                'jumlah' => 'required|numeric|min:0.01',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            ]);

            $budget->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Budget updated successfully.',
                'data' => $budget->load('kategori'),
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
            $budget = Anggaran::where('id_anggaran', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$budget) {
                return response()->json([
                    'status' => false,
                    'message' => 'Budget not found.',
                ], 404);
            }

            $budget->delete();

            return response()->json([
                'status' => true,
                'message' => 'Budget deleted successfully.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}